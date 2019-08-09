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
           <Sider class="sider" width="200" collapsible v-model="isCollapsibled" :class="{'sider-hide': isCollapsibled}">
               <Menu class="sider-menu" theme="dark" active-name="data" width="200">
                   <Submenu name="item1">
                       <template slot="title">
                           <Icon type="md-apps" />
                           <span>系统设置</span>
                       </template>
                       <MenuItem name="role">权限分配</MenuItem>
                       <MenuItem name="form">表单设计</MenuItem>
                   </Submenu>
                   <Submenu name="item2">
                       <template slot="title">
                           <Icon type="md-calendar" />
                           <span>数据采集</span>
                       </template>
                       <MenuItem name="data">数据列表</MenuItem>
                   </Submenu>
                   <Submenu name="item3">
                       <template slot="title">
                           <Icon type="md-analytics" />
                           <span>税费测算</span>
                       </template>
                       <MenuItem name="count">测算列表</MenuItem>
                   </Submenu>

                   <Submenu name="item4">
                       <template slot="title">
                           <Icon type="md-barcode" />
                           <span>协作成果</span>
                       </template>
                       <MenuItem name="result">协作成果</MenuItem>

                   </Submenu>
               </Menu>
           </Sider>
           <Content class="content" :class="{'content-expand': isCollapsibled}">
               <Breadcrumb>
                   <BreadcrumbItem to="/">首页</BreadcrumbItem>
                   <BreadcrumbItem to="/about">数据采集</BreadcrumbItem>
                   <BreadcrumbItem>数据列表</BreadcrumbItem>
               </Breadcrumb>
               <Card style="margin-top: 16px">
                   <Slot></Slot>
               </Card>
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
        font-size: 24px;
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
    .sider-hide .ivu-menu-submenu-title span{
        display: none;
    }
    .sider-hide .ivu-menu-submenu-title-icon{
        display: none;
    }
    .sider-hide .ivu-menu-item{
        display: none;
    }
    .content{
        margin-left: 200px;
        margin-top: 60px;
        padding: 16px;
        transition: all .2s ease-in-out;
    }
    .content-expand{
        margin-left: 64px;
    }

</style>