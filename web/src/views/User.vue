<template>
    <dev-article>
        <Card>
            <Tabs value="table">
                <TabPane label="用户列表" name="table">
                    <Table
                            :columns="cols"
                            :data="datas"
                            :loading="tableLoading"
                            ref="datas"
                            size="small"
                            border stripe>
                    </Table>
                    <Row class="margin-top16 hidden-nowrap align-right">
                        <Page
                                :total="ajax_datas.length"
                                :page-size="pageSize"
                                :page-size-opts="[10, 20, 50, 100]"
                                show-sizer
                                transfer
                                @on-change="pageChange"
                                @on-page-size-change="sizeChange"
                        />
                    </Row>
                </TabPane>
                <!--表头附加相关操作：-->
                <template slot="extra">
                    <Row class="hidden-nowrap">
                        <Button type="primary" size="small" @click="formAdd">添加</Button>
                    </Row>
                </template>
            </Tabs>
        </Card>
        <Modal
                v-model="model.add"
                :title="formTitle"
                :mask-closable="false"
                :loading="formLoading"
                @on-ok="formOk('add')"
                @on-cancel="formCancel('add')"
        >
            <Form ref="add" :model="addData" :rules="addRule" label-position="top">
                <FormItem prop="id" label="帐号">
                    <Input v-model="addData.id" :maxlength="20" placeholder="输入帐号，5-20个字符"/>
                </FormItem>
                <FormItem prop="name" label="名称">
                    <Input v-model="addData.name" :maxlength="20" placeholder="输入帐号名称，2-10个中文字符"/>
                </FormItem>
                <FormItem prop="pass" label="密码">
                    <Input v-model="addData.pass" :maxlength="20" placeholder="输入用户密码，6-20个字符"/>
                </FormItem>
                <FormItem prop="group_id" label="权限分组">
                    <Select v-model="addData.group_id" placeholder="权限组选择..." transfer>
                        <Option
                                v-for="item in groups"
                                :value="item.id"
                                :key="item.id">{{ item.name }}
                        </Option>
                    </Select>
                </FormItem>

            </Form>
        </Modal>
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
                <FormItem prop="group_id" label="权限分组">
                    <Select v-model="editData.group_id" placeholder="权限组选择..." transfer>
                        <Option
                                v-for="item in groups"
                                :value="item.id"
                                :key="item.id">{{ item.name }}
                        </Option>
                    </Select>

                    
                </FormItem>
                <div id="selectGroup" name="group_id" v-model="editData.group_id">权限分组：</div>

            </Form>
        </Modal>
        <Modal
                v-model="model.pass"
                :title="formTitle"
                :mask-closable="false"
                :loading="formLoading"
                @on-ok="formOk('pass')"
                @on-cancel="formCancel('pass')"
        >
            <Form ref="pass" :model="passData" :rules="passRule" label-position="top">
                <FormItem prop="id" label="帐号">
                    <Input v-model="passData.id" :maxlength="20" placeholder="输入帐号，5-20个字符" :disabled="inputDisable"/>
                </FormItem>
                <FormItem prop="name" label="名称">
                    <Input v-model="passData.name" :maxlength="20" placeholder="输入帐号名称，2-10个中文字符"
                           :disabled="inputDisable"/>
                </FormItem>
                <FormItem prop="pass" label="密码">
                    <Input v-model="passData.pass" :maxlength="20" placeholder="输入用户密码，6-20个字符"/>
                </FormItem>
            </Form>
        </Modal>
    </dev-article>
</template>

