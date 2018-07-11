<!DOCTYPE html>
@langrtl
    <html lang="{{ app()->getLocale() }}" dir="rtl">
@else
    <html lang="{{ app()->getLocale() }}">
@endlangrtl
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title> EduCore Installer</title>
    <meta name="description" content="EduCore Installer">
    <meta name="author" content="Fusi Gabz">
    
    <link media="all" type="text/css" rel="stylesheet" href="/css/backend.css">

    <style type="text/css">
        .good-table .table td, 
        .good-table .table th:not(.line-numbers) {
            padding: .5rem !important;
         }
         .btn:hover,
         .btn:focus{
             border-shadow: none !important;
             box-shadow: none !important;
         }
        .form-control:focus{
            box-shadow: none !important;
        }
        [v-cloak]{
            display: none;
        }
        .input-group-addon {
            padding: 0.5rem 0.75rem;
            margin-bottom: 0;
            font-size: 0.875rem;
            font-weight: normal;
            line-height: 1.25;
            color: #3e515b;
            text-align: center;
            background-color: #f0f3f5;
            border: 1px solid #c2cfd6;
        }
    </style>
</head>

<body class="x">
    <div id="app-body" class="app-body">
        
        <main class="main">
            <div class="container">
                <div class="animated fadeIn">
                    <div class="row">
                        <site-installer inline-template v-cloak>
                            <div class="col-md-6 offset-md-3 mt-3">
                                <div class="text-center">
                                    <img src="/img/logo.png" style="max-width:200px; background:#536c79; border-radius:5px;" class="p-0" />
                                </div>
                                <div class="card mt-3" style="z-index:8;">
                                    <div class="card-body">
                                        
                                        <form-wizard title='EduCore Installation' 
                                            subtitle='Follow these simple steps to install your Awesome EduCore script'
                                            shape="tab"
                                            finish-button-text="Finish Installation"
                                            @on-complete="onComplete"
                                            color="#536c79">
                                            
                                            <tab-content title="Check Folder Permissions" icon="fa fa-folder-open" :before-change="systemPermissionErrors">
                                                <div class="mt-4">
                                                    <ul class="list-group">
                                                        <li class="list-group-item pt-1 pb-1" v-for="(permission,k) in permissions">
                                                            @{{ k }} 
                                                            <span v-if="permission.status == 'ERROR'" class="text-danger pull-right">
                                                                Required: @{{ permission.required_permissions }} | 
                                                                Actual: @{{ permission.actual_permissions }} 
                                                                <span class="fa fa-times-circle"></span>
                                                            </span>
                                                            <span v-if="permission.status == 'OK'" class="text-success pull-right">
                                                                Required: @{{ permission.required_permissions }} | 
                                                                Actual: @{{ permission.actual_permissions }} 
                                                                <span class="fa fa-check-circle"></span>
                                                            </span>
                                                        </li>
                                                    </ul>
                                                    <div class="text-center mt-4">
                                                        <p v-if="permissions.length == 0">Let's check your folder permissions</p>
                                                        <button class="btn btn-danger btn-lg" @click.prevent="checkFolderPermissions">
                                                            Run check
                                                        </button>
                                                    </div>
                                                </div>
                                            </tab-content>
                                            
                                            <tab-content title="System Requirements" icon="fa fa-cogs" :before-change="systemRequirementsErrors">
                                                <ul class="list-group">
                                                    <li class="list-group-item pt-1 pb-1" v-for="(req,k) in requirements">
                                                        @{{ k }}
                                                        <span v-if="req.message && req.status == 'FAILED'" class="text-danger pull-right">
                                                            @{{ req.message }}
                                                            <span class="fa fa-times-circle"></span>
                                                        </span>
                                                        <span v-if="req.message && req.status == 'WARNING'" class="text-warning pull-right">
                                                            @{{ req.message }}
                                                            <span class="fa fa-warning"></span>
                                                        </span>
                                                        <span v-if="!req.message" class="text-success pull-right">
                                                            <span class="fa fa-check-circle"></span>
                                                        </span>
                                                    </li>
                                                </ul>
                                                <div class="text-center mt-4">
                                                    <p v-if="requirements.length == 0">Let's check your system for basic requirements</p>
                                                    <button class="btn btn-danger btn-lg" @click.prevent="checkSystemRequirements">
                                                        Run check
                                                    </button>
                                                </div>
                                            </tab-content>
                                            
                                            <tab-content title="Database Connection" icon="fa fa-database" :before-change="databaseErrors">
                                                <p>Please enter your Database connection details below. If you are not sure, contact your host to obtain them</p>
                                            
                                                <form @submit.prevent="saveDbConfig">
                                                    
                                                    <div class="alert alert-danger" v-if="connection_error" role="alert">
                                                        @{{ connection_error }}
                                                    </div>
                                                    <div class="alert alert-success" v-if="connection_successful" role="alert">
                                                        Database connection successful. Click "Next"
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-sm-5 col-form-label text-right">Database Host</label>
                                                        <div class="col-sm-7">
                                                            <input type="text" class="form-control" v-model="form.db_host" 
                                                            :class="{ 'is-invalid': form.errors.has('db_host') }" >
                                                            <has-error :form="form" field="db_host"></has-error>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-sm-5 col-form-label text-right">Database name</label>
                                                        <div class="col-sm-7">
                                                            <input type="text" class="form-control" v-model="form.db_name" 
                                                                :class="{ 'is-invalid': form.errors.has('db_name') }" >
                                                            <has-error :form="form" field="db_name"></has-error>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="form-group row">
                                                        <label class="col-sm-5 col-form-label text-right">Database User</label>
                                                        <div class="col-sm-7">
                                                            <input type="text" class="form-control" v-model="form.db_user" 
                                                                :class="{ 'is-invalid': form.errors.has('db_user') }" >
                                                            <has-error :form="form" field="db_user"></has-error>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="form-group row">
                                                        <label class="col-sm-5 col-form-label text-right">Database Password</label>
                                                        <div class="col-sm-7">
                                                            <input type="password" class="form-control"  v-model="form.db_password" 
                                                                :class="{ 'is-invalid': form.errors.has('db_password') }" >
                                                            <has-error :form="form" field="db_password"></has-error>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <div class="col-sm-5"></div>
                                                        <div class="col-sm-7">
                                                            <button type="submit" class="btn btn-success pull-right">
                                                                Connect
                                                            </button>
                                                        </div>
                                                    </div>
                                                    
                                                </form>
                                            </tab-content>
                                            
                                            <tab-content title="Install" icon="fa fa-plug" :before-change="loadDataErrors">
                                                <div class="text-center">
                                                    <button @click.prevent="loadData" v-if="!install_completed" class="btn btn-success btn-lg">
                                                        <span v-if="installing">
                                                            <i class="fa fa-cog fa-spin"></i> Installing...
                                                        </span>
                                                        <span v-else>Install Now!</span>
                                                    </button>
                                                    
                                                    <div class="" v-if="install_completed">
                                                        <p>
                                                            <i class="fa fa-check-circle text-success"></i>
                                                            Well now, that was easy `\(^_^)/`.
                                                        </p>
                                                        <p>
                                                            Click on <b>"Finish Installation"</b> to complete your installation. Once done, login as Admin and configure the rest of
                                                            your site settings.
                                                        </p>
                                                        
                                                        <h4>Admin login credentials:</h4>
                                                        <p>
                                                            Email: <code>admin@educore.io</code> <br>
                                                            Password: <code>password</code> <br>
                                                        </p>
                                                    </div>
                                                </div>
                                            </tab-content>
                                            
                                        </form-wizard>
                                        
                                        
                                        
                                        
                                        
                                        
                                        
                                    </div>
                                </div>
                            </div>
                        </site-installer>
                    </div>
                </div><!--animated-->
            </div><!--container-fluid-->
        </main><!--main-->
        

    </div><!--app-body-->
    
    <script type="text/javascript" src="/js/backend.js"></script>
</body>
</html>
