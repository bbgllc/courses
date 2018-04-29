<template>
    <div class="panel-group">
        <div class="card card-body" v-if="editing">
            <form class="form-horizontal"> 
                <div class="form-group" :class="{'has-error' : err.title}">
                    <div class="col font-weight-bold">
                        {{trans('t.edit-lesson')}}:
                    </div>
                    <div class="col">
                        <input type="text" class="form-control" v-model="lesson.title" :placeholder="trans('t.title')" />
                        <small class="text-danger" v-if="err.title">{{ err.title[0] }}</small>
                    </div>
                </div>
                <div class="form-group" :class="{'has-error' : err.description}">
                    <div class="col font-weight-bold">
                        {{trans('t.description')}}:
                    </div>
                    <div class="col">
                        <label>{{trans('t.what-is-this-lesson-about')}}</label>
                        <input type="text" class="form-control" v-model="lesson.description" />
                        <small class="text-danger" v-if="err.description">@{{ err.description[0] }}</small>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col font-weight-bold">
                        {{trans('t.set-as-free-preview')}}:
                    </div>
                    <div class="col">
                        <select class="custom-select" id="inputGroupSelect01" v-model="lesson.preview">
                            <option value="1">{{trans('t.yes')}}</option>
                            <option value="0">{{trans('t.no')}}</option>
                        </select>
                    </div>
                </div>
                <!--
                <div class="form-group">
                    <div class="col font-weight-bold"></div>
                    <div class="col">
                        <label class="custom-control custom-checkbox">
                          <input type="checkbox" id="setAsPreview" class="custom-control-input" v-model="lesson.preview">
                          <span class="custom-control-indicator"></span>
                          <span class="custom-control-description">{{trans('t.set-as-free-preview')}}</span>
                        </label>
                    </div>
                </div>
                -->
                <div class="form-group">
                    <div class="col font-weight-bold"></div>
                    <div class="col">
                        <button type="button" class="btn btn-success btn-xs" @click.prevent="updateLesson">
                            {{trans('t.update')}}
                        </button>
                        <a href="#" @click.prevent="editing = !editing">{{trans('t.cancel')}}</a>
                    </div>
                </div>
            </form>    
        </div>
        
        <div class="card" v-if="!editing">
            <!-- START HEADING -->
            <div :id="'lesson-heading-'+lesson.id" class="panel-heading lesson-heading" @mouseover="showIcons(lesson.id)" @mouseout="hideIcons(lesson.id)">
                <div class="card-header bg-white">
                    <div class="row">
                        <div class="col-md-7">
                            <span>
                                <a data-toggle="collapse" :href="'#lesson-'+lesson.id">
                                    <span v-if="lesson.content && lesson.content.content_type == 'video'" class="fa fa-play-circle"></span>
                                    <span v-if="lesson.content && lesson.content.content_type == 'article'" class="fa fa-file-text"></span>
                                    <span v-if="lesson.lesson_type == 'quiz'" class="fa fa-check-square-o"></span>
                                    <span v-if="!lesson.content && lesson.lesson_type !='quiz'" class="fa fa-circle-o"></span>
                                    {{trans('t.lesson')}} {{lesson.sortOrder}}: 
                                    {{lesson.title}}  
                                </a>
                                <span class="action_links" v-if="!adding_content && !editing_article" style="display:none;">
                                    &nbsp;
                                    <a href="#" @click.prevent="editing=!editing">
                                        <i class="fa fa-edit"></i>    
                                    </a> &nbsp;
                                    <a href="#" @click.prevent="destroy">
                                        <i class="fa fa-trash"></i>
                                    </a> &nbsp;
                                </span>
                            </span>
                        </div>
                        <div class="col-md-3">
                            <span v-if="!adding_content && !editing_article">
                                <span class="pull-right" v-if="lesson.preview==1">({{trans('t.free-preview')}})</span>
                            </span>
                        </div>
                        <div class="col-md-2">
                            
                            <span class="pull-right" style="margin-left:8px;">
                                <a data-toggle="collapse" :href="'#lesson-'+lesson.id">
                                    <i class="fa fa-chevron-down"></i>
                                </a>
                            </span>
                            <span class="action_links pull-right" v-if="!adding_content && !editing_article" style="display:none;">
                                <i class="fa fa-bars dragme"></i>
                            </span>
                            
                        </div>
                    </div>
                </div>
            </div> <!--/ END HEADING -->
            
            
            <div :id="'lesson-'+lesson.id" class="panel-collapse collapse">
                <div class="card-body">
                    
                    <!-- Body section when the lesson has no content and when create form is not loaded -->
                    <div v-if="(!adding_content && !lesson.content) || (editing_quiz && editing_quiz_id !== lesson.id)" class="text-center">
                        
                        <div class="row">
                            <div class="col-md-6 offset-md-3 text-center" v-if="lesson.lesson_type != 'quiz'">
                                <div v-if="lesson.lesson_type == 'lecture'">
                                    <p>
                                        {{trans('t.select-content-type')}}
                                        
                                    </p>
                                    <a href="#" class="btn btn-sq btn-success" @click.prevent="addVideo" 
                                        v-if="settings('video_allow_upload')==1">
                                        <span>
                                            <i class="fa fa-file-video-o fa-2x"></i><br/>
                                            {{trans('t.video-upload')}}
                                        </span>
                                    </a>
                                    
                                    
                                    <a href="#" class="btn btn-sq btn-danger" @click.prevent="embedVideo" 
                                        v-if="settings('video_allow_youtube')==1 || settings('video_allow_vimeo')==1">
                                        <span>
                                            <i class="fa fa-youtube fa-2x"></i><br/>
                                            {{trans('t.video-embed')}}
                                        </span>
                                    </a>
                                    
                                    <a href="#" class="btn btn-sq btn-info" @click.prevent="addArticle">
                                        <span>
                                            <i class="fa fa-file-text-o fa-2x"></i><br/>
                                            {{trans('t.text-article')}}
                                        </span>
                                    </a>
                                </div>
                                
                            </div>
                            
                            <!-- quiz type. Show list of questions and button to add questions and answers -->
                            <div class="col text-left" v-if="lesson.lesson_type == 'quiz'">
                                
                                <ul class="list-group mb-3">
                                    <quiz-question v-for="(question,index) in lesson.quiz_questions" :index="index+1" :question="question" :key="question.id"></quiz-question>
                                </ul>
                                
                                <a href="#" class="btn btn-danger pull-right" @click.prevent="addQuiz">
                                    <i class="fa fa-question"></i> {{trans('t.add-question')}}
                                </a>
                            </div>
                            
                        </div>
                    </div>
                    
                    <!-- Render forms for CRUD -->
                    <div v-if="adding_content">
                        <div v-if="adding_quiz">
                            <quiz-create :lesson="lesson" action="creating"></quiz-create>
                        </div>
                        
                        <div v-if="editing_quiz && lesson.id == editing_quiz_id">
                            <quiz-create :lesson="lesson" :quiz="quizObject" action="editing"></quiz-create>
                        </div>
                        
                        
                        <div v-if="adding_article">
                            <content-article :lesson="lesson" action="creating"></content-article>
                        </div>
                        
                        <div v-if="editing_article">
                            <content-article :lesson="lesson" action="editing"></content-article>
                        </div>
                        
                        <div v-if="adding_video">
                            <content-video :lesson="lesson"></content-video>
                        </div>
                        
                        <div v-if="embedding_video">
                            <content-embed :lesson="lesson" action="creating"></content-embed>
                        </div>
                        
                        <div v-if="editing_embedded_video">
                            <content-embed :lesson="lesson" action="editing"></content-embed>
                        </div>
                        
                    </div>
                    
                    <div class="row">
                        <!-- show article list if content is added as article -->
                        <div class="col-md-4" v-if="lesson.content && lesson.content.content_type == 'article' && !editing_article">
                            <div class="media">
                                <div class="pull-left">
                                    <img src="/img/frontend/File.jpg" class="media-object mr-3" width="60">
                                </div>
                                <div class="media-body">
                                    <p>
                                        <a href="#" :href="'/course/'+course_slug+'/learn/v1/'+lesson.uid" target="_blank">
                                            <i class="fa fa-eye"></i> Preview article</a><br />
                                        <a href="#" @click.prevent="editArticle"><i class="fa fa-pencil"></i> {{trans('t.edit-article')}}</a>
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-4" v-if="lesson.content && lesson.content.content_type == 'video' && lesson.content.video_src == 'upload' && !adding_content">
                            <div class="media">
                                <div class="pull-left">
                                    <img src="/img/frontend/Video.jpg" class="media-object mr-3" width = "60p">
                                </div>
                                <div class="media-body">
                                    <p>
                                        <a :href="'/course/'+course_slug+'/learn/v1/'+lesson.uid" target="_blank">
                                            <i class="fa fa-eye"></i> {{trans('t.preview')}}
                                        </a><br />
                                        <a href="#" @click.prevent="addVideo"><i class="fa fa-pencil"></i> {{trans('t.edit-content')}}</a>
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-4" v-if="lesson.content && lesson.content.content_type == 'video' && lesson.content.video_src == 'embed' && !adding_content">
                            <div class="media">
                                <div class="pull-left">
                                    <img src="/img/frontend/Video.jpg" class="media-object mr-3" width = "60p">
                                </div>
                                <div class="media-body">
                                    <p>
                                        <a :href="'/course/'+course_slug+'/learn/v1/'+lesson.uid" target="_blank">
                                            <i class="fa fa-eye"></i> {{trans('t.preview')}}</a><br />
                                        <a href="#" @click.prevent="editEmbeddedVideo"><i class="fa fa-pencil"></i> {{trans('t.edit-content')}}</a>
                                    </p>
                                </div>
                            </div>
                        </div>
                    
                        <div class="col-md-8" v-if="lesson.content && !adding_content">
                            <div v-if="!lesson.attachments.length">
                                
                                <a href="#" @click.prevent="addAttachment" v-if="!adding_attachment">
                                    <i class="fa fa-paperclip"></i> {{trans('t.add-attachment')}}</a>
                                </a>
                                
                                <file-attachment 
                                    :lesson="lesson" 
                                    v-if="adding_attachment"
                                    @file-uploaded="cancelContentAdd"
                                    @file-removed="cancelContentAdd"
                                ></file-attachment>
                            </div>
                            <div v-else>
                                
                                <ul class="list-inline" v-for="attachment in lesson.attachments">
    	                            <li>
                                        <b><i class="fa fa-paperclip"></i> {{trans('t.attachment')}}:</b>
                                        <a :href="'/uploads/attachments/'+attachment.filename" target="_blank">
                                            {{attachment.filename}}
                                        </a> &nbsp;
                                        
    	                                <a href="#" @click.prevent="deleteAttatchment(lesson.id, attachment.key)">
    	                                    <i class="fa fa-trash text-danger"></i>
                                        </a>
                                    </li>
    	                        </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import Bus from '../../../bus'            
    export default {
        data () {
            return {
                editing: false,
                editing_id: null,
                adding_article: false,
                embedding_video: false,
                editing_embedded_video: false,
                editing_article: false,
                adding_video: false,
                adding_content: false,
                adding_attachment: false,
                
                quizObject: [],
                
                adding_quiz: false,
                editing_quiz: false,
                err: [],
                editing_quiz_id: null
                
            }
        },
         
        props: ['lesson', 'course_slug'],
        
        methods: {
            
            updateLesson(){
                return axios.put('/api/author/lessons/'+this.lesson.id, {
                    title: this.lesson.title,
                    description: this.lesson.description,
                    preview: this.lesson.preview
                }).then( (response) => {
                    this.editing = false
                    this.err = []
                    this.adding_quiz = false
                    this.editing_quiz = false
                    this.adding_content = false
                    this.editing_article = false
                    this.embedding_video = false
                    this.editing_embedded_video = false
                    this.adding_video = false
                    this.adding_article = false
                    Bus.$emit('lesson.updated', 'New')
                }).catch((error) => {
                    this.err = error.response.data
                })
            },
            
            
            
            destroy(){
                this.$dialog.confirm(this.trans('t.are-you-sure'), {
                    okText: this.trans('t.yes-delete'),
                    cancelText: this.trans('t.cancel'),
                    animation: 'zoom', // Available: "zoom", "bounce", "fade"
                    type: 'basic', // coming soon: 'soft', 'hard'
                }).then( () => {
            		return axios.delete('/api/author/lesson/'+this.lesson.id)
                        .then(() => {
                            Bus.$emit('lesson.deleted', 'New')
                    })
            	}).catch(function () {
            		
            	});
            	
            },
            
            addVideo(){
                this.adding_content = true
                this.adding_video = true
                this.adding_article = false
                this.embedding_video = false
                this.adding_quiz = false
                this.editing_quiz = false
            },
            
            embedVideo(){
                this.adding_content = true
                this.adding_video = false
                this.adding_article = false
                this.embedding_video = true
                this.adding_quiz = false
                this.editing_quiz = false
            },
            
            editEmbeddedVideo(){
                this.adding_content = true
                this.editing_article = false
                this.adding_article = false
                this.adding_video = false
                this.embedding_video = false
                this.editing_embedded_video = true
                this.adding_quiz = false
                this.editing_quiz = false
                
            },
            
            addArticle(){
                this.adding_content = true
                this.adding_article = true
                this.adding_video = false
                this.editing_article = false
                this.embedding_video = false
                this.editing_embedded_video = false
                this.adding_quiz = false
                this.editing_quiz = false
            },
            
            editArticle(){
                this.adding_content = true
                this.editing_article = true
                this.adding_article = false
                this.adding_video = false
                this.embedding_video = false
                this.editing_embedded_video = false
                this.adding_quiz = false
                this.editing_quiz = false
                
            },
            
            addQuiz(){
                this.adding_content = true
                this.adding_article = false
                this.adding_video = false
                this.editing_article = false
                this.embedding_video = false
                this.editing_embedded_video = false
                this.adding_quiz = true
                this.editing_quiz = false
            },
            
            editQuiz(quizObject){
                this.adding_content = true
                this.editing_article = false
                this.adding_article = false
                this.adding_video = false
                this.embedding_video = false
                this.editing_embedded_video = false
                this.adding_quiz = false
                this.editing_quiz = true
                this.editing_quiz_id = quizObject.lesson_id
                this.quizObject = quizObject
                
            },
            
             addAttachment(){
                this.adding_attachment = true
            },
            
            deleteAttatchment(lesson, attachment){
                this.$dialog.confirm(this.trans('t.are-you-sure'), {
                    okText: this.trans('t.yes-delete'),
                    cancelText: this.trans('t.cancel'),
                    animation: 'zoom', // Available: "zoom", "bounce", "fade"
                    type: 'basic', // coming soon: 'soft', 'hard'
                }).then( () => {
            		axios.delete('/api/author/lesson/'+lesson+'/attachment/'+attachment+'/destroy').then((response) => {
                        this.cancelContentAdd();
                    })
            	}).catch(function () {
            		
            	});
            	
            },
            cancelContentAdd(){
                this.adding_content = false
                this.adding_video = false
                this.adding_article = false
                this.editing_article = false
                this.adding_attachment = false
                this.embedding_video = false
                this.editing_embedded_video = false
                this.editing_quiz_id = null
                Bus.$emit('lesson.updated', 'New')
            },
            
           
            
            showIcons(lesson){
                let id = 'lesson-heading-'+lesson
                $('#'+id+' .action_links').show();
            },
            
            hideIcons(lesson){
                let id = 'lesson-heading-'+lesson
                $('#'+id+' .action_links').hide();
            },
            
            
        },
        
        mounted () {
            
            Bus.$on('content.cancelled', (data) => {
                this.cancelContentAdd();
            })
            .$on('content.added', (data) => {
                this.cancelContentAdd()
            })
            .$on('quiz.edit', (data) => {
                this.editing_quiz_id = data.editing_quiz_id
                this.editQuiz(data.question)
            })
            
        }
    }
</script>

<style>
    .panel-default > .panel-heading.lesson-heading {
        color: #6d6d6d;
        background-color: #ffffff;
        height:48px;
    }
    .panel-group {
        margin-bottom: 10px;
    }
    
    .btn-sq-lg {
      width: 150px !important;
      height: 150px !important;
    }
    
    .btn-sq {
      width: 80px !important;
      height: 80px !important;
      font-size: 10px;
      padding: 5% 0;
      margin-right: 20px;
    }
    
    
    
    .btn-sq-sm {
      width: 50px !important;
      height: 50px !important;
      font-size: 10px;
    }
    
    .btn-sq-xs {
      width: 25px !important;
      height: 25px !important;
      padding:2px;
    }
    


</style>