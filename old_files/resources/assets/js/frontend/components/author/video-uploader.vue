<template>
    <vue-clip ref="vc" :options="options" 
        :on-added-file="fileAdded" 
        :on-complete="complete"
        :on-removed-file="removedFile">
        
        <template slot="clip-uploader-action" v-if="showUpload">
            <div class="uploader-action">
                <div class="dz-message">
                    <button type="button" class="btn btn-success btn-sm">
                        {{ trans('t.choose-file') }}
                    </button>
                </div>
            </div>
        </template>
        
        <template slot="clip-uploader-body" slot-scope="props" v-if="showList">
            <div v-for="file in props.files">

                {{ file.name }} <!--{{ file.status }}--> <span class="text-danger">{{ file.errorMessage }}</span><br />
                
                <div class="row" v-if="file.status == 'error'">
                    <div class="col-12">
                        <a href="#" @click.prevent="cancelWithError">{{ trans('t.cancel') }}</a>
                    </div>
                </div>
                <div class="row" v-if="file.status !== 'error' && file.status !== 'success'">
                    <div class="col-10 mr-0 pr-0">
                        <div class="progress rounded-0" style="height:30px">
                          <div class="progress-bar bg-success rounded-0" style="height: 30px;" v-bind:style="{ width: file.progress + '%'}">
                              {{ Math.floor(file.progress) + "%" }}
                          </div>
                        </div>
                    </div>
                    <div class="col-2 ml-0 pl-0">
                        <a href="#" style="height:30px;" @click.prevent="removeFile(file)" 
                            class="btn btn-sm btn-block btn-danger rounded-0">
                            <i class="fa fa-stop-circle"></i> {{ trans('t.cancel') }}
                        </a>
                    </div>
                </div>
            </div>
        </template>
    </vue-clip>
   
</template>

<script>

    import uploader from 'vue-simple-uploader'
    import Bus from '../../../bus'
    
    export default {
        data () {
            return {
                showUpload: true,
                showList: false,
                started: false,
                uploader:{},
                
                options: {
                    url: '/api/author/lesson/'+this.lesson.id+'/video/upload',
                    paramName: 'file',
                    maxFiles: 1,
                    maxFilesize: {
                        limit: this.settings('vmz'), // 1mb
                        message: this.trans('t.max-video-size') + ' ' + this.settings('vms')
                    },
                    acceptedFiles: {
                        extensions: ['video/mp4'],
                        message: this.trans('t.invalid-file')
                    }
                },
                autoStart: false,
                single: true,
                attrs: {
                    accept: 'video/mp4'
                }
            }
        },
        
        props: [
           'lesson'
        ],
        
        methods: {
            
            removeFile(file) {
                this.$refs.vc.removeFile(file)
            },
            
            removedFile(e){
                this.showUpload = true
                this.showList = false
            },
            
            fileAdded() {
                this.showUpload = false
                this.showList = true
                this.$emit('video-upload-started', 'Started');
            },
            
            cancelWithError(){
               this.$emit('file-uploaded', 'Uploaded'); 
            },
            
            complete(file, status, xhr){
                if(status !== 'error'){
                    this.$emit('file-uploaded', 'Uploaded');
                    setTimeout(() => {
                        this.showUpload = true
                        this.showList = false
                    }, 2000)
                }
            },
            
        },
        
        mounted(){
            
        }
  }
</script>