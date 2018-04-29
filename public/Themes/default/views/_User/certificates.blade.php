@extends('layouts.master')

@section('title', app_name() . ' | ' .  __('t.my-certificates'))

@section('content')
    
    
    @include('includes._title_header', ['title' =>  __('t.my-certificates') ] )
    
    <section class="content-area bg-gray pt-2 pb-2">
        <div class="container">
            <div class="row mb-4">
                <div class="col-12 mb-5">
                    <ul id="tabsJustified" class="nav nav-tabs" id="myTab" role="tablist">
                        @include('_User._myNav')
                    </ul>
                    <div id="myTabContent" class="card card-body border-top-0 p-4">
                        <div class="tab-content">
                            <div id="enrolled-courses" class="tab-pane fade show active" role="tabpanel">
                                <div class="row">
                                    @if($certificates->count())
                                        <table class="table table-sm table-hover table-striped">
                                            <thead>
                                                <th>{{ __('t.course') }}</th>
                                                <th>{{ __('t.date-completed') }}</th>
                                                <th></th>
                                            </thead>
                                            <tbody>
                                                @foreach($certificates as $certificate)
                                                    <tr>
                                                        <td>{{$certificate->course_title}}</td>
                                                        <td>{{ date('F d, Y', strtotime($certificate->created_at))}}</td>
                                                        <td>
                                                            <a href="{{route('frontend.user.certificate.download', $certificate->course)}}" class="btn btn-outline-info btn-sm">
                                                                {{ __('t.download') }}
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    @else
                                        <div class="col-md-12 text-center">
                                            <p>
                                                 {{__('t.no-certificates')}}
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

