<template>
  <dev-article>
    <Row :gutter="16">
      <i-col span="6">
        <Card title="标的清单">
          <Tag color="green" slot="extra">的</Tag>
          <Row class="data-collect hidden-nowrap">总数：{{ count.data_total }}</Row>
          <Divider size="small" dashed></Divider>
          <Progress :percent="count.data_percent" stroke-color="#19be6b" hide-info></Progress>
        </Card>
      </i-col>
      <i-col span="6">
        <Card title="税率测算">
          <Tag color="red" slot="extra">算</Tag>
          <Row class="data-collect hidden-nowrap">待测：{{ count.count_num }}</Row>
          <Divider size="small" dashed></Divider>
          <Progress :percent="count.count_not_percent" stroke-color="red" hide-info></Progress>
        </Card>
      </i-col>
      <i-col span="6">
        <Card title="测算反馈">
          <Tag color="geekblue" slot="extra">馈</Tag>
          <Row class="data-collect hidden-nowrap">反馈：{{ count.back_num }}</Row>
          <Divider size="small" dashed></Divider>
          <Progress :percent="count.back_percent" stroke-color="geekblue" hide-info></Progress>
        </Card>
      </i-col>
      <i-col span="6">
        <Card title="协作成果">
          <Tag color="blue" slot="extra">果</Tag>
          <Row class="data-collect hidden-nowrap">完成：{{ count.result_num }}</Row>
          <Divider size="small" dashed></Divider>
          <Progress :percent="count.result_percent" stroke-color="red" hide-info></Progress>
        </Card>
      </i-col>
    </Row>
    <br>
    <div id="Split">
      <Split v-model="split1" class="split" min="600" max="300">
        <div slot="left" class="slot-left">
          <Tabs value="table">
            <TabPane label="测算标的" name="table">
              <Table
                :columns="cols"
                :data="datas"
                :loading="tableLoading"
                ref="selection"
                size="small"
                @on-current-change="selectChange"
                highlight-row border stripe>
              </Table>
              <Row class="margin-top16">
                <i-col class="hidden-nowrap align-right">
                  <Page :total="ajaxs.length" show-sizer transfer/>
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
                <Button class="margin-left8" type="primary" size="small" @click="countDateClick">查询
                </Button>
              </Row>
            </template>
          </Tabs>
        </div>
        <div slot="right" class="slot-right">
          <Tabs value="table">
            <TabPane label="税费清单" name="table">
              <div v-if="current">
                <Table
                  :columns="count_cols"
                  :data="counts"
                  :loading="countLoading"
                  ref="count_sec"
                  size="small"
                  border stripe>
                </Table>
                <br>
                <Row v-if="counts.length" class="hidden-nowrap">
                  <Tag color="success">测算合计：{{amounts}}</Tag>
                  <Button class="margin-left16" type="primary" @click="countUpto">提交审核</Button>
                </Row>
              </div>
            </TabPane>
            <!--表头附加相关操作：-->
            <template slot="extra">
              <Row class="hidden-nowrap">
                <Button
                  class="margin-left8"
                  type="primary"
                  size="small"
                  v-if="current"
                  @click="countAdd">添加
                </Button>
              </Row>
            </template>
          </Tabs>
        </div>
      </Split>
    </div>
    <Modal
      title="税种添加"
      v-model="formModel"
      :mask-closable="false"
      :loading="formLoading"
      @on-ok="formOk('form')"
      @on-cancel="formCancel('form')"
      width="550"
    >
      <Form ref="form" :model="form" :rules="rule" label-position="top">
        <FormItem prop="tax_id" label="税种">
          <Select v-model="form.tax_id" placeholder="税种选择..." transfer>
            <Option :value="tax.id" :key="tax.id" v-for="tax of taxs">{{tax.name}}
            </Option>
          </Select>
        </FormItem>
        <FormItem prop="tax_percent" label="税率">
          <Input v-model="form.tax_percent" placeholder="输入税种对应税率"/>
        </FormItem>
        <FormItem prop="tax_amount" label="税额">
          <Input v-model="form.tax_amount" placeholder="输入税种对应税额"/>
        </FormItem>
      </Form>
    </Modal>
  </dev-article>
