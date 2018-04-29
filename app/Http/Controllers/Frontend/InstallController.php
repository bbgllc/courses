<?php

namespace App\Http\Controllers\Frontend;

use Brotzka\DotenvEditor\DotenvEditor;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class InstallController extends Controller
{
    
    
    public function index()
    {
        if(\Storage::disk('installPath')->exists('DO_NOT_TOUCH/site_installed.key')){
            return redirect('/');
        }
        
        return view('installer.install');
    }
        
    public function checkRequirements()
    {
        
        $req = array();
        $errors = 0;
        
        if(version_compare(PHP_VERSION , "7.0", ">=")){
            $req['PHP Version >= 7.0'] = ['status' => 'OK', 'message' => ''];
        } else {
            $req['PHP Version'] = ['status' => 'FAILED', 'message' => 'Your PHP version is less than 7.0'];
            $errors = $errors+1;
        }
        
        if(!ini_get('safe_mode')){
            $req['Safe Mode'] = ['status' => 'OK', 'message' => ''];
    	} else {
    	    $req['Safe Mode'] = ['status' => 'FAILED', 'message' => 'Please turn Safe Mode OFF in your php.ini'];
    	    $errors = $errors+1;
    	}
    	
    	if($this->getMemoryLimit() >= 104857600){ // 100MB
    	    $req['Memory Limit'] = ['status' => 'OK', 'message' => ''];
    	} else {
    	    $req['Memory Limit'] = ['status' => 'WARNING', 'message' => 'Consider increasing the Memory Limit in your php.ini'];
    	}
    	
    	if(function_exists('apache_get_modules')){
            if(in_array('mod_rewrite', apache_get_modules())){
                $req['Apache mod_rewrite'] = ['status' => 'OK', 'message' => ''];
            } else {
                $req['Apache mod_rewrite'] = ['status' => 'FAILED', 'message' => 'Please enable Apache mod_rewrite'];
                $errors = $errors+1;
            }
        }
        
        $extensions = [
            'pdo_mysql' => 'PDO MySQL',
            'fileinfo' => 'FileInfo',
            'openssl' => 'OpenSSL',
            'mbstring' => 'MBString',
            'tokenizer' => 'Tokenizer',
            'gd' => 'GD Library',
            'JSON' => 'JSon',
            'xml' => 'XML',
            'Ctype' => 'Ctype'
        ];

        foreach($extensions as $k => $v) {
            if(extension_loaded($k)){
                $req[$v] = ['status' => 'OK', 'message' => ''];
            } else {
                $req[$v] = ['status' => 'FAILED', 'message' => 'You do not seem to have '. $v .' PHP extension loaded.'];
    	        $errors = $errors+1;
            }
        }

        return response()->json(['requirements' => $req, 'errors' => $errors], 200);

        
    }
    
    
    public function checkFolderPermissions()
    {
        $req = array();
        $errors = 0;
        $folders = [
            'storage/framework/'     => '775',
            'storage/logs/'          => '775',
            'bootstrap/cache/'       => '775'
        ];
        foreach($folders as $folder => $permission){
            $actual_permission = $this->getPermission($folder);
            
            if(!($actual_permission >= $permission)){
                $req[$folder] = ['status' => 'ERROR', 'required_permissions' => $permission, 'actual_permissions' => $actual_permission];
                $errors = $errors+1;
            } else {
                $req[$folder] = ['status' => 'OK', 'required_permissions' => $permission, 'actual_permissions' => $actual_permission];
            }
        }
        return response()->json(['permissions' => $req, 'errors' => $errors], 200);
    }
    
    
    public function writeEnv(Request $request)
    {
        $this->validate($request, [
            'db_name' => 'required|string',
            'db_host' => 'required',
            'db_user' => 'required'
        ]);
        
        $env = new DotenvEditor();
        $env->changeEnv([
            'DB_HOST'   => $request->db_host,
            'DB_DATABASE'   => $request->db_name,
            'DB_USERNAME' => $request->db_user,
            'DB_PASSWORD' => $request->db_password
        ]);
        
        return response()->json(null, 200);
    }
    
    public function testDatabaseConnection(Request $request)
    {
        try {
            \DB::connection()->getPdo();
        } catch (\Exception $e) {
            return response()->json(['error' => 'Unable to connect to the database. Check the credentials and try again.'], 422);
        }
        
        return response()->json(null, 200);
    }
    
    public function loadData()
    {
        
        ini_set('memory_limit', '100M');
        $sql_dump = \File::get(base_path().'/dump.sql');
        \DB::connection()->getPdo()->exec($sql_dump);
        
        \File::put(storage_path().'/DO_NOT_TOUCH/site_installed.key', 'Installation completed on '.\Carbon\Carbon::now());
        
        return response()->json(null, 200);
    }
    
    protected function getPermission($folder)
    {
        return substr(sprintf('%o', fileperms(base_path($folder))), -4);
    }
    
    
    protected function getMemoryLimit()
    {
        
        // credit: StackOverflow: https://stackoverflow.com/questions/10208698/checking-memory-limit-in-php
        $memory_limit = ini_get('memory_limit');
        if (preg_match('/^(\d+)(.)$/', $memory_limit, $matches)) {
            if ($matches[2] == 'M') {
                $memory_limit = $matches[1] * 1024 * 1024; // nnnM -> nnn MB
            } else if ($matches[2] == 'K') {
                $memory_limit = $matches[1] * 1024; // nnnK -> nnn KB
            }
        }
        
        return $memory_limit;
    }
    

}
