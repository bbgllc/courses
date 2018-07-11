
<script>
   
    import Quill from 'quill';
    import { quillEditor } from 'vue-quill-editor';
    
    export default {
        data: function () {
            return {
                questions: [],
                editQuestion: [],
                detailQuestion: [],
                results_count: null,
                showCreate: false,
                showEdit: false,
                showDetail: false,
                editorOption: {
                    modules: {
                        toolbar: [
                          ['bold', 'italic'],
                          ['code-block'],
                          [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                          ['link']
                        ],
                        history: {
                          delay: 1000,
                          maxStack: 50,
                          userOnly: false
                        }
                    }
                },
                body: '',
                title: '',
                err: [],
                saveStatus: null,
                current_page: 1,
                meta: [],
                total_pages: null,
                showEdit: false,
                editComment: [],
                keyword: ''
            }
        },    
  
        components: {
            quillEditor
        },
        
        props: [
            'course_id',
            'course_slug'
        ],
        methods: {
            resetForm(){
                this.current_page = 1;
                this.showEdit = false;
                this.showDetail = false;
                this.showCreate = false;
                this.title = '';
                this.body = '';
                this.detailQuestion = [];    
            },
            
            fetchQuestions(){
                return axios.get('/api/questions/'+ this.course_id + '/get_questions?page=' + this.current_page + '&keyword='+this.keyword)
                    .then((response) => {
                        this.questions = response.data.data;
                        this.total_pages = response.data.last_page;
                        this.current_page = 1;
                        this.resetForm();
                    }).catch((error) => {
                        console.log(error);
                    });
            },
            
            strippedHTML(msg) {
                return $(msg).text();
            },
          
            fetchMoreQuestions(){
                return axios.get('/api/questions/'+ this.course_id + '/get_questions?page=' + parseInt(this.current_page+1) + '&keyword='+this.keyword)
                    .then((response) => {
                       this.questions = this.questions.concat(response.data.data)
                       this.current_page = this.current_page+1
                    }).catch((error) => { 
                        console.log('Error fetching questions');
                    });    
            },
            
            onEditorReady(editor) {

            },
            
            storeQuestion() {
                axios.post('/api/questions/'+this.course_id+'/store_question', {
                    title: this.title,
                    body: this.body
                }).then((response) => {
                    this.fetchQuestions();
                    this.title = '';
                    this.body = '';
                    this.showCreate = false;
                }).catch((error) => {
                    this.err = error.response.data;
                })
            },
            
            fetchEditQuestion(id){
                this.showEdit = true;
                return axios.get('/api/questions/'+id+'/get_edit_question').then((response) => {
                    this.editQuestion = response.data;
                }).catch((error) => {
                    console.log('Could not fetch question');
                });
            },
            
            updateQuestion(id){
                axios.put('/api/questions/'+id+'/update_question', {
                    title: this.editQuestion.title,
                    body: this.editQuestion.body
                }).then((response) => {
                    this.fetchQuestions();  
                }).catch((error) => {
                    this.err = error.response.data;
                })
            },
            
            deleteQuestion(id){
                if(this.settings('enable_demo')){
                    swal('Not allowed in Demo mode');
                    return;
                }
                this.$dialog.confirm(this.trans('t.are-you-sure'), {
                    okText: this.trans('t.yes-delete'),
                    cancelText: this.trans('t.cancel'),
                    animation: 'zoom', // Available: "zoom", "bounce", "fade"
                    type: 'basic', // coming soon: 'soft', 'hard'
                }).then( () => {
            		axios.delete('/api/questions/'+id+'/delete_question').then((response) => {
                        this.fetchQuestions();
                    })
            	});
            }
        },
        
        mounted() {
            this.fetchQuestions();
        }
        
    }
</script>

<style>
    textarea.comment-box {
        width: 100%;
        height: 50px;
        font-size: 12px;
        color: #666;
        padding: 10px 14px;
        resize: none;
        line-height: 1.5em;
        border: 1px solid #d4d4d4;
    }
    
    ul.commentlist,
    ul.announcement{
        list-style-type: none;
        margin-top: 0;
        margin-bottom: 11.5px;
        padding-left: 0;
    }
    
    .avatar-img {
        margin-right: 8px;
    }
    
    .comment-content p {
        font-size: 13px;
        color: #666;
        position: absolute;
    }
    h6.question{
        margin-bottom: 0px;
    }
    .info-instructor {
        position: relative;
        line-height: 12px;
        margin-top: 0px;
        overflow: hidden;
    }
    
    input.form-control:focus {
        box-shadow: none !important;
    }
</style>