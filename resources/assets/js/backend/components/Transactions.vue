
<script>
    
    export default {

        data: function () {
            return {
                showTable: false,
                // table data
                columns: [
                    {
                      label: 'UUID',
                      field: 'uuid',
                      html: false,
                      filterable: false,
                    },
                    
                    {
                      label: this.trans('t.date'),
                      field: 'created_at_formatted',
                      type: 'date',
                      inputFormat: 'MM/Do/YYYY',
                      outputFormat: 'MMM Do YY',
                      sortable: true
                    },
                    
                    
                    {
                      label: this.trans('t.user'),
                      field: 'owner',
                      filterable: true,
                      sortable: true
                    },
                    {
                      label: this.trans('t.description'),
                      field: 'description',
                      html: false,
                      filterable: true,
                    },
                    {
                      label: this.trans('t.long-description'),
                      field: 'long_description',
                      html: false,
                      filterable: false,
                      sortable: true
                    },
                    {
                      label: this.trans('t.amount'),
                      field: 'formatted_amt',
                      html: false,
                      filterable: true,
                    },
                    {
                      label: this.trans('t.type'),
                      field: 't_type',
                      sortable: false,
                      html: true
                    },
                ],
                
                rows: []
            }
        },    
        
        methods: {
            
            fetchTransactionTableData(){
                axios.get('/api/admin/finance/all_transactions').then((response) => {
                    this.rows = response.data
                    this.showTable = true
                })
            }
        },
        
        mounted() {
            this.fetchTransactionTableData()
            
        }
        
        
    }
</script>

