
<script>
    
    
    export default {
        data: function () {
            return {
                comments: [],
                body: '',
                saveStatus: null,
                err: [],
                current_page: 1,
                total_pages: null,
                showEdit: false,
                editComment: []
            }
        },    
        
        props: [
            'announcement_id'    
        ],
        methods: {
            fetchComments(){
                return axios.get('/api/announcements/'+ this.announcement_id + '/get_comments?page=' + this.current_page)
                    .then((response)=> {
                        this.comments = response.data.data
                        this.total_pages = response.data.last_page
                        this.current_page = 1
                        this.showEdit = false
                })
                .catch((error) => {
                    console.log('Could not fetch comments');
                });
            },
            
            fetchMoreComments(){
                axios.get('/api/announcements/'+ this.announcement_id + '/get_comments?page=' + parseInt(this.current_page+1)).then(response => {
                   this.comments = this.comments.concat(response.data.data)
                   this.current_page = this.current_page+1
                }, response => { 
                    console.log('Error fetching comments');
                });    
            },
            
            storeComment() {
                axios.post('/api/announcements/'+this.announcement_id+'/store_comment', {
                    body: this.body
                }).then((response) => {
                    this.fetchComments();
                    this.body = '';
                    this.saveStatus = this.trans('t.comment-posted');
                    setTimeout(() => {
                       this.saveStatus = null 
                    }, 3000);
                }).catch((error) => {
                    this.saveStatus = 'Error saving. Try again';
                    setTimeout(() => {
                       this.saveStatus = null 
                    }, 3000);
                    
                    this.err = error.response.data;
                })
            },
            
            fetchEditComment(id){
                this.showEdit = true;
                return axios.get('/api/announcements/comment/'+id+'/get_edit_comment').then((response) => {
                    this.editComment = response.data;
                })
                .catch((error) => {
                    console.log('Could not fetch comments');
                });
            },
            
            updateComment(id){
                axios.put('/api/announcements/comment/'+id+'/update_comment', {
                    body: this.editComment.description
                }).then((response)=>{
                    this.fetchComments();  
                }).catch((error)=>{
                    this.saveStatus = 'Error saving. Try again';
                    setTimeout(() => {
                       this.saveStatus = null 
                    }, 3000);
                    
                    this.err = error.response.data;
                })
            },

            deleteComment(id){
                if(this.settings('enable_demo')){
                    swal('Not allowed in Demo mode');
                    return;
                }
                this.$dialog.confirm(this.trans('t.are-you-sure'), {
                    okText: this.trans('t.yes-delete'),
                    cancelText: this.trans('t.cancel'),
                    animation: 'zoom', // Available: "zoom", "bounce", "fade"
                    type: 'basic', // coming soon: 'soft', 'hard'
                }).then( () => {
            		axios.delete('/api/announcements/comment/'+id+'/delete_comment').then((response) => {
                        this.fetchComments();
                    })
            	}).catch(function () {
            		
            	});
            	
            },
            
            
        },
        
        mounted() {
            this.fetchComments();
        }
        
    }
</script>

<style>
    textarea.comment-box {
        width: 100%;
        height: 50px;
        font-size: 12px;
        color: #666;
        padding: 10px 14px;
        resize: none;
        line-height: 1.5em;
        border: 1px solid #d4d4d4;
    }
    
    ul.commentlist,
    ul.announcement{
        list-style-type: none;
        margin-top: 0;
        margin-bottom: 11.5px;
        padding-left: 0;
    }
    
    .avatar-img {
        margin-right: 8px;
    }
    
    .comment-content p {
        font-size: 13px;
        color: #666;
        position: absolute;
    }
</style>