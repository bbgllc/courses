

    
<script>
    import { mapGetters, mapActions } from 'vuex'
    import { isEmpty } from 'lodash'
    import draggable from 'vuedraggable'
    import Bus from '../../../bus'
    
    export default {
    
        data() {
            return {
                editing: false,
                creating_section: false,
                creating_lesson: false,
                editing_section: [],
                sections:[],
                title: '',
                objective: '',
                lesson_title: '',
                lesson_description: '',
                preview_lesson: 0,
                lesson_type: '',
                err: []
            }
        },  
        
        components: {
            draggable
        },
        
        props: [
           'course'
        ],
    
        computed: mapGetters({
            mysections: 'sections/sections',
        }),
    
        methods: {
            ...mapActions({
                fetchSections: 'sections/fetchSections',
                updateSection: 'sections/updateSection',
                saveSection: 'sections/saveSection',
                deleteSection: 'sections/deleteSection',
                saveLesson: 'sections/saveLesson',
            }),  
            
            getSections(){
                
                this.fetchSections({
                    course: this.course.id
                }).then(()=>{
                    this.sections = this.mysections
                    Bus.$emit('lessons.created', 'New')
                })
                
                this.editing = false
                this.creating_section = false
                this.creating_lesson = false
                this.editing_section = []
                this.title = ''
                this.objective = ''
                this.err = []
            },
            
            update(){
                this.updateSection({
                    payload: {
                      title: this.editing_section.title,
                      objective: this.editing_section.objective
                    },
                    section: this.editing_section.id,
                    course: this.course.id
                }).then(() => {
                    this.getSections()
                })
            },
            
            save(){
                this.saveSection({
                    payload: {
                        title: this.title,
                        objective: this.objective
                    },
                    course: this.course.id,
                    context: this
                }).then(() => {
                    this.getSections()
                })
            },
            
            storeLesson(){
                this.saveLesson({
                    payload: {
                        title: this.lesson_title,
                        description: this.lesson_description,
                        preview: this.preview_lesson,
                        lesson_type: this.lesson_type
                    },
                    course: this.course.id,
                    context: this
                }).then(() => {
                    if(isEmpty(this.err)){
                        this.creating_lesson = false
                        this.lesson_title = ''
                        this.lesson_description = ''
                        this.lesson_type = ''
                        this.preview_lesson = false
                        this.getSections
                        
                        Bus.$emit('lessons.created', 'New')
                    }
                })
            },
            
            destroy(id){
                if(this.settings('enable_demo')){
                    swal('Not allowed in Demo mode');
                    return;
                }
                this.$dialog.confirm(this.trans('t.are-you-sure'), {
                    okText: this.trans('t.yes-delete'),
                    cancelText: this.trans('cancel'),
                    animation: 'zoom', // Available: "zoom", "bounce", "fade"
                    type: 'basic', // coming soon: 'soft', 'hard'
                }).then( () => {
            		this.deleteSection({
                        section: id,
                        course: this.course.id
                    }).then(() => {
                        this.getSections()
                    })
            	}).catch(function () {
            		
            	});
                    
            },
            
            updateSectionSort(e){
                this.sections.map((section, index) => {
                    section.sortOrder = index + 1
                    this.saveSortOrderToDB(section);
                })
            },
            
            saveSortOrderToDB(obj){
                axios.put('/api/author/sections/draggable', {
                    data: obj
                }).then((response) => {
                    this.getSections()
                }).catch((error) => {
                    
                })    
            },
            
            setEditing(id){
                axios.get('/api/author/section/'+id).then((response) => {
                    this.editing = true
                    this.editing_section = response.data   
                })
            },
            
            showIcons(section){
                let id = 'section-heading-'+section
                $('#'+id+' .action_links').show();
            },
            
            hideIcons(section){
                let id = 'section-heading-'+section
                $('#'+id+' .action_links').hide();
            }
        },
        
        mounted() {
            this.getSections();
            
        }
    }
    
</script>

<style>
    .dragme{
        cursor: move;
        padding: 3px;
        border: 1px solid #0d568a;
    }
    
    .list-complete-item {
      transition: all 1s;
    }
    
    .list-complete-enter, .list-complete-leave-active {
      opacity: 0;
    }
    
    
</style>
