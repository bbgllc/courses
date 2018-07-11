<template>
    <span data-toggle="tooltip" :title="userHasBookmarked==true ? trans('t.remove-from-wishlist') : trans('t.add-to-wishlist')">
        <a href="#" @click.prevent="handle">
            <i :class="{'fa fa-heart fa-2x text-primary' : userHasBookmarked==true, 'fa fa-heart-o fa-2x' : userHasBookmarked==false}"></i>
        </a>
    </span>
</template>

<script>
    
    export default {
        data: function () {
            return {
                userHasBookmarked: false
            }
        },    

        props: [
            'slug'    
        ],
        
        methods: {
            addToWishlist(){
                axios.post('/api/courses/' + this.slug +'/bookmark')
                    .then(() => {
                        this.getBookmarkStatus()
                    });
            },
            
            getBookmarkStatus() {
                axios.get('/api/courses/' + this.slug + '/get-wishlist-status').then((response) => {
                    this.userHasBookmarked = response.data;
                })
            },
            
        },
        
        mounted() {
            this.getBookmarkStatus();
        }
        
    }
</script>