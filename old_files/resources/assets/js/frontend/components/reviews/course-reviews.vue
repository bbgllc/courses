<template>
    <div class="col-md-12" ref="reviews" v-if="reviews.length > 0">
       
        <h5 class="caption-heading">{{ trans('t.reviews') }}</h5>
        <div class="review-block">
            
            <course-review v-for="(review,index) in reviews" :index="index" :course="course" :auth_user="auth_user" :key="review.id" :review="review"></course-review>
            
        </div>
        <div v-if="reviews.length > 0 && current_page < total_pages">
            <a href="" @click.prevent="fetchMoreReviews()" class="btn btn-block btn-secondary text-white"> 
                <i class="fa fa-refresh"></i> {{ trans('t.load-more') }}...
            </a>
        </div>
        
    </div>
</template>

<script>
    import Bus from '../../../bus'

    export default {
        
        data () {
            return {
                reviews: [],
                current_page: 1,
                total_pages: null
            }
        },
        
        props: ['course', 'auth_user'],
        
        methods: {
            
            fetchReviews(){
                return axios.get('/api/courses/'+ this.course.id + '/reviews?page=' + this.current_page).then((response) => {
                    this.reviews = response.data.data;
                    this.total_pages = response.data.last_page;
                    this.current_page = 1;
                })
                .catch(function(error){
                    console.log(error);
                });
            },
            
            fetchMoreReviews(){
                return axios.get('/api/courses/'+ this.course.id + '/reviews?page=' + parseInt(this.current_page+1)).then((response)=>{
                    this.reviews = this.reviews.concat(response.data.data);
                    this.current_page = this.current_page+1;
                }).catch((error) => {
                    console.log(error);
                })    
            },
            
            
        },
        
        mounted () {
            
            this.fetchReviews();
            
            Bus.$on('reviews-created', (data) => {
                this.fetchReviews();
            })
            .$on('reply-created', (data) => {
                this.fetchReviews();
            })
            
        }
    }
</script>

