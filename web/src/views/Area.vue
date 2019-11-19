<template>
  <dev-article>
    <Card>
      <Tabs value="table">
        <TabPane label="地区列表" name="table">
          <Table
            :columns="cols"
            :data="datas"
            :loading="tableLoading"
            ref="datas"
            size="small"
            border stripe>
          </Table>
          <Row class="margin-top16 hidden-nowrap">
            <i-col span="12" class="align-left">
              <Button type="primary" size="small" @click="townAdd">乡镇添加</Button>
            </i-col>
            <i-col span="12" class="align-right">
              <Page
                :total="ajax_datas.length"
                :page-size="pageSize"
                :page-size-opts="[5, 10]"
                show-sizer
                transfer
                @on-change="pageChange"
                @on-page-size-change="sizeChange"
              />
            </i-col>

          </Row>
        </TabPane>
        <!--表头附加相关操作：-->
        <template slot="extra">
          <Row class="hidden-nowrap">
            <Select v-model="area_id" placeholder="地区选择..." style="width: 120px;" class="margin-right8"
                    @on-change="areaChange" transfer>
              <Option :value="area.id" :key="area.id" v-for="area of areas">{{area.name}}</Option>
            </Select>
            <Button type="primary" size="small" @click="formAdd">地区添加</Button>
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
      @on-cancel="formCancel('form')"
    >
      <Form ref="form" :model="form" :rules="rule" label-position="top">
        <FormItem prop="id" label="编号">
          <Input v-model="form.id" :maxlength="6" placeholder="输入地区编号，6位数字" :disabled="inputDisable"/>
        </FormItem>
        <FormItem prop="name" label="名称">
          <Input v-model="form.name" :maxlength="10" placeholder="输入地区名称，3-10个中文字符"/>
        </FormItem>
      </Form>
    </Modal>

    <Modal
      :title="formTitle"
      v-model="townModel"
      :mask-closable="false"
      :loading="formLoading"
      @on-ok="townOk('town')"
      @on-cancel="formCancel('town')"
    >
      <Form ref="town" :model="town" :rules="town_rule" label-position="top">
        <FormItem prop="id" label="编号">
          <Input v-model="town.id" :maxlength="8" placeholder="输入乡镇编号，8位数字" disabled="disabled"/>
        </FormItem>
        <FormItem prop="code" label="序号">
          <Input v-model="town.code" :maxlength="2" placeholder="输入乡镇序号，2位数字" @on-change="codeChange" :disabled="inputDisable"/>
        </FormItem>
        <FormItem prop="name" label="名称">
          <Input v-model="town.name" :maxlength="10" placeholder="输入乡镇名称，3-10个中文字符"/>
        </FormItem>
        <FormItem prop="up_id" label="地区编号">
          <Select v-model="town.up_id" placeholder="地区选择..." disabled="disabled" transfer>
            <Option :value="area.id" :key="area.id" v-for="area of areas">{{area.name}}</Option>
          </Select>
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

    const townData = {
        code: '',
        id: '',
        uid: '',
        name: '',
        up_id: '',
    };

    export default {
        name: "Area",
        data() {
            return {
                tableLoading: true,

                pageIndex: 1,
                pageSize: 5,
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
                                            this.townEdit(params.index)
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

                area_id: '',
                areas: [],
                ajax_datas: [],

                formType: 'add',
                formModel: false,
                formLoading: true,
                form: Object.assign({}, formData),
                rule: {
                    id: [
                        {
                            required: true, message: '地区编号不得为空', trigger: 'blur'
                        },
                        {
                            len: 6,
                            pattern: /^\d{6}$/,
                            message: '长度6位，必须是数字',
                            trigger: 'change'
                        },
                    ],
                    name: [
                        {
                            required: true, message: '地区名称不得为空', trigger: 'blur'
                        },
                        {
                            min: 3,
                            max: 10,
                            message: '长度最小3位，最大10位',
                            trigger: 'change'
                        },
                        {
                            pattern: /^[\u4e00-\u9fa5]+$/,
                            message: '必须是汉字，不得有空格',
                            trigger: 'change'
                        },
                    ],
                },

                townModel: false,
                town: Object.assign({}, townData),
                town_rule: {
                    id: [
                        {
                            required: true, message: '乡镇编号不得为空', trigger: 'blur'
                        },
                        {
                            len: 8,
                            pattern: /^\d{8}$/,
                            message: '长度8位，必须是数字',
                            trigger: 'change'
                        },
                    ],
                    code: [
                        {
                            required: true, message: '乡镇序号不得为空', trigger: 'blur'
                        },
                        {
                            len: 2,
                            pattern: /^\d{2}$/,
                            message: '长度2位，必须是数字',
                            trigger: 'change'
                        },
                    ],
                    name: [
                        {
                            required: true, message: '地区名称不得为空', trigger: 'blur'
                        },
                        {
                            min: 3,
                            max: 10,
                            message: '长度最小3位，最大10位',
                            trigger: 'change'
                        },
                        {
                            pattern: /^[\u4e00-\u9fa5]+$/,
                            message: '必须是汉字，不得有空格',
                            trigger: 'change'
                        },
                    ],
                    up_id: [
                        {
                            required: true, message: '地区编号不得为空', trigger: 'blur'
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

            formDel(index) {
                let data = this.datas[index];
                let uid = data.uid;

                this.$.posts('/area/del', {uid})
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
                        this.$.posts('/area/add', this.form)
                            .then(res => {
                                this.areas.push(res);

                                this.formModel = false;
                                this.$refs[name].resetFields();
                                this.$Message.success(this.formTitle + '成功！');
                            })
                            .catch(error => {
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
            townAdd() {
                if (!this.area_id) {
                    this.$Message.error('先选择地区');
                    return;
                }
                this.formType = 'addtown';
                this.townModel = true;
                this.town = Object.assign({}, townData, {up_id: this.area_id})
            },
            townEdit(index) {
                // edit town
                this.formType = 'edit';
                this.townModel = true;
                let data = this.datas[index];
                this.town = Object.assign({}, data);
            },
            codeChange() {
                this.town.id = this.area_id + this.town.code;
            },
            townOk(name) {
                let action = this.formType;
                this.$refs[name].validate((valid) => {
                    if (valid) {
                        this.$.posts('/area/' + action, this.town)
                            .then(res => {
                                if (action === 'addtown') {
                                    this.ajax_datas.push(res);
                                } else {
                                    this.ajax_datas = xcon.arrsEdit(this.ajax_datas, 'id', res.id, res)
                                }

                                this.townModel = false;
                                this.$refs[name].resetFields();
                                this.$Message.success(this.formTitle + '成功！');
                            })
                            .catch(error => {
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

            areaChange(val) {
                this.tableLoading = true;
                this.$.posts('/area/find', {area_id: val})
                    .then(res => {
                        this.ajax_datas = res;
                        this.tableLoading = false;
                    })
                    .catch(error => {
                        this.$Message.error(error);
                    })
            },
        },
        computed: {
            datas() {
                return xcon.pageData(this.ajax_datas, this.pageIndex, this.pageSize)
            },
            formTitle() {
                let title;
                switch (this.formType) {
                    case 'add' :
                        title = '地区添加';
                        break;
                    case 'addtown':
                        title = '乡镇添加';
                        break;
                    case 'edit':
                        title = '乡镇修改';
                        break;
                }
                return title;
            },
            inputDisable() {
                return this.formType === 'edit'
            }
        },
        created() {
            this.$.gets('/area/')
                .then(res => {
                    this.ajax_datas = res.datas;
                    this.areas = res.areas;
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