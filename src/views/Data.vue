<template>
    <dev-article>
        <template>
            <Row :gutter="16">
                <i-col span="6">
                    <Card title="采集总数">
                        <!--数据采集完毕的数据 confirmed-->
                        <Tag color="green" slot="extra">数</Tag>
                        <div class="data-collect">{{ collect.num }}</div>
                        <Divider size="small" dashed></Divider>
                        <div class="data-collect_not">剩余总数 {{ collect.not }}</div>
                    </Card>
                </i-col>
                <i-col span="6">
                    <Card title="税率测算">
                        <Tooltip content="税率测算说明" slot="extra" placement="top" transfer>
                            <Icon type="ios-alert-outline" size="18" />
                        </Tooltip>
                        <div class="data-collect">{{ count.num }}%</div>
                        <Divider size="small" dashed></Divider>
                        <Progress status="success" :percent="count.num" hide-info></Progress>
                    </Card>
                </i-col>
                <i-col span="6">
                    <Card title="协作成果">
                        <Tooltip content="协作成果说明" slot="extra" placement="top" transfer>
                            <Icon type="ios-alert-outline" size="18" />
                        </Tooltip>
                        <div class="data-collect">{{ count.num }}%</div>
                        <Divider size="small" dashed></Divider>
                        <Progress status="wrong" :percent="count.num" hide-info></Progress>
                    </Card>
                </i-col>
                <i-col span="6">
                    <Card title="快捷操作">

                    </Card>
                </i-col>
            </Row>
            <Row style="margin-top: 16px">
                <Card>
                    <Table :columns="cols" :data="datas"></Table>
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
                    num: 300,
                    not: 20
                },
                count: {
                    num: 60,
                    not: 10
                },
                cols: [
                    {
                        title: '名称',
                        key: 'name'
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
                    }
                ]
            }
        }
    }
</script>

<style scoped>
    .data-collect{
        font-size: 24px;
        font-weight: bold;
    }
    .data-collect_not{
        /*font-weight: bold;*/
        /*text-align: right;*/
        overflow: hidden;
        white-space: nowrap;
    }
    .ivu-divider-horizontal{
        margin: 16px 0;
    }
</style>