<template>
  <dev-article>
    <Card class="home-body">
      <div class="home-items">
        <router-link class="home-item hidden-nowrap" :to="menu.name" replace v-for="menu of homeMenus" v-bind:key="menu.id">
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
                    menu.src = '/static/' + menu.id + '.png';
                });
                return menus;
            }
        }
    }
</script>

<!--html:-->

<!--<img ref="study" :src="studyDataPic">-->
<!--1-->
<!--script:-->

<!--data: {-->
	<!--return {-->
		<!--studyDataPic: require('@/assets/images/school.png')-->
	<!--}-->
<!--}-->
<!--1-->
<!--2-->
<!--3-->
<!--4-->
<!--5-->
<!--更改图片地址：-->

<!--this.studyDataPic = require('@/assets/images/load.gif')-->
<!--1-->
<!--注意：-->
<!--1.必须使用require-->
<!--因为动态添加src被当做静态资源处理了，没有进行编译，所以要加上require-->
<!--2.require里面的地址，要使用单引号-->
<!--————————————————-->
<!--版权声明：本文为CSDN博主「红瓤西瓜」的原创文章，遵循 CC 4.0 BY-SA 版权协议，转载请附上原文出处链接及本声明。-->
<!--原文链接：https://blog.csdn.net/tian_xuewu/article/details/97980899-->

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
    width: 57px;
    height: 57px;
    margin-bottom: 8px;
    border: none;
  }

  .home-item .text {
    font-size: 14px;
  }

</style>