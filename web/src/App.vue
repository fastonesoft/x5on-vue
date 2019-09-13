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
            //在页面加载时读取sessionStorage里的状态信息
            let store = sessionStorage.getItem("store");
            if (store) {
                this.$store.replaceState(Object.assign({}, this.$store.state, JSON.parse(store)))
            }

            //在页面刷新时将vuex里的信息保存到sessionStorage里
            window.addEventListener("beforeunload", () => {
                sessionStorage.setItem("store", JSON.stringify(this.$store.state))
            })
        }
    }
</script>

<style>

</style>
