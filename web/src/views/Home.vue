<template>
  <dev-article>
    <div class="home-body">
      <div class="home-items">
        <router-link class="home-item hidden-nowrap" to="/vueuser" replace>
          <img class="image" src="../assets/head1.png" alt=""/>
          <div class="text">权限分配</div>
        </router-link>
        <router-link class="home-item hidden-nowrap" to="/vuegrid" replace>
          <img class="image" src="../assets/head2.png" alt=""/>
          <div class="text">数据表单</div>
        </router-link>
        <router-link class="home-item hidden-nowrap" to="/vuedata" replace>
          <img class="image" src="../assets/head3.png" alt=""/>
          <div class="text">标的清单</div>
        </router-link>
        <router-link class="home-item hidden-nowrap" to="/vuecount" replace>
          <img class="image" src="../assets/head4.png" alt=""/>
          <div class="text">税费测算</div>
        </router-link>
        <router-link class="home-item hidden-nowrap" to="/vueresult" replace>
          <img class="image" src="../assets/head5.png" alt=""/>
          <div class="text">协作成果</div>
        </router-link>
        <div class="home-item hidden-nowrap">
          <img class="image" src="../assets/head6.png" alt=""/>
          <div class="text">测试图标</div>
        </div>
        <div class="home-item hidden-nowrap">
          <img class="image" src="../assets/head7.png" alt=""/>
          <div class="text">测试图标</div>
        </div>
      </div>
    </div>
  </dev-article>
</template>

<script>
    export default {
        name: "Home"
    }
</script>

<style scoped>

  .home-body {

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
    width: 57px;
    height: 57px;
    margin-bottom: 8px;
    border: none;
  }

  .home-item .text {
    font-size: 14px;
  }

</style>