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
                :title="formTitle"
                v-model="formModel"
                :mask-closable="false"
                :loading="formLoading"
                @on-ok="formOk('form')"
                @on-cancel="formCancel"
        >
            <Form ref="form" :model="form" :rules="rule" label-position="top">
                <FormItem prop="id" label="帐号">
                    <Input v-model="form.id" :maxlength="20" placeholder="输入帐号，5-20个字符"/>
                </FormItem>
                <FormItem prop="name" label="名称">
                    <Input v-model="form.name" :maxlength="20" placeholder="输入帐号名称，4-10个中文字符"/>
                </FormItem>
                <FormItem prop="pass" label="密码">
                    <Input v-model="form.pass" :maxlength="20" placeholder="输入用户密码，6-20个字符"/>
                </FormItem>
                <FormItem prop="part_id" label="部门选择">
                    <Select v-model="form.part_id" placeholder="部门选择..." transfer>
                        <Option
                                v-for="item in parts"
                                :value="item.id"
                                :key="item.id">{{ item.name }}
                        </Option>
                    </Select>
                </FormItem>
                <FormItem prop="group_id" label="权限分组">
                    <Select v-model="form.group_id" placeholder="权限组选择..." transfer>
                        <Option
                                v-for="item in groups"
                                :value="item.id"
                                :key="item.id">{{ item.name }}
                        </Option>
                    </Select>
                </FormItem>

            </Form>
        </Modal>
    </dev-article>
</template>

<script>
    import xcon from '../libs/xcon'

    const addData = {
        id: '',
        uid: '',
        name: '',
        pass: '',
        part_id: '',
        group_id: '',
    };
    const editData = {
        id: '',
        uid: '',
        name: '',
        part_id: '',
        group_id: '',
    };
    const passData = {
        id: '',
        uid: '',
        pass: '',
    }

    export default {
        name: "User",
        data() {
            return {
                tableLoading: true,

                pageIndex: 1,
                pageSize: 10,
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
                        title: '权限用户',
                        key: 'group_name',
                    },
                    {
                        title: '操作',
                        key: 'action',
                        width: 200,
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
                                            this.userEdit(params.index)
                                        }
                                    }
                                }, '编辑'),
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
                                            this.userEdit(params.index)
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
                                            this.userDel(params.index)
                                        }
                                    }
                                }, '删除')
                            ]);
                        }
                    }
                ],
                ajax_datas: [],
                parts: [],
                groups: [],

                formType: 'add',
                formModel: false,
                formLoading: true,
                form: Object.assign({}, addData),
                rule: {
                    id: [
                        {
                            required: true, message: '用户账号不得为空', trigger: 'blur'
                        },
                        {
                            min: 5,
                            max: 20,
                            message: '长度最小5位，最大20位',
                            trigger: 'change'
                        }
                    ],
                    name: [
                        {
                            required: true, message: '用户名称不得为空', trigger: 'blur'
                        },
                        {
                            min: 5,
                            max: 20,
                            message: '长度最小5位，最大20位',
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
                            required: true, message: '用户密码不得为空', trigger: 'blur'
                        },
                        {
                            min: 6,
                            max: 20,
                            message: '长度最小6位，最大20位',
                            trigger: 'change'
                        },
                        {
                            pattern: /^\w+$/,
                            message: '必须是字母、数字或者下划线',
                            trigger: 'change'
                        },
                    ],
                    part_id: [
                        {
                            required: true, message: '请选择相应的部门', trigger: 'change'
                        }
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
            formAdd() {
                this.formType = 'add';
                this.formModel = true;
                this.form = Object.assign({}, addData)
            },
            formEdit(index) {
                this.formType = 'edit';
                this.formModel = true;
                let data = this.datas[index];
                let {id, uid, name, part_id, group_id} = data;
                this.form = Object.assign({}, {id, uid, name, part_id, group_id});
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
                // 增加表单类型检测
                let action = this.formType;
                this.$refs[name].validate((valid) => {
                    if (valid) {
                        this.$.posts('/user/' + action, this.form)
                            .then(res => {
                                if (action === 'add') {
                                    this.ajax_datas.push(res);
                                } else if (action === 'edit') {
                                    this.ajax_datas = xcon.arrsEdit(this.ajax_datas, 'id', res.id, res)
                                }

                                this.formModel = false;
                                this.$refs['form'].resetFields();
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

            formCancel() {
                this.$refs['form'].resetFields();
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
            this.$.gets('/part/')
                .then(res => {
                    this.parts = res;
                })
                .catch(error => {
                    this.$Message.error(error);
                });
            this.$.gets('/group/')
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