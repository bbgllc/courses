import { isEmpty } from 'lodash';

const state = {
    messages: [],
    threads: [],
    attachments: []
}

const mutations = {
    SET_MESSAGES(state, data){
        state.messages = data.messages
        state.attachments = data.attachments
        
    },
    SET_THREADS(state, data){
        state.threads = data
    },

}

const actions = {

    fetchThreads({commit}, {q}){
        return axios.get('/api/threads?q='+q).then((response) => {
            console.log(response)
            commit('SET_THREADS', response.data)
        }).catch((error) => {
            console.log(error)
        })
    },

    fetchMessages({commit, dispatch}, {thread_id}){
        return axios.get('/api/thread/'+thread_id+'/messages').then((response) => {
            commit('SET_MESSAGES', response.data)
        }).catch((error) => {
            console.log(error)
        })
    },
    
    sendMessage({dispatch}, {payload, thread_id}){
        return axios.put('/api/thread/'+thread_id+'/message', {
            body: payload
        }).then((response) => {
            dispatch('fetchMessages', {thread_id});
        })
    },
    
    markThreadAsRead({dispatch}, {thread_id}){
        return axios.put('/api/thread/markAsRead/'+thread_id).then((response) => {
            dispatch('fetchMessages', {thread_id});
        })
    }
    
}

const getters = {
    threads: state => {
        return state.threads
    },
    
    messages: state => {
        return state.messages
    },
    
    attachments: state => {
        return state.attachments
    },
}

export default ({
    namespaced: true,
    state,
    mutations,
    getters,
    actions
})