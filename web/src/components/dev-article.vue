<template>
  <Layout class="layout-body">
    <Header class="header header-height">
      <Row class="header-margin-top">
        <i-col style="height: 64px; text-align: right;">
          <img src="../assets/logo.png" alt="" class="logo" @click="logoClick">
          <i-col span="19" class="title">
            税收合作 合作共赢（Tax Coperation）
          </i-col>
          <i-col span="5">
            <Row>
              <i-col class="hidden-nowrap margin-left16" style="text-align: right;">
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
          </i-col>
        </i-col>
      </Row>
    </Header>
    <Layout class="content-body">
      <Sider
        class="sider"
        v-model="isCollaped"
        :width="leftWidth"
        :class="{'sider-hide': isCollaped}"
        collapsible
      >
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
      <Content class="content" :class="{'content-expand': isCollaped}">
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
  body {
    background: #f8f8f9;
  }

  .layout-body {
    height: 100%;
  }

  .header {
    width: 100%;
    position: fixed;
    background: #fff;
    box-shadow: 0 1px 1px rgba(0, 0, 0, .05);
    padding: 0;
    top: 0;
    left: 0;
    z-index: 2;
  }

  .header-height {
    height: 80px;
  }

  .content-body {
    height: 100%;
  }

  .logo {
    width: 66px;
    height: 60px;
    position: fixed;
    left: 67px;
    top: 10px;
    z-index: 10;
    cursor: pointer;
  }

  .title {
    font-size: 24px;
    white-space: nowrap;
    padding-left: 216px;
    text-align: left;
    cursor: default;
  }

  .header-margin-top {
    margin-top: 8px;
  }

  /*.ivu-menu-horizontal {*/
  /*height: 80px;*/
  /*line-height: 80px;*/
  /*}*/
  /*.ivu-menu-horizontal.ivu-menu-light:after{*/
  /*display: none;*/
  /*}*/
  .sider {
    position: fixed;
    height: 100%;
    left: 0;
    overflow: auto;
    z-index: 1;
  }

  .sider-menu {
    margin-top: 80px;
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

  .content {
    margin-left: 200px;
    margin-top: 80px;
    padding: 16px;
    transition: all .2s ease-in-out;
  }

  .content-expand {
    margin-left: 64px;
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