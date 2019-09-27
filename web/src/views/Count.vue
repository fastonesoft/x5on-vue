<template>
  <dev-article>
    <Row :gutter="16">
      <i-col span="6">
        <Card title="标的清单">
          <Tag color="green" slot="extra">的</Tag>
          <Row class="data-collect hidden-nowrap">总数：{{ count.data_total }}</Row>
          <Divider size="small" dashed></Divider>
          <Row class="data-collect_not">未审核清单：{{ count.data_not }}</Row>
        </Card>
      </i-col>
      <i-col span="6">
        <Card title="税率测算">
          <Tag color="red" slot="extra">算</Tag>
          <Row class="data-collect hidden-nowrap">待测：{{ count.count_num }}</Row>
          <Divider size="small" dashed></Divider>
          <Progress status="success" :percent="count.count_percent" hide-info></Progress>
        </Card>
      </i-col>
      <i-col span="6">
        <Card title="测算反馈">
          <Tag color="geekblue" slot="extra">馈</Tag>
          <Row class="data-collect hidden-nowrap">反馈：{{ count.back_num }}</Row>
          <Divider size="small" dashed></Divider>
          <Progress status="active" :percent="count.back_percent" hide-info></Progress>
        </Card>
      </i-col>
      <i-col span="6">
        <Card title="协作成果">
          <Tag color="blue" slot="extra">果</Tag>
          <Row class="data-collect hidden-nowrap">完成：{{ count.result_num }}</Row>
          <Divider size="small" dashed></Divider>
          <Progress status="wrong" :percent="count.result_percent" hide-info></Progress>
        </Card>
      </i-col>
    </Row>
    <Row class="margin-top16" :gutter="16">
      <i-col :span="this.countId?'16':'24'" style="transition: all .2s ease-in-out;">
        <Card>
          <Tabs value="table">
            <TabPane label="测算标的" name="table">
              <Table
                :columns="cols"
                :data="datas"
                :loading="tableLoading"
                ref="selection"
                size="small"
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
        </Card>
      </i-col>
      <i-col :span="this.countId?'8':'0'" style="transition: all .2s ease-in-out;">
        <Card>
          <Tabs value="table">
            <TabPane label="测算清单" name="table">
              <Table
                :columns="count_cols"
                :data="counts"
                :loading="countLoading"
                ref="count_sec"
                size="small"
                border stripe>
              </Table>
            </TabPane>
            <!--表头附加相关操作：-->
            <template slot="extra">
              <Row class="hidden-nowrap">
                <Button type="error" size="small" @click="countDoneClick">完成</Button>
                <Button class="margin-left8" type="primary" size="small" @click="countAddClick">添加
                </Button>
              </Row>
            </template>
          </Tabs>
        </Card>
      </i-col>
    </Row>
  </dev-article>
</template>

<script>
    import xcon from '../libs/xcon'

    export default {
        name: "Count",
        data() {
            return {
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

                // form
                formModel: false,

                countLoading: false,
                count_cols: [
                    {
                        width: 50,
                        type: 'index',
                        align: 'center',
                    },
                    {
                        title: '税种',
                        key: 'id',
                    },
                    {
                        title: '税率',
                        key: 'name',
                    },
                    {
                        title: '税额',
                        key: 'owner',
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
                                        type: 'error',
                                        size: 'small'
                                    },
                                    style: {
                                        marginRight: '5px'
                                    },
                                    on: {
                                        click: () => {
                                            this.dataCount(params.index)
                                        }
                                    }
                                }, '删除')
                            ]);
                        }
                    }
                ],
                counts: [],
                countId: null,
            }
        },
        methods: {
            countDateClick() {
                let begin = xcon.dateFormat(this.countDate[0], 'yyyy-MM-dd');
                let end = xcon.dateFormat(this.countDate[1], 'yyyy-MM-dd');

                this.tableLoading = true;
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

            // 开启测算
            dataCount(index) {
                this.countId = this.ajaxs[index].id;
                this.countUid = this.ajaxs[index].uid;
            },

            // 添加清单
            countAddClick() {
                this.$Message.info('未开启测试');
            },

            // 测算结束
            countDoneClick() {
                let uid = this.countUid;
                this.countId = null;
                this.countUid = null;
                // 提交标的，测算结束
                this.countLoading = true;
                this.$.posts('/count/upto', {uid})
                    .then(res => {
                        this.ajaxs = res;
                        this.countLoading = false;
                    })
                    .catch(error => {
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

                    data_not = Number(data_not);
                    data_total = Number(data_total);

                    count_all = Number(count_all);
                    count_num = Number(count_num);
                    let count_percent = count_all ? (count_all - count_num) / count_all * 100 : 0;

                    back_all = Number(back_all);
                    back_num = Number(back_num);
                    let back_percent = back_all ? (back_all - back_num) / back_all * 100 : 0;

                    result_num = Number(result_num);
                    let result_percent = data_total ? result_num / data_total * 100 : 0;

                    return {
                        data_total,
                        data_not,
                        count_num,
                        count_all,
                        back_num,
                        back_all,
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
        },
        created() {
            this.$.gets('/count/index')
                .then(res => {
                    this.ajaxs = res.datas;
                    this.ajax_count = res.count;
                    this.tableLoading = false
                })
                .catch(error => {
                    this.tableLoading = false;
                    this.$Message.error(error);
                })
        }
    }
</script>

<style scoped>

</style>