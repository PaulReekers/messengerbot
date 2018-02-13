<template>
  <div class="question">
    <el-card class="box-card" :key="question.id" v-for="question in questions">
      <div slot="header" class="clearfix">
        <span>Story #{{question.id }}</span>
        <router-link :to="{ name: 'QuestionPage', params: { question: question.id } }">
          <el-button style="float: right; padding: 3px 0" type="text">Go to story</el-button>
        </router-link>
      </div>
      <div>
        <div>{{ question.text }}</div>
        <ul>
          <li :key="option.id" v-for="option in question.options" v-if="option.to_question_id !== 0">
            {{ option.text }} (to story: <router-link :to="{ name: 'QuestionPage', params: { question: option.to_question_id } }">#{{ option.to_question_id }}</router-link>)
          </li>
        </ul>
      </div>
    </el-card>
  </div>
</template>

<script>
import axios from 'axios'

export default {
  name: 'All',
  data () {
    return {
      questions: []
    }
  },

  methods: {

    getQuestions () {
      let self = this

      axios.get('api/v1/questions')
        .then(function (response) {
          self.questions = response.data
        })
    },

    goTo (option) {
      if (option.to_question_id) {
        this.$router.push({ name: 'QuestionPage', params: { question: option.to_question_id } })
      }
    }
  },

  created () {
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
    text-align: left;
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
