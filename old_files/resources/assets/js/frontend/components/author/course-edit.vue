
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
                
                form: new Form({
                    title: '',
                    subtitle: '',
                    slug: '',
                    category: '',
                    description: '',
                    language: '',
                    level: '',
                    published: false,
                    approved: false
                    
                }),
                
                src: '',
                uploadURL: '/api/author/course/image/'+this.course.id,
                uploading: false,
                
                parent_category: '',
                categories: [],
                subcategories: [],
                languages: [],
                
                editorOption: {
                  modules: {
                    toolbar: [
                      ['bold', 'italic'],
                      ['code-block'],
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
            'course',
        ],
        
        methods: {
            updateCourse(){
                this.form.put('/api/author/course/'+this.course.slug+'/update')
                    .then(({ data }) => {
                        window.location.href = '/author/course/'+data.slug+'/edit';
                    })
            },

            
            fetchSubcategories(){
                this.subcategories = [];
                axios.get('/api/subcategories/'+this.parent_category).then((response) => {
                    this.subcategories = response.data;
                })
            },
            
            fileUploaded(response) {
                this.src=response.data.path;  
                this.uploading = false;
            },
            
            onEditorReady(editor) {

            },
            
            sansAccent(text){
                let w = "àâäçéèêëîïôöùûüÿÀÂÄÇÉÈÊËÎÏÔÖÙÛÜŸ".split("");
                w.push("Œ","œ");
                let wo = "aaaceeeeiioouuuyAAACEEEEIIOOUUUY".split("");
                wo.push("OE","oe");    
                
                for(var i=0 ; i< w.length ; i++){
                    text = text.replace( new RegExp(w[i],"g") , wo[i]);
                }
                return text;
            },
            
            sanitizeTitle: function(title) {
                let str = this.sansAccent(title);
    			str = str.replace(/[^a-zA-Z0-9\s]/g,"");
    			str = str.toLowerCase();
    			str = str.replace(/\s/g,'-');
    			
    			return str;     
                
            },
            
            generateSlug(){
                this.form.slug = this.sanitizeTitle(this.form.title);
            }
           
        },
        
        mounted() {
            this.form.title = this.course.title
            this.form.subtitle = this.course.subtitle
            this.form.slug = this.course.slug
            this.form.category = this.course.category_id
            this.form.description = this.course.description
            this.form.language = this.course.language
            this.form.level = this.course.level
            this.form.published = this.course.published
            this.form.approved = this.course.approved
            
            this.src=this.course.cover_image;
            
            axios.get('/api/categories').then((response) => {
                this.categories = response.data;
            });
            
            axios.get('/api/languages').then((response) => {
                this.languages = response.data;
            })
            
            this.parent_category = this.course.parent_category;
            
            this.fetchSubcategories();
            
            
            
        }
        
    }
</script>

<style>
    .ql-editor{
        min-height: 130px;
    }
</style>