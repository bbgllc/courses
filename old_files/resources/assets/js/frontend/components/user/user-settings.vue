
<script>

    import Form from 'vform'
 
    export default {
        data: function () {
            return {
                
                form: new Form({
                    show_profile_in_search: '',
                    notify_when_mentioned: '',
                    notify_when_question_responded: '',
                    notify_when_question_i_am_following_responded: '',
                    notify_when_new_announcement:'',
                    notify_when_answer_marked_as_correct: '',
                    notify_when_followed_question_is_answered: '',
                    notify_when_my_question_is_marked_as_answered: '',
                    notify_when_course_is_reviewed: '',
                    send_me_helpful_resources: '',
                    notify_when_new_question_in_my_course: ''
                }),
               
            }
        },    
        
        props: [
            'user', 'settings'
        ],
        
        methods: {
            updateSettings(){
                if(this.settings('enable_demo')){
                    swal('Not allowed in Demo mode');
                    return;
                }
                this.form.post('/api/update-settings')
                    .then(({ data }) => {
                        this.$toastr('success', this.trans('t.done'), this.trans('t.settings-saved'))
                        //this.form.reset()
                    }).catch((e) => {
                        console.log(e.error)
                    })
            },

        },
        
        mounted() {
            this.form.show_profile_in_search = this.settings.show_profile_in_search,
            this.form.notify_when_mentioned = this.settings.notify_when_mentioned,
            this.form.notify_when_question_responded = this.settings.notify_when_question_responded,
            this.form.notify_when_question_i_am_following_responded = this.settings.notify_when_question_i_am_following_responded,
            this.form.notify_when_new_announcement = this.settings.notify_when_new_announcement,
            this.form.notify_when_answer_marked_as_correct = this.settings.notify_when_answer_marked_as_correct,
            this.form.notify_when_followed_question_is_answered = this.settings.notify_when_followed_question_is_answered,
            this.form.notify_when_my_question_is_marked_as_answered = this.settings.notify_when_my_question_is_marked_as_answered,
            this.form.notify_when_course_is_reviewed = this.settings.notify_when_course_is_reviewed,
            this.form.send_me_helpful_resources = this.settings.send_me_helpful_resources,
            this.form.notify_when_new_question_in_my_course = this.settings.notify_when_new_question_in_my_course
            
            
            
        }
        
    }
</script>

<style>
    .ql-editor{
        min-height: 130px;
    }
</style>