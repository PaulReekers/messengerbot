import Vue from 'vue'
import Vuex from 'vuex'
import axios from 'axios'

Vue.use(Vuex);

export default new Vuex.Store({
  state: {
    questions: [],
  },

  actions: {
    LOAD_QUESTIONS: function ({ commit }) {
      axios.get('api/v1/questions').then((response) => {
        commit('SET_QUESTIONS', {
          questions: response.data
        });
      }, (err) => {
        console.log(err)
      })
    },
    NEW_QUESTION: function({ commit }, data) {
      var router = data.router;
      axios.post('api/v1/question', {
        text: '',
        option: data.id
      }).then(function (response) {
        const newId = response.data.question.id
        var question = response.data.question;
        question.options = [];
        commit('ADD_QUESTION', {
          question: question
        });
        commit('SET_OPTION', {
          id: data.question,
          option: data.id,
          to_question_id: question.id
        });
        router.push({ name: 'QuestionPage', params: { question: newId } })
      })
    },

    SAVE_QUESTION: function({ commit }, data) {
      axios.post('api/v1/question/' + data.id, {
        text: data.text
      });
    },

    ADD_OPTION: function({ commit }, data) {
      axios.post('api/v1/question/' + data.id + '/option', {
        text: ''
      }).then(function(response){
        commit('ADD_OPTION', {
          id: response.data.id,
          question: data.id
        });
      });
    },

    SAVE_OPTION: function({ commit }, data) {
      var saveData = {};
      if (data.to_question_id) {
        saveData.to_question_id = data.to_question_id;
      }
      if (data.text) {
        saveData.text = data.text
      }
      axios.post('api/v1/question/' + data.id + '/option/' + data.option, saveData)
    }
  },

  mutations: {
    SET_QUESTIONS: (state, { questions }) => {
      state.questions = questions;
    },
    ADD_QUESTION: (state, { question }) => {
      state.questions.push(question);
    },
    ADD_OPTION: (state, { id, question }) => {
      var index = state.questions.findIndex(item => item.id == question);
      var question = state.questions[index];
      var option = {
        id: id,
        attachment: '',
        question_id: question,
        text: '',
        to_question_id: null
      };
      //if (!question.options ) {
      //  question.options = [];
      //}
      question.options.push(option);
      Vue.set(state.questions, index, question);
    },
    SET_OPTION: (state, { id, option, to_question_id }) => {
      var index = state.questions.findIndex(item => item.id == id);
      var question = state.questions[index];
      var optionIndex = question.options.findIndex(item => item.id == option);
      question.options[optionIndex].to_question_id = to_question_id;
      Vue.set(state.questions, index, question);
    }
  },

  getters: {
    questions : state =>{
      return state.questions;
    },

    questionsById: (state, getters) => (id) => {
      return state.questions.find(question => question.id == id);
    },

    filteredQuestions: state => {
      return state.questions.map(item => {
        var text = '';
        if (item.text) {
          text = item.text.substr(0,40)
        }
        return {
          label: 'Story #' + item.id + ' - ' + text,
          id: item.id
        }
      })
    }
  }
});