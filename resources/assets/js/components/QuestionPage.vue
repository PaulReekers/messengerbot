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
        v-for="(option, index) in question.options"
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
      <el-button type="primary" class="green-button" @click="addOption()">Add</el-button>
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
    }
  },

  computed: {
    questions() {
      return this.$store.getters.filteredQuestions;
    },

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

      var qid = this.$route.params.question
      var question = this.$store.getters.questionsById(qid);

      this.question = question;
    },

    updateText: debounce(function () {
      this.$store.dispatch('SAVE_QUESTION', {
        id: this.question.id,
        text: this.question.text
      });
    }, 1000),

    updateOptionText: debounce(function (option) {
      this.$store.dispatch('SAVE_OPTION', {
        id: this.question.id,
        option: option.id,
        text: option.text
      });
    }, 500),

    updateToQuestion (option) {
      this.$store.dispatch('SAVE_OPTION', {
        id: this.question.id,
        option: option.id,
        to_question_id: option.to_question_id
      });
    },

    goTo (option) {
      let self = this
      if (option.to_question_id) {
        this.$router.push({ name: 'QuestionPage', params: { question: option.to_question_id } })
      }
      if (!option.id) {
        return
      }

      this.$store.dispatch('NEW_QUESTION', {
        id: option.id,
        question: this.question.id,
        router: this.$router
      });
    },

    addOption (questionId) {
      this.$store.dispatch('ADD_OPTION', {
        id: this.question.id
      });
    }
  },

  created () {
    this.getQuestion()
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
