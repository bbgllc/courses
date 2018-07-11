<template>
    <div class="card card-body">
        <form class="form-horizontal">
            <div class="form-row">
                <div class="form-group col">
                    <label>{{trans('t.answer')}}</label>
                    <textarea class="form-control form-control-sm" v-model="form.text" :class="{ 'is-invalid': form.errors.has('text') }"></textarea>
                    <has-error :form="form" field="text"></has-error>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col">
                    <label>{{trans('t.explain-answer')}}</label>
                    <input class="form-control form-control-sm" type="text" v-model="form.explanation" :class="{ 'is-invalid': form.errors.has('explanation') }">
                    <has-error :form="form" field="explanation"></has-error>
                </div>
            </div>
            <div class="form-row">
                <div class="col-12">
                    <label class="custom-control custom-checkbox mb-0">
                        <input type="checkbox" class="custom-control-input" v-model="form.correct" v-if="!question.correct_answer_provided">
                        <input type="checkbox" disabled class="custom-control-input" v-model="form.correct" v-if="question.correct_answer_provided">
                        <span class="custom-control-indicator"></span>
                        <span class="custom-control-description"> {{trans('t.is-this-correct')}}</span>
                    </label>
                </div>
                <div class="form-group col-12" v-if="question.correct_answer_provided">
                    <small class="text-info">{{trans('t.you-have-chosen-a-right-answer')}}</small>
                </div>
            </div>
            <a href="#" @click.prevent="cancelAdd">{{trans('t.cancel')}}</a>
            <button @click.prevent="storeAnswer" class="btn btn-info btn-sm pull-right">{{trans('t.save')}}</button>
        </form>
    </div>
</template>

<script>
    import Bus from '../../../bus'
    import Form from 'vform'
    
    export default {
        data () {
            return {
                form: new Form({
                    text: '',
                    correct: false,
                    explanation: ''
                })
            }
        },
        
        props: ['question'],
        
        methods: {
            
            storeAnswer(){
                this.form.post('/api/author/question/'+this.question.id+'/create_answer')
                    .then(({ data }) => {
                        this.text = ''
                        this.correct = false
                        this.explanation = ''
                        
                        Bus.$emit('answer.added', 'NewAnswer')
                    })    
            },
            
            
            cancelAdd(){
                this.form.text = ''
                this.form.correct = false
                this.form.explanation = ''
                Bus.$emit('answer.added', 'NewAnswer')
            }
            
        },

        mounted() {
            
        }
    }
</script>
