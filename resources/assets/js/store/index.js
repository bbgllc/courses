import Vue from 'vue';
import Vuex from 'vuex';
import sections from './modules/authorSections';
import messenger from './modules/messaging';

Vue.use(Vuex)

export default new Vuex.Store({
    modules: {
        sections: sections,
        messenger: messenger,
    }
})
