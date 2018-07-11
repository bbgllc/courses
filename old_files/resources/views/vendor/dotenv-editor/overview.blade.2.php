@extends ('backend.layouts.app')

@section('content')
    <div id="app" class="row">
        <div class="col">
            <div class="card">
                <div class="card-header">
                    
                    <ul class="nav nav-tabs card-header-tabs">
                        
                        <li class="nav-item" v-for="(index,view) in views" role="presentation">
    						<a class="nav-link" :class="index==0 ? 'active' : ''" :href="'#'+view.slug" data-toggle="tab" @click="setActiveView(view.name)" role="tab">
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

    <script src="https://cdnjs.cloudflare.com/ajax/libs/vue/1.0.26/vue.js"></script>
    <script>
        new Vue({
            el: '#app',
            data: {
                loadButton: true,
                alertsuccess: 0,
                alertmessage: '',
                views: [
                    {name: "{{ trans('dotenv-editor::views.overview') }}", active: 1, slug: "overview"},
                    {name: "{{ trans('dotenv-editor::views.addnew') }}", active: 0, slug: "addnew"},
                    {name: "{{ trans('dotenv-editor::views.backups') }}", active: 0, slug: "backups"},
                    {name: "{{ trans('dotenv-editor::views.upload') }}", active: 0, slug: "upload"}
                ],
                newEntry: {
                    key: "",
                    value: ""
                },
                details: {},
                currentBackup: {
                    timestamp: ''
                },
                toEdit: {},
                toDelete: {},
                deleteModal: {
                    title: '',
                    content: ''
                },
                token: "{!! csrf_token() !!}",
                entries: [

                ]
            },
            methods: {
                loadEnv: function(){
                    var vm = this;
                    this.loadButton = false;
                    $.getJSON("/{{ $url }}/getdetails", function(items){
                        vm.entries = items;
                    });
                },
                setActiveView: function(viewName){
                    $.each(this.views, function(key, value){
                        if(value.name == viewName){
                            value.active = 1;
                        } else {
                            value.active = 0;
                        }
                    })
                },
                addNew: function(){
                    var vm = this;
                    var newkey = this.newEntry.key;
                    var newvalue = this.newEntry.value;
                    $.ajax({
                        url: "/{{ $url }}/add",
                        type: "post",
                        data: {
                            _token: this.token,
                            key: newkey,
                            value: newvalue
                        },
                        success: function(){
                            vm.entries.push({
                                key: newkey,
                                value: newvalue
                            });
                            var msg = "{{ trans('dotenv-editor::views.new_entry_added') }}";
                            vm.showAlert("success", msg);
                            vm.alertsuccess = 1;
                            $("#newkey").val("");
                            vm.newEntry.key = "";
                            vm.newEntry.value = "";
                            $("#newvalue").val("");
                            $('#newkey').focus();
                        }
                    })
                },
                editEntry: function(entry){
                    this.toEdit = {};
                    this.toEdit = entry;
                    $('#editModal').modal('show');
                },
                updateEntry: function(){
                    var vm = this;
                    $.ajax({
                        url: "/{{ $url }}/update",
                        type: "post",
                        data: {
                            _token: this.token,
                            key: vm.toEdit.key,
                            value: vm.toEdit.value
                        },
                        success: function(){
                            var msg = "{{ trans('dotenv-editor::views.entry_edited') }}";
                            vm.showAlert("success", msg);
                            $('#editModal').modal('hide');
                        }
                    })
                },
                makeBackup: function(){
                    var vm = this;
                    $.ajax({
                        url: "/{{ $url }}/createbackup",
                        type: "get",
                        success: function(){
                            vm.showAlert('success', "{{ trans('dotenv-editor::views.backup_created') }}");
                        }
                    })
                },
                showBackupDetails: function(timestamp, formattedtimestamp){
                    this.currentBackup.timestamp = timestamp;
                    var vm = this;
                    $.getJSON("/{{ $url }}/getdetails/" + timestamp, function(items){
                        vm.details = items;
                        $('#showDetails').modal('show');
                    });
                },
                restoreBackup: function(timestamp){
                    var vm = this;
                    $.ajax({
                        url: "/{{ $url }}/restore/" + timestamp,
                        type: "get",
                        success: function(){
                            vm.loadEnv();
                            $('#showDetails').modal('hide');
                            vm.setActiveView('overview');
                            vm.showAlert('success', '{{ trans('dotenv-editor::views.backup_restored') }}');
                        }
                    })
                },
                deleteEntry: function(){
                    var entry = this.toDelete;
                    var vm = this;

                    $.ajax({
                        url: "/{{ $url }}/delete",
                        type: "post",
                        data: {
                            _token: this.token,
                            key: entry.key
                        },
                        success: function(){
                            var msg = "{{ trans('dotenv-editor::views.entry_deleted') }}";
                            vm.showAlert("success", msg);
                        }
                    });
                    this.entries.$remove(entry);
                    this.toDelete = {};
                    $('#deleteModal').modal('hide');
                },
                showAlert: function(type, message){
                    this.alertmessage = message;
                    this.alertsuccess = 1;
                },
                closeAlert: function(){
                    this.alertsuccess = 0;
                },
                modal: function(entry){
                    this.toDelete = entry;
                    this.deleteModal.title = "{{ trans('dotenv-editor::views.delete_entry') }}";
                    this.deleteModal.content = entry.key + "=" + entry.value;
                    $('#deleteModal').modal('show');
                }
            }
        })
    </script>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.js"></script>
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

@endsection
@push('after-scripts')
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
