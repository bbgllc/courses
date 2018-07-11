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
    <link rel="icon" href="{{ config('site_settings.site_favicon') }}" type="image/x-icon" />
    <title>@yield('title', app_name())</title>
    <meta name="description" content="@yield('meta_description', 'EduCore - Udemy Clone Script')">
    <meta name="author" content="@yield('meta_author', 'Fusi Gabz')">
    @yield('meta')

    {{-- See https://laravel.com/docs/5.5/blade#stacks for usage --}}
    @stack('before-styles')

    <!-- Check if the language is set to RTL, so apply the RTL layouts -->
    <!-- Otherwise apply the normal LTR layouts -->
    {{ style(mix('css/backend.css')) }}

    @stack('after-styles')
    
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
    
    <script>
        //See https://laracasts.com/discuss/channels/vue/use-trans-in-vuejs
        window.trans = @php
            // copy all translations from /resources/lang/CURRENT_LOCALE/* to global JS variable
            $lang_files = File::files(resource_path() . '/lang/' . App::getLocale());
            $trans = [];
            foreach ($lang_files as $f) {
                $filename = pathinfo($f)['filename'];
                $trans[$filename] = trans($filename);
            }
            //$trans['adminlte_lang_message'] = trans('adminlte_lang::message');
            echo json_encode($trans);
        @endphp
        
        window.stg = @php
            $settinigs = [
                'video_allow_upload' => config('site_settings.video_allow_upload'),
                'video_allow_youtube' => config('site_settings.video_allow_youtube'),
                'video_allow_vimeo' => config('site_settings.video_allow_vimeo'),
                'vms' => config('site_settings.video_max_size'),
                'enable_demo' => config('settings.enable_demo')
            ];
            
            echo json_encode($settinigs);
        @endphp
    </script>
</head>

<body class="{{ config('backend.body_classes') }}">
    @include('includes.partials.ga')
    
    @include('backend.includes.header')

    <div id="app-body" class="app-body">
        @include('backend.includes.sidebar')

        <main class="main">
            @include('includes.partials.logged-in-as')
            {{-- Breadcrumbs::render() --}}

            <ol class="breadcrumb">
                <li class="breadcrumb-item font-weight-bold">
                    {{ __('t.admin-panel') }} &nbsp; |  &nbsp; {{ __('t.welcome') }} {{ auth()->user()->name }}  &nbsp; 
                    <span class="pull-right text-muted">
                        {{ \Carbon\Carbon::now()->format('F d, Y' ) }}
                    </span>
                </li>  
            </ol>

            <div class="container-fluid">
                <div class="animated fadeIn">
                    <div class="content-header">
                        @yield('page-header')
                    </div><!--content-header-->

                    @include('includes.partials.messages')
                    @yield('content')
                </div><!--animated-->
            </div><!--container-fluid-->
        </main><!--main-->

        @include('backend.includes.aside')
    </div><!--app-body-->

    @include('backend.includes.footer')

    <!-- Scripts -->
    @stack('before-scripts')
    {!! script(mix('js/backend.js')) !!}
    @stack('after-scripts')
    
    @yield('modals')
    
    
</body>
</html>
