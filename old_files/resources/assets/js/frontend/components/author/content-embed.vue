<template>
    <div class="col-md-12" ref="content_embed">
        <form class="form-horizontal">
            <div class="form-row">
                <div class="form-group col">
                    <div class="font-weight-bold">
                        {{ trans('t.video-service') }}:
                    </div> 
                    <div class="">
                        <select class="form-control" v-model="form.video_provider" :class="{ 'is-invalid': form.errors.has('video_provider') }" >
                            <option value="youtube" v-if="settings('video_allow_youtube')">
                                {{ trans('t.youtube-video') }}
                            </option>
                            <option value="vimeo" v-if="settings('video_allow_vimeo')">
                                {{ trans('t.vimeo-video') }}
                            </option>
                        </select>
                        <has-error :form="form" field="video_provider"></has-error>
                    </div>
                </div>
                <div class="form-group col">
                    <div class="font-weight-bold">
                        {{ trans('t.video-duration') }} ({{ trans('t.minutes') }}):
                    </div> 
                    <div class="">
                        <input type="number" v-model="form.video_duration" :class="{ 'is-invalid': form.errors.has('video_duration') }" class="form-control">
                        <has-error :form="form" field="video_duration"></has-error>
                    </div>
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group col">
                    <div class="font-weight-bold">
                        {{ trans('t.video-link') }}
                    </div> 
                    <div class="">
                        <input type="text" v-model="form.video_link" :class="{ 'is-invalid': form.errors.has('video_link') }" :placeholder="trans('t.youtube-vimeo')" class="form-control">
                        <has-error :form="form" field="video_link"></has-error>
                    </div>
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group col text-right">
                    <a href="#" @click.prevent="cancelContentAdd">{{ trans('t.cancel') }}</a>
                    <button type="button" @click.prevent="saveEmbed" v-if="action=='creating'" class="btn btn-info">
                        <i class="fa fa-cog fa-spin" v-if="form.busy"></i> {{ trans('t.save') }}
                    </button>
                    <button type="button" @click.prevent="updateEmbedVideo" v-if="action=='editing'" class="btn btn-info">
                        <i class="fa fa-cog fa-spin" v-if="form.busy"></i> {{ trans('t.save') }}
                    </button>
                    
                </div>
            </div>
        </form>
    </div>
</template>

<script>

    import Form from 'vform'
    import Bus from '../../../bus'
    
    export default {
        data () {
            return {
                form: new Form({
                    video_link: '',
                    video_provider: '',
                    video_duration: 1  
                }),
                
            }
        },
        
        props: ['lesson', 'action'],
        
        methods: {
            
            saveEmbed(){
                this.form.post('/api/author/lesson/'+this.lesson.id+'/embed/create')
                    .then(({ data }) => {
                        this.video_link = ''
                        this.video_provider = ''
                        this.video_duration = 1
                        
                        Bus.$emit('content.added', 'New')
                    })
            },
            
            updateEmbedVideo(){
                this.form.put('/api/author/lesson/embed/'+  this.lesson.content.id)
                    .then(({ data }) => {
                        this.video_link = ''
                        this.video_provider = ''
                        this.video_duration = 1
                        Bus.$emit('content.added', 'New')
                    })
            },
            
            cancelContentAdd(){
                Bus.$emit('content.cancelled', 'New')
            }
            
            
        },
        
        mounted () {
            if(this.action == 'editing' && this.lesson.content){
                this.form.video_link = this.lesson.content.video_path
                this.form.video_provider = this.lesson.content.video_provider
                this.form.video_duration = this.lesson.content.video_duration
            }
        }
    }
</script>

<style>
    .ql-editor{
        min-height: 100px;
    }
</style>

