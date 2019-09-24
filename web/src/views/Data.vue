<template>
  <dev-article>
    <Row :gutter="16">
      <i-col span="6">
        <Card title="标的清单">
          <!--数据采集完毕的数据 confirmed-->
          <Tag color="green" slot="extra">的</Tag>
          <Row class="data-collect hidden-nowrap">{{ count.collect.num }}%</Row>
          <Divider size="small" dashed></Divider>
          <Row class="data-collect_not">剩余总数 {{ count.collect.not }}</Row>
        </Card>
      </i-col>
      <i-col span="6">
        <Card title="税率测算">
          <Tag color="red" slot="extra">算</Tag>
          <Row class="data-collect hidden-nowrap">{{ count.count.num }}%</Row>
          <Divider size="small" dashed></Divider>
          <Progress status="success" :percent="count.count.num" hide-info></Progress>
        </Card>
      </i-col>
      <i-col span="6">
        <Card title="协作成果">
          <Tag color="blue" slot="extra">果</Tag>
          <Row class="data-collect hidden-nowrap">{{ count.result.num }}%</Row>
          <Divider size="small" dashed></Divider>
          <Progress status="wrong" :percent="count.result.num" hide-info></Progress>
        </Card>
      </i-col>
      <i-col span="6">
        <Card title="快捷操作">
          <Row class="data-collect align-center margin-bottom22 hidden-nowrap">添加标的数据</Row>
          <Button type="primary" icon="md-add" @click="formAdd=true" long>添加</Button>
        </Card>
      </i-col>
    </Row>
    <Row class="margin-top16">
      <Card>
        <Tabs value="table">
          <TabPane label="标的清单" name="table">
            <Table
              :columns="cols"
              :data="datas"
              :loading="tableLoading"
              ref="table"
              size="small"
              border stripe>
            </Table>
            <Row class="margin-top16">
              <i-col span="12" class="hidden-nowrap align-left">
                <Button type="primary" @click="updateCount">提交测算</Button>
                <Button type="error" class="margin-left16" @click="delRecord">删除记录</Button>
              </i-col>
              <i-col span="12" class="hidden-nowrap align-right">
                <Page
                  :total="ajax_datas.length"
                  :page-size="pageSize"
                  :page-size-opts="[10, 20, 50, 100]"
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
              <RadioGroup v-model="dateType" @on-change="dateTypeChange">
                <Radio label="day">今日</Radio>
                <Radio label="week">周</Radio>
                <Radio label="month">月</Radio>
                <Radio label="year">年</Radio>
              </RadioGroup>
              <DatePicker
                v-model="countDate"
                type="daterange"
                style="width: 180px"
                @on-change="dateChange"
                transfer>
              </DatePicker>
              <Button class="margin-left8" type="primary" size="small" @click="countDateClick">查询</Button>
            </Row>
          </template>
        </Tabs>
      </Card>
    </Row>
    <Modal
      title="标的添加"
      v-model="formAdd"
      :mask-closable="false"
      :loading="formLoading"
      @on-ok="formOk('form')"
      @on-cancel="formCancel"
      width="550"
    >
      <Form ref="form" :model="form" :rules="rule" label-position="top" inline>
        <FormItem prop="id" label="编号">
          <Input v-model="form.id" placeholder="输入标的编号"/>
        </FormItem>
        <FormItem prop="name" label="拍卖标的" style="width: 330px;">
          <i-col></i-col>
          <Input v-model="form.name" placeholder="输入拍卖标的相关说明"/>
        </FormItem>
        <br>
        <FormItem prop="owner" label="产权人">
          <Input v-model="form.owner" placeholder="输入产权人姓名"/>
        </FormItem>
        <FormItem prop="type_name" label="产权性质">
          <Select v-model="form.type_name" placeholder="产权性质选择..." style="width: 162px;" transfer>
            <Option value="国有" key="国有">国有</Option>
            <Option value="集体" key="集体">集体</Option>
          </Select>
        </FormItem>
        <FormItem prop="area_id" label="所属地区">
          <Select v-model="form.area_id" placeholder="地区选择..." style="width: 162px;" transfer>
            <Option value="321204" key="321204">姜堰区</Option>
            <Option value="999999" key="999999">其他地区</Option>
          </Select>
        </FormItem>
        <FormItem prop="area_build" label="建筑面积">
          <Input v-model="form.area_build" placeholder="输入建筑面积"/>
        </FormItem>
        <FormItem prop="area_soil" label="土地面积">
          <Input v-model="form.area_soil" placeholder="输入土地面积"/>
        </FormItem>
        <FormItem prop="use_year" label="使用年限">
          <Input v-model="form.use_year" placeholder="输入使用年限"/>
        </FormItem>
        <br>
        <FormItem prop="price_begin" label="初始价格">
          <Input v-model="form.price_begin" placeholder="输入标的初始价格"/>
        </FormItem>
        <FormItem prop="price_ass" label="评估价格">
          <Input v-model="form.price_ass" placeholder="输入标的评估价格"/>
        </FormItem>
        <FormItem prop="price_shoot" label="起拍价格">
          <Input v-model="form.price_shoot" placeholder="输入标的起拍价格"/>
        </FormItem>
      </Form>
    </Modal>
  </dev-article>
