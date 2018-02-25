<template>
  <li class="option" v-if="option.text">
    <span class="option" @click="hit()">{{ option.text }}</span>
    <TreeItem v-if="option.to_question_id && show" :item="getQuestion(option.to_question_id)"></TreeItem>
  </li>
</template>

<script>
  export default {
    name: 'treeoption',
    props: {
      option: {
        type: Object
      },
    },
    data () {
      return {
        show: false
      }
    },

    methods: {
      hit() {
        this.show = !this.show
      },
      getQuestion(item) {
        if (item && item != 1) {
          return this.$store.getters.questionsById(item);
        }
        return null;
      }
    },
  }
</script>