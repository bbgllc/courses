
<script>
    export default {
        data: function () {
            return {
                courses: [],
                category: [],
                current_page: 1,
                total_pages: null
            }
        },    
        
        props: ['categories'],
        
        methods: {
            
            fetchCourses(category){
                this.category = category
                return axios.get('/api/category/'+ category.id + '/fetchCourses?page=' + this.current_page)
                    .then((response) => {
                        this.courses = response.data.data
                        this.total_pages = response.data.last_page;
                        this.current_page = 1;
                    }).catch((error) => {
                        console.log(error);
                    });
            }
            
        },
        
        mounted() {
            this.category = this.categories[0]
            this.fetchCourses(this.category)
            
        }
        
    }
</script>
