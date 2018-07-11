
<script>
    import Bus from '../../../bus'
    
    export default {
        data: function () {
            return {
                sections: [],
                keyword: ''
            }
        },    

        props: ['course'],
        
        methods: {
            
            fetchSections(){
                axios.get('/api/courses/'+this.course.slug+'/fetch-course-content?q='+this.keyword)
                   .then((response) => {
                       this.sections = response.data
                       //$('.section-title').click();
                   })
            }
        },
        
        mounted() {
            this.fetchSections()
        }
        
    }
</script>