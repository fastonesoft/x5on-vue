<template>
    <dev-article>
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
                                    <Button class="margin-left8" type="primary" size="small" @click="countExam">通过复核</Button>
                                </Row>
                            </div>
                        </TabPane>
                        <!--表头附加相关操作：-->
                        <template slot="extra">
                            <Row class="hidden-nowrap">
                                <Button type="error" size="small" @click="countBack" v-if="current">退回修改</Button>
                            </Row>
                        </template>
                    </Tabs>
                </div>
            </Split>
        </div>
    </dev-article>
</template>

<script>
    import xcon from '../libs/xcon'

    export default {
        name: "Counted",
        data() {
            return {
                // split
                split1: 0.7,

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

        },
    }
</script>

<style scoped>

</style>