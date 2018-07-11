<template>
    <div ref="lessons">
        <draggable style="min-height: 1px;" v-model="lessons" :list="lessons" :options="{'handle': '.dragme', group: 'sorting'}"  @change="updateLessonSort">
            <course-lesson v-for="(lesson,index) in lessons" :course_slug="course_slug" :key="lesson.id" :lesson="lesson"></course-lesson>
        </draggable>
    </div>
</template>

<script>
    import Bus from '../../../bus'
    import draggable from 'vuedraggable'
    
    export default {
        data () {
            return {
                lessons: []
            }
        },
        
        components: {
            draggable
        },
        
        props: ['section', 'course_slug'],
        
        methods: {
            
            fetchLessons(){
                axios.get('/api/author/sections/'+this.section.id+'/lessons').then((response) => {
                    this.lessons = response.data
                });
            },
            
            updateLessonSort(e) {
                
                this.lessons.map((lesson, index) => {
                    lesson.sortOrder = index + 1
                    lesson.section_id = this.section.id
                    this.saveLessonSortOrderToDB(lesson);
                })
            },
            
            saveLessonSortOrderToDB(obj){
                axios.put('/api/author/update_draggable_lesson', {
                    data: obj
                }).then((response) => {
                    this.fetchLessons();
                }).catch((error) => {
                    console.log(error.response)
                })    
            },
            
        },
        
        mounted () {
            
            this.fetchLessons();
            
            Bus.$on('lessons.created', (data) => {
                this.fetchLessons();
            })
            .$on('lesson.updated', (data) => {
                this.fetchLessons()
            })
            .$on('lesson.deleted', (data) => {
                this.fetchLessons()
            })
        }
    }
</script>

