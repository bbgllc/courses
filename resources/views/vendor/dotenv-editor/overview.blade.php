@extends ('backend.layouts.app')

@section('content')
    
    <env-editor inline-template url="{{$url}}" token="{{csrf_token()}}" v-cloak>
                    
        <div class="row">
            <div class="col">
                <div class="card">
                    <div class="card-header">
                        <ul class="nav nav-tabs card-header-tabs pt-4">
                            <li class="nav-item"
                                v-for="view in views" 
                                :class="view.active ? 'border border-top-1 border-left-0 border-right-0 border-bottom-0' : ''"
                                role="presentation" v-if="view.name == 'overview'">
        						<a class="nav-link" :class="view.active ? 'active' : ''" :href="'#'+view.slug" data-toggle="tab" @click="setActiveView(view.name)" role="tab">
        						    @{{ view.name }}
    						    </a>
        					</li>
                        </ul>
                    </div><!--card-header-->
                    <div class="card-block">
                        {{-- Error-Container --}}
        				<div>
        					{{-- VueJS-Errors --}}
        					<div class="alert alert-success" role="alert" v-show="alertsuccess">
        						<button type="button" class="close" @click="closeAlert" aria-label="Close">
        							<span aria-hidden="true">&times;</span>
        						</button>
        						@{{ alertmessage }}
        					</div>
        					{{-- Errors from POST-Requests --}}
        					@if(session('dotenv'))
        						<div class="alert alert-success alert-dismissable" role="alert">
        							<button type="button" class="close" aria-label="Close" data-dismiss="alert" aria-label="Close">
        							<span aria-hidden="true">&times;</span>
        							</button>
        							{{ session('dotenv') }}
        						</div>
        					@endif
        				</div>
                        
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active" :id="views[0].slug" role="tabpanel" aria-labelledby="overview-tab">
                                @include('dotenv-editor::tabs._overview')
                            </div>
                            
                            <div class="tab-pane fade" :id="views[1].slug" role="tabpanel" aria-labelledby="add-new-tab">
                                @include('dotenv-editor::tabs._add_new')
                            </div>
                            <div class="tab-pane fade" :id="views[2].slug" role="tabpanel" aria-labelledby="backups-tab">
                                @include('dotenv-editor::tabs._backups')
                            </div>
                            <div class="tab-pane fade" :id="views[3].slug" role="tabpanel" aria-labelledby="upload-tab">
                                @include('dotenv-editor::tabs._uploads')    
                            </div>
                            
                        </div>
                    </div><!--card-block-->
                </div><!--card-->
            </div><!--col-->
        </div><!--row-->
    </env-editor>

    
    
    
    

@endsection
@push('after-scripts')
    <script>
        $(document).ready(function(){
            $(function () {
                $('[data-toggle="popover"]').popover()
            });
            $(function () {
                $('[data-toggle="tooltip"]').tooltip()
            })
        })
    </script>
    
    <style type="text/css">
        .modal-backdrop {
            position: relative;
        }
        
        .modal-dialog {
            max-width: 500px;
            margin: 70px auto;
        }
        .btn-sm, .btn-group-sm > .btn {
            padding: 0.1rem 0.25rem;
        }
    </style>
@endpush
