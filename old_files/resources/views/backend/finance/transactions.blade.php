@extends ('backend.layouts.app')

@section ('title', __('t.transactions'))

@section('breadcrumb-links', '')

@section('content')
<transaction-list inline-template v-cloak>
    <div class="card">
        <div class="card-header">
            <i class="fa fa-align-justify"></i>
            <strong>{{__('t.transactions')}}</strong>
            <small class="text-muted">{{__('t.all-system-transactions')}}</small>
            
        </div><!--card-header-->
        <div class="card-body">
    
            <div class="row mt-4">
                <div class="col">
                    
                    <vue-good-table
                            :columns="columns"
                            :rows="rows"
                            :paginate="true"
                            :lineNumbers="true"
                            :defaultSortBy="{field: 'status_code', type: 'asc'}"
                            styleClass="table table-stripped table-bordered condensed"/>
                    
                    
                    <!--
                    <div class="table-responsive">
                        <table class="table table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>UUID#</th>
                                    <th>{{__('t.date')}}</th>
                                    <th>{{__('t.user')}}</th>
                                    <th>{{__('t.description')}}</th>
                                    <th>{{__('t.long-description')}}</th>
                                    <th>{{__('t.amount')}} ($)</th>
                                    <th>{{__('t.type')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach ($transactions as $transaction)
                                <tr>
                                    <td>{{ $transaction->uuid }}</td>
                                    <td>{{ $transaction->created_at->format('m-d-Y') }}</td>
                                    <td>{{ $transaction->user->name }}</td>
                                    <td>{{ $transaction->description }}</td>
                                    <td>{{ $transaction->long_description }}</td>
                                    <td>{{ $transaction->amount }}</td>
                                    <td>
                                        @if($transaction->type == 'credit')
                                            <span class="fa fa-plus-circle text-success"></span>
                                            {{ $transaction->type }}
                                        @else 
                                            <span class="fa fa-minus-circle text-danger"></span>
                                            {{ $transaction->type }}
                                        @endif
                                        
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    -->
                    
                    
                </div><!--col-->
            </div><!--row-->
            
        </div><!--card-body-->
    </div><!--card-->
</transaction-list>



@endsection

@push('after-scripts')
@endpush