
<script>

    import Datepicker from 'vuejs-datepicker';
    import vueSlider from 'vue-slider-component';
    import Vodal from 'vodal';
    import Form from 'vform';
    
    export default {
        data: function () {
            return {
                course: [],
                coupons: [],
                saveStatus: null,
                showCreateForm: false,
                maxPercent: 100,
                showModal: false,
                couponLink: '',
                copyStatus: null,
                savedCoursePrice: 0,
                err: [],
                
                form: new Form({
                    course_id: this.course_id,
                    code: '',
                    percent: 5,
                    quantity: 1,
                    expires: null
                })
            }
                
        },
        
        components: {
            Datepicker,
            vueSlider,
            Vodal
        },
        
        props: ['course_id'],
        
        methods: {
            fetchCourse(id){
                return axios.get('/api/author/course/'+id+'/fetchCourse').then((response) => {
                    this.course = response.data;
                    this.savedCoursePrice = this.course.price;
                })
                .catch((error) => {
                    console.log('Could not fetch course');
                });
            },
            
            createCoupon(){
                this.saveStatus = this.trans('t.saving') + '...';
                
                this.form.post('/api/author/course/coupon')
                    .then(({ data }) => {
                        this.showCreateForm = false;
                        this.saveStatus = this.trans('t.saved');
                        setTimeout(() => {
                           this.saveStatus = null 
                        }, 3000);
                        this.fetchCoupons();
                    }) 
            },
            
            
            fetchCoupons(){
                return axios.get('/api/author/course/' + this.course_id + '/coupons').then((response) => {
                    this.coupons = response.data.data;
                })
                .catch((error) => {
                    console.log(error);
                })
            },
            
            toggleActive(id, status){
                this.saveStatus = this.trans('t.updating');
                axios.put('/api/author/coupon/'+id+'/activate',{
                    id: id,
                    status:status
                }).then((response) => {
                    this.fetchCoupons();
                    this.saveStatus = this.trans('t.status-updated');
                    setTimeout(() => {
                       this.saveStatus = null 
                    }, 3000);
                }).catch((error) => {
                    console.log(error);
                })
            },
            getLink(link){
                this.showModal=true; 
                this.couponLink=link;
            },
            copyToClipboard(){
                document.querySelector('#couponLink').select();
                document.execCommand('copy');  
                this.copyStatus = this.trans('t.copied-to-clipboard');
                setTimeout(() => {
                   this.copyStatus = null 
                }, 3000);
            },
            updatePrice(){
                this.saveStatus = 'Updating';
                axios.put('/api/author/courses/' + this.course_id +'/updatePrice', {
                    price: this.course.price
                }).then((response) => {
                    this.saveStatus = this.trans('t.price-updated');
                    setTimeout(() => {
                       this.saveStatus = null 
                    }, 3000);
                    this.fetchCourse(this.course_id);
                    this.fetchCoupons();
                }).catch((error) => {
                    console.log(error);
                })
            },
            
            
    
        },

        mounted(){
            this.fetchCoupons();
            
            return axios.get('/api/author/course/'+this.course_id+'/fetchCourse').then( (response) => {
                this.course = response.data;
                this.savedCoursePrice = this.course.price;
            });
            
        }
    }

</script>

