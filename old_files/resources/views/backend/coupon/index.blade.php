@extends ('backend.layouts.app')

@section ('title', __('t.discount-coupons'))

@section('breadcrumb-links', '')

@section('content')
<div class="card">
    <div class="card-header">
        <i class="fa fa-align-justify"></i>
        <strong>{{__('t.discount-coupons')}}</strong>
        <small class="text-muted">{{__('t.manage-discount-coupons')}}</small>
        
        <div class="btn-toolbar float-right" role="toolbar" data-toggle="tooltip" title="{{__('t.create-new')}}">
            <a href="#" class="btn btn-sm btn-success ml-1" data-backdrop="static" data-keyboard="false" data-toggle="modal" data-target="#createCouponModal" >
                <i class="fa fa-plus-circle"></i>
            </a>
        </div><!--btn-toolbar-->
    </div><!--card-header-->
    <div class="card-body">

        <div class="row mt-4">
            <div class="col">
                <div class="table-responsive">
                    <table class="table table-striped table-sm">
                        <thead>
                        <tr>
                            <th>{{__('t.code')}}</th>
                            <th>{{__('t.percent-off')}}</th>
                            <th>{{__('t.quantity')}}</th>
                            <th>{{__('t.redeemed')}}</th>
                            <th>{{__('t.expires')}}</th>
                            <th>{{__('t.status')}}</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach($coupons as $coupon)
                                <tr>
                                    <td>{{ $coupon->code }}</td>
                                    <td>{{ $coupon->percent }}</td>
                                    <td>{{ $coupon->quantity }}</td>
                                    <td>{{ $coupon->redeemed }}</td>
                                    <td>{{ $coupon->expires }}</td>
                                    <td>{!! $coupon->active ? '<span class="badge badge-success">'.__('t.active').'</span>' : '<span class="badge badge-warning">' . __('t.inactive') . '</span>' !!}</td>
                                    <td>
                                        @if($coupon->active)
                                            <a href="{{route('admin.finance.coupons.activation', $coupon->id)}}" class="btn btn-sm btn-warning">
                                                {{__('t.deactivate')}}
                                            </a>
                                        @else
                                            <a href="{{route('admin.finance.coupons.activation', $coupon->id)}}" class="btn btn-sm btn-success">
                                                {{__('t.activate')}}
                                            </a>
                                            @if($coupon->redeemed == 0)
                                                <a href="{{ route('admin.finance.coupons.destroy', $coupon->id) }}"
                                                     data-method="delete"
                                                     data-trans-button-cancel="{{__('t.cancel')}}"
                                                     data-trans-button-confirm="{{__('t.delete')}}"
                                                     data-trans-title="{{__('t.are-you-sure')}}"
                                                     class="btn btn-sm btn-danger text-white">
                                                    {{__('t.delete')}}
                                                </a>
                                            @endif
                                        @endif
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

@section('modals')

    <!-- Modal -->
    <div class="modal fade" id="createCouponModal" tabindex="-1" role="dialog" aria-labelledby="CreateCoupon" aria-hidden="true">
        <div class="modal-dialog modal-success" role="document">
            {!! Form::open(['route' => ['admin.finance.coupons.store'], 'method'=>'POST', 'class' => 'form-horizontal create-coupon-form']) !!}
                <div class="modal-content">
                    <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{__('t.create-coupon')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="">
                      <span aria-hidden="true">&times;</span>
                    </button>
                    </div>
                    <div class="modal-body">
                        {{ csrf_field() }}
                        <div class="form-group">
                            {!! Form::label("code", __('t.code')) !!}
                            {!! Form::text("code", null, ['class'=>'form-control', 'required']) !!}
                             @if ($errors->has('code'))
                                <div class="text-danger"><small>{{ $errors->first('code') }}</small></div>
                            @endif
                        </div>
                        
                        <div class="form-group">
                            {!! Form::label("quantity", __('t.quantity')) !!}
                            {!! Form::number("quantity", null, ['class'=>'form-control', 'required']) !!}
                             @if ($errors->has('quantity'))
                                <div class="text-danger"><small>{{ $errors->first('quantity') }}</small></div>
                            @endif
                        </div>
                        
                        <div class="form-group">
                            {!! Form::label("percent", __('t.percent-off')) !!}
                            {!! Form::number("percent", null, ['class'=>'form-control', 'required']) !!}
                             @if ($errors->has('percent'))
                                <div class="text-danger"><small>{{ $errors->first('percent') }}</small></div>
                            @endif
                        </div>
                        
                        <div class="form-group">
                            {!! Form::label("expires", __('t.expires')) !!}
                            {!! Form::date("expires", null, ['class'=>'form-control expires', 'required']) !!}
                            <span class="err text-danger"></span>
                             @if ($errors->has('expires'))
                                <div class="text-danger"><small>{{ $errors->first('expires') }}</small></div>
                            @endif
                        </div>
                        
            	        {{ csrf_field() }}
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">{{__('t.close')}}</button>
                        <button type="submit" id="create-coupon-submit" class="btn btn-primary">{{__('t.create')}}</button>
                    </div>
                </div>
            {!! Form::close() !!}
        </div>
    </div>
    
    <script>
        $(document).ready(function($){
            var CurrentDate = new Date();
            $('.expires').on('change', function(){
                
            })
            
    		// create category
    		$('#create-coupon-submit').click(function(e){
    		    e.preventDefault();
    		    var expireDateStr = $('.expires').val();
                var expireDateArr = expireDateStr.split("-");
    
                var expireDate = new Date(expireDateArr[0], expireDateArr[1], expireDateArr[2]);
                var todayDate = new Date();
                if (todayDate > expireDate) {
                    $('.err').html("{{__('t.expiry-date-cannot-be-in-the-past')}}");
                } else {
                    $('.err').html('');
                    $('.create-coupon-form').submit();
                };
    		});
    
        });
        
        //confirm delete
        $('button.delete').on('click', function(e){
            e.preventDefault();
            
            swal({   
                title: "{{__('t.are-you-sure')}}",
                text: "",
                type: "warning",   
                showCancelButton: true,   
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes Delete!", 
                closeOnConfirm: false,
                closeOnCancel: false,
                showLoaderOnConfirm: true
            }, 
            
            function(isConfirmed){   
                if(isConfirmed){
                    setTimeout(function(){
                        swal("Deleted", "", "success");
                        $(".delete-coupon-form").submit();
                    }, 2000);
                } else {
                       swal("Cancelled", "", "error"); 
                }
              
            });
        });
    </script>
@stop