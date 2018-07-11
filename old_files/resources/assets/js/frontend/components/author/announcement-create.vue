
<script>

    import Form from 'vform'
    import Quill from 'quill';
    import { quillEditor } from 'vue-quill-editor';
    import 'quill/dist/quill.core.css'
    import 'quill/dist/quill.snow.css'
    import Selectize from 'vue2-selectize'

    export default {
        data: function () {
            return {
                
                form: new Form({
                    title: '',
                    body: '',
                    courses: ''
                }),
                settings: {
                    maxItems: 10,
                    hideSelected: true,
                    plugins: ['remove_button', 'restore_on_backspace'],
                    delimiter: ',',
                    persist: false,
                }, 
                selected: 1,
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
            Selectize
        },
        
        props: [
            'course',
            'courses'
        ],
        
        methods: {
            saveAnnouncement(){
                this.form.post('/api/author/courses/'+this.course.slug+'/announcements')
                    .then(({ data }) => {
                        window.location.href = '/author/courses/'+this.course.slug+'/announcements';
                    })
            }
           
        },
        
        mounted() {
            
        }
        
    }
</script>

<style>
    .ql-editor{
        min-height: 130px;
    }
</style>