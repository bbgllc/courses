<template>
    <div>
        <span v-if="uploading">
            <vue-simple-progress size="small" :val="progress" text="20%"></vue-simple-progress>
        </span>
        <input type="file" @change="onFileSelected"
            accept="image/*,.zip,.pdf,.doc,.docx,.xls,.xlsx,.txt,.ppt,.sql"
            style="display:none;" ref="fileinput"/>
        
        <span v-if="selectedFile && !uploading">
            {{ selectedFile.name }}
            <a href="#" class="badge badge-success" @click.prevent="onUpload">
                Start upload
            </a>
            <a href="#" @click.prevent="selectedFile=''" class="text-danger">Cancel</a>
        </span>
        <div class="alert alert-danger" v-if="message">{{message}}</div>
        <span v-if="!selectedFile">
            <button class="btn btn-sm btn-success" 
                @click="$refs.fileinput.click()">
                {{trans('t.select-file')}}
            </button>
            <a href="#" @click.prevent="cancelProcess" class="text-danger">
                {{trans('t.cancel')}}
            </a>
        </span>
    </div>
</template>

<script>

    import Bus from '../../../bus'
    import ProgressBar from 'vue-simple-progress'
    
    export default {
        data () {
            return {
                selectedFile: '',
                uploading: false,
                progress: 0,
                max_upload_size: 0,
                message: ''
            }
        },
        
        props: [
           'lesson'
        ],
        
        components: {
            ProgressBar
        },
        
        methods: {
            onFileSelected(e){
                this.selectedFile = e.target.files[0]
                var fileSize = this.selectedFile.size/1000000
                if(fileSize > this.max_upload_size){
                    this.message = 'Max. file size allowed: ' + this.max_upload_size + 'MB.' 
                    this.selectedFile = ''
                } else {
                    this.message = ''
                }
            },
            
            onUpload(){
                this.uploading=true
                var fd = new FormData()
                fd.append('file', this.selectedFile, this.selectedFile.name)
                axios.post('/api/author/lesson/'+this.lesson.id+'/attachment/upload', fd,{
                        onUploadProgress: uploadEvent => {
                            this.progress = Math.round(uploadEvent.loaded / uploadEvent.total*100)
                        }
                    }).then(res => {
                        this.$emit('file-uploaded', 'Uploaded');
                        setTimeout(() => {
                            this.selectedFile = ''
                            this.uploading = false
                        }, 2000)
                    })
            },
            
            cancelProcess(){
                this.$emit('file-uploaded', 'Uploaded');
            }
            
            
        },
        
        mounted(){
            this.max_upload_size = this.settings('vms');
        }
  }
</script>