<script>
    import xcon from '../libs/xcon'

    const addConst = {
        id: '',
        uid: '',
        name: '',
        pass: '',
        group_id: '',
    };

    const editConst = {
        id: '',
        uid: '',
        name: '',
        group_id: '',
    };

    const passConst = {
        id: '',
        uid: '',
        name: '',
        pass: '',
    };

    export default {
        name: "User",
        data() {
            return {
                pageIndex: 1,
                pageSize: 10,
                tableLoading: true,
                cols: [
                    {
                        width: 50,
                        type: 'index',
                        align: 'center',
                    },
                    {
                        title: '帐号',
                        key: 'id',
                    },
                    {
                        title: '名称',
                        key: 'name',
                    },
                    {
                        title: '所属部门',
                        key: 'part_name',
                    },
                    {
                        title: '权限分组',
                        key: 'group_name',
                    },
                    {
                        title: '操作',
                        key: 'action',
                        width: 220,
                        align: 'center',
                        render: (h, params) => {
                            return h('div', [
                                h('Button', {
                                    props: {
                                        type: 'primary',
                                        size: 'small'
                                    },
                                    style: {
                                        marginRight: '5px'
                                    },
                                    on: {
                                        click: () => {
                                            this.formEdit(params.index)
                                        }
                                    }
                                }, '修改'),
                                h('Button', {
                                    props: {
                                        type: 'success',
                                        size: 'small'
                                    },
                                    style: {
                                        marginRight: '5px'
                                    },
                                    on: {
                                        click: () => {
                                            this.formPass(params.index)
                                        }
                                    }
                                }, '重置密码'),
                                h('Button', {
                                    props: {
                                        type: 'error',
                                        size: 'small'
                                    },
                                    on: {
                                        click: () => {
                                            this.formDel(params.index)
                                        }
                                    }
                                }, '删除')
                            ]);
                        }
                    }
                ],

                ajax_datas: [],
                groups: [],

                formType: 'add',
                model: {add: false, edit: false, pass: false},
                formLoading: true,

                addData: Object.assign({}, addConst),
                addRule: {
                    id: [
                        {
                            required: true, message: '用户账号不得为空', trigger: 'change'
                        },
                        {
                            min: 5,
                            max: 20,
                            message: '长度最小5位，最大20位',
                            trigger: 'change'
                        },
                        {
                            pattern: /^\w+$/,
                            message: '必须是字母、数字或者下划线',
                            trigger: 'change'
                        },
                    ],
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
                    pass: [
                        {
                            required: true, message: '用户密码不得为空', trigger: 'change'
                        },
                        {
                            min: 6,
                            max: 20,
                            message: '长度最小6位，最大20位',
                            trigger: 'change'
                        },
                        {
                            pattern: /^[\\.\w]+$/,
                            message: '必须是字母、数字或者下划线',
                            trigger: 'change'
                        },
                    ],
                    group_id: [
                        {
                            required: true, message: '请选择相应的权限用户', trigger: 'change'
                        }
                    ],
                },

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

                passData: Object.assign({}, passConst),
                passRule: {
                    pass: [
                        {
                            required: true, message: '用户密码不得为空', trigger: 'change'
                        },
                        {
                            min: 6,
                            max: 20,
                            message: '长度最小6位，最大20位',
                            trigger: 'change'
                        },
                        {
                            pattern: /^[\\.\w]+$/,
                            message: '必须是字母、数字或者下划线',
                            trigger: 'change'
                        },
                    ],
                },
            }
        },
        methods: {
            formAdd() {
                this.formType = 'add';
                this.model.add = true;
                this.addData = Object.assign({}, addConst)
            },
            formEdit(index) {
                this.formType = 'edit';
                this.model.edit = true;
                let data = this.datas[index];
                let {id, uid, name, group_id} = data;
                this.editData = Object.assign(editConst, {id, uid, name, group_id});
            },
            formPass(index) {
                this.formType = 'pass';
                this.model.pass = true;
                let data = this.datas[index];
                let {id, uid, name} = data;
                this.passData = Object.assign(passConst, {id, uid, name});
            },
            formDel(index) {
                let data = this.datas[index];
                let uid = data.uid;
                this.$.posts('/user/del', {uid})
                    .then(res => {
                        this.$Message.success(res + '条记录删除成功！');
                        this.ajax_datas = xcon.arrsDel(this.ajax_datas, 'uid', uid)
                    })
                    .catch(error => {
                        this.$Message.error(error);
                    })
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

            pageChange(index) {
                this.pageIndex = index;
            },

            sizeChange(size) {
                this.pageSize = size;
            },
        },
        computed: {
            datas() {
                return xcon.pageData(this.ajax_datas, this.pageIndex, this.pageSize)
            },

            formTitle() {
                let title = '';
                switch (this.formType) {
                    case 'add':
                        title = '用户添加';
                        break;
                    case 'edit':
                        title = '用户修改';
                        break;
                    case 'pass':
                        title = '密码重置';
                        break;
                }
                return title;
            },
            inputDisable() {
                return this.formType !== 'add'
            },
        },
        created() {
            this.$.gets('/user/')
                .then(res => {
                    this.ajax_datas = res;
                })
                .catch(error => {
                    this.$Message.error(error);
                });
            this.$.gets('/user/group')
                .then(res => {
                    this.groups = res;
                    this.tableLoading = false;
                })
                .catch(error => {
                    this.$Message.error(error);
                })
        }
    }
</script>

<style scoped>

</style>