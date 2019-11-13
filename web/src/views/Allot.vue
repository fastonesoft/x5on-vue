<template>
    <dev-article>
        <GeminiScrollbar class="xcon-split">
            <Card title="任务流程" style="height:160px;">
                <Tag color="geekblue" slot="extra">程</Tag>
                <Steps :current="0">
                    <Step title="任务分配" content="案件测算工作分配"></Step>
                    <Step title="税费测算" content="接受任务进行测算"></Step>
                    <Step title="测算复核" content="测算结果二次复核"></Step>
                    <Step title="案件审批" content="案件集体审议记录"></Step>
                    <Step title="文书制作" content="出具涉税处理意见"></Step>
                </Steps>
            </Card>
            <Split v-model="split1" class="split margin-top8" min="600" max="300" @on-move-end="splitMoved">
                <div slot="left" class="slot-left">
                    <Tabs value="table">
                        <TabPane label="任务标的" name="table">
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
                                    <Page
                                            :total="ajaxs.length"
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
                                        class="data-picker"
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
                        <TabPane label="任务分配" name="table">
                            <CellGroup style="height:500px;">
                                <Cell title="编号：" extra="afdaf"/>
                                <Cell title="标的名称：" extra="adfasdf"/>

<div id="demo1" class="xcon-select"><div>任务分配</div></div>
<div id="demo2" class="xcon-select"><div>税费测算</div></div>
<div id="demo3" class="xcon-select"><div>测算复核</div></div>
<div id="demo4" class="xcon-select"><div>案件审批</div></div>


                                <Row class="margin-top16 align-right">
                                    <Button type="primary" class="margin-left16" @click="backedExam">复核通过</Button>
                                </Row>
                            </CellGroup>
                        </TabPane>
                        <!--表头附加相关操作：-->
                        <template slot="extra">
                            <Row class="hidden-nowrap">
                                <Button type="error" size="small" @click="countBack">退回修改</Button>
                            </Row>
                        </template>
                    </Tabs>
                </div>
            </Split>
        </GeminiScrollbar>
    </dev-article>
</template>

<script>
    import xcon from '../libs/xcon'
    import print from '../libs/print';

    export default {
        name: "Allot",
        data() {
            return {
                // split
                split1: 0.6,

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
                        title: '依据',
                        key: 'tax_base',
                        align: 'right',
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

                // select.data
                selectData: [
                    {name: '张三', value: 1, selected: true, disabled: true},
                    {name: '李四', value: 2, selected: true},
                    {name: '王五', value: 3},
                ]
            }
        },
        methods: {
            countDateClick() {
                this.current = null;
                this.tableLoading = true;

                let begin = xcon.dateFormat(this.countDate[0], 'yyyy-MM-dd');
                let end = xcon.dateFormat(this.countDate[1], 'yyyy-MM-dd');
                this.$.posts('/counted/find', {begin, end})
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

            // page
            pageChange(index) {
                this.pageIndex = index;
            },
            sizeChange(size) {
                this.pageSize = size;
            },

            // 表格选择
            selectChange(row) {
                this.current = row;
                this.countLoading = true;
                // 查询标的对应测算税种列表
                this.$.posts('/counted/tax', {data_id: row.id})
                    .then(res => {
                        this.counts = res;
                        this.countLoading = false
                    })
                    .catch(error => {
                        this.countLoading = false;
                        this.$Message.error(error);
                    });
            },

            // 退回测算标的
            countBack() {
                let row = this.current;
                if (row === null) {
                    this.$Message.error('没有选择测算标的！');
                    return
                }
                this.$.posts('/counted/back', {uid: row.uid})
                    .then(res => {
                        // 删除
                        xcon.arrsDel(this.ajaxs, 'uid', row.uid);

                        this.current = null;
                        this.$Message.success(res + '条“标的”测算已退回！');
                    })
                    .catch(error => {
                        this.$Message.error(error);
                    });
            },
            // 测算结束，提交复核
            countExam() {
                let row = this.current;
                if (row === null) {
                    this.$Message.error('没有选择测算标的！');
                    return
                }
                this.countLoading = true;

                this.$.posts('/counted/exam', {uid: row.uid})
                    .then(res => {
                        this.current = null;
                        this.countLoading = false;

                        xcon.arrsDel(this.ajaxs, 'uid', row.uid);
                        this.$Message.success(res + '条测算标的复核通过！');
                    })
                    .catch(error => {
                        this.countLoading = false;
                        this.$Message.error(error);
                    })
            },

            // 分隔拖动
            splitMoved() {
                 window.console.log(this.split1)
            }
        },
        computed: {
            datas() {
                return xcon.pageData(this.ajaxs, this.pageIndex, this.pageSize)
            },
            amounts() {
                let total = 0.0;
                this.counts.forEach(item => {
                    total += parseFloat(item.tax_amount)
                });
                return total.toFixed(2)
            },
        },
        created() {
            this.$.gets('/counted/index')
                .then(res => {
                    this.ajaxs = res;
                    this.tableLoading = false
                })
                .catch(error => {
                    this.tableLoading = false;
                    this.$Message.error(error);
                });
        },
        mounted() {
            var demo1 = xmSelect.render({
                el: '#demo1',
                theme: {
                    color: '#2d8cf0',
                },
                data: this.selectData,
            })
            var demo2 = xmSelect.render({
                el: '#demo2',
                theme: {
                    color: '#2d8cf0',
                },
                data: this.selectData,
            })
            var demo3 = xmSelect.render({
                el: '#demo3',
                theme: {
                    color: '#2d8cf0',
                },
                data: this.selectData,
            })
            var demo4 = xmSelect.render({
                el: '#demo4',
                theme: {
                    color: '#2d8cf0',
                },
                data: this.selectData,
            })
        },
    }
</script>

<style scoped>
    .xcon-select {
        width: 200px;
    }
</style>