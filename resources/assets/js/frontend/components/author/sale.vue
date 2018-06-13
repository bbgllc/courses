<script>
import Form from 'vform';

export default {
    data: function(){
        return {
            course: [],
            sales: [],
            saveStatus: null,
            showSalesForm: false,
            savedCoursePrice: 0,
            err: [],

            formSale: new Form({
                    course_id: this.course_id,
                    percent: 5,
                    expires: null,
            })
        }
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

        createSale(){
            this.saveStatus = this.trans('t.saving')+ '...';
            this.formSale.post('/api/author/course/sale')
            .then(({data}) => {
                this.showSalesForm = false;
                this.saveStatus = this.trans('t.saved');
                    setTimeout(() => {
                        this.saveStatus = null 
                    }, 3000);
                this.fetchSales();
            })
        },

        fetchSales(){
            return axios.get('/api/author/course/'+ this.course_id + '/sales').then((response) => {
                this.sales = response.data.data;
            })
            .catch((error) => {
                console.log(error);
            })
        }, 
            
        toggleActive(id, status){
            this.saveStatus = this.trans('t.updating');
            axios.put('/api/author/sale/'+id+'/activate',{
                id: id,
                status:status
            }).then((response) => {
                this.fetchSales();
                this.saveStatus = this.trans('t.status-updated');
                setTimeout(() => {
                    this.saveStatus = null 
                }, 3000);
            }).catch((error) => {
                console.log(error);
            })
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
                this.fetchSales();
            }).catch((error) => {
                console.log(error);
            })
        },
    },
    mounted(){
        this.fetchSales();
        
        return axios.get('/api/author/course/'+this.course_id+'/fetchCourse').then( (response) => {
            this.course = response.data;
            this.savedCoursePrice = this.course.price;
        });
        
    }
}
</script>
