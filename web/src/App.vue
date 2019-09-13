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
            // 读取状态信息
            let store = sessionStorage.getItem("store");
            if (store) {
                this.$store.replaceState(Object.assign({}, this.$store.state, JSON.parse(store)))
            }

            // 保存vuex=>sessionStorage
            window.addEventListener("beforeunload", () => {
                sessionStorage.setItem("store", JSON.stringify(this.$store.state))
            })
        }
    }
</script>

<style>

</style>
