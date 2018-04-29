<template>
    
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <video-uploader 
                    :lesson="lesson" 
                    @file-uploaded="uploadCompleted"
                    @file-removed="uploadCancelled"
                    @video-upload-started = "uploadStarted">
                </video-uploader>
                
            </div>
            <a href="#" @click.prevent="cancelContentAdd" v-if="!videoIsUploading" style="margin-left:0px; padding-top:15px;">
                {{ trans('t.cancel') }}
            </a>
        </div>
    </div>
</template>

<script>
    import Bus from '../../../bus'

    export default {
        data () {
            return {
                videoIsUploading: false
            }
        },
        
        
        props: ['lesson'],
        
        methods: {
            uploadStarted(){
                this.videoIsUploading = true
            },
            
            uploadCompleted(){
                Bus.$emit('content.added', 'New')
            },
            
            cancelContentAdd(){
                Bus.$emit('content.cancelled', 'New')
            },
            
            uploadCancelled(){
                this.videoIsUploading = false
                Bus.$emit('content.cancelled', 'New')
            }
            
            
        },
        
        mounted () {
            
        }
    }
</script>

<style>

    .uploader-drop {
        position: relative;
        padding: 0px !important;
        overflow: hidden;
        border: 1px solid #cacbcc !important;
        background-color: #f2f3f5 !important;
        height: 40px;
        color:#686f7a !important;
    }
    .uploader-area .uploader-btn {
        float: right;
        height: 100%;
        border-radius: 0px !important;
        margin-right: 0 !important;
        background: #17a2b8 !important;
        color: #fff !important;
        border: 1px solid #17a2b8 !important;
    }

    .uploader-file {
        border-bottom: 0px solid #cdcdcd !important;
        background: #f3f3f3;
    }
    
    .uploader-file-progress {
        background: #c1e4f5 !important;
    }
    .uploader-area .uploader-list {
        margin-top: 0 !important;
    }
</style>