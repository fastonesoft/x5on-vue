<template>
  <dev-article>
    <Row :gutter="16">
      <i-col span="8">
        <Card title="采集总数">
          <!--数据采集完毕的数据 confirmed-->
          <Tag color="green" slot="extra">数</Tag>
          <Row class="data-collect hidden-nowrap">{{ count.collect.num }}%</Row>
          <Divider size="small" dashed></Divider>
          <Row class="data-collect_not">剩余总数 {{ count.collect.not }}</Row>
        </Card>
      </i-col>
      <i-col span="8">
        <Card title="税率测算">
          <Tooltip content="税率测算说明" slot="extra" placement="top" transfer>
            <Icon type="ios-alert-outline" size="18"/>
          </Tooltip>
          <Row class="data-collect hidden-nowrap">{{ count.count.num }}%</Row>
          <Divider size="small" dashed></Divider>
          <Progress status="success" :percent="count.count.num" hide-info></Progress>
        </Card>
      </i-col>
      <i-col span="8">
        <Card title="协作成果">
          <Tooltip content="协作成果说明" slot="extra" placement="top" transfer>
            <Icon type="ios-alert-outline" size="18"/>
          </Tooltip>
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
              <Select v-model="area_id" placeholder="地区选择..." style="width:160px" transfer>
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
                area_id: '999999',
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
        methods: {},
        created() {
            setTimeout(() => {
                this.tableLoading = false
            }, 1000)
        }
    }
</script>

<style scoped>

</style>