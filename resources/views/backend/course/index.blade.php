@extends ('backend.layouts.app')

@section ('title', __('t.course-management'))

@section('breadcrumb-links', '')

@section('content')
<course-list inline-template>
    <div class="card">
        <div class="card-header">
            <i class="fa fa-align-justify"></i>
            <strong>{{ __('t.courses') }}</strong>
            <small class="text-muted">{{ __('t.manage-courses') }}</small>
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
                        <template slot="table-column" slot-scope="props">
                            <span v-if="props.column.label !='actions'">
                                @{{props.column.label}}
                            </span>
                        </template>
                        <template slot="table-row-after" slot-scope="props">
                            <td>
                                <a :href="'/admin/course/courses/'+props.row.slug+'/details'" class="btn btn-sm btn-primary">
                                   <i class="fa fa-eye" data-toggle="tooltip" data-placement="top" title="{{__('t.details')}}"></i> 
                                </a>
                                
                                <a href="#" v-if="props.row.can_be_deleted" @click.prevent="deleteCourse(props.row.id)" class="btn btn-sm btn-danger">
                                    <i class="fa fa-trash text-white"></i>
                                </a>
                            </td>
                        </template>
                    </div>
                    
                </div><!--col-->
            </div><!--row-->

        </div><!--card-body-->
    </div><!--card-->
</course-list>

@endsection
