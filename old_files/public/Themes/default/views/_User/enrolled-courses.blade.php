@extends('layouts.master')

@section('title', app_name() . ' | ' . __('t.my-courses'))

@section('content')
    
    
    @include('includes._title_header', ['title' => __('t.my-enrolled-courses')])
    
            
    <section class="content-area bg-gray pt-2 pb-2">
        <div class="container">
            <div class="row mb-4">
                <div class="col-12 mb-5">
                    <ul id="tabsJustified" class="nav nav-tabs" id="myTab" role="tablist">
                        @include('_User._myNav')
                    </ul>
                    <div id="myTabContent" class="card card-body border-top-0">
                        <div class="tab-content">
                            <div id="enrolled-courses" class="tab-pane fade show active" role="tabpanel">
                                <div class="row">
                                    @if($courses->count())
                                        @each('courses.partials._course', $courses, 'course')
                                    @else
                                    <div class="col-md-12 text-center">
                                        <p>
                                            {{ __('t.no-enrollment-yet') }}
                                        </p>
                                    </div>
                                    @endif 
                                </div>
                            </div>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

