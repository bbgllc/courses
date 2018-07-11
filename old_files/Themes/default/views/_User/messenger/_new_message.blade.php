<script type="text/javascript">
       
    $("[data-toggle=popover]").each(function(i, obj) {
        $(this).popover({
            html: true,
            trigger: "manual",
            animation: true,
            content: function() {
                var id = $(this).attr('id')
                return $('#popover-course-' + id).html();
            }
        }).on("mouseenter", function () {
            var _this = this;
            $(this).popover("show");
            $(this).siblings(".popover").on("mouseleave", function () {
                $(_this).popover('hide');
            });
        }).on("mouseleave", function () {
            var _this = this;
            setTimeout(function () {
                if (!$(".popover:hover").length) {
                    $(_this).popover("hide")
                }
            }, 200);
        });
    
    });
    
    
    // messenger form
    $(document).ready(function(){
        $('#submit-form').prop('disabled',true);
        $('#message').keyup(function(){
            $('#submit-form').prop('disabled', ($.trim(this.value)) == '' ? true : false);     
        });
    });
    
</script>

<div class="modal fade" id="sendMessage" tabindex="-1" role="dialog" aria-labelledby="sendMessage" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Messaging {{$user->name}}</h5>
        <button type="button" class="close close-modal" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="{{ route('frontend.user.message.send') }}" id="message-form" method="POST">
            {{ csrf_field() }}
            <input type="hidden" name="recipient" id="recipient" value="{{$user->id}}" />
            <div class="form-group">
                <label for="message" class="col-form-label">Message:</label>
                <textarea class="form-control" rows="6" id="message" name="message"></textarea>
            </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-sm btn-info" id="submit-form" form="message-form">Send message</button>
      </div>
    </div>
  </div>
</div>