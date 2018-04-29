<template>
    <uploader ref="uploader"
        :options="options" 
        :attrs="attrs" 
        :autoStart="autoStart"
        @file-added="fileAdded"
        @file-success="fileSuccess"
        @file-removed="fileRemoved"
        class="uploader-area"
    >
        <uploader-unsupport></uploader-unsupport>
        <div class="row">
            <div class="col" v-if="showUpload">
                <uploader-drop>
                    <uploader-btn :attrs="attrs" :single="single">
                        <i class="fa fa-cloud-upload" aria-hidden="true"></i>
                         Select Video (.mp4)
                    </uploader-btn>
                </uploader-drop>
            </div>
            <div class="col" v-if="showList">
                <div class="row">
                    <div class="col-md-9" style="padding: 0px 0px 0px 15px;">
                        <uploader-list></uploader-list>
                    </div>
                    <div class="col-md-3" style="padding:0;">
                        <button type="button" v-if="!started" class="btn btn-info btn-block" @click="startUpload" style="height:49px;">
                            Start Upload
                        </button>
                        <button type="button" v-if="started" class="btn btn-danger btn-block" @click="stopUpload" style="height:49px;">
                            Stop Upload
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </uploader>
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
                options: {
                    target: '/api/author/lesson/'+this.lesson.id+'/video/upload',
                    testChunks: false,
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
            fileAdded() {
                this.showUpload = false
                this.showList = true
            },
            
            uploadStarted(){
                
            },
            
            fileSuccess(){
                this.$emit('file-uploaded', 'Uploaded');
                setTimeout(() => {
                    this.showUpload = true
                    this.showList = false
                }, 2000)
                
                
            },
            
            fileRemoved(){
                this.$emit('file-removed', 'Removed'); 
            },
            
            complete(){
                
            },
            startUpload(){
                this.started = true
                this.uploader.upload()
            },
            
            stopUpload(){
                this.started = false
                this.uploader.cancel()
                Bus.$emit('content.cancelled', 'New')
            }
        },
        
        mounted(){
            this.uploader = this.$refs.uploader.uploader

        }
  }
</script>