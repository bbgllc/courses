<template>
  <uploader :options="options" 
    :attrs="attrs" 
    @file-added="fileAdded"
    @file-success="fileSuccess"
    class="uploader-area">
    <uploader-unsupport></uploader-unsupport>
    <div class="col-md-12">
        <uploader-drop v-if="showUpload">
            <uploader-btn :attrs="attrs" :single="single">
                <i class="fa fa-cloud-upload" aria-hidden="true"></i>
                 {{ trans('t.add-file') }}
            </uploader-btn>
        </uploader-drop>
    </div>
    <div class="col-md-12" v-if="showList">
        <uploader-list></uploader-list>
    </div>
  </uploader>
</template>

<script>

    import uploader from 'vue-simple-uploader'
    
    export default {
        data () {
          return {
              showUpload: true,
              showList: false,
            options: {
                target: '/api/attachments/'+this.thread_id+'/'+this.auth_user+'/upload',
                testChunks: false,
            },
            single: true,
            attrs: {
              //accept: 'image/*,application/zip'
              accept: '.gif,.jpg,.png,.doc,.docx,.pdf,.xls,.xlsx,.zip'
            }
          }
        },
        
        props: [
           'thread_id',
           'auth_user'
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
            
            complete(){
                
            }
        },
        
        mounted(){
           
        }
  }
</script>

<style>
    .uploader-drop {
        position: relative;
        padding: 0px !important;
        overflow: hidden;
        border: 0px dashed #ccc !important;
        background-color: #f5f5f5;
    }
    .uploader-area {
        width: 100%;
        padding: 0px;
        margin: 0px auto 0;
        font-size: 12px;
    }
    .uploader-btn {
        position: relative;
        padding: 4px 8px;
        font-size: 100%;
        line-height: 1.4;
        color: #fff !important;
        border: 1px solid #607D8B !important;
        cursor: pointer;
        border-radius: 2px !important;
        background: #607D8B !important;
        outline: none;
    }
    .uploader-area .uploader-btn {
        margin-right: 4px;
        display:inline;
        width:100px%;
    }
    .uploader-area .uploader-list {
        max-height: 440px;
        overflow: auto;
        overflow-x: hidden;
        overflow-y: auto;
        margin-top: 10px;
    }
  
    .uploader-list>ul {
        list-style: none;
        margin: 0;
        padding: 0;
    }
    
    .uploader-list>ul li {
        padding:0;
    }
   
</style>