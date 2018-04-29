<template>
    <div class="col-md-12" ref="content_article">
        <form class="form-horizontal">
            <div class="form-group">
                <quill-editor ref="myTextEditor"
                    v-model="article"
                >
                </quill-editor>
            </div>
            <div class="form-group">
                <button type="button" @click.prevent="saveArticle" v-if="action=='creating'" class="btn btn-sm btn-info">{{ trans('t.save') }}</button>
                <button type="button" @click.prevent="updateArticle" v-if="action=='editing'" class="btn btn-sm btn-info">{{ trans('t.save') }}</button>
                <a href="#" @click.prevent="cancelContentAdd">
                    {{ trans('t.cancel') }}
                </a>
            </div>
        </form>
    </div>
</template>

<script>
    import Bus from '../../../bus'
    import Quill from 'quill';
    import { quillEditor } from 'vue-quill-editor'
    
    export default {
        data () {
            return {
                article: '',
                
                editorOption: {
                  modules: {
                    toolbar: [
                      ['bold', 'italic'],
                      ['code-block'],
                      [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                      ['link', 'image']
                    ],
                    history: {
                      delay: 1000,
                      maxStack: 50,
                      userOnly: false
                    },
                    imageImport: true,
                    imageResize: {
                      displaySize: true
                    }
                  }
                }
            }
        },
        
        components: {
            quillEditor
        },
        
        props: ['lesson', 'action'],
        
        methods: {
            
            saveArticle(){
                axios.post('/api/author/lesson/article/create', {
                    lesson_id: this.lesson.id,
                    article_body: this.article
                }).then((response) => {
                    this.article = '';
                    Bus.$emit('content.added', 'New')
                }).catch((error) => {
                    console.log(error);
                })
            },
            
            updateArticle(){
                axios.put('/api/author/lesson/article/'+  this.lesson.content.id, {
                    article_body: this.article
                }).then((response) => {
                    this.article = '';
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
            if(this.action == 'editing' && this.lesson.content){
                this.article = this.lesson.content.article_body
            }
        }
    }
</script>

<style>
    .ql-editor{
        min-height: 100px;
    }
</style>

