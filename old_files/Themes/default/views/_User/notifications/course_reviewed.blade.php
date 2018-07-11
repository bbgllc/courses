<li class="list-group-item mb-0 {{ $notification->read() ? 'read-notification ' : 'unread-notification ' }}
  border border-right-0 border-left-0  border-bottom-0"
  style="font-size: .8em;">
  <div class="row">
    <div class="col-md-2">
      {{$notification->created_at->diffForHumans()}}
    </div>
    <div class="col-md-9">
      Your course 
      <a href="/author/course/{{$notification->data['course_slug']}}/admin-approval">
        "{{ $notification->data['course_title'] }}"
      </a>
      has been reviewed by the Administrator.
    </div>
    <div class="col-md-1 text-right">
      @if( $notification->unread() )
        <a href="{{ route('frontend.user.notifications.mark', $notification->id) }}">
          <i class="fa fa-dot-circle-o" data-toggle="tooltip" title="{{ __('t.mark-as-read') }}"></i>
        </a>
      @endif
    </div>
  </div>
</li>