<script>
    
    import Quill from 'quill';
    import { quillEditor } from 'vue-quill-editor';
    
    export default {
        data: function () {
            return {
                answers: [],
                body: '',
                saveStatus: null,
                err: [],
                current_page: 1,
                total_pages: null,
                showEdit: false,
                editAnswer: [],
                avatar_size: 35,
                avatar_radius: 25,
                
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
            }
        },    
        
        components: {
            quillEditor
        },
        
        props: [
            'question_id'    
        ],
        methods: {
            fetchAnswers(){
                return axios.get('/api/questions/'+ this.question_id + '/get_answers?page=' + this.current_page)
                    .then((response) => {
                        this.answers = response.data.data;
                        this.total_pages = response.data.last_page;
                        this.current_page = 1;
                        this.showEdit = false;
                }).catch((error) => {
                    console.log('Could not fetch answers');
                });
            },
            
            fetchMoreAnswers(){
                axios.get('/api/questions/'+ this.question_id + '/get_answers?page=' + parseInt(this.current_page+1))
                    .then(response => {
                       this.answers = this.answers.concat(response.data.data)
                       this.current_page = this.current_page+1
                }, response => { 
                    console.log('Error fetching answers');
                });    
            },
            
            storeAnswer() {
                axios.post('/api/questions/'+this.question_id+'/store_answer', {
                    body: this.body
                }).then((response) => {
                    this.current_page = 1;
                    this.fetchAnswers();
                    this.body = '';
                }).catch((error)=>{
                    this.err = error.response.data;
                })
            },
            
            fetchEditAnswer(id){
                this.showEdit = true;
                return axios.get('/api/questions/answer/'+id+'/get_edit_answer').then((response) => {
                    this.editAnswer = response.data;
                })
                .catch((error) => {
                    console.log('Could not fetch answers');
                });
            },
            
            updateAnswer(id){
                axios.put('/api/questions/answer/'+id+'/update_answer', {
                    body: this.editAnswer.description
                }).then((response) => {
                    this.fetchAnswers();  
                }).catch((error) => {
                    this.err = error.response.data;
                })
            },

            deleteAnswer(id){
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
            		axios.delete('/api/questions/answer/'+id+'/delete_answer').then((response) => {
                        this.fetchAnswers();
                    })
            	})
                        
            },
            
            markAsAnswer(id){
                axios.put('/api/answers/'+id+'/mark_as_answer')
                    .then((response) => {
                        this.fetchAnswers();  
                }).catch((error) => {
                    this.err = error.response.data;
                })
            },
            
            onEditorReady(editor) {

            }
            
        },
        
        mounted() {
            this.fetchAnswers();
        }
        
    }
</script>

