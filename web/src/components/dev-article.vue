<template>
  <Layout class="content-body">
    <Sider
      class="sider"
      v-model="isCollaped"
      :width="leftWidth"
      :class="{'sider-hide': isCollaped}"
      collapsible
      breakpoint="xl"
    >
      <Row>
        <i-col class="sider-logo-center">
          <img src="../assets/logo.png" alt="" class="sider-logo" :class="{'sider-hide': isCollaped}"
               @click="logoClick">
        </i-col>
      </Row>

      <Menu
        class="sider-menu"
        theme="dark"
        :width="leftWidth"
        :active-name="activeName"
      >
        <MenuGroup title="设置">
          <MenuItem class="hidden-nowrap" name="/vueuser" to="/vueuser" replace>
            <Icon type="md-document"/>
            <span>用户权限</span>
          </MenuItem>
        </MenuGroup>
        <MenuGroup title="法院">
          <MenuItem class="hidden-nowrap" name="/vuedata" to="/vuedata" replace>
            <Icon type="md-calendar"/>
            <span>标的清单</span>
          </MenuItem>
          <MenuItem class="hidden-nowrap" name="/vueback" to="/vueback" replace>
            <Icon type="md-calendar"/>
            <span>测算反馈</span>
          </MenuItem>
        </MenuGroup>
        <MenuGroup title="税务">
          <MenuItem class="hidden-nowrap" name="/vuecount" to="/vuecount" replace>
            <Icon type="md-analytics"/>
            <span>税费测算</span>
          </MenuItem>
        </MenuGroup>
        <MenuGroup title="成果">
          <MenuItem class="hidden-nowrap" name="/vueresult" to="/vueresult" replace>
            <Icon type="md-barcode"/>
            <span>协作成果</span>
          </MenuItem>
        </MenuGroup>
      </Menu>
    </Sider>
    <Layout>
      <Header class="header">
        <Row>
          <i-col span="19" class="header-title">
            税收合作 合作共赢（Tax Coperation）
          </i-col>
          <i-col span="5" class="hidden-nowrap" style="text-align: right;">
            <Tag color="error" v-if="this.userName">{{ this.userName }}，{{ this.groupName }}</Tag>
            <Dropdown @on-click="downMenuClick" class="margin-left16" transfer>
              <Avatar icon="ios-person"/>
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
      <Content class="content">
        <slot></slot>
      </Content>
    </Layout>


  </Layout>
</template>

<script>
    export default {
        data() {
            return {
                activeName: this.$route.path,
                count: 0,
                isCollaped: false,
                menuDark: false,
                leftWidth: '200',
                userName: null,
                groupName: null,
            };
        },
        created() {
            this.activeName = this.$route.path;
            this.userName = this.$store.state.user.name;
            this.groupName = this.$store.state.user.group_name;
        },
        methods: {
            logoClick() {
                this.$router.replace('/vuehome');
            },
            downMenuClick(name) {
                switch (name) {
                    case 'logout': {
                        this.$Message.info('退出登录');

                        this.$.gets('/home/logout')
                            .then(() => {
                                // 记录用户信息
                                this.$store.state.user = null;
                                this.$store.state.token = null;

                                this.$router.replace('/vuelogin');
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
    height: 100%;
  }

  /*侧边菜单设置*/
  .sider {
    z-index: 999;
  }

  .sider-logo-center {
    text-align: center;
  }

  .sider-logo {
    width: 70px;
    margin: 10px auto;
    cursor: pointer;
  }

  .sider-hide .sider-logo {
    width: 50px;
  }

  .sider-menu {

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

  /*顶部菜单设置*/

  .header {
    background: #fff;
    box-shadow: 0 2px 2px rgba(0, 0, 0, .05);
  }

  .header-title {
    font-size: 24px;
    white-space: nowrap;
    cursor: default;
  }

  .content {
    padding: 16px;
    transition: all .2s ease-in-out;
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

  .margin-top16 {
    margin-top: 16px;
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
</style>