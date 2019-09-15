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
            <Row>
                <i-col class="sider-logo-center">
                    <img src="../assets/logo.png" alt="" class="sider-logo" :class="{'sider-hide': isCollaped}"
                         @click="logoClick">
                </i-col>
            </Row>

            <Menu
                    class="sider-menu"
                    theme="dark"
                    width="200"
                    :active-name="activeName"
            >
                <MenuGroup :title="menu.type_name" v-for="menu of menus" v-bind:key="menu.type_id">
                    <MenuItem
                            class="hidden-nowrap"
                            :name="item.id"
                            :to="item.id"
                            v-for="item of items.filter(item => item.type_id === menu.type_id)"
                            v-bind:key="item.id"
                            replace>
                        <Icon :type="item.icon"/>
                        <span>{{item.title}}</span>
                    </MenuItem>
                </MenuGroup>
            </Menu>
        </Sider>
        <Layout>
            <Header class="header">
                <Row>
                    <i-col span="19" class="header-title">
                        <!--税收合作 -->合作共赢（Tax Coperation）
                    </i-col>
                    <i-col span="5" class="hidden-nowrap" style="text-align: right;">
                        <Tooltip placement="left" transfer>
                            <Tag color="error" v-if="this.user.name">{{ this.user.name }}</Tag>
                            <div slot="content">{{ this.user.group_name }}</div>
                        </Tooltip>
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
    import xcon from "../libs/xcon";

    export default {
        data() {
            return {
                activeName: this.$route.path,
                count: 0,
                isCollaped: false,
                menuDark: false,
                Types: [],
                ajax_Items: [],
            };
        },

        computed: {
            user() {
                return this.$store.state.user;
            },
            menus() {
                return this.$store.state.menus;
            },
            items() {
                return this.$store.state.items;
            }
        },
        created() {
            this.activeName = this.$route.path;

            window.console.log(this.menus)
            window.console.log(this.items)
            window.console.log(this.user)
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
                                // 清除用户信息
                                this.$store.commit('userSet', null);
                                this.$store.commit('menuSet', []);
                                this.$store.commit('itemSet', []);
                                this.$store.commit('roleSet', []);
                                // 清楚session
                                xcon.stateClear();
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