</template>

<script>
    import xcon from '../libs/xcon'

    export default {
        name: "Data",
        data() {
            return {
                count: {
                    collect: {num: 30, not: 20},
                    count: {num: 60, not: 10},
                    result: {num: 90, not: 20}
                },
                formAdd: false,
                dateType: 'day',
                countDate: [new Date(), new Date()],

                pageIndex: 1,
                pageSize: 10,
                tableLoading: true,  // 表格数据加载中
                formLoading: true,  // 表单加载中
                form: {
                    id: '',
                    name: '',
                    owner: '',
                    type_name: '',
                    area_build: '',
                    area_soil: '',
                    use_year: '',
                    price_begin: '',
                    price_ass: '',
                    price_shoot: '',
                    area_id: ''
                },
                cols: [
                    {
                        width: 50,
                        type: 'selection',
                        align: 'center',
                    },
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
                        title: '标的名称',
                        key: 'name',
                    },
                    {
                        title: '产权人',
                        key: 'owner',
                    },
                    {
                        title: '产权性质',
                        key: 'type_name',
                    },
                    {
                        title: '所属地区',
                        key: 'area_name',
                    },
                    {
                        title: '建筑面积',
                        key: 'area_build',
                    },
                    {
                        title: '土地面积',
                        key: 'area_soil',
                    },
                    {
                        title: '使用年限',
                        key: 'use_year',
                    },
                    {
                        title: '初始价格',
                        key: 'price_begin',
                    },
                    {
                        title: '评价价格',
                        key: 'price_ass',
                    },
                    {
                        title: '起拍价格',
                        key: 'price_shoot',
                    },
                ],
                ajax_datas: [],
                datas: [],
                rule: {
                    id: [
                        {
                            required: true, message: '编号不得为空，且为数字', trigger: 'blur'
                        },
                        {
                            min: 4,
                            max: 20,
                            message: '编号长度最少4位，最多20位',
                            trigger: 'change'
                        },
                        {
                            pattern: /^\d+$/,
                            message: '必须以数字编号',
                            trigger: 'change'
                        },
                    ],
                    name: [
                        {
                            required: true, message: '拍卖标的名称不得为空', trigger: 'blur'
                        },
                        {
                            min: 6,
                            max: 50,
                            message: '标的名称最少6位，最多50位',
                            trigger: 'change'
                        },
                        {
                            pattern: /^[\x21-\x7f\u4e00-\u9fa5]+$/,
                            message: '可以用字母、数学、汉字',
                            trigger: 'change'
                        },
                    ],
                    owner: [
                        {
                            required: true, message: '产权人姓名不得为空', trigger: 'blur'
                        },
                        {
                            min: 2,
                            max: 4,
                            message: '产权人名称最少2位，最多4位',
                            trigger: 'change'
                        },
                        {
                            pattern: /^[\u4e00-\u9fa5]+$/,
                            message: '必须是汉字，不得有空格',
                            trigger: 'change'
                        },

                    ],
                    type_name: [
                        {
                            required: true, message: '请选择相应的产权性质', trigger: 'change'
                        }
                    ],
                    area_id: [
                        {
                            required: true, message: '请选择产权所属地区', trigger: 'change'
                        }
                    ],
                    area_build: [
                        {
                            required: true, message: '建筑面积不得为空', trigger: 'blur'
                        },
                        {
                            pattern: /^\d+(\.\d{1,2})?$/,
                            message: '数值型，可以带两位小数',
                            trigger: 'change'
                        }
                    ],
                    area_soil: [
                        {
                            required: true, message: '土地面积不得为空', trigger: 'blur'
                        },
                        {
                            pattern: /^\d+(\.\d{1,2})?$/,
                            message: '数值型，可以带两位小数',
                            trigger: 'change'
                        }
                    ],
                    use_year: [
                        {
                            required: true, message: '使用年限不得为空', trigger: 'blur'
                        },
                        {
                            pattern: /^\d{1,2}$/,
                            message: '最少1年，最多99年',
                            trigger: 'change'
                        }
                    ],
                    price_begin: [
                        {
                            required: true, message: '初始价格不得为空', trigger: 'blur'
                        },
                        {
                            pattern: /^\d+(\.\d{1,2})?$/,
                            message: '数值型，可以带两位小数',
                            trigger: 'change'
                        }
                    ],
                    price_ass: [
                        {
                            required: true, message: '评估价格不得为空', trigger: 'blur'
                        },
                        {
                            pattern: /^\d+(\.\d{1,2})?$/,
                            message: '数值型，可以带两位小数',
                            trigger: 'change'
                        }
                    ],
                    price_shoot: [
                        {
                            required: true, message: '起拍价格不得为空', trigger: 'blur'
                        },
                        {
                            pattern: /^\d+(\.\d{1,2})?$/,
                            message: '数值型，可以带两位小数',
                            trigger: 'change'
                        }
                    ],
                },
            }
        },
        methods: {
            countDateClick() {
                let begin = xcon.dateFormat(this.countDate[0], 'yyyy-MM-dd');
                let end = xcon.dateFormat(this.countDate[1], 'yyyy-MM-dd');

                this.tableLoading = true;
                this.$.posts('/data/find', {begin, end})
                    .then(res => {
                        this.datas = res;
                        this.tableLoading = false;
                    })
                    .catch(error => {
                        this.$Message.error(error);
                    })
            },
            dateChange(val) {
                // 自定义日期列表，清除radio选项
                this.dateType = '';
                this.countDate = val;
            },
            dateTypeChange(val) {
                const today = (new Date()).getTime();
                let date;
                switch (val) {
                    case 'day':
                        date = today;
                        break;
                    case 'week':
                        date = today - 86400000 * 7;
                        break;
                    case 'month':
                        date = today - 86400000 * 30;
                        break;
                    case 'year':
                        date = today - 86400000 * 365;
                        break;
                }
                this.countDate = [new Date(date), new Date(today)];
            },
            formOk(name) {
                this.$refs[name].validate((valid) => {
                    if (valid) {
                        // 提交this.form服务器端认证
                        this.$.posts('/data/add', this.form)
                            .then(res => {
                                Object.keys(res).length && this.datas.push(res);

                                this.formAdd = false;
                                // 重置表单
                                this.$refs['form'].resetFields();
                                this.$Message.success('数据添加成功！');
                            })
                            .catch(error => {
                                // 修改按钮状态
                                this.formLoading = false;
                                this.$nextTick(() => {
                                    this.formLoading = true;
                                });
                                // 出错提示
                                this.$Message.error(error);
                            });
                    } else {
                        this.formLoading = false;
                        this.$nextTick(() => {
                            this.formLoading = true;
                        });

                        this.$Message.error('表单验证无法通过，请重新输入');
                    }
                });
            },
            formCancel() {
                this.$refs['form'].resetFields();
                this.$Message.warning('表单添加取消！');
            },
            updateCount() {
                let select = this.$refs.table.getSelection();
                let arrs = [];
                select.forEach(function (item) {
                    arrs.push(item.uid)
                });
                let uids = arrs.join(',');

                // 提交测算
                this.tableLoading = true;
                this.$.posts('/data/upto', {uids})
                    .then(res => {
                        this.datas = res;
                        this.tableLoading = false;
                    })
                    .catch(error => {
                        this.$Message.error(error);
                    });
            },
            delRecord() {
                let select = this.$refs.table.getSelection();
                let arrs = [];
                select.forEach(function (item) {
                    arrs.push(item.uid)
                });
                let uids = arrs.join(',');

                // 删除数据
                this.tableLoading = true;
                this.$.posts('/data/del', {uids})
                    .then(res => {
                        this.datas = res;
                        this.tableLoading = false;
                    })
                    .catch(error => {
                        this.$Message.error(error);
                    });
            },

            pageChange(index) {
                this.pageIndex = index;
            },

            sizeChange(size) {
                this.pageSize = size;
            },
        },
        created() {
            this.$.gets('/data/index')
                .then(res => {
                    this.datas = res;
                    this.tableLoading = false
                })
                .catch(error => {
                    this.$Message.error(error);
                })
        }
    }
</script>

<style scoped>

</style>