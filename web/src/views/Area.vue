<template>
    <dev-article>
        <Card>
            <Tabs value="table">
                <TabPane label="部门列表" name="table">
                    <Table
                            :columns="cols"
                            :data="parts"
                            :loading="tableLoading"
                            ref="parts"
                            size="small"
                            border stripe>
                    </Table>
                    <Row class="margin-top16 hidden-nowrap align-right">
                        <Page
                                :total="ajax_parts.length"
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
                        <Button type="primary" size="small" @click="partAdd">添加</Button>
                    </Row>
                </template>
            </Tabs>
        </Card>
        <Modal
                title="部门添加"
                v-model="formAdd"
                :mask-closable="false"
                :loading="formLoading"
                @on-ok="formOk('form')"
                @on-cancel="formCancel"
        >
            <Form ref="form" :model="form" :rules="rule" label-position="top">
                <FormItem prop="id" label="编号">
                    <Input v-model="form.id" :maxlength="2" placeholder="输入部门编号，1-2位数字"/>
                </FormItem>
                <FormItem prop="name" label="名称">
                    <Input v-model="form.name" :maxlength="10" placeholder="输入部门名称，2-10个中文字符"/>
                </FormItem>
            </Form>
        </Modal>
    </dev-article>
</template>

<script>
    import xcon from '../libs/xcon'

    export default {
        name: "Area",
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
                        title: '编号',
                        key: 'id',
                    },
                    {
                        title: '名称',
                        key: 'name',
                    },
                    {
                        title: '操作',
                        key: 'action',
                        width: 150,
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
                                            this.partEdit(params.index)
                                        }
                                    }
                                }, '修改'),
                                h('Button', {
                                    props: {
                                        type: 'error',
                                        size: 'small'
                                    },
                                    on: {
                                        click: () => {
                                            this.partDel(params.index)
                                        }
                                    }
                                }, '删除')
                            ]);
                        }
                    }
                ],
                ajax_parts: [],

                formAdd: false,
                formLoading: true,
                form: {
                    id: '',
                    name: '',
                },
                rule: {
                    id: [
                        {
                            required: true, message: '部门编号不得为空', trigger: 'blur'
                        },
                        {
                            min: 1,
                            max: 2,
                            message: '帐号长度最小1位，最大2位',
                            trigger: 'change'
                        }
                    ],
                    name: [
                        {
                            required: true, message: '部门名称不得为空', trigger: 'blur'
                        },
                        {
                            min: 2,
                            max: 10,
                            message: '帐号长度最小2位，最大10位',
                            trigger: 'change'
                        },
                        {
                            pattern: /^[\u4e00-\u9fa5]+$/,
                            message: '必须是汉字，不得有空格',
                            trigger: 'change'
                        },
                    ],
                },
            }
        },
        methods: {
            partAdd() {
                this.formAdd = true;
            },
            partDel(index) {
                this.ajax_parts.splice(index, 1);
            },
            partEdit(index) {
                this.$Modal.info({
                    title: '用户信息',
                    content: `帐号：${this.ajax_parts[index].id}<br>名称：${this.ajax_parts[index].name}<br>部门信息：${this.ajax_parts[index].part_name}<br>权限分组：${this.ajax_parts[index].group_name}`
                })
            },
            formOk(name) {
                this.$refs[name].validate((valid) => {
                    if (valid) {
                        // 提交this.form服务器端认证
                        window.console.log(this.form);
                        this.ajax_parts.push(this.form);
                        this.formAdd = false;

                        this.$Message.success('用户帐号添加成功！');
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
                this.$Message.warning('表单添加取消！');
            },

            pageChange(index) {
                this.pageIndex = index;
            },
            sizeChange(size) {
                this.pageSize = size;
            },
        },
        computed: {
            parts() {
                return xcon.pageData(this.ajax_parts, this.pageIndex, this.pageSize)
            },
        },
        created() {
            this.$.gets('/part/')
                .then(res => {
                    this.ajax_parts = res;
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