<template>
  <dev-article>
    <Card>
      <Tabs value="table">
        <TabPane label="部门列表" name="table">
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
        <FormItem prop="id" label="编号">
          <Input v-model="form.id" :maxlength="2" placeholder="输入部门编号，1-2位数字" :disabled="inputDisable"/>
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

    const formData = {
        id: '',
        uid: '',
        name: '',
    };

    export default {
        name: "Part",
        data() {
            return {
                tableLoading: true,

                pageIndex: 1,
                pageSize: 10,
                cols: [
                    {
                        width: 55,
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
                                            this.formEdit(params.index)
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
                                            this.formDel(params.index)
                                        }
                                    }
                                }, '删除')
                            ]);
                        }
                    }
                ],
                ajax_datas: [],

                formType: 'add',
                formModel: false,
                formLoading: true,
                form: Object.assign({}, formData),
                rule: {
                    id: [
                        {
                            required: true, message: '部门编号不得为空', trigger: 'blur'
                        },
                        {
                            min: 1,
                            max: 2,
                            message: '长度最小1位，最大2位',
                            trigger: 'change'
                        },
                        {
                            pattern: /^\d{1,2}$/,
                            message: '必须是数字',
                            trigger: 'change'
                        },
                    ],
                    name: [
                        {
                            required: true, message: '部门名称不得为空', trigger: 'blur'
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
                },
            }
        },
        methods: {
            formAdd() {
                this.formType = 'add';
                this.formModel = true;
                this.form = Object.assign({}, formData)
            },
            formEdit(index) {
                this.formType = 'edit';
                this.formModel = true;
                let data = this.datas[index];
                this.form = Object.assign({}, data);
            },
            formDel(index) {
                let data = this.datas[index];
                let uid = data.uid;

                this.$.posts('/part/del', {uid})
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
                        this.$.posts('/part/' + action, this.form)
                            .then(res => {
                                if (action === 'add') {
                                    this.ajax_datas.push(res);
                                } else {
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
                return this.formType === 'add' ? '部门添加' : '部门修改'
            },
            inputDisable() {
                return this.formType !== 'add'
            }
        },
        created() {
            this.$.gets('/part/')
                .then(res => {
                    this.ajax_datas = res;
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