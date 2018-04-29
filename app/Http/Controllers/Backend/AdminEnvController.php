<?php

namespace App\Http\Controllers\Backend;

use Brotzka\DotenvEditor\DotenvEditor;
use Brotzka\DotenvEditor\Exceptions\DotEnvException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdminEnvController extends Controller
{
    public function index()
    {
        return view('vendor.dotenv-editor.overview');
    }
    
    
}
