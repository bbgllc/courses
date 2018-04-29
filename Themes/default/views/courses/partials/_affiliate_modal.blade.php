<!-- Modal -->
<div class="modal fade" id="affiliateLink-{{$course->id}}" role="dialog">
    <div class="modal-dialog modal-md" role="document">
      <div class="modal-content">
        <div class="modal-header">
            <h6 class="modal-title">
                {{trans('t.copy-affiliate-link')}}  
            </h6>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="form-group">
                <div class="input-group">
                  <input type="text" class="form-control" style="font-size:12px !important;" id="promoLink" :value="link">
                  <span class="input-group-addon">
                      <a href="#" @click.prevent="copyToClipboard()">
                          <i class="fa fa-clipboard"></i>
                      </a>
                  </span>
                </div>
                <small class="text-success">@{{copyStatus}}</small>
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">
              {{trans('t.close')}}
          </button>
        </div>
      </div>
    </div>
</div>