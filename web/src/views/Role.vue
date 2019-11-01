<template>
    <dev-article>
        <div id="roleSplit">
            <Split v-model="split1" class="split" min="300" max="150">
                <div slot="left" class="slot-left">
                    <Tabs value="menus">
                        <TabPane label="菜单鉴权" name="menus">
                            <Tree ref="menu" :data="menu_datas" show-checkbox @on-check-change="menuCheck"
                                  @on-select-change="menuSelect"></Tree>
                        </TabPane>
                        <!--表头附加相关操作：-->
                        <template slot="extra">
                            <Row class="hidden-nowrap">
                                <Select v-model="group_uid" placeholder="分组选择..." style="width:120px"
                                        @on-change="groupChange"
                                        transfer>
                                    <Option
                                            v-for="item in groups"
                                            :value="item.uid"
                                            :key="item.id">{{ item.name }}
                                    </Option>
                                </Select>
                            </Row>
                        </template>
                    </Tabs>
                </div>
                <div slot="right" class="slot-right">
                    <Tabs value="menus">
                        <TabPane label="模块鉴权" name="menus">
                            <Tree :data="action_datas" show-checkbox @on-check-change="actionCheck"
                                  @on-select-change="actionSelect"></Tree>
                        </TabPane>
                    </Tabs>
                </div>
            </Split>
        </div>
    </dev-article>
</template>

<script>
    export default {
        name: "Role",
        data() {
            return {

                split1: 0.3,

                groups: [],
                group_uid: '',

                menu_datas: [],
                action_datas: [],
            }
        },
        methods: {
            menuCheck(arr, curr) {
                // 提交分组uid、curr menu.uid
                let uid = this.group_uid;
                let uids = {};
                uids[curr.uid] = curr.checked;

                this.$.posts('/role/upto', {uid, uids: JSON.stringify(uids)})
                    .then(res => {
                        this.$Message.success(res + '条记录状态变更！');
                    })
                    .catch(error => {
                        this.$Message.error(error);
                    });
            },
            menuSelect(val) {
                window.console.log(val)
            },
            actionCheck(val) {
                window.console.log(val)
            },
            actionSelect(val) {
                window.console.log(val)
            },
            groupChange(uid) {
                this.$.posts('/role/menus', {uid})
                    .then(res => {
                        let {types, menus, group_menus} = res;
                        // 合并分组菜单和所有菜单项
                        menus.forEach(menu => {
                            menu.checked = group_menus.filter(item => item.menu_id === menu.id).length > 0;
                        });
                        let datas = [];
                        // 合并菜单分类和菜单项
                        types.forEach(type => {
                            // 构造子菜单
                            let children = [];
                            let childs = menus.filter(menu => menu.type_id === type.type_id);
                            childs.forEach(child => {
                                let sub_item = {};
                                sub_item.title = child.title;
                                sub_item.uid = child.uid;
                                sub_item.is_menu = true;
                                sub_item.checked = child.checked;
                                children.push(sub_item)
                            });
                            // 创建菜单项
                            let item = {};
                            item.expand = true;
                            item.disabled = true;
                            item.is_menu = false;
                            item.title = type.title;
                            item.children = children;
                            datas.push(item)
                        });

                        this.menu_datas = datas;
                    })
                    .catch(error => {
                        this.$Message.error(error);
                    });
            },
        },
        computed: {},
        created() {
            this.$.gets('/role/')
                .then(res => {
                    this.groups = res;
                })
                .catch(error => {
                    this.$Message.error(error);
                });
        },
        mounted() {
            const that = this;
            window.onresize = function () {
                if (that.$route.path !== '/vrole') return;

                // 分割条高度计算
                let height = document.body.clientHeight - 60 - 36;
                document.getElementById('roleSplit').style.height = height + 'px';
            };
            window.onresize();
        },
    }
</script>

<style scoped>

</style>