</template>

<script>
    import xcon from '../libs/xcon'

    const formConst = {
        tax_id: '',
        tax_percent: '',
        tax_amount: '',
    };

    export default {
        name: "Count",
        data() {
            return {
                // split
                split1: 0.7,

                // count
                ajax_count: null,

                // date
                dateType: 'day',
                countDate: [new Date(), new Date()],

                // table
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
                        title: '标的名称',
                        key: 'name',
                    },
                    {
                        title: '产权人',
                        key: 'owner',
                    },
                    {
                        title: '产权性质',
                        key: 'area_type',
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
                ajaxs: [],
                pageIndex: 1,
                pageSize: 10,
                tableLoading: true,
                current: null,

                // form
                taxs: [],
                formModel: false,
                formLoading: true,
                form: Object.assign({}, formConst),
                rule: {
                    tax_id: [
                        {
                            required: true, message: '请选择相应的税种', trigger: 'change'
                        }
                    ],
                    tax_percent: [
                        {
                            required: true, message: '税率不得为空', trigger: 'blur'
                        },
                        {
                            pattern: /^\d+(\.\d{1,3})?$/,
                            message: '数值型，可以带3位小数',
                            trigger: 'change'
                        }
                    ],
                    tax_amount: [
                        {
                            required: true, message: '税额不得为空', trigger: 'blur'
                        },
                        {
                            pattern: /^\d+(\.\d{1,2})?$/,
                            message: '数值型，可以带2位小数',
                            trigger: 'change'
                        }
                    ],
                },

                countLoading: false,
                count_cols: [
                    {
                        width: 50,
                        type: 'index',
                        align: 'center',
                    },
                    {
                        title: '税种',
                        key: 'tax_name',
                    },
                    {
                        title: '税率',
                        key: 'tax_percent',
                        align: 'right',
                    },
                    {
                        title: '税额',
                        key: 'tax_amount',
                        align: 'right',
                    },
                    {
                        title: '操作',
                        key: 'action',
                        width: 80,
                        align: 'center',
                        render: (h, params) => {
                            return h('div', [
                                h('Button', {
                                    props: {
                                        type: 'warning',
                                        size: 'small'
                                    },
                                    style: {
                                        marginRight: '5px'
                                    },
                                    on: {
                                        click: () => {
                                            this.countDel(params.index)
                                        }
                                    }
                                }, '删除')
                            ]);
                        }
                    }
                ],
                counts: [],
            }
        },
        methods: {
            countDateClick() {
                this.current = null;
                this.tableLoading = true;

                let begin = xcon.dateFormat(this.countDate[0], 'yyyy-MM-dd');
                let end = xcon.dateFormat(this.countDate[1], 'yyyy-MM-dd');
                this.$.posts('/count/find', {begin, end})
                    .then(res => {
                        this.ajaxs = res;
                        this.tableLoading = false;
                    })
                    .catch(error => {
                        this.tableLoading = false;
                        this.$Message.error(error);
                    })
            },
            dateChange(val) {
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

            // 表格选择
            selectChange(row) {
                this.current = row;
                this.countLoading = true;
                // 查询标的对应测算税种列表
                this.$.posts('/count/tax', {data_id: row.id})
                    .then(res => {
                        this.counts = res;
                        this.countLoading = false
                    })
                    .catch(error => {
                        this.countLoading = false;
                        this.$Message.error(error);
                    });
            },

            // 添加测算税种
            countAdd() {
                this.formModel = true
            },
            // 删除测算税种
            countDel(index) {
                let row = this.counts[index];
                this.countLoading = true;
                this.$.posts('/count/del', {uid: row.uid})
                    .then(res => {
                        xcon.arrsDel(this.counts, 'uid', row.uid);

                        this.countLoading = false;
                        this.$Message.success(res + '条税种删除成功！');
                    })
                    .catch(error => {
                        this.countLoading = false;
                        this.$Message.error(error);
                    });
            },

            formOk(name) {
                this.$refs[name].validate((valid) => {
                    if (valid) {
                        // 构造提交数据
                        let param = this.form;
                        param.data_id = this.current.id;
                        this.$.posts('/count/add', param)
                            .then(res => {
                                this.counts.push(res);

                                this.formModel = false;
                                this.$refs[name].resetFields();
                                this.$Message.success('税种、税额添加成功！');
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
                        this.$Message.error('表单验证无法通过，请重新输入');
                    }
                });
            },
            formCancel(name) {
                this.$refs[name].resetFields();
                this.$Message.warning('表单添加取消！');
            },

            // 测算结束，提交审核
            countUpto() {
                let row = this.current;
                this.countLoading = true;

                this.$.posts('/count/upto', {uid: row.uid})
                    .then(res => {
                        this.current = null;
                        this.countLoading = false;
                        this.ajax_count.count_num--;

                        xcon.arrsDel(this.ajaxs, 'uid', row.uid);
                        this.$Message.success(res + '条测算标的提交审核成功！');
                    })
                    .catch(error => {
                        this.countLoading = false;
                        this.$Message.error(error);
                    })
            },
        },
        computed: {
            count() {
                if (this.ajax_count === null) {
                    return {
                        data_not: 0,
                        data_total: 0,
                        data_percent: 0,
                        count_num: 0,
                        count_all: 0,
                        count_percent: 0,
                        back_num: 0,
                        back_all: 0,
                        back_percent: 0,
                        result_num: 0,
                        result_percent: 0,
                    }
                } else {
                    let {data_total, data_not, count_num, count_all, back_num, back_all, result_num} = this.ajax_count;

                    data_not = parseFloat(data_not);
                    data_total = parseFloat(data_total);
                    count_num = parseFloat(count_num);
                    count_all = parseFloat(count_all);
                    back_num = parseFloat(back_num);
                    back_all = parseFloat(back_all);
                    result_num = parseFloat(result_num);

                    let data_percent = count_all + data_not > 0 ? count_all / (count_all + data_not) * 100 : 0;

                    let count_percent = count_all ? (count_all - count_num) / count_all * 100 : 0;
                    let count_not_percent = count_all + back_num > 0 ? back_num / (count_all + back_num) * 100 : 0;

                    let back_percent = back_all ? (back_all - back_num) / back_all * 100 : 0;
                    let back_not_percent = back_all + result_num > 0 ? result_num / (back_all + result_num) * 100 : 0;

                    let result_percent = data_total ? result_num / data_total * 100 : 0;

                    return {
                        data_not,
                        data_total,
                        data_percent,
                        count_num,
                        count_all,
                        count_not_percent,
                        back_num,
                        back_all,
                        back_not_percent,
                        result_num,
                        count_percent,
                        back_percent,
                        result_percent
                    };
                }
            },
            datas() {
                return xcon.pageData(this.ajaxs, this.pageIndex, this.pageSize)
            },
            amounts() {
                let total = 0;
                this.counts.forEach(item => {
                    total += Number(item.tax_amount)
                });
                return total
            },
        },
        created() {
            this.$.gets('/count/index')
                .then(res => {
                    this.ajaxs = res.datas;
                    this.taxs = res.taxs;
                    this.ajax_count = res.count;
                    this.tableLoading = false
                })
                .catch(error => {
                    this.tableLoading = false;
                    this.$Message.error(error);
                });
        },
        mounted() {
            const that = this;
            window.onresize = function () {
                if (that.$route.path !== '/vcount') return;

                // 分割条高度计算
                let height = document.body.clientHeight - 60 - 36 - 196 - 20;
                document.getElementById('Split').style.height = height + 'px';
            };
            window.onresize();
        },
    }
</script>

<style scoped>

</style>