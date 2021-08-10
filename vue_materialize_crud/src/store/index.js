import Vue from "vue";
import Vuex from 'vuex';

Vue.use(Vuex);

const store = new Vuex.Store({
    state: {
        back:{

            in_form: false,
            rota: "",
            exibe : false,
            onBack: function(){}

        }
    },
    mutations: {
        backmutation( state,  payload){
            var tmp = { in_form: payload.in_form,
                rota: payload.rota,
                exibe : payload.exibe,
                onBack: payload.onBack };

                state.back = tmp;
    
        }
    },
    actions: {
     setButtonBack: ({commit}, payload) => {

        commit('backmutation',  payload )
      }
    },
     strict: false
});

export { store } ;