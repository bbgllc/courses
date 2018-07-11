

<script>
    
    import Vodal from 'vodal';
    export default {
        data: function () {
            return {
                showModal: false,
                promoLink: null,
                copyStatus: null
            }
                
        },
        
        props: ['link'],
        
        components: {
            Vodal
        },

        methods: {
            getLink(link){
                this.showModal=true; 
                this.promoLink=link;
            },
            copyToClipboard(){
                document.querySelector('#promoLink').select();
                document.execCommand('copy');  
                this.copyStatus = this.trans('t.copied-to-clipboard');
                setTimeout(() => {
                   this.copyStatus = null 
                }, 3000);
            }, 
        }
        
    }
</script>
