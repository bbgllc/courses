<template>
    <div class="col-md-12" ref="content_quiz_form">
        <form class="form-horizontal">
            <div class="form-group">
                <quill-editor ref="myTextEditor"
                    v-model="question"
                >
                </quill-editor>
            </div>
            <div class="form-group text-right">
                <a href="#" @click.prevent="cancelContentAdd">{{trans('t.cancel')}}</a>
                <button @click.prevent="saveQuestion" v-if="action=='creating'" type="button" class="btn btn-sm btn-info">{{trans('t.save')}}</button>
                <button @click.prevent="updateQuestion" v-if="action=='editing'" type="button" class="btn btn-sm btn-info">{{trans('t.update')}}</button>
            </div>
        </form>
    </div>
</template>

<script>
    import Bus from '../../../bus'
    import Quill from 'quill';
    import { quillEditor } from 'vue-quill-editor'
    import Form from 'vform'
    
    export default {
        data () {
            return {
                question: null,
                
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
                    }
                  }
                },
                
            }
        },
        
        components: {
            quillEditor
        },
        
        props: ['lesson', 'action', 'quiz'],
        
        methods: {
            saveQuestion(){
                axios.post('/api/author/quiz/'+this.lesson.id+'/question', {
                    question: this.question
                }).then((response) => {
                    this.question = '';
                    Bus.$emit('content.added', 'New')
                }).catch((error) => {
                    console.log(error);
                })
            },
            
            updateQuestion(){
                axios.put('/api/author/quiz/'+this.quiz.id+'/update', {
                    question: this.question
                }).then((response) => {
                    this.question = '';
                    Bus.$emit('content.added', 'New')
                }).catch((error) => {
                    console.log(error);
                })
            },
            
            cancelContentAdd(){
                Bus.$emit('content.cancelled', 'New')
            }
        },
        
        mounted () {
            if(this.action == 'editing'){
                this.question = this.quiz.question
            }
        }
    }
</script>

<style>
    .ql-editor{
        min-height: 100px;
    }
</style>

