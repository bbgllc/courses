<template>
    <div ref="reviews" v-if="can_review" class="clearfix">
        <h5 class="caption-heading">{{ trans('t.write-a-review') }}</h5>
        <form @submit.prevent="saveReview">
            <div class="form-group">
                <star-rating v-model="form.rating" :increment="0.5" :star-size="30" :show-rating="true" active-color="#f4c150"></star-rating>
                <has-error :form="form" field="rating"></has-error>
            </div>
            <div class="form-group">
                <input v-model="form.title" class="form-control" 
                    :placeholder="trans('t.review-subject')" :class="{ 'is-invalid': form.errors.has('title') }" />
                <has-error :form="form" field="title"></has-error>
            </div>
            <div class="form-group">
                <textarea v-model="form.comment" class="form-control"
                    :class="{ 'is-invalid': form.errors.has('comment') }"
                    :placeholder="trans('t.enter-review')"></textarea>
                <has-error :form="form" field="comment"></has-error>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-success btn-xs pull-right">
                    {{ trans('t.post-review') }}
                </button>
            </div>
        </form>
    </div>
</template>

<script>
    import Bus from '../../../bus'
    import StarRating from 'vue-star-rating'
    import Form from 'vform'

    export default {
        data () {
            return {
                form: new Form({
                    rating: 0,
                    comment: '',
                    title: '',
                }),

                can_review: false,
            }
        },
        
        components: {
            StarRating
        },
        
        props: [
            'course',
            'user_can_review'
        ],
        
        methods: {
            
            saveReview(){
                this.form.post('/api/courses/'+ this.course.id + '/review')
                    .then(({ data }) => {
                        this.form.rating = 1
                        this.form.title = ''
                        this.form.body = ''
                        this.can_review = false
                        Bus.$emit('reviews-created', 'Review')
                    })  
            },
            
        },
        
        mounted () {
            this.can_review = this.user_can_review
            
        }
    }
</script>

