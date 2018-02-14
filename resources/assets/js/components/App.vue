<template>
  <div id="app">
    <img class="full" src="./assets/christmas.gif" />
    <router-link class="top" to="all">
      <el-button>Overview</el-button>
    </router-link>
    <router-view/>
  </div>
</template>

<script>
export default {
  name: "app"
};
</script>

<style lang="scss">
html,
body {
  margin: 0;
  padding: 0;
}

.full {
  width: 100%;
}

.top {
  position: absolute;
  left: 50px;
}

.presents {
  position: absolute;
  top: 20px;
  left: 0;
  right: 0;
  margin: 0 auto;
  width: 150px;
  margin-bottom: 20px;
}

.carol {
  margin: 0 auto;
  width: 500px;
  margin-bottom: 40px;
}

#app {
  font-family: "Avenir", Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  text-align: center;
  color: #2c3e50;
}
</style>
