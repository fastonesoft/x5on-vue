<template>
    <div class="xcon-body">
        <GeminiScrollbar class="xcon-left">
            <Sider class="sider" v-model="isCollaped" width="200">
                <Menu theme="dark" width="200" :active-name="activeName" class="sider-menu">
                    <MenuGroup :title="type.type_name" v-for="type of types" v-bind:key="type.type_id">
                        <MenuItem class="hidden-nowrap" :name="menu.name" :to="menu.name"
                            v-for="menu of menus.filter(menu => menu.type_id === type.type_id)" v-bind:key="menu.id"
                            replace>

                        <Tooltip :content="menu.title" placement="right" transfer>
                            <Icon :type="menu.icon" />
                            <span class="margin-left8">{{menu.title}}</span>
                        </Tooltip>

                        </MenuItem>
                    </MenuGroup>
                </Menu>
            </Sider>
        </GeminiScrollbar>

        <div class="xcon-logo">
            <div class="sider-logo-center">
                <img src="../assets/logo.png" alt="" class="sider-logo">
            </div>
        </div>

        <Row class="xcon-header">
            <i-col span="19" class="xcon-header-title">
                <!-- 税收合作 -->合作共赢（Tax Coperation） 
            </i-col>
            <i-col span="5" class="hidden-nowrap" style="text-align: right;">
                <Tooltip placement="left" transfer>
                    <Tag color="error" v-if="user && user.name">{{ user.name }}</Tag>
                    <div slot="content" v-if="user && user.group_name">{{ user.group_name }}</div>
                </Tooltip>
                <Dropdown @on-click="downMenuClick" class="margin-left16" transfer>
                    <Avatar style="background-color: #87d068" icon="ios-person" />
                    <DropdownMenu slot="list">
                        <!--<DropdownItem name="set">-->
                        <!--设置-->
                        <!--<Badge status="error"></Badge>-->
                        <!--</DropdownItem>-->
                        <DropdownItem name="logout">退出登录</DropdownItem>
                    </DropdownMenu>
                </Dropdown>
                <Badge :count="count" :offset="[20,4]" class="margin-left16 margin-right16">
                    <Icon type="md-notifications-outline" size="24" />
                </Badge>
            </i-col>
        </Row>
        <Row class="xcon-footer">2019 &copy; Fastone</Row>
        <div class="xcon-right">
            <GeminiScrollbar>
                <slot></slot>
            </GeminiScrollbar>
        </div>
    </div>
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
    .xcon-body {
        /** 不能删除 */
        height: 100%;
    }

    .xcon-right {
        position: fixed;
        top: 0;
        left: 200px;
        bottom: 0;
        right: 0;

        /* margin: 60px 0; */
        padding: 68px 8px;
    }

    .xcon-footer {
        position: fixed;
        left: 0;
        bottom: 0;
        right: 0;
        height: 60px;
        line-height: 60px;
        z-index: 1;

        text-align: right;
        padding: 0;
        padding-right: 16px;

        background: #fff;
        box-shadow: 0 -2px 3px rgba(0, 0, 0, .05);
    }

    /*顶部菜单设置*/
    .xcon-header {
        position: fixed;
        top: 0;
        left: 0px;
        right: 0;
        height: 60px;
        line-height: 60px;
        padding: 0 30px;
        z-index: 1;

        background: #fff;
        box-shadow: 0 2px 3px rgba(0, 0, 0, .05);
    }

    .xcon-header-title {
        padding-left: 200px;
        font-size: 24px;
        white-space: nowrap;
        cursor: default;
    }

    /* 滚动条 */
    .xcon-left {
        z-index: 2;
        width: 200px;
        background: #515a6e;
    }

    /*侧边菜单设置*/
    .sider {
        z-index: 2;
    }

    .sider-logo-center {
        text-align: center;
    }

    .sider-logo {
        width: 60px;
        height: 60px;
        margin: 10px auto;
    }

    .xcon-logo {
        position: fixed;
        top: 0;
        left: 0;
        width: 200px;
        height: 80px;
        z-index: 3;
        background: #515a6e;
    }

    .sider-hide .sider-logo {
        width: 35px;
        height: 35px;
    }

    .sider-menu {
        margin: 80px 0;
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

    /* 选择器 */
    .form-select {
        width: 186px;
    }

    .data-picker {
        width: 200px;
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
    .xcon-split {
        min-height: 200px;
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

    /* 分隔条左右两边样式 */
    .slot-left {
        padding: 16px;
    }

    .slot-right {
        padding: 16px 16px 16px 20px;
    }

    /* 权限树底部可见 */
    .xcon-tree-bottom {
        margin-bottom: 0px;
    }

    /* 隐藏split超过部分 */
    .ivu-split-pane {
        overflow: auto;
    }

    .ivu-split-trigger-con {
        margin-left: 1px;
    }

    /* 只对-webkit有效 */
    .ivu-split-pane::-webkit-scrollbar {
        width: 6px;
    }

    .ivu-split-pane::-webkit-scrollbar-thumb {
        cursor: pointer;
        border-radius: 3px;
        background-color: rgba(0, 0, 0, .2);
        transform: translate3d(0, 0, 0);
    }

    .ivu-split-pane::-webkit-scrollbar-thumb:hover {
        background-color: rgba(0, 0, 0, .3);
    }

    .ivu-split-pane::-webkit-scrollbar-track {
        background: transparent;
    }

    /* 表格cell底边 */
    .ivu-cell {
        border-bottom: dashed #dcdee2 1px;
    }

    /* 多选择控件修饰 */
    /* .xm-select {
        border-radius: 4px;
        border: 1px solid #dcdee2;
    } */
</style>