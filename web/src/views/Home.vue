<template>
  <dev-article>
    <Card class="home-body">
      <div class="home-items">
        <router-link class="home-item hidden-nowrap" :to="menu.name" replace v-for="menu of homeMenus"
                     v-bind:key="menu.id">
          <img class="image" :src="menu.src" alt=""/>
          <div class="text">{{menu.title}}</div>
        </router-link>
      </div>
    </Card>
  </dev-article>
</template>

<script>
    export default {
        name: "Home",
        computed: {
            homeMenus() {
                let menus = this.$store.state.menus;
                menus.forEach(menu => {
                    menu.src = require('@/assets/home/' + menu.id + '.png')
                });
                return menus;
            }
        }
    }
</script>

<style scoped>

  .home-body {
    height: 100%;
  }

  .home-items {
    padding: 50px;
    display: flex;
    flex-flow: row wrap;
  }

  .home-item {
    width: 20%;
    height: 120px;
    text-align: center;

    align-items: center;
    align-content: space-around;
    justify-content: center;
  }

  .home-item .image {
    width: 52px;
    height: 52px;
    margin-bottom: 8px;
    border: none;
  }

  .home-item .text {
    font-size: 14px;
  }

</style>