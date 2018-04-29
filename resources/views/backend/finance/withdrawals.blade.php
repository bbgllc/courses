@extends ('backend.layouts.app')

@section ('title', __('t.withdrawals'))

@section('breadcrumb-links', '')

@section('content')
<div class="card">
    <div class="card-header">
        <i class="fa fa-align-justify"></i>
        <strong>{{__('t.withdrawals')}}</strong>
        <small class="text-muted">{{__('t.manage-withdrawal-requests')}}</small>
        
        <div id="tri" class="btn-group btn-group-sm pull-right" role="group">
            <a href="{{route('admin.finance.withdrawals')}}?filter=" type="button" name="total" class="btn {{is_null($filter) ? ' btn-success active':' btn-info'}}">
                {{__('t.all-requests')}}
            </a>
            <a href="{{route('admin.finance.withdrawals')}}?filter=pending" type="button" name="total" class="btn {{$filter=='pending' ? ' btn-success active':'btn-info'}}">
                {{__('t.pending')}}
            </a>
            <a href="{{route('admin.finance.withdrawals')}}?filter=processing" type="button" name="total" class="btn {{$filter=='processing' ? ' btn-success active':'btn-info'}}">
                {{__('t.processing')}}
            </a>
            <a href="{{route('admin.finance.withdrawals')}}?filter=processed" type="button" name="total" class="btn {{$filter=='processed' ? ' btn-success active':'btn-info'}}">
                {{__('t.processed')}}
            </a>
        </div>
    </div><!--card-header-->
    <div class="card-body">

        <div class="row mt-4">
            <div class="col">
                <div class="table-responsive">
                    <table class="table table-striped table-sm">
                        <thead>
                            <tr>
                                <th>{{__('t.id')}}#</th>
                                <th>{{__('t.user')}}</th>
                                <th>{{__('t.paypal-email')}}</th>
                                <th>{{__('t.amount')}}</th>
                                <th>{{__('t.status')}}</th>
                                <th>{{__('t.date')}}</th>
                                <th>{{__('t.comments')}}</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach ($withdrawals as $withdrawal)
                            <tr>
                                <td>{{ $withdrawal->id }}</td>
                                <td><a ref="#">{{ $withdrawal->user->name }}</a></td>
                                <td>{{ $withdrawal->paypal_email }}</td>
                                <td>{{ $withdrawal->amount }}</td>
                                <td>{{ $withdrawal->status }}</td>
                                <td>{{ $withdrawal->created_at->format('m-d-Y') }}</td>
                                <td>{{ $withdrawal->comment }}</td>
                                <td>
                                    <a href="#" data-backdrop="static" data-keyboard="false" class="updateWithdrawal btn btn-primary btn-sm" data-toggle="modal" data-id="{{$withdrawal->id}}" data-status="{{$withdrawal->status}}" data-comment="{{$withdrawal->comment}}" data-target="#updateWithdrawal">
                                        {{__('t.update')}}
                                    </a>
                                    
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div><!--col-->
        </div><!--row-->
        <div class="row">
            <div class="col-7">
                <div class="float-left">
                    
                </div>
            </div><!--col-->

            <div class="col-5">
                <div class="float-right">
                    
                </div>
            </div><!--col-->
            
        </div><!--row-->
    </div><!--card-body-->
</div><!--card-->



@endsection

@push('after-scripts')
    <!--------------------- modal for sending messages --------------------->
    <div class="modal fade" id="updateWithdrawal" role="dialog">
        <div class="modal-dialog modal-md">
          <div class="modal-content">
            
            <div class="modal-body clearfix">
                <form action="{{route('admin.finance.withdrawals.approval')}}" method="POST" id="updateWithdrawalRequest">
                    {{ method_field('PUT') }}
                    {{ csrf_field() }}
                    <div class="form-group">
                        <input type="hidden" id="withdrawal_id" name="withdrawal_id"/>
                    </div>
                    <div class="form-group">
                        <select class="form-control" name="status" id="withdrawal_status" >
                            <option value="pending">{{__('t.pending')}}</option>
                            <option value="processing">{{__('t.processing')}}</option>
                            <option value="processed">{{__('t.processed')}}</option>
                        </select> 
                    </div>
                    <div class="form-group">
                        <textarea name="comment" class="form-control" id="withdrawal_comment" placeholder="{{__('t.enter-notes')}}"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer clearfix">
              <button type="button" class="btn btn-default" data-dismiss="modal">{{__('t.close')}}</button>
              <button type="submit" form="updateWithdrawalRequest" class="btn btn-success">{{__('t.update')}}</button>
            </div>
          </div>
        </div>
      </div>
    <!--------------------- //modal for sending message --------------------->
    
    <script type="text/javascript">

        $(document).on("click", ".updateWithdrawal", function () {
            var withdrawal_id = $(this).data('id');
            var withdrawal_status = $(this).data('status');
            var withdrawal_comment = $(this).data('comment');
             
            $(".modal-body #withdrawal_id").val(withdrawal_id);
            $(".modal-body #withdrawal_status").val(withdrawal_status);
            $(".modal-body #withdrawal_comment").val(withdrawal_comment);
        });
    </script>
@endpush