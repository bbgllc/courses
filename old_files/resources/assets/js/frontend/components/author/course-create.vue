
<script>

    import Form from 'vform'

    export default {
        data: function () {
            return {
                
                form: new Form({
                    title: '',
                    subtitle: '',
                    slug: '',
                    category: '',
                    parent_category: '',    
                }),
                
                categories: [],
                subcategories: []
            }
        },    
        
        methods: {
            saveCourse(){
                this.form.post('/api/author/course')
                    .then(({ data }) => {
                        window.location.href = '/author/course/'+data.slug+'/edit';
                    })
            },

            
            fetchSubcategories(){
                this.subcategories = [];
                axios.get('/api/subcategories/'+this.form.parent_category).then((response) => {
                    this.subcategories = response.data;
                })
            },
            
            sansAccent(text){
                let w = "àâäçéèêëîïôöùûüÿÀÂÄÇÉÈÊËÎÏÔÖÙÛÜŸ".split("");
                w.push("Œ","œ");
                let wo = "aaaceeeeiioouuuyAAACEEEEIIOOUUUY".split("");
                wo.push("OE","oe");    
                
                for(var i=0 ; i< w.length ; i++){
                    text = text.replace( new RegExp(w[i],"g") , wo[i]);
                }
                return text;
            },
            
            sanitizeTitle: function(title) {
                let str = this.sansAccent(title);
    			str = str.replace(/[^a-zA-Z0-9\s]/g,"");
    			str = str.toLowerCase();
    			str = str.replace(/\s/g,'-');
    			
    			return str;     
                
            },
            
            generateSlug(){
                this.form.slug = this.sanitizeTitle(this.form.title);
            }
           
        },
        
        mounted() {

            axios.get('/api/categories').then((response) => {
                this.categories = response.data;
            });
            
        }
        
    }
</script>