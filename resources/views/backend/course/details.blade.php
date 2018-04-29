@extends ('backend.layouts.app')

@section ('title', $course->title)

@section('breadcrumb-links', '')

@section('content')
<div class="card">
    <div class="card-header">
        <i class="fa fa-align-justify"></i>
        <strong>{{$course->title}}</strong>
        <small class="text-muted">{{$course->subtitle}}</small>
    </div><!--card-header-->
    <div class="card-body">

        <div class="row mt-1">
            <div class="col">
                
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-toggle="tab" href="#approval" role="tab" aria-controls="approval" aria-expanded="true">
                            <i class="fa fa-check"></i> {{__('t.approval')}}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#overview" role="tab" aria-controls="overview" aria-expanded="true">
                            <i class="fa fa-list"></i> {{__('t.course-details')}}
                        </a>
                    </li>
                </ul>

                <div class="tab-content">
                    <!--TAB ONE -->
                    <div class="tab-pane active" id="approval" role="tabpanel" aria-expanded="true">
                        <div class="row">

                            <!-- APPROVAL FORM -->
                            <div class="col">

                                <div class="card card-danger">
                                    <div class="card-header">
                                        {{__('t.status')}}: {!! $course->status() !!}
                                    </div>
                                    <div class="card-body">
                                        <form action="{{route('admin.course.courses.approval', $course)}}" method="POST" class="form-horizontal">
                                            {{csrf_field()}}
                                            <div class="form-group">
                                                <label for="inputEmail3 control-label">
                                                    {{__('t.review-comments')}}
                                                </label>
                                                <textarea class="form-control" rows="5" name="comment" placeholder="{{__('t.enter-notes')}}"></textarea>
                                            </div>
                                            
                                            <div class="form-group">
                                                <label for="inputEmail3" class="control-label">
                                                    {{__('t.review-decision')}}
                                                </label>
                                                <select class="form-control" name="decision">
                                                    <option></option>
                                                    <option value="approved">{{__('t.approved')}}</option>    
                                                    <option value="disapproved">{{__('t.not-approved')}}</option>
                                                </select>
                                            </div>

                                            <button type="submit" class="btn btn-info pull-right">
                                                {{ __('t.submit-review') }}
                                            </button>
                                        </form>
                                    </div>
                                </div>

                            </div>


                            <!-- APPROVAL HISTORY -->
                            <div class="col">

                                <div class="card border-info">
                                    <div class="card-header">
                                        {{ __('t.course-review-history') }}
                                    </div>
                                    <div class="card-body">
                                        <ul class="timeline">
                                            @foreach($approvals as $approval)
                                                <li class="timeline-inverted">
                                                  <div class="timeline-badge {{ $approval->decision == 'approved' ? 'success' : 'danger'}}">
                                                      <i class="fa fa-thumbs-{{ $approval->decision == 'approved' ? 'up' : 'down'}}"></i>
                                                  </div>
                                                  <div class="timeline-panel">
                                                    <div class="timeline-heading">
                                                      <h6 class="timeline-title">
                                                          {{strToUpper($approval->decision)}}
                                                      </h6>
                                                      <span class="time text-muted">
                                                          <i class="fa fa-clock-o"></i> {{ $approval->created_at->format('H:i:s') }}
                                                      </span>
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
                        </div><!--/row-->


                    </div><!--tab-->

                    <!-- TAB TWO -->
                    <div class="tab-pane" id="overview" role="tabpanel" aria-expanded="true">
                        <div class="row">
                            <div class="col">

                                <div class="card card-danger">
                                    <div class="card-header">
                                        {{__('t.description')}}
                                    </div>
                                    <div class="card-body">
                                        {!! $course->description !!}
                                    </div>
                                </div>

                            </div>

                            <div class="col">
                                <div class="card card-danger">
                                    <div class="card-header">
                                        {{__('t.statistics')}}
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col">
                                                <h5>{{__('t.course-image')}}</h5>
                                                <img src="{{$course->coverImage}}" width="100%" />
                                            </div>
                                            <div class="col">
                                                <h5>{{__('t.details')}}</h5>
                                                <ul class="fa-ul">
                                                    <li><i class="fa-li fa fa-file-video-o"></i> {{$course->total_hours}} {{str_plural(__('t.hour'), $course->total_hours)}} {{__('t.of-video-content')}}</li>
                                                    <li><i class="fa-li fa fa-file-text-o"></i> {{$course->total_articles}} text-based {{str_plural('lesson', $course->total_articles)}}</li>
                                                    <li><i class="fa-li fa fa-paperclip"></i> {{$course->total_attachments}} {{str_plural(__('t.attachment'), $course->total_attachments)}}</li>
                                                    <li><i class="fa-li fa fa-question-circle"></i> {{$course->total_quizzes}} {{str_plural(__('t.quiz'), $course->total_quizzes)}}</li>
                                                    <li><i class="fa-li fa fa-wifi"></i> {{__('t.level')}}: {{ucfirst($course->level)}}</li>
                                                </ul>

                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div><!--tab-->


                   
                </div><!--tab-content-->




            </div><!--col-->
        </div><!--row-->

    </div><!--card-body-->

    <div class="card-footer">
        <div class="row">
            <div class="col">
                <small class="text-muted">
                    <a href="{{route('admin.course.courses')}}" class="btn btn-sm btn-danger">
                        {{__('t.back')}}
                    </a>
                </small>
            </div><!--col-->
        </div><!--row-->
    </div><!--card-footer-->
</div><!--card-->

@endsection
