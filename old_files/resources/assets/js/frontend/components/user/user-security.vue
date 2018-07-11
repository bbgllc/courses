
<script>

    import Form from 'vform'
 
    export default {
        data: function () {
            return {
                
                form: new Form({
                    old_password: '',
                    password: '',
                    password_confirmation: ''
                }),
               
            }
        },    
        
        props: [
            'user',
        ],
        
        methods: {
            
            updatePassword(){
                if(this.settings('enable_demo')){
                    swal('Not allowed in Demo mode');
                    return;
                }
                this.form.post('/api/update-password')
                    .then(({ data }) => {
                        
                        this.$toastr('success', this.trans('t.done'), this.trans('t.updated'))
                        this.form.reset() 
                        
                    }).catch((e) => {
                        
                    })
            },

        },
        
        mounted() {
            
        }
        
    }
</script>

<style>
    .ql-editor{
        min-height: 130px;
    }
</style>