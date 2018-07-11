<template>
    <li class="list-group-item answers">
        {{trans('t.question')}}{{index}}: {{strippedContent(question.question)}} 
            <span class="pull-right">
                <a href="#" @click.prevent="editQuestion(question)" class="text-info">{{trans('t.edit')}}</a> &nbsp;
                <a href="#" @click.prevent="deleteQuestion(question.id)" class="text-danger">{{trans('t.delete')}}</a>
            </span>
        <ol class="ml-4">
            <li v-for="(answer,index) in question.answers">
                {{answer.answer}}
                <span v-if="answer.correct" class="text-success">({{trans('t.correct-answer')}})</span>
                <a href="#" @click.prevent="deleteAnswer(answer.id)"><i class="fa fa-trash text-danger"></i></a> 
            </li>
        </ol>
        <div v-if="addingAnswer" class="mt-3">
            <quiz-answer-form :question="question"></quiz-answer-form>
        </div>
        <a href="#" v-if="!addingAnswer" class="text-success pull-right" @click.prevent="addingAnswer=true">
            {{trans('t.add-answer')}}
        </a>
        
    </li>
</template>

<script>
    import Bus from '../../../bus'
    import Quill from 'quill';
    import { quillEditor } from 'vue-quill-editor'
    
    export default {
        data () {
            return {
                addingAnswer: false
            }
        },
        
        
        props: ['question', 'index'],
        
        methods: {
            deleteQuestion(id){
                
                this.$dialog.confirm(this.trans('t.are-you-sure'), {
                    okText: this.trans('t.yes-delete'),
                    cancelText: this.trans('t.cancel'),
                    animation: 'zoom', // Available: "zoom", "bounce", "fade"
                    type: 'basic', // coming soon: 'soft', 'hard'
                }).then( () => {
            		axios.delete('/api/author/quiz_question/'+id+'/destroy')
                    .then(() => {
                        Bus.$emit('lesson.deleted', 'New')
                    })
            	})
                
                
            },
            
            deleteAnswer(id){
                
                this.$dialog.confirm(this.trans('t.are-you-sure'), {
                    okText: this.trans('t.yes-delete'),
                    cancelText: this.trans('t.cancel'),
                    animation: 'zoom', // Available: "zoom", "bounce", "fade"
                    type: 'basic', // coming soon: 'soft', 'hard'
                }).then( () => {
            		axios.delete('/api/author/answer/'+id+'/destroy')
                    .then(() => {
                        Bus.$emit('lesson.deleted', 'New')
                    })
            	})
                
                
            },
            
            editQuestion(question){
                Bus.$emit('quiz.edit', {
                        question: question,
                        id: question.lesson_id
                    }
                );    
            },
            
            strippedContent(text) {
                let regex = /(<([^>]+)>)/ig;
                return text.replace(regex, "");
            },
            
        },
        
        mounted () {
            Bus.$on('answer.added', ()=>{
                this.addingAnswer=false
                Bus.$emit('lesson.deleted', 'New')
            })
            
        }
    }
</script>

<style>
    li.answers:nth-child(odd) { background: #fbfbfb; }
</style>

