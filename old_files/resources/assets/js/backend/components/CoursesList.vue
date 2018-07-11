
<script>
    
    export default {

        data: function () {
            return {
                show_chart: false,
                showTable: false,
                period: 7,
                chartData:{},
                
                // table data
                columns: [
                    {
                      label: this.trans('t.image'),
                      field: 'img',
                      html: true,
                      sortable: false
                    },
                    {
                      label: this.trans('t.title'),
                      field: 'title',
                      html: false,
                      filterable: true,
                    },
                    {
                      label: this.trans('t.subtitle'),
                      field: 'subtitle',
                      filterable: true,
                      sortable: true
                    },
                    {
                      label: this.trans('t.author'),
                      field: 'created_by',
                      html: false,
                      filterable: true,
                    },
                    {
                      label: this.trans('t.status'),
                      field: 'status_tag',
                      html: true,
                      filterable: false,
                      sortable: true
                    },
                    {
                      label: 'actions',
                      sortable: false,
                    },
                ],
                
                rows: []
            }
        },    
        
        methods: {
            
            fetchCourseTableData(){
                axios.get('/api/admin/courses/fetchCourses').then((response) => {
                    this.rows = response.data
                    this.showTable = true
                })
            },
            
            deleteCourse(id){
                this.$dialog.confirm(this.trans('t.are-you-sure'), {
                    okText: this.trans('t.yes-delete'),
                    cancelText: this.trans('cancel'),
                    animation: 'zoom', // Available: "zoom", "bounce", "fade"
                    type: 'basic', // coming soon: 'soft', 'hard'
                }).then( () => {
            		axios.delete('/api/admin/course/courses/'+id+'/destroy').then(() =>{
            		    this.fetchCourseTableData()
            		})
            	})
            }
        },
        
        mounted() {
            this.fetchCourseTableData()
            
        }
        
        
    }
</script>

