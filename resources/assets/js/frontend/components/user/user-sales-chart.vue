
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
                      label: 'Date',
                      field: 'created_at_formatted',
                      type: 'date',
                      inputFormat: 'MM/Do/YYYY',
                      outputFormat: 'MMM Do YY',
                      sortable: true
                    },
                    {
                      label: 'Purchased by',
                      field: 'purchased_by',
                      html: false,
                      filterable: true,
                    },
                    {
                      label: 'Course',
                      field: 'purchased_course',
                      filterable: true,
                      sortable: true
                    },
                    {
                      label: 'Coupon Code',
                      field: 'purchase_coupon',
                      html: false,
                      filterable: true,
                    },
                    {
                      label: 'Amount Paid',
                      field: 'paid_amount',
                      html: false,
                      filterable: false,
                      sortable: true
                    },
                    {
                      label: 'Your Earning',
                      field: 'your_earning',
                      html: false,
                      filterable: false,
                      sortable: true
                    }
                ],
                
                rows: []
            }
        },    
        
        methods: {
            fetchSalesData(){
                axios.get('/api/dashboard/fetch_sales_data?period='+this.period).then((response) => {
                    this.chartData = response.data
                    this.show_chart = true
                })
            },
            
            fetchSalesTableData(){
                axios.get('/api/dashboard/fetch_sales_table_data').then((response) => {
                    this.rows = response.data
                    this.showTable = true
                })
            }
        },
        
        mounted() {
            this.fetchSalesData()
            this.fetchSalesTableData()
            
            $('.table-bordered').addClass('table-sm');
            
        },
        
        created() {
            $('.table-bordered').addClass('table-sm');
        }
        
    }
</script>

