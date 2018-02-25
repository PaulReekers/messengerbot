<template>
  <div class="tree-container">
    <TreeItem :item="getFirst()"></TreeItem>
  </div>
</template>

<script>
  export default {
    name: 'Tree',
    data () {
      return {
      }
    },

    methods: {
      getFirst() {
        return this.questions.find(function(item){ return item.first });
      }

    },

    computed: {
      questions() {
        return this.$store.getters.questions;
      }
    }
  }
</script>

<style>
  .tree-container {
    margin-top: 50px;
  }
</style>