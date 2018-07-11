<script>

    import { mapGetters, mapActions } from 'vuex'
    import { isEmpty } from 'lodash'
    import fileUploader from './file-uploader.vue'
    
    export default {
        data: function () {
            return {
                body: null, 
                loading: false,
                keyword: '',
                err: ''
            }
        },
        
        components: {
            fileUploader,
        },
        
        props: [
           'first_thread',
           'auth_user'
        ],
        
        computed: mapGetters({
            threads: 'messenger/threads',
            messages: 'messenger/messages',
            attachments: 'messenger/attachments',
        }),
        
        methods: {
            ...mapActions({
                fetchThreads: 'messenger/fetchThreads',
                fetchMessages: 'messenger/fetchMessages',
                sendMessage: 'messenger/sendMessage',
                markThreadAsRead: 'messenger/markThreadAsRead'
            }),  
            
            getThreads(keyword){
                this.loading = true;
                setTimeout(() => {
                    this.fetchThreads({
                        q: keyword
                    }).then(() => {
                        if(this.threads && this.threads.length){
                            this.fetchMessages({
                                thread_id: this.threads[0].id
                            })
                        }
                    })
                    this.loading = false;
                }, 200)
                
            },
            
            markAsRead(thread_id){
                this.markThreadAsRead({
                    thread_id: thread_id
                })
            },
            
            getMessages(thread_id){
                this.loading = true;
                setTimeout(() => {
                    this.scrollToTop()
                    this.markAsRead(thread_id)
                    this.fetchMessages({
                        thread_id: thread_id
                    }).then(() => {
                        this.loading = false;
                        this.scrollToTop();
                    })
                    
                }, 400)
            },
            
            handleMessageInput(e){
                if(e.keyCode == 13 && !e.shiftKey){
                    e.preventDefault();
                    this.send();
                }
                
            },
            
            handleSearchInput(e){
                if(e.keyCode == 13 && !e.shiftKey){
                    e.preventDefault();
                    this.getThreads(this.keyword);
                }
                
            },
            
            send(){
                if ($.trim(this.body).length !== 0) {
                    this.sendMessage({
                        payload: this.body,
                        thread_id: this.messages.id
                    }).then(() => {
                        this.body = null
                        this.err = ''
                        this.scrollToTop()
                    })
                } else {
                    this.err = 'Please enter a message';
                }
                
            },
            
            scrollToTop(){
                var element = document.getElementById("chat_area");
                element.scrollTop = element.scrollHeight;
            }
            
        },
        
        mounted() {
            
            this.getThreads(this.keyword);
            
            
            if(this.first_thread){
                this.getMessages(this.first_thread);
            }
            
            /*
            Echo.private('chat')
                .listen('Messaging.MessageCreated', (e) => {
                    this.getMessages(e.thread_id);
                })
                
              */  
        }
        
    }
</script>
