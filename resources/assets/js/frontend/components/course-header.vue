
<script>
    export default {
        data: function () {
            return {
                first_lesson: [],
                last_watched: [],
                user_rating: []
            }
        },    
  
        props: [
            'course',
        ],
        methods: {
            
            fetchInfo(){
                return axios.get('/api/courses/'+ this.course.slug + '/fetch-header-information')
                    .then((response) => {
                        this.first_lesson = response.data.first_lesson;
                        this.last_watched = response.data.last_watched;
                        this.user_rating = response.data.user_rating;
                    }).catch((error) => {
                        console.log(error);
                    });
            },
            
        },
        
        mounted() {
            this.fetchInfo();
        }
        
    }
</script>
