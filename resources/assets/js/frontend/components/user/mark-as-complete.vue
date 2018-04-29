
<script>
    import Bus from '../../../bus'
    
    export default {
        data: function () {
            return {
                userHasCompleted: false
            }
        },    

        props: [
            'lesson', 'course'
        ],
        
        methods: {
            markAsCompleted(){
                axios.post('/api/lesson/' + this.lesson.uid +'/mark-as-complete')
                    .then((response) => {
                        Bus.$emit('completion.status.updated', {
                            percent_completed: response.data,
                            section_id: this.lesson.section_id
                        })
                        this.getCompletionStatus()
                    });
            },
            
            getCompletionStatus() {
                axios.get('/api/lesson/' + this.lesson.uid + '/get-complete-status').then((response) => {
                    this.userHasCompleted = response.data;
                })
            },
            
        },
        
        mounted() {
            this.getCompletionStatus();
        }
        
    }
</script>