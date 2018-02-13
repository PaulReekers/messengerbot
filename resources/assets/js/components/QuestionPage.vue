<template>
  <div class="question">
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>Story #{{ question.id }}</span>
      </div>
      <el-input
        type="textarea"
        class="question-input"
        :autosize="{ minRows: 3 }"
        placeholder="Once upon a time..."
        v-model="question.text"
        @change="updateText"
      />
    </el-card>

    <el-card class="box-card">
      <div
        class="choice-row"
        :key="index"
        v-for="(option, index) in options"
      >
        <el-input
          class="choice"
          v-model="option.text"
          type="text"
          :maxlength="20"
          @change="updateOptionText(option)"
        />
        <el-select
          class="choice-select"
          v-model="option.to_question_id"
          placeholder="Select"
          @change="updateToQuestion(option)"
        >
          <el-option
            label="New"
            :value="0"
          />
          <el-option
            v-for="item in questionsWithoutSelf"
            :key="item.id"
            :label="item.label"
            :value="item.id">
          </el-option>
        </el-select>
        <el-button
          @click="goTo(option)"
          type="primary"
          icon="el-icon-d-arrow-right"
          class="green-button"
        ></el-button>
      </div>
    </el-card>
  </div>
</template>

<script>
import axios from 'axios'
import debounce from 'lodash.debounce'

export default {
  name: 'Question',
  data () {
    return {
      question: {},
      options: [],
      questions: [],
      values: []
    }
  },

  computed: {
    questionsWithoutSelf () {
      return this.questions.filter((item) => {
        return item.id !== this.question.id
      })
    }
  },

  watch: {
    '$route' (to, from) {
      if (Number(from.params.question) !== Number(to.params.question)) {
        this.getQuestion()
      }
    }
  },

  methods: {
    getQuestion () {
      let self = this

      let url = 'https://fishpi.paul.style/api/v1/question'
      if (this.$route.params.question) {
        url += '/' + this.$route.params.question
      }

      axios.get(url)
        .then(function (response) {
          self.question = response.data.question
          self.options = response.data.options
        })
    },

    getQuestions () {
      let self = this

      axios.get('https://fishpi.paul.style/api/v1/questions')
        .then(function (response) {
          self.questions = response.data.map(item => {
            return {
              label: 'Story #' + item.id,
              id: item.id
            }
          })
        })
    },

    updateText: debounce(function () {
      return axios.post('https://fishpi.paul.style/api/v1/question/' + this.question.id, {
        text: this.question.text
      })
    }, 1000),

    updateOptionText: debounce(function (option) {
      axios.post('https://fishpi.paul.style/api/v1/question/' + this.question.id + '/option/' + option.id, {
        text: option.text
      })
    }, 500),

    updateToQuestion (option) {
      axios.post('https://fishpi.paul.style/api/v1/question/' + this.question.id + '/option/' + option.id, {
        to_question_id: option.to_question_id
      })
    },

    goTo (option) {
      let self = this
      if (option.to_question_id) {
        this.$router.push({ name: 'QuestionPage', params: { question: option.to_question_id } })
      }
      if (!option.id) {
        return
      }
      axios.post('https://fishpi.paul.style/api/v1/question', {
        text: '',
        option: option.id
      }).then(function (response) {
        const newId = response.data.question.id
        self.addOption(newId)
        self.addOption(newId)
        setTimeout(function () {
          self.goTo({
            to_question_id: newId
          })
        }, 1000)
      })
    },

    addOption (questionId) {
      axios.post('https://fishpi.paul.style/api/v1/question/' + questionId + '/option', {
        text: ''
      })
    }
  },

  created () {
    this.getQuestion()
    this.getQuestions()
  }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="scss">
  .text {
    font-size: 14px;
  }

  .green-button {
    background-color: #236b4b;
    border-color: #236b4b;
  }

  .clearfix:before,
  .clearfix:after {
    display: table;
    content: "";
  }
  .clearfix:after {
    clear: both
  }

  .box-card {
    width: 480px;
    margin: 0 auto 20px auto;
  }

  .choice-row {
    margin-bottom: 10px;
    display: flex;
    &:last-child {
      margin-bottom: 0;
    }

    .choice-select {
      margin: 0 10px;
      min-width: 120px;
    }
  }
</style>
