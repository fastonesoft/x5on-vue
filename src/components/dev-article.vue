<template>
   <Layout>
       <Header class="header">
           <Row>
               <i-col span="4" offset="1">
                   <img src="../assets/logo.png" alt="" class="logo">
               </i-col>
               <i-col span="16">
                   <Menu mode="horizontal" :active-name="activeName" class="head-menu">
                       <MenuItem name="/home" to="/home" class="title" replace>税收合作</MenuItem>
                       <MenuItem name="/about" to="/about" class="title" replace>合作共赢</MenuItem>
                   </Menu>
               </i-col>
               <i-col span="2">
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
       </Header>
       <Layout>
           <Sider class="sider" width="240" collapsible v-model="isCollapsibled" :class="{'sider-hide': isCollapsibled}">
               <Menu class="sider-menu" theme="dark" active-name="data" width="240">
                   <MenuItem name="system">
                       <Icon type="md-apps" />
                       <span>系统设置</span>
                   </MenuItem>
                   <MenuItem name="data">
                       <Icon type="ios-calendar" />
                       <span>数据采集</span>
                   </MenuItem>
                   <MenuItem name="count">
                       <Icon type="md-analytics" />
                       <span>税费测算</span>
                   </MenuItem>
                   <MenuItem name="result">
                       <Icon type="md-barcode" />
                       <span>协作成果</span>
                   </MenuItem>
               </Menu>
           </Sider>
           <Content></Content>
       </Layout>
   </Layout>
</template>

<script>
   export default {
       data () {
           return {
               activeName: this.$route.path,
               count: 62,
               isCollapsibled: false,
           };
       },
       created () {
           this.activeName = this.$route.path
       }
   }
</script>

<style>
    .header{
        width: 100%;
        height: 60px;
        position: fixed;
        background: #fff;
        box-shadow: 0 1px 1px rgba(0,0,0,.05);
        top: 0;
        left: 0;
        z-index: 2;
    }
    .head-menu{
        overflow: hidden;
    }
    .logo{
        height: 50px;
        margin-top: 5px;
    }
    .title{
        font-size: 30px;
        overflow: hidden;
    }
    .ivu-menu-horizontal.ivu-menu-light:after{
        display: none;
    }
    .sider{
        position: fixed;
        height: 100%;
        left: 0;
        overflow: auto;
        z-index: 1;
    }
    .sider-menu{
        margin-top: 60px;
    }
    .sider-hide .ivu-menu-item span{
        display: none;
    }

</style>