<template>
    <dev-article>
        这是首页ss
    </dev-article>
</template>

<script>
    export default {
        name: "Home",
        mounted () {
            const that = this
            window.onresize = () => {
                return (() => {
                    window.screenWidth = document.body.clientWidth
                    that.screenWidth = window.screenWidth
                })()
            }
        }

    }
</script>

<style scoped>

</style>