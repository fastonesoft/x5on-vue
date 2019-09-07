<template>
  <dev-article>
    <div class="user-body">
      <Card>
        <Tabs value="table">
          <TabPane label="用户列表" name="table">
            <Table
              :columns="cols"
              :data="datas"
              :loading="tableLoading"
              ref="users"
              size="small"
              border stripe>
            </Table>
            <Row class="margin-top16 hidden-nowrap align-right">
              <Page :total="datas.length" show-sizer transfer/>
            </Row>
          </TabPane>
          <!--表头附加相关操作：-->
          <template slot="extra">
            <Row class="hidden-nowrap">
              <Select v-model="part_id" placeholder="部门选择..." style="width:160px" transfer>
                <Option
                  v-for="item in parts"
                  :value="item.part_id"
                  :key="item.part_id">{{ item.part_name }}
                </Option>
              </Select>
              <Dropdown style="margin-left: 16px" @on-click="userDown" transfer>
                <Button>
                  用户菜单
                  <Icon type="ios-arrow-down"></Icon>
                </Button>
                <DropdownMenu slot="list">
                  <DropdownItem name="add">用户添加</DropdownItem>
                  <DropdownItem name="down" divided>用户数据导出</DropdownItem>
                </DropdownMenu>
              </Dropdown>
            </Row>


          </template>
        </Tabs>
      </Card>
    </div>
    <Modal
      title="用户添加"
      v-model="formAdd"
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
        <FormItem prop="part_name" label="部门选择">
          <Select v-model="form.part_name" placeholder="部门选择..." transfer>
            <Option
              v-for="item in parts"
              :value="item.part_name"
              :key="item.part_id">{{ item.part_name }}
            </Option>
          </Select>
        </FormItem>
        <FormItem prop="role_name" label="权限分组">
          <Select v-model="form.role_name" placeholder="权限组选择..." transfer>
            <Option
              v-for="item in roles"
              :value="item.role_name"
              :key="item.role_id">{{ item.role_name }}
            </Option>
          </Select>
        </FormItem>

      </Form>
    </Modal>
  </dev-article>
</template>

<script>
    export default {
        name: "User",
        data() {
            return {

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
                        key: 'role_name',
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
                                            this.userEdit(params.index)
                                        }
                                    }
                                }, '编辑'),
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
                datas: [
                    {
                        id: 'wanger',
                        name: '王二',
                        part_name: '税务局',
                        role_name: '员工组'
                    },
                    {
                        id: 'qiansan',
                        name: '钱三',
                        part_name: '工商局',
                        role_name: '员工组'
                    },
                    {
                        id: 'zhaowu',
                        name: '赵五',
                        part_name: '法院',
                        role_name: '管理组'
                    },
                    {
                        id: 'zhaowu',
                        name: '赵五',
                        part_name: '法院',
                        role_name: '管理组'
                    },
                    {
                        id: 'zhaowu',
                        name: '赵五',
                        part_name: '法院',
                        role_name: '管理组'
                    },
                    {
                        id: 'zhaowu',
                        name: '赵五',
                        part_name: '法院',
                        role_name: '管理组'
                    },
                    {
                        id: 'zhaowu',
                        name: '赵五',
                        part_name: '法院',
                        role_name: '管理组'
                    },
                    {
                        id: 'zhaowu',
                        name: '赵五',
                        part_name: '法院',
                        role_name: '管理组'
                    },
                    {
                        id: 'zhaowu',
                        name: '赵五',
                        part_name: '法院',
                        role_name: '管理组'
                    },
                    {
                        id: 'zhaowu',
                        name: '赵五',
                        part_name: '法院',
                        role_name: '管理组'
                    },
                    {
                        id: 'zhaowu',
                        name: '赵五',
                        part_name: '法院',
                        role_name: '管理组'
                    },
                ],
                part_id: '02',
                parts: [
                    {
                        part_id: '01',
                        part_name: '税务局',
                    },
                    {
                        part_id: '02',
                        part_name: '法院',
                    },
                    {
                        part_id: '03',
                        part_name: '工商局',
                    },
                ],
                role_id: '01',
                roles: [
                    {
                        role_id: '01',
                        role_name: '员工组',
                    },
                    {
                        role_id: '99',
                        role_name: '管理组',
                    },
                ],
                formAdd: false,
                formLoading: true,
                form: {
                    id: '',
                    name: '',
                    pass: '',
                    part_name: '',
                    role_name: '',
                },
                rule: {
                    id: [
                        {
                            required: true, message: '用户账号不得为空', trigger: 'blur'
                        },
                        {
                            min: 5,
                            max: 20,
                            message: '帐号长度最小5位，最大20位',
                            trigger: 'blur'
                        }
                    ],
                    name: [
                        {
                            required: true, message: '用户名称不得为空', trigger: 'blur'
                        },
                        {
                            min: 5,
                            max: 20,
                            message: '帐号长度最小5位，最大20位',
                            trigger: 'blur'
                        }
                    ],
                    pass: [
                        {
                            required: true, message: '用户密码不得为空', trigger: 'blur'
                        },
                        {
                            min: 6,
                            max: 20,
                            message: '帐号长度最小6位，最大20位',
                            trigger: 'blur'
                        }
                    ],
                    part_name: [
                        {
                            required: true, message: '请选择相应的部门', trigger: 'blur'
                        }
                    ],
                    role_name: [
                        {
                            required: true, message: '请选择相应的权限分组', trigger: 'blur'
                        }
                    ],
                }
            }
        },
        methods: {
            userDown(name) {
                switch (name) {
                    case 'add': {
                        this.formAdd = true;
                        break;
                    }
                    case 'down': {
                        this.$refs.users.exportCsv({
                            filename: '用户信息'
                        });
                        break;
                    }
                }
            },
            userDel(index) {
                this.datas.splice(index, 1);
            },
            userEdit(index) {
                this.$Modal.info({
                    title: '用户信息',
                    content: `帐号：${this.datas[index].id}<br>名称：${this.datas[index].name}<br>部门信息：${this.datas[index].part_name}<br>权限分组：${this.datas[index].role_name}`
                })
            },
            formOk(name) {
                this.$refs[name].validate((valid) => {
                    if (valid) {
                        // 提交this.form服务器端认证
                        window.console.log(this.form);
                        this.datas.push(this.form);
                        this.formAdd = false;

                        this.$Message.success('用户帐号添加成功！');
                    } else {
                        this.formLoading = false;
                        setTimeout(() => {
                            this.$nextTick(() => {
                                this.formLoading = true;
                            });
                        }, 100);

                        this.$Message.error('用户帐号无法通过验证，请重新输入');
                    }
                });

            },
            formCancel() {
                this.$refs['form'].resetFields();
                this.$Message.warning('表单添加取消！');
            },
        },
        created() {
            setTimeout(() => {
                this.tableLoading = false;
            }, 1000)
        }
    }
</script>

<style scoped>

  .user-body {

  }

  .user-collect {
    font-size: 16px;
  }

  .text-indent {
    text-indent: 30px;
  }

</style>