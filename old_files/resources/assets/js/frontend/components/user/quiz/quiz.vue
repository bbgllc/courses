
<script>
    
    export default {
        
        data: function () {
            
            return {
                quiz_attempts: [],
                show_attempts: false,
                questions: [],
                currentQuestion: [],
                answeredQuestions: [],
                currentQuestionNumber: 1,
                showResults: false,
                isLastQuestion: false
            }
            
        },    

        
        props: [
            'lesson'    
        ],
        
        computed: {
            totalCorrect: function () {
            
                return this.answeredQuestions.filter(function(obj){
                    return obj.question.selectedAnswer.correct==1
                }).length
                
            },
            
            percent: function() {
                return Math.round((this.totalCorrect / this.questions.length)*100);
            }
        },
        
        methods: {
            strippedContent(text) {
                let regex = /(<([^>]+)>)/ig;
                return text.replace(regex, "");
            },
            
            storeAnswer(question, answer){
                if(this.currentQuestionNumber == this.questions.length-1){
                    this.isLastQuestion = true;
                }
                
                if(this.currentQuestionNumber == this.questions.length){
                    this.showResults = true;
                }
                question.selectedAnswer = answer
                
                this.answeredQuestions.push({
                    question: question
                })
                

                this.currentQuestionNumber++
                this.currentQuestion = this.questions[this.currentQuestionNumber -1]
                
                // also write to the database if this is the last question
                if(this.showResults){
                    axios.post('/api/user/quiz/'+this.lesson+'/saveAttempt', {
                        questions: this.answeredQuestions
                    }).then((response) => {
                        console.log('Done')
                    }).catch((error) => {
                        console.log(error)
                    })
                }
            }

        },
        
        mounted() {
            return axios.get('/api/user/quiz/'+this.lesson+'/questions').then((response) => {
                this.questions = response.data.questions
                this.currentQuestion = response.data.questions[0]
                this.quiz_attempts = response.data.quiz_attempts
                
                if(this.quiz_attempts.length > 0){
                    this.show_attempts = true
                } else {
                    this.show_attempts = false
                }
            }); 
            
        }
        
    }
</script>