
<template>

      	<div class="thumbnail ">
        	<div class="caption">
    			<p class="author p-author">
        			<a :href="'/user/'+course.author.username" class="myicon-right">
        				<img :src="course.author.picture" width="20" height="20" class="rounded-circle img-avatar-shots">
    				</a>
        			<a :href="'/user/'+course.author.username" class="myicon-right text-decoration-none" title="">
    					{{ course.author.full_name | truncate(20) }}
        			</a>
    			</p>
    		</div><!-- /caption -->
        	<a class="position-relative btn-block course-img" :href="'/course/'+course.slug" :id="course.id">
        		<img :src="course.thumbnail" class="image-url img-responsive btn-block pop">
    		</a>
    		<div class="caption">
    			<h1 class="title-shots">
    				<a :href="'/course/'+course.slug" data-toggle="tooltip" :title="course.title" class="item-link" >
    				    {{ course.title | truncate(25) }}
    				</a>
    			</h1>
    			<div class="profile-rating">
    			    <a :href="'/courses?category='+course.category.slug" style="font-size: 0.8em;">
                        {{ course.category.name }}
                    </a>
                </div>
    			<div class="profile-rating">
                    <stars :rating="course.average_rating" size="14"></stars> <span class="text-muted">({{ course.total_reviews }})</span>
                </div>
    		</div><!-- /caption -->
        	<div class="caption">
    			<p class="actions">
    			    <i class="fa fa-users myicon-right pt-3"></i> 
    			    <span class="like_count myicon-right strongSpan">
    			        {{course.students_count}}
			        </span>
        			<span class="pull-right">
                        <span class="discount" v-if="global_coupon && course.price > 0">
                            {{ course.formatted_price }}
                        </span>
                       
                        &nbsp;
                        <span class="final_price">
                            {{ course.formatted_final_price }}
                        </span>
    				</span>
    			</p>
    		</div><!-- /caption -->		
        </div><!-- /thumbnail -->

</template>

<script>
    export default {
        data: function () {
            return {
                
            }
        },    
        
        computed: {
             description: function() {
                 return this.course.description.substring(0, 400);
             }
        },
        
        props: ['course', 'global_coupon'],
        
        methods: {
            
            
        },
        
        mounted() {
            
        }
        
    }
</script>
