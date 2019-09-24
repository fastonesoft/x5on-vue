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
                    <!--<Tooltip content="完成税率测算的数据占比" slot="extra" placement="top" transfer>-->
                    <!--<Icon type="ios-alert-outline" size="18"/>-->
                    <!--</Tooltip>-->
                    <Tag color="red" slot="extra">算</Tag>
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
                tableLoading: true,
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
                    {
                        title: '操作',
                        key: 'action',
                        width: 80,
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
                datas: [],

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
                let begin = this.countDate[0] instanceof Date ? this.countDate[0].format('yyyy-MM-dd') : this.countDate[0];
                let end = this.countDate[1] instanceof Date ? this.countDate[1].format('yyyy-MM-dd') : this.countDate[1];

                this.tableLoading = true;
                this.$.posts('/count/find', {begin, end})
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
                this.countDate = [new Date(date), new Date(today)]
            },

            // 开启测算
            dataCount(index) {
                this.countId = this.datas[index].id;
                this.countUid = this.datas[index].uid;
                // 读取测算数据

                // this.$Modal.info({
                //     title: '测算信息',
                //     content: `标的名称：${this.datas[index].name}<br>标的测试：${this.datas[index].value}`
                // })
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
                        this.datas = res;
                        this.countLoading = false;
                    })
                    .catch(error => {
                        this.$Message.error(error);
                    })
            },
        },
        created() {
            this.tableLoading = true;
            this.$.gets('/count/index')
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