<template>
   <Layout>
       <Header class="header header-height">
           <Row class="header-margin-top">
               <i-col span="4" style="height: 64px; text-align: center;">
                   <img src="../assets/logo.png" alt="" class="logo">
               </i-col>
               <i-col span="20">
                   <Row>
                       <i-col span="21" class="title">
                           税收合作 合作共赢（Tax Coperation）
                       </i-col>
                       <i-col span="3">
                           <Row>
                               <i-col span="12">
                                   <Dropdown>
                                       <Avatar icon="ios-person" />
                                       <DropdownMenu slot="list">
                                           <DropdownItem>
                                               设置
                                               <Badge status="error"></Badge>
                                           </DropdownItem>
                                           <DropdownItem divided>退出登录</DropdownItem>
                                       </DropdownMenu>
                                   </Dropdown>
                               </i-col>
                               <i-col span="12">
                                   <Badge :count="count" :offset="[20,4]">
                                       <Icon type="md-notifications-outline" size="24" />
                                   </Badge>
                               </i-col>
                           </Row>
                       </i-col>
                   </Row>
               </i-col>
           </Row>
       </Header>
       <Layout>
           <Sider class="sider" :width="leftWidth" collapsible v-model="isCollaped" :class="{'sider-hide': isCollaped}">
               <Menu class="sider-menu" theme="dark" :active-name="activeName" :width="leftWidth">
                   <MenuGroup title="系统设置">
                       <MenuItem name="/home" to="/home" replace>
                           <Icon type="md-document" />
                           <span>权限分配</span>
                       </MenuItem>
                       <MenuItem name="/about" to="/about" replace>
                           <Icon type="md-chatbubbles" />
                           <span>表单设计</span>
                       </MenuItem>
                   </MenuGroup>
                   <MenuGroup title="数据采集">
                       <MenuItem name="/data" to="/data" replace>
                           <Icon type="md-calendar" />
                           <span>数据列表</span>
                       </MenuItem>
                   </MenuGroup>
                   <MenuGroup title="税费测算">
                       <MenuItem name="/count" to="/count" replace>
                           <Icon type="md-analytics" />
                           <span>测算列表</span>
                       </MenuItem>
                   </MenuGroup>
                   <MenuGroup title="协作成果">
                       <MenuItem name="/result" to="/result" replace>
                           <Icon type="md-barcode" />
                           <span>成果列表</span>
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
       data () {
           return {
               activeName: this.$route.path,
               count: 62,
               isCollaped: false,
               menuDark: false,
               leftWidth: '200',
           };
       },
       created () {
           this.activeName = this.$route.path
       }
   }
</script>

<style>
    body{
        background: #f8f8f9;
    }
    .header{
        width: 100%;
        position: fixed;
        background: #fff;
        box-shadow: 0 1px 1px rgba(0,0,0,.05);
        padding: 0;
        top: 0;
        left: 0;
        z-index: 2;
    }
    .header-height{
        height: 80px;
    }
    .logo{
        height: 60px;
    }
    .title{
        font-size: 24px;
        white-space: nowrap;
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
    .sider{
        position: fixed;
        height: 100%;
        left: 0;
        overflow: auto;
        z-index: 1;
    }
    .sider-menu{
        margin-top: 80px;
    }
    .sider-hide .ivu-menu-submenu-title span{
        display: none;
    }
    .sider-hide .ivu-menu-item-group-title{
        display: none;
    }
    .sider-hide .ivu-menu-submenu-title-icon{
        display: none;
    }
    .sider-hide .ivu-menu-item span{
        display: none;
    }
    .content{
        margin-left: 200px;
        margin-top: 80px;
        padding: 16px;
        transition: all .2s ease-in-out;
    }
    .content-expand{
        margin-left: 64px;
    }

</style>