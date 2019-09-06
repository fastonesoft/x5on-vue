<template>
  <dev-article>
    <Row :gutter="16">
      <i-col span="8">
        <Card title="标的清单">
          <!--数据采集完毕的数据 confirmed-->
          <Tag color="green" slot="extra">的</Tag>
          <Row class="data-collect hidden-nowrap">{{ count.collect.num }}%</Row>
          <Divider size="small" dashed></Divider>
          <Row class="data-collect_not">剩余总数 {{ count.collect.not }}</Row>
        </Card>
      </i-col>
      <i-col span="8">
        <Card title="税率测算">
          <Tooltip content="完成税率测算的数据占比" slot="extra" placement="top" transfer>
            <Icon type="ios-alert-outline" size="18"/>
          </Tooltip>
          <Row class="data-collect hidden-nowrap">{{ count.count.num }}%</Row>
          <Divider size="small" dashed></Divider>
          <Progress status="success" :percent="count.count.num" hide-info></Progress>
        </Card>
      </i-col>
      <i-col span="8">
        <Card title="协作成果">
          <Tag color="blue" slot="extra">果</Tag>
          <Row class="data-collect hidden-nowrap">{{ count.result.num }}%</Row>
          <Divider size="small" dashed></Divider>
          <Progress status="wrong" :percent="count.result.num" hide-info></Progress>
        </Card>
      </i-col>
    </Row>
    <Row class="margin-top16">
      <Card>
        <Tabs value="table">
          <TabPane label="测算列表" name="table">
            <Table
              :columns="cols"
              :data="datas"
              :loading="tableLoading"
              ref="selection"
              size="small"
              border stripe>
            </Table>
            <Row class="margin-top16">
              <i-col class="hidden-nowrap align-right">
                <Page :total="datas.length" show-sizer transfer/>
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
              <Button class="margin-left8" type="primary" @click="countDateClick">查询</Button>
            </Row>
          </template>
        </Tabs>
      </Card>
    </Row>
  </dev-article>
</template>

<script>
    export default {
        name: "Count",
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

                tableLoading: true,  // 表格数据加载中

                cols: [
                    {
                        width: 50,
                        type: 'index',
                        align: 'center',
                    },
                    {
                        title: '名称',
                        key: 'name',
                    },
                    {
                        title: '测试',
                        key: 'value'
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
                                            this.dataCount(params.index)
                                        }
                                    }
                                }, '测算')
                            ]);
                        }
                    }
                ],
                datas: [
                    {
                        name: '第一行',
                        value: '数据是什么东西'
                    },
                    {
                        name: '第二行',
                        value: '数据是什么东西'
                    },
                    {
                        name: '第三行',
                        value: '数据是什么东西'
                    },
                    {
                        name: '第二行',
                        value: '数据是什么东西'
                    },
                ],
            }
        },
        methods: {
            countDateClick() {
                this.$Message.success('test！');
                window.console.log(this.countDate)
            },
            dateChange() {
                this.dateType = ''
                // 自定义日期列表，清除radio选项
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
                this.countDate = [new Date(date), new Date(today)]
            },
            dataCount(index) {
                this.$Modal.info({
                    title: '测算信息',
                    content: `标的名称：${this.datas[index].name}<br>标的测试：${this.datas[index].value}`
                })
            }
        },
        created() {
            setTimeout(() => {
                this.tableLoading = false
            }, 1000)
        }
    }
</script>

<style scoped>

</style>