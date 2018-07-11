<script>
    import Form from 'vform'

    export default {
        data () {
            return {
                form: new Form({
                    email: '',
                    username: '',
                    certificate_number: ''
                }),
                results: {
                    awarded_to: '',
                    certificate_number: '',
                    course: '',
                    author: '',
                    date_obtained: ''
                },
                
                msg: ''
                
            }
        },
        
        methods: {
            
            verify(){
                this.reset()
                this.form.post('/api/cert')
                    .then(({ data }) => {
                        if(data.status == 'success'){
                            this.results.awarded_to = data.awarded_to
                            this.results.certificate_number = data.certificate_number
                            this.results.course = data.course
                            this.results.author = data.course_author
                            this.results.date_obtained = data.date_obtained
                        } else {
                            this.msg = this.trans('t.unable-to-verify-certificate');
                        }
                        
                    })  
            },
            
            reset(){
                this.results.awarded_to = ''
                this.results.certificate_number = ''
                this.results.course = ''
                this.results.author = ''
                this.results.date_obtained = ''
                this.msg=''
            }
            
        },
        
        mounted () {
            
        }
    }
</script>

