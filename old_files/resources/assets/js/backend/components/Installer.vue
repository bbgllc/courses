
<script>
    import Form from 'vform'
    export default {
        data: function () {
            return {
                form: new Form({
                    db_host: '',
                    db_name: '',
                    db_user: '',
                    db_password: ''
                }),
                requirements: [],
                permissions: [],
                connection_error: '',
                connection_successful: false,
                install_completed: false,
                installing: false,
                errors: {
                    permissions: 1,
                    requirements: 1,
                    database: 1,
                    load_data: 1
                }
            }
        },    

        methods: {
            onComplete(){
                window.location.href = '/login';
            },
            
            systemRequirementsErrors(){
                return this.errors.requirements == 0;    
            },
            
            systemPermissionErrors(){
                return this.errors.permissions == 0;    
            },
            
            databaseErrors(){
                return this.errors.database == 0;    
            },
            
            loadDataErrors(){
                return this.errors.load_data == 0;    
            },
            
            checkSystemRequirements(){
                return axios.get('/api/install/check-requirements')
                    .then((res) => {
                        this.requirements = res.data.requirements
                        this.errors.requirements = res.data.errors
                        //return this.errors.requirements == 0;
                    })
                
            },
            
            checkFolderPermissions(){
                return axios.get('/api/install/check-permissions')
                    .then((res) => {
                        this.permissions = res.data.permissions
                        this.errors.permissions = res.data.errors
                        //return this.errors.permissions == 0;
                    })
            },
            
            saveDbConfig(){
                this.connection_successful = false
                this.connection_error = ''
                this.errors.database = 1
                return this.form.post('/api/install/save-env')
                    .then(() => {
                        axios.get('/api/install/test-db')
                            .then(res => {
                                this.connection_successful = true
                                this.errors.database = 0
                            }).catch(error => {
                                this.connection_error = error.response.data.error  
                                this.errors.database = 1
                            })
                            
                    })
            },
            
            loadData(){
                this.installing = true
                
                setTimeout(() => {
                    axios.get('/api/install/load-data')
                    .then(res => {
                        this.install_completed = true
                        this.errors.load_data = 0
                        this.installing = false
                    })
                    
                }, 3000)
                
                
            }
           
        },
        
        
        
        mounted() {
           
        }
        
    }
</script>

<style>
    .vue-form-wizard .wizard-card-footer {
        padding: 0 20px;
        margin-top: 40px;
    }
</style>