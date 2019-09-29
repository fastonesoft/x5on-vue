<template>
  <dev-article>
    <Card>
      <Tabs value="table">
        <TabPane label="统计清单" name="table">
          <Table
            :columns="cols"
            :data="datas"
            :loading="tableLoading"
            ref="selection"
            size="small"
            @on-current-change="selectChange"
            border stripe>
          </Table>
          <Row class="margin-top16 hidden-nowrap">
            <i-col span="6">
              <Tag color="success">税费合计：{{amounts}}</Tag>
            </i-col>
            <i-col span="18" class="align-right">
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

  </dev-article>
</template>

<script>
    import xcon from '../libs/xcon'

    export default {
        name: "Result",
        data() {
            return {
                // split
                split1: 0.8,

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
                    {
                        title: '成交价格',
                        key: 'price_end',
                    },
                    {
                        title: '应征税费',
                        key: 'price_tax',
                    },
                    {
                        title: '最终价格',
                        key: 'price',
                    },
                ],
                ajaxs: [],
                pageIndex: 1,
                pageSize: 10,
                tableLoading: true,
                current: null,

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
                this.$.posts('/result/find', {begin, end})
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
        },
        computed: {
            datas() {
                return xcon.pageData(this.ajaxs, this.pageIndex, this.pageSize)
            },
            amounts() {
                let total = 0.0;
                this.ajaxs.forEach(item => {
                    total += parseFloat(item.price_tax)
                });
                return total
            },
        },
        created() {
            this.$.gets('/result/index')
                .then(res => {
                    this.ajaxs = res;
                    this.tableLoading = false
                })
                .catch(error => {
                    this.tableLoading = false;
                    this.$Message.error(error);
                });
        },
    }
</script>

<style scoped>

</style>