@extends('layouts.master')

@section('title', app_name() . ' | ' . $course->title)

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
                        <div class="card-body" style="min-height:250px;">
                            <h4 class="text-info mb-4">
                                {{__('t.admin-review-notes')}}
                            </h4>
                            
                            <ul class="timeline">
                                @foreach($approvals as $approval)
                                    <li class="timeline-inverted">
                                      <div class="timeline-badge {{ $approval->decision == 'approved' ? 'success' : 'danger'}}">
                                          <i class="fa fa-thumbs-{{ $approval->decision == 'approved' ? 'up' : 'down'}}"></i>
                                      </div>
                                      <div class="timeline-panel">
                                        <div class="timeline-heading">
                                          <h6 class="timeline-title">
                                              {{ __('t.'.$approval->decision)}}
                                          </h6>
                                          <span class="time text-muted"><i class="fa fa-clock-o"></i> {{ $approval->created_at->format('H:i:s') }}</span>
                                        </div>
                                        <div class="timeline-body">
                                            {{ $approval->comment }}  
                                        </div>
                                      </div>
                                    </li>
                                @endforeach
                            </ul>
                            
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