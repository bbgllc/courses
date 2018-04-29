
<script>
    import Bus from '../../../bus'
    
    export default {
        data: function () {
            return {
                percent_section_completed: 0
            }
        },    

        props: ['percent_completed', 'section'],
        
        methods: {
           
        },
        
        mounted() {
            this.percent_section_completed = this.percent_completed;

            Bus.$on('completion.status.updated', (data) => {
                if(data.section_id == this.section){
                    this.percent_section_completed = data.percent_completed;
                }
            })
        }
        
    }
</script>