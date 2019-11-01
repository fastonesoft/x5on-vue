<template>
    <dev-article>
        <div id="Split">
            <Split v-model="split1" class="split" min="600" max="150">
                <div slot="left" class="slot-left">
                    <Tabs value="table">
                        <TabPane label="标的清单" name="table">
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
                        <TabPane label="协作进展" name="table">
                            <div v-if="exam_user.length>0">
                                <Steps :current="exam_user.length" direction="vertical">
                                    <Step :title="item.exam_name" :content="'协作执行：' + item.user_name + '，' + item.exam_time" v-for="item of exam_user" :key="item.exam_id"></Step>
                                </Steps>
                                <Divider dashed />
                                <div class="align-right">
                                    <Button type="error" size="small" @click="countBackClick">撤销最近</Button>
                                </div>
                            </div>
                        </TabPane>
                    </Tabs>
                </div>
            </Split>
        </div>
    </dev-article>
</template>

<script>
    import xcon from '../libs/xcon'

    export default {
        name: "Stepm",
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
                ],
                ajaxs: [],
                pageIndex: 1,
                pageSize: 10,
                tableLoading: true,
                current: null,
                exam_user: [],
                minWidth: 100,
            }
        },
        methods: {
            countDateClick() {
                this.current = null;
                this.exam_user = [];
                this.tableLoading = true;

                let begin = xcon.dateFormat(this.countDate[0], 'yyyy-MM-dd');
                let end = xcon.dateFormat(this.countDate[1], 'yyyy-MM-dd');
                this.$.posts('/stepm/find', {begin, end})
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
                // 获取审核用户
                this.$Loading.start();
                this.$.posts('/stepm/user', {data_uid: row.uid})
                    .then(res => {
                        this.exam_user = res;
                        this.$Loading.finish();
                    })
                    .catch(error => {
                        this.$Message.error(error);
                        this.$Loading.error();
                    })
            },

            pageChange(index) {
                this.pageIndex = index;
            },
            sizeChange(size) {
                this.pageSize = size;
            },

            countBackClick() {
                this.$Modal.confirm({
                    title: '确认提示',
                    content: '是否确认撤消当前“标的”最近一次协作执行记录？',
                    onOk: () => {
                        let {uid} = this.current;
                        let value = this.exam_user.length;

                        this.$Loading.start();
                        this.$.posts('/stepm/back', {data_uid: uid, value})
                            .then(res => {
                                this.exam_user = res;
                                this.$Loading.finish();
                            })
                            .catch(error => {
                                this.$Message.error(error);
                                this.$Loading.error();
                            })
                    },
                })
            },
        },
        computed: {
            datas() {
                return xcon.pageData(this.ajaxs, this.pageIndex, this.pageSize)
            },
            step_value() {
                let value = 0;
                let row = this.current;
                if (row) {
                    value += parseInt(row.data);
                    value += parseInt(row.dataed);
                    value += parseInt(row.count);
                    value += parseInt(row.counted);
                    value += parseInt(row.back);
                    value += parseInt(row.backed);
                }
                return value;
            },
        },
        created() {
            this.$.gets('/stepm/index')
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
            const that = this;
            window.onresize = function () {
                if (that.$route.path !== '/vstepm') return;

                // 分割条高度计算
                let height = document.body.clientHeight - 64 - 32;
                document.getElementById('Split').style.height = height + 'px';                
            };
            window.onresize();
        },
    }
</script>

<style scoped>

</style>