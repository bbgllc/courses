
<script>
    import imageUpload from 'vue-core-image-upload';
    
    export default {
        data: function () {
            return {
                uploadURL: this.url,
                src: '',
                saveStatus: '',
      
            }
        },    
        
        components: {
            imageUpload
        },
        
        props: [
            'img_src',
            'url',
            'msg',
            'field'
        ],
        
        methods: {
            fileUploaded(response) {
                this.saveStatus = 'Upload complete';
                    setTimeout(() => {
                       this.saveStatus = null 
                    }, 1000);
                this.src=response.data.path;  
            }
        },
        
        mounted() {
            this.src = this.img_src
        }
        
    }
</script>

