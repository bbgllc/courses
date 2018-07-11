
<script>
    import vueDropzone from 'vue2-dropzone'
    import 'vue2-dropzone/dist/vue2Dropzone.css'

    export default {
        data: function () {
            return {
                //src: '',
                dropzoneOptions: {
                    url: '/api/admin/post/'+this.post_id+'/image',
                    thumbnailWidth: 400,
                    thumbnailHeight: 250,
                    maxFilesize: 2,
                    maxFiles: 2,
                    acceptedFiles: 'image/*',
                    addRemoveLinks: true,
                    dictRemoveFile: 'Remove',
                    dictDefaultMessage: "<i class='fa fa-cloud-upload'></i>" + this.trans('t.choose-featured-image')
                }
            }
        },    
        
        components: {
            vueDropzone
        },
        
        props: [
            'post_id',
            'src'
        ],
        
        methods: {
            uploadSuccessful(file, response) {
                
            },
            removeFile(file, error, xhr){
                axios.get('/api/admin/image/'+this.post_id+'/removeImage');
            }
        },
        
        mounted() {
           if(this.src){
                var file = { size: 0, name: this.post_id };
                this.$refs.myVueDropzone.manuallyAddFile(file, this.src);
           }
        }
        
    }
</script>

<style>
    [v-cloak]{
        display: none;
    }
    .dropzone {
        border: 1px solid rgba(0, 0, 0, 0.15) !important;
    }
    .dz-size, .dz-filename{ display: none; }
    .dz-image img{
        width:100% !important;
        /*height: auto !important;*/
    }
    .dropzone {
        min-height: 150px;
        border: 2px solid rgba(0, 0, 0, 0.3);
        background: white;
        padding: 0;
    }
    .dropzone .dz-preview {
        position: relative;
        display: inline-block;
        vertical-align: top;
        margin: 0px;
        min-height: 100px;
    }
</style>
