
<script>

    import Form from 'vform'

    export default {
        data: function () {
            return {
                courses: [],
                q: '',
                sort_by: ''
            }
        },
        
        props: ['author'],
        
        methods: {
            
            fetchCourses(){
                axios.get('/api/author/fetchCourses?q='+this.q+'&order_by='+this.sort_by).then((response) => {
                    this.courses = response.data.courses;
                })
            }
           
        },
        
        mounted() {
            
            this.fetchCourses(this.q)
            
        }
        
    }
</script>