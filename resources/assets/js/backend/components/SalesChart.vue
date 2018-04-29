
<script>

    export default {

        data: function () {
            return {
                show_chart: false,
                total_sales: 0,
                total_earnings: 0,
                lifetime_sales: 0,
                lifetime_earnings: 0,
                online: 0,
                period: 7,
                chartData:{},
                countryStats: {},
                messages: {}
            }
        },    
        
        methods: {
            fetchSalesData(){
                axios.get('/api/admin/dashboard/fetch_admin_sales_data?period='+this.period).then((response) => {
                    this.chartData = response.data.chartData
                    this.countryStats = response.data.country_stats
                    this.online = response.data.online
                    this.messages = response.data.messages
                    this.show_chart = true
                    this.total_sales = Math.round(this.sumObj(response.data.chartData[0].data),2)
                    this.total_earnings = Math.round(this.sumObj(response.data.chartData[1].data),2)
                    this.lifetime_sales = Math.round(response.data.lifetimeData.lifetime_sales,2)
                    this.lifetime_earnings = Math.round(response.data.lifetimeData.lifetime_earnings,2)
                    
                })
            },
            
            sumObj(obj) {
                return Object.keys(obj).reduce(function (sum, next) {
                    return sum + obj[next];
                }, 0);
            }
        },
        
        mounted() {
            
            this.fetchSalesData()
            
        }
        
    }
</script>

