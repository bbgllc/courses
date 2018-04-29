
<script>

    export default {
        data: function () {
            return {
                loadButton: true,
                alertsuccess: 0,
                alertmessage: '',
                views: [
                    {name: this.trans('t.overview'), active: 1, slug: 'overview'},
                    {name: this.trans('t.add-new'), active: 0, slug: 'addnew'},
                    {name: this.trans('t.backups'), active: 0, slug: 'backups'},
                    {name: this.trans('t.upload'), active: 0, slug: 'upload'}
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
                
                entries: [

                ]
            }
        },    
      
        props: [
            'url',
            'token'
        ],
        
        methods: {
            loadEnv(){
                this.loadButton = false;
                axios.get('/'+this.url+'/getdetails').then((response) => {
                    this.entries = response.data
                })
            },
            
            setActiveView(viewName){
                $.each(this.views, function(key, value){
                    if(value.name == viewName){
                        value.active = 1;
                    } else {
                        value.active = 0;
                    }
                })
            },
            
            addNew(){
                if(this.settings('enable_demo')){
                    swal('Not allowed in Demo mode');
                    return;
                }
                var newkey = this.newEntry.key;
                var newvalue = this.newEntry.value;
                axios.post('/'+this.url+'/add', {
                    _token: this.token,
                    key: newkey,
                    value: newvalue
                }).then((response) => {
                    this.entries.push({
                        key: newkey,
                        value: newvalue
                    })
                    var msg = this.trans('t.new_entry_added')
                    this.showAlert("success", msg)
                    this.alertsuccess = 1
                    $("#newkey").val("")
                    this.newEntry.key = ""
                    this.newEntry.value = ""
                    $("#newvalue").val("")
                    $('#newkey').focus()
                })
            },
            
            editEntry(entry){
                if(this.settings('enable_demo')){
                    swal('Not allowed in Demo mode');
                    return;
                }
                this.toEdit = {};
                this.toEdit = entry;
                $('#editModal').modal('show');
            },
            updateEntry(){
                if(this.settings('enable_demo')){
                    swal('Not allowed in Demo mode');
                    return;
                }
                axios.post('/'+this.url+'/update', {
                    _token: this.token,
                    key: this.toEdit.key,
                    value: this.toEdit.value
                }).then(() => {
                    var msg = this.trans('t.entry-edited');
                    this.showAlert("success", msg);
                    $('#editModal').modal('hide');
                })
                
                
                /*
                
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
                })*/
                
            },
            
            makeBackup(){
                axios.get('/'+this.url+'/createbackup').then(()=>{
                    this.showAlert('success', this.trans('t.backup-created'))
                })
            },
            
            showBackupDetails(timestamp, formattedtimestamp){
                this.currentBackup.timestamp = timestamp;
                axios.get('/'+this.url+'/getdetails/'+timestamp).then((items) => {
                    this.details = items;
                    $('#showDetails').modal('show');
                });
            },
            
            restoreBackup(timestamp){
                if(this.settings('enable_demo')){
                    swal('Not allowed in Demo mode');
                    return;
                }
                axios.get('/'+this.url+'/restore/'+timestamp).then((items) => {
                    this.loadEnv();
                    $('#showDetails').modal('hide');
                    this.setActiveView('overview');
                    this.showAlert('success', this.trans('t.backup-restored'));
                });
            },
            
            deleteEntry(){
                if(this.settings('enable_demo')){
                    swal('Not allowed in Demo mode');
                    return;
                }
                var entry = this.toDelete;
                axios.post('/'+this.url+'/delete', {
                    _token: this.token,
                    key: entry.key
                }).then(()=>{
                    var msg = this.trans('t.entry-deleted');
                    this.showAlert("success", msg);
                })
                //this.entries.$remove(entry);
                this.loadEnv()
                this.toDelete = {};
                $('#deleteModal').modal('hide');
            },
            
            showAlert(type, message){
                this.alertmessage = message;
                this.alertsuccess = 1;
            },
            closeAlert(){
                this.alertsuccess = 0;
            },
            
            modal(entry){
                this.toDelete = entry;
                this.deleteModal.title =  this.trans('delete-entry');
                this.deleteModal.content = entry.key + "=" + entry.value;
                $('#deleteModal').modal('show');
            }
        },
        
        mounted() {
           //console.log('working')
        }
        
    }
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

