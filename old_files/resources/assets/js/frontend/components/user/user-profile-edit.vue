
<script>

    import Form from 'vform'
    import imageUpload from 'vue-core-image-upload';
    import Quill from 'quill';
    import { quillEditor } from 'vue-quill-editor';
    import 'quill/dist/quill.core.css'
    import 'quill/dist/quill.snow.css'

    export default {
        data: function () {
            return {
                show_form_submit: false,
                countries: [],
                form: new Form({
                    country: '',
                    first_name: '',
                    last_name: '',
                    user: '',
                    biography: '',
                    headline: '',
                    website: '',
                    facebook: '',
                    linkedin: '',
                    twitter: '',
                    github: ''
                    
                }),
                
                src: '',
                uploadURL: '/api/user/'+this.user.id+'/avatar',
                uploading: false,
                
                editorOption: {
                  modules: {
                    toolbar: [
                      ['bold', 'italic'],
                      [{ 'list': 'ordered'}, { 'list': 'bullet' }]
                    ],
                    history: {
                      delay: 1000,
                      maxStack: 50,
                      userOnly: false
                    },
                    
                  }
                },
            }
        },    
        
        components: {
            quillEditor,
            imageUpload
        },
        
        props: [
            'user',
        ],
        
        methods: {
            updateProfile(){
                if(this.settings('enable_demo')){
                    swal('Not allowed in Demo mode');
                    return;
                }
                this.form.put('/api/update-profile')
                    .then(({ data }) => {
                        this.$toastr('success', this.trans('t.done'), this.trans('t.updated'))
                    })
            },

            
            fileUploaded(response) {
                this.src=response.data.path;  
                this.uploading = false;
                this.$toastr('success', this.trans('t.done'), this.trans('t.updated'))
                $('.profile-img').attr('src', this.src);
            },
            
            onEditorReady(editor) {

            },
            
           
        },
        
        mounted() {
            this.form.country = this.user.country_code
            this.form.first_name = this.user.first_name
            this.form.last_name = this.user.last_name
            this.form.username = this.user.username
            this.form.headline = this.user.tagline
            this.form.biography = this.user.bio
            this.form.web = this.user.web
            this.form.facebook = this.user.facebook
            this.form.twitter = this.user.twitter
            this.form.linkedin = this.user.linkedin
            this.form.github = this.user.github
            
            this.src=this.user.picture
            
            axios.get('/api/countries').then((response)=>{
                this.countries = response.data
                this.show_form_submit = true
            })
            

        }
        
    }
</script>

<style>
    .ql-editor{
        min-height: 130px;
    }
</style>