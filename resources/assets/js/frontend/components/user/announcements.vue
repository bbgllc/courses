
<script>
   
    export default {
        data: function () {
            return {
                announcements: [],
                current_page: 1,
                total_pages: null,
                keyword: ''
            }
        },    
  
        props: [
            'course_id',
            'course_slug'
        ],
        
        methods: {
            
            fetchAnnouncements(){
                return axios.get('/api/announcements/'+ this.course_id + '/get_announcements?page=' + this.current_page + '&keyword='+this.keyword)
                    .then((response) => {
                        this.announcements = response.data.data;
                        this.total_pages = response.data.last_page;
                        this.current_page = 1;
                        
                    }).catch((error) => {
                        console.log(error);
                    });
            },
            
            strippedHTML(msg) {
                return $(msg).text();
            },
          
            fetchMoreAnnouncements(){
                return axios.get('/api/announcements/'+ this.course_id + '/get_announcements?page=' + parseInt(this.current_page+1) + '&keyword='+this.keyword)
                    .then((response) => {
                       this.announcements = this.announcements.concat(response.data.data)
                       this.current_page = this.current_page+1
                    }).catch((error) => { 
                        console.log('Error fetching announcements');
                    });    
            },
            
            
        },
        
        mounted() {
            this.fetchAnnouncements();
        }
        
    }
</script>

