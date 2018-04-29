
<script>
   
    export default {
        data: function () {
            return {
                user_is_following: false
            }
        },    
        
        props: [
            'question_id'
        ],
        
        methods: {
            
            followQuestion(){
                axios.get('/api/question/'+this.question_id+'/follow')
                    .then(() => {
                        this.getFollowStatus(this.question_id)
                    })
            },
            
            getFollowStatus(id){
                axios.get('/api/question/'+id+'/get-follow-status')
                    .then((response) => {
                        this.user_is_following = response.data;
                    })
            }
            
            
        },
        
        mounted() {
            this.getFollowStatus(this.question_id);
        }
        
    }
</script>

