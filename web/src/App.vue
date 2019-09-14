<template>
  <div id="app">
    <router-view></router-view>
  </div>
</template>

<script>
    export default {
        name: 'app',
        components: {},
        created() {
            // 读取session保存的store.state状态信息
            let store = sessionStorage.getItem("xc-store");
            if (store) {
                this.$store.replaceState(Object.assign({}, this.$store.state, JSON.parse(store)))
            }
        }
    }
</script>

<style>

</style>
