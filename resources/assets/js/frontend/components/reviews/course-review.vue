<template>
	
	    <div class="row" :style="index > 0 ? 'padding-top:15px' : ''" style="margin-bottom:0px; border-bottom:1px solid #e8e8e8;padding-bottom:15px;">
	    	
			<div class="col-sm-2 text-center">
				<img :src="review.author_image" class="img-rounded" style="max-width:60px;">
				<div class="review-block-name"><a :href="'/user/'+review.user.username">{{review.user.name}}</a></div>
				<div class="review-block-date">{{review.created_at_human}}</div>
			</div>
			<div class="col-sm-10">
				<div class="review-block-rate mb-0">
				    <stars :rating="review.rating" size="18"></stars>
				</div>
				<div class="review-block-title text-muted">{{review.title}}</div>
				<div class="review-block-description">
				    {{review.comment}}
			    </div>
			    
			    <div v-if="review.comments.length == 0 && auth_user">
				    <div class="review-block-description" style="margin-top: 5px; text-align:right;" v-if="!replying && auth_user.id == course.user_id">
					    <a href="#" @click.prevent="replying = !replying">
					    	<i class="fa fa-reply"></i> {{ trans('t.post-a-reply') }}
					    </a>
				    </div>
				    <div v-if="replying">
					    <div class="form-group">
					    	<textarea v-model="reply" 
					    		id="autosize-textarea"
					    		class="form-control"
					    		style=" overflow: hidden; margin-top:15px; word-wrap: break-word; resize: none; height: 40px; border-radius:5px;" 
					    		:placeholder="trans('t.write-reply')"></textarea>
				    		<div class="mt-2">
						    	<a href="#" @click.prevent="cancelReply">{{ trans('t.cancel') }}</a>
						    	<a href="#" class="btn btn-sm btn-info pull-right" @click.prevent="postReply">
						    		{{ trans('t.post-reply') }}
					    		</a>
					    	</div>
					    </div>
				    </div>
			    </div>
			    
			    <div v-else>
			    	<hr style="margin-top:10px;margin-bottom:0;" />
			    	<div class="media" v-for="comment in review.comments">
					    <div class="media-body">
					    	<b>{{ trans('t.author-reply') }}</b>
					    	<p>{{comment.description}}</p>
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
            	reply: '',
                replying: false
            }
        },
         
        props: ['review', 'index', 'course', 'auth_user'],
        
        methods: {
            cancelReply(){
            	this.replying = false 
            	this.reply = ''
            },
            
            postReply(){
            	axios.post('/api/reviews/'+this.review.id+'/reply', {
            		description: this.reply
            	}).then((response) => {
            		Bus.$emit('reply-created', 'Reply')
            		this.replying = false 
            		this.reply = ''
            	})
            }
            
            
            
        },
        
        mounted () {

        }
    }
</script>
