<template>
    <dev-article>
        <div id="roleSplit">
            <Split v-model="split1" class="split">
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
        <Modal
                v-model="model.edit"
                :title="formTitle"
                :mask-closable="false"
                :loading="formLoading"
                @on-ok="formOk('edit')"
                @on-cancel="formCancel('edit')"
        >
            <Form ref="edit" :model="editData" :rules="editRule" label-position="top">
                <FormItem prop="id" label="帐号">
                    <Input v-model="editData.id" :maxlength="20" placeholder="输入帐号，5-20个字符" :disabled="inputDisable"/>
                </FormItem>
                <FormItem prop="name" label="名称">
                    <Input v-model="editData.name" :maxlength="20" placeholder="输入帐号名称，2-10个中文字符"/>
                </FormItem>
            </Form>
        </Modal>
    </dev-article>
</template>

<script>
    import xcon from '../libs/xcon'

    const editConst = {
        id: '',
        uid: '',
        name: '',
        group_id: '',
    };

    export default {
        name: "Role",
        data() {
            return {

                split1: 0.4,

                groups: [],
                group_uid: '',

                menus: [],
                menu_datas: [],
                action_datas: [],
                formLoading: true,

                ajax_datas: [],
                model: {edit: false},

                editData: Object.assign({}, editConst),
                editRule: {
                    name: [
                        {
                            required: true, message: '用户名称不得为空', trigger: 'change'
                        },
                        {
                            min: 2,
                            max: 10,
                            message: '长度最小2位，最大10位',
                            trigger: 'change'
                        },
                        {
                            pattern: /^[\u4e00-\u9fa5]+$/,
                            message: '必须是汉字，不得有空格',
                            trigger: 'change'
                        },
                    ],
                    group_id: [
                        {
                            required: true, message: '请选择相应的权限用户', trigger: 'change'
                        }
                    ],
                },

            }
        },
        methods: {

            menuCheck(val) {
                // 提交分组uid、所有的菜单uids的状态
                let uid = this.group_uid;
                let checked = val.filter(menu => menu.is_menu);
                let uids = {};
                let menus = this.menus;
                menus.forEach(menu => {
                    uids[menu.uid] = checked.filter(item => item.id === menu.id).length > 0;
                });
                this.$.posts('/role/upto', {uid, uids: JSON.stringify(uids)})
                    .then(res => {
                        window.console.log(res);
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
                        this.menus = menus;

                        // 合并分组菜单和所有菜单项
                        menus.forEach(menu => {
                            menu.is_menu = true;
                            menu.checked = group_menus.filter(item => item.menu_id === menu.id).length > 0;
                        });
                        // 合并菜单分类和菜单项
                        types.forEach(item => {
                            item.expand = true;
                            item.disabled = true;
                            item.checked = false;
                            item.is_menu = false;
                            item.disableCheckbox = true;
                            item.children = menus.filter(menu => menu.type_id === item.type_id);
                        });

                        this.menu_datas = types;
                    })
                    .catch(error => {
                        this.$Message.error(error);
                    });
            },

            formEdit(index) {
                this.formType = 'edit';
                this.model.edit = true;
                let data = this.datas[index];
                let {id, uid, name, group_id} = data;
                this.editData = Object.assign(editConst, {id, uid, name, group_id});
            },

            formOk(name) {
                this.$refs[name].validate((valid) => {
                    if (valid) {
                        // 增加表单类型检测
                        let data = null;
                        switch (name) {
                            case 'add':
                                data = this.addData;
                                break;
                            case 'edit':
                                data = this.editData;
                                break;
                            case 'pass':
                                data = this.passData;
                                break;
                        }
                        // 提交数据
                        this.$.posts('/user/' + name, data)
                            .then(res => {
                                if (name === 'add') {
                                    this.ajax_datas.push(res);
                                } else if (name === 'edit') {
                                    this.ajax_datas = xcon.arrsEdit(this.ajax_datas, 'id', res.id, res)
                                } else {
                                    // 重置密码，没有反馈
                                }

                                // 关闭窗口
                                this.$set(this.model, name, false);
                                this.$refs[name].resetFields();
                                this.$Message.success(this.formTitle + '成功！');
                            })
                            .catch(error => {
                                // 修改按钮状态
                                this.formLoading = false;
                                this.$nextTick(() => {
                                    this.formLoading = true;
                                });
                                this.$Message.error(error);
                            })
                    } else {
                        this.formLoading = false;
                        this.$nextTick(() => {
                            this.formLoading = true;
                        });
                        this.$Message.error('无法通过验证，请重新输入');
                    }
                });
            },

            formCancel(name) {
                this.$refs[name].resetFields();
            },

        },
        computed: {
            formTitle() {
                let title = '';
                switch (this.formType) {
                    case 'edit':
                        title = '名称修改';
                        break;
                }
                return title;
            },
            inputDisable() {
                return this.formType !== 'add'
            },
        },
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
                if (that.$route.path !== '/vuerole') return;

                let height = document.body.clientHeight - 60 - 36;
                document.getElementById('roleSplit').style.height = height + 'px';
            };
            window.onresize();
        },
    }
</script>

<style scoped>

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

    .slot-left {
        padding: 16px;
    }

    .slot-right {
        padding: 16px 16px 16px 20px;
    }

</style>