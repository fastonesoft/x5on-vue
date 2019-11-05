<template>
  <Layout class="content-body">
    <Sider
      class="sider"
      v-model="isCollaped"
      width="200"
      :class="{'sider-hide': isCollaped}"
      collapsible
      breakpoint="xl"
    >
      <Row class="sider-logo-fixed">
        <i-col class="sider-logo-center">
          <img src="../assets/logo.png" alt="" class="sider-logo" :class="{'sider-hide': isCollaped}">
        </i-col>
      </Row>
      <Menu
        theme="dark"
        width="200"
        :active-name="activeName"
        class="sider-menu"
      >
        <MenuGroup :title="type.type_name" v-for="type of types" v-bind:key="type.type_id">
          <MenuItem
            class="hidden-nowrap"
            :name="menu.name"
            :to="menu.name"
            v-for="menu of menus.filter(menu => menu.type_id === type.type_id)"
            v-bind:key="menu.id"
            replace>

            <Tooltip :content="menu.title" placement="right" transfer>
                <Icon :type="menu.icon"/>
                <span class="margin-left8">{{menu.title}}</span>
            </Tooltip>

          </MenuItem>
        </MenuGroup>
      </Menu>
    </Sider>
    <Layout class="content-right">
      <Header class="header">
        <Row>
          <i-col span="19" class="header-title">
            税收合作 合作共赢（Tax Coperation）
          </i-col>
          <i-col span="5" class="hidden-nowrap" style="text-align: right;">
            <Tooltip placement="left" transfer>
              <Tag color="error" v-if="user && user.name">{{ user.name }}</Tag>
              <div slot="content" v-if="user && user.group_name">{{ user.group_name }}</div>
            </Tooltip>
            <Dropdown @on-click="downMenuClick" class="margin-left16" transfer>
              <Avatar style="background-color: #87d068" icon="ios-person"/>
              <DropdownMenu slot="list">
                <!--<DropdownItem name="set">-->
                <!--设置-->
                <!--<Badge status="error"></Badge>-->
                <!--</DropdownItem>-->
                <DropdownItem name="logout">退出登录</DropdownItem>
              </DropdownMenu>
            </Dropdown>
            <Badge :count="count" :offset="[20,4]" class="margin-left16 margin-right16">
              <Icon type="md-notifications-outline" size="24"/>
            </Badge>
          </i-col>
        </Row>
      </Header>
      <Content class="content-main">
        <slot></slot>
      </Content>
      <Footer class="content-footer">2019 &copy; Fastone</Footer>
    </Layout>
  </Layout>
</template>

<script>
    import xcon from "../libs/xcon";

    export default {
        data() {
            return {
                activeName: this.$route.path,
                count: 0,
                isCollaped: false,
                Types: [],
            };
        },

        computed: {
            user() {
                return this.$store.state.user;
            },
            types() {
                return this.$store.state.types;
            },
            menus() {
                return this.$store.state.menus;
            }
        },
        created() {
            this.activeName = this.$route.path;
        },

        methods: {

            downMenuClick(name) {
                switch (name) {
                    case 'logout': {
                        this.$Message.info('退出登录');

                        this.$.gets('/home/logout')
                            .then(() => {
                                // 清除用户信息
                                this.$store.commit('userSet', null);
                                this.$store.commit('typeSet', []);
                                this.$store.commit('menuSet', []);
                                this.$store.commit('timeSet', []);
                                // 清楚session
                                xcon.stateClear();
                                this.$router.replace('/vlogin');
                            })
                            .catch(error => {
                                this.$Message.error(error);
                            });

                        break;
                    }
                }

            }
        }
    }
</script>

<style>

  .content-body {
    width: 100%;
    height: 100%;
  }

  .content-right {
    position: relative;
  }

  .content-main {
    position: absolute;
    top: 60px;
    left: 0;
    bottom: 60px;
    right: 0;
    z-index: 1;

    overflow: auto;

    margin: 8px;
    transition: all .5s ease-in-out;
  }

  .content-footer {
    position: fixed;
    left: 0;
    bottom: 0;
    right: 0;
    height: 60px;
    line-height: 60px;
    z-index: 2;

    text-align: right;
    padding: 0;
    padding-right: 16px;

    background: #fff;
    box-shadow: 0 -2px 3px rgba(0, 0, 0, .05);
  }

  /*顶部菜单设置*/
  .header {
    position: fixed;
    top: 0;
    left: 0px;
    right: 0;
    height: 60px;
    z-index: 2;

    background: #fff;
    box-shadow: 0 2px 3px rgba(0, 0, 0, .05);
  }

  .header-title {
    padding-left: 200px;
    font-size: 24px;
    white-space: nowrap;
    cursor: default;
  }

  /*侧边菜单设置*/
  .sider {
    z-index: 999;
    overflow: inherit;
  }

  .sider-logo-center {
    text-align: center;
  }

  .sider-logo {
    width: 60px;
    height: 60px;
    margin: 10px auto;
  }

  .sider-hide .sider-logo {
    width: 35px;
    height: 35px;
  }

  .sider-menu {
    padding-bottom: 60px;
  }

  .ivu-badge-count {
    cursor: default;
  }

  .ivu-menu-item-group-title {
    cursor: default;
  }

  .sider-hide .ivu-menu-submenu-title span {
    display: none;
  }

  .sider-hide .ivu-menu-item-group-title {
    display: none;
  }

  .sider-hide .ivu-menu-submenu-title-icon {
    display: none;
  }

  .sider-hide .ivu-menu-item span {
    display: none;
  }

  .data-collect {
    font-size: 24px;
    font-weight: bold;
  }

  .data-collect_not {
    overflow: hidden;
    white-space: nowrap;
  }

  .hidden-nowrap {
    overflow: hidden;
    white-space: nowrap;
  }

  .align-left {
    text-align: left;
  }

  .align-right {
    text-align: right;
  }

  .align-center {
    text-align: center;
  }

  .margin-left8 {
    margin-left: 8px;
  }

  .margin-left16 {
    margin-left: 16px;
  }

  .margin-left24 {
    margin-left: 24px;
  }

  .margin-top6 {
    margin-top: 6px;
  }


  .margin-top8 {
    margin-top: 8px;
  }

  .margin-top16 {
    margin-top: 16px;
  }

  .margin-right8 {
    margin-right: 8px;
  }

  .margin-right16 {
    margin-right: 16px;
  }

  .margin-bottom8 {
    margin-bottom: 8px;
  }

  .margin-bottom16 {
    margin-bottom: 16px;
  }

  .margin-bottom22 {
    margin-bottom: 22px;
  }

  .margin-bottom24 {
    margin-bottom: 24px;
  }

  .margin-bottom32 {
    margin-bottom: 32px;
  }

  .ivu-divider-horizontal {
    margin: 16px 0;
  }

  /* 分隔条 */
  #Split {
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
  }

  .split {
    border: 1px solid #e8eaec;
    border-radius: 4px;
    background: #fff;
    transition: all .2s ease-in-out;
  }

  .split:hover {
    border-color: #eee;
    box-shadow: 0 1px 6px rgba(0, 0, 0, .2);
    transition: all .2s ease-in-out;
  }

  .split .ivu-split-pane {
    overflow: auto;
  }

  /* 分隔条左右两边样式 */
  .slot-left {
    padding: 16px;
  }

  .slot-right {
    padding: 16px 16px 16px 20px;
  }

</style>