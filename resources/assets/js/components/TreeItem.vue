<template>
  <div>
    <ul class="tree">
      <li class="question"><span class="question" @click="goTo(item.id)">{{ item.text }}</span></li>
      <TreeOption v-for="option in item.options" v-if="option.text" :key="option.id" :option="option"></TreeOption>
    </ul>
  </div>
</template>

<script>
  export default {
    name: 'treeitem',
    props: {
      item: {
        type: Object
      },
    },
    data () {
      return {
      }
    },

    methods: {
      goTo (question_id) {
        this.$router.push({ name: 'QuestionPage', params: { question: question_id } })
      }
    },

    computed: {
    },
  }
</script>

<style>
  .tree {
    list-style-type: none;
    margin-top: 5px;
  }

  .question {
    text-align: left;
  }

  .question span.question {
    background-color: #EAEAEA;
    border-radius: 10px;
    padding: 1px 5px;
    margin-bottom: 2px;
    display: block;
    float: left;
    cursor: pointer;
  }

  .option {
    margin-bottom: 3px;
    text-align: left;
    clear: both;
  }

  .option span.option {
    background-color: #AAAAAA;
    border-radius: 10px;
    padding: 1px 5px;
    cursor: pointer;
  }
</style>