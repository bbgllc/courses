@extends('layouts.master')

@section('title', app_name() . ' | ' .  $course->title)

@section('content')
    
    
    @include('includes._title_header', ['title' => $course->title])
    
    
    <section class="content-area bg-gray pt-5 pb-5">
        <div class="container">
            
            <div class="row mb-4">
                
                <div class="col-md-3">
                    @include('includes._author_course_sidebar')
                </div>
                
                
                <div class="col-md-9">
                    
                        <div class="card border-info">
                            <div class="card-body">
                                <h4 class="text-info mb-4">
                                    {{__('t.announcements')}}
                                </h4>
                                
                                <a href="{{route('frontend.author.announcements.create', $course)}}" class="btn btn-sm btn-success pull-right">
                                    <i class="fa fa-plus-circle"></i> {{__('t.create')}}
                                </a>
                                <div class="clearfix"></div>
                                <table class="table table-striped mt-2">
        				            <thead>
        				                <th>{{__('t.announcement')}}</th>
        				                <th>{{__('t.created')}}</th>
        				                <th>{{__('t.courses')}}</th>
        				            </thead>
        				            <tbody>
        				                @foreach($announcements as $announcement)
            				                <tr>
            				                    <td>
            				                        <a href="#">
            				                            {{$announcement->title}}
        				                            </a>
        				                        </td>
            				                    <td>{{$announcement->created_at}}</td>
            				                    <td>
            				                        @foreach($announcement->courses as $course)
            				                            {{$course->title}} {{$loop->last ? '':', '}}
            				                        @endforeach
            				                    </td>
            				                </tr>
        				                @endforeach
        				            </tbody>
        				        </table>
                                
                            </div>
                        </div>
                    
                </div>
            </div>

            
        </div>
    </section>

@endsection

@push('after-scripts')
    <style type="text/css">
        .popover {
            min-width: 100px !important;
        }
    </style>
    <script>
   
        $(function () {
          $('[data-toggle="popover"]').popover();
        })
        
        $(document).on('click',".cancelPop", function () {
            $('[data-toggle="popover"]').popover('hide');
        });
        
    </script>
@endpush