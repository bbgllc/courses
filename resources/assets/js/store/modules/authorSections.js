import { isEmpty } from 'lodash';

const state = {
    sections: []
}

const mutations = {
    SET_SECTIONS(state, data){
        state.sections = data
    }

}


const actions = {

    fetchSections({commit}, {course}){
        return axios.get('/api/author/'+course+'/fetchSections').then((response) => {
            commit('SET_SECTIONS', response.data)
        }).catch((error) => {
            console.log(error)
        })
    },
    
    updateSection({dispatch}, {payload, section, course}){
        return axios.put('/api/author/section/'+section, payload).then((response) => {
            dispatch('fetchSections', {course});
        })
    },
    
    saveSection({dispatch}, {payload, course, context}){
        context.err = []
        return axios.post('/api/author/'+course+'/section', payload).then((response) => {
            dispatch('fetchSections', {course});
        }).catch((error) => {
            context.err = error.response.data
        })
    },
   
    deleteSection({dispatch}, {section, course}){
        return axios.delete('/api/author/section/'+section).then((response) => {
            dispatch('fetchSections', {course});
        }).catch((error) => {
            //context.err = error.response.data
        })
    },
    
    saveLesson({dispatch}, {payload, course, context}){
        context.err = []
        return axios.post('/api/author/add_lesson/'+course+'/lesson', payload).then((response) => {
            dispatch('fetchSections', {course});
        }).catch((error) => {
            context.err = error.response.data
        })
    },
    
}

const getters = {
    sections: state => {
        return state.sections
    }
}

export default ({
    namespaced: true,
    state,
    mutations,
    getters,
    actions
})