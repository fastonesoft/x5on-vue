<template>
    <dev-article>
        <template>
            <Row :gutter="16">
                <i-col span="6">
                    <Card title="采集总数">
                        <!--数据采集完毕的数据 confirmed-->
                        <Tag color="green" slot="extra">数</Tag>
                        <Row class="data-collect">{{ collect.num }}%</Row>
                        <Divider size="small" dashed></Divider>
                        <Row class="data-collect_not">剩余总数 {{ collect.not }}</Row>
                    </Card>
                </i-col>
                <i-col span="6">
                    <Card title="税率测算">
                        <Tooltip content="税率测算说明" slot="extra" placement="top" transfer>
                            <Icon type="ios-alert-outline" size="18" />
                        </Tooltip>
                        <Row class="data-collect">{{ count.num }}%</Row>
                        <Divider size="small" dashed></Divider>
                        <Progress status="success" :percent="count.num" hide-info></Progress>
                    </Card>
                </i-col>
                <i-col span="6">
                    <Card title="协作成果">
                        <Tooltip content="协作成果说明" slot="extra" placement="top" transfer>
                            <Icon type="ios-alert-outline" size="18" />
                        </Tooltip>
                        <Row class="data-collect">{{ result.num }}%</Row>
                        <Divider size="small" dashed></Divider>
                        <Progress status="wrong" :percent="result.num" hide-info></Progress>
                    </Card>
                </i-col>
                <i-col span="6">
                    <Card title="快捷操作">
                        <Row class="data-collect align-center" style="margin-bottom: 22px;">添加采集数据</Row>
                        <Button type="primary" icon="ios-add" long>添加</Button>
                    </Card>
                </i-col>
            </Row>
            <Row class="margin-top16">
                <Card>
                    <Tabs value="table">
                        <TabPane label="数据列表" name="table">
                            <Table :columns="cols" :data="datas" ref="selection" :loading="tableLoading" border stripe></Table>
                            <Row class="margin-top16">
                                <i-col span="12" class="hidden-nowrap align-left">
                                    <Checkbox v-model="checkAll" @on-change="checkAllChange">全选</Checkbox>
                                    <Button type="primary" class="margin-left8">提交测算</Button>
                                    <Button type="error" class="margin-left16">删除记录</Button>
                                </i-col>
                                <i-col span="12" class="hidden-nowrap align-right">
                                    <Page :total="datas.length" show-sizer transfer />
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
                                <DatePicker v-model="countDate" type="daterange" style="width: 180px" transfer>
                                </DatePicker>
                                <Button type="primary" style="margin-left: 8px;">查询</Button>
                            </Row>
                        </template>
                    </Tabs>
                </Card>
            </Row>
        </template>
    </dev-article>
</template>

<script>
    export default {
        name: "Data",
        data () {
            return {
                collect: {
                    num: 30,
                    not: 20
                },
                count: {
                    num: 60,
                    not: 10
                },
                result: {
                    num: 90,
                    not: 20
                },
                dateType: 'day',
                countDate: [],     // 统计日期
                checkAll: false,   // 全选状态
                tableLoading: true,  // 表格数据加载中
                cols: [
                    {
                        key: 'index',
                        width: 60,
                        align: 'center',
                        type: 'selection'
                    },
                    {
                        title: '名称',
                        key: 'name',
                    },
                    {
                        title: '测试',
                        key: 'value'
                    }
                ],
                datas: [
                    {
                        name: 'adfasdf',
                        value: 'adfa12123123123'
                    },
                    {
                        name: '第二行',
                        value: '数据是什么东西'
                    },
                    {
                        name: '第二行',
                        value: '数据是什么东西'
                    },
                ]
            }
        },
        methods: {
            dateTypeChange (val) {
                const today = (new Date()).getTime();
                let date;
                switch (val) {
                    case 'day': date = today; break;
                    case 'week': date = today - 86400000 * 7; break;
                    case 'month': date = today - 86400000 * 30; break;
                    case 'year': date = today - 86400000 * 365; break;
                }
                this.countDate = [new Date(date), new Date(today)]
            },
            checkAllChange (val) {
                this.$refs.selection.selectAll(val);
            }
        },
        created () {
            setTimeout(()=>{
                this.tableLoading = false
            }, 3000)
        }
    }
</script>

<style scoped>
    .data-collect{
        font-size: 24px;
        font-weight: bold;
    }
    .data-collect_not{
        overflow: hidden;
        white-space: nowrap;
    }
    .ivu-divider-horizontal{
        margin: 16px 0;
    }
</style>