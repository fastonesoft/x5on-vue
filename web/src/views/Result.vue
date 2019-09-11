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
        <Row class="margin-top16">
            <Card>
                <Tabs value="table">
                    <TabPane label="完成列表" name="table">
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
                            <Select v-model="area_id" placeholder="地区选择..." style="width:160px" @on-change="areaChange"
                                    transfer>
                                <Option
                                        v-for="item in areas"
                                        :value="item.area_id"
                                        :key="item.area_id">{{ item.area_name }}
                                </Option>
                            </Select>
                        </Row>
                    </template>
                </Tabs>
            </Card>
        </Row>
    </dev-article>
</template>

<script>
    export default {
        name: "Result",
        data() {
            return {
                count: {
                    collect: {num: 30, not: 20},
                    count: {num: 60, not: 10},
                    result: {num: 90, not: 20}
                },
                formAdd: false,

                tableLoading: true,  // 表格数据加载中

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
                ],
                datas: [],
                area_id: '',
                areas: [
                    {
                        area_id: '321204',
                        area_name: '姜堰区'
                    },
                    {
                        area_id: '999999',
                        area_name: '其它地区'
                    },
                ],
            }
        },
        methods: {
            areaChange(area_id) {
                this.tableLoading = true;
                this.$.posts('/result/find', {area_id})
                    .then(res => {
                        this.datas = res;
                        this.tableLoading = false
                    })
                    .catch(error => {
                        this.$Message.error(error);
                    })
            }
        },
        created() {
            this.tableLoading = true;
            this.$.gets('/result/index')
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