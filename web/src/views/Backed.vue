<template>
  <dev-article>
    <GeminiScrollbar class="xcon-split">
      <Split v-model="split1" class="split" min="600" max="150">
        <div slot="left" class="slot-left">
          <Tabs value="table">
            <TabPane label="标的反馈" name="table">
              <Table
                :columns="cols"
                :data="datas"
                :loading="tableLoading"
                ref="table"
                size="small"
                @on-current-change="selectChange"
                highlight-row
                border stripe>
              </Table>
              <Row class="margin-top16 hidden-nowrap align-right">
                <Page
                  :total="ajaxs.length"
                  :page-size="pageSize"
                  :page-size-opts="[10, 20, 50, 100]"
                  show-sizer
                  transfer
                  @on-change="pageChange"
                  @on-page-size-change="sizeChange"
                />
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
          <Tabs value="menus">
            <TabPane label="反馈详情" name="menus">
              <CellGroup v-if="current">
                <Cell title="编号：" :extra="current.id"/>
                <Cell title="标的名称：" :extra="current.name"/>
                <Cell title="产权性质：" :extra="current.area_type"/>
                <Cell title="所属地区：" :extra="current.area_name"/>
                <Cell title="建筑面积：" :extra="current.area_build"/>
                <Cell title="土地面积：" :extra="current.area_soil"/>
                <Cell title="使用年限：" :extra="current.use_year"/>
                <Cell title="初始价格：" :extra="current.price_begin"/>
                <Cell title="评价价格：" :extra="current.price_ass"/>
                <Cell title="起拍价格：" :extra="current.price_shoot"/>
                <Cell title="反馈复核" disabled/>
                <Cell title="成交价格：" :extra="current.price_end"/>
                <Cell title="应征税费：" :extra="current.price_tax"/>
                <Cell title="最终价格：" :extra="current.price"/>
                <Row class="margin-top16 align-right">
                  <Button type="primary" class="margin-left16" @click="backedExam">复核通过</Button>
                </Row>
              </CellGroup>
            </TabPane>
            <!--表头附加相关操作：-->
            <template slot="extra">
              <Row class="hidden-nowrap">
                <Button type="error" size="small" @click="backedBack" v-if="current">退回修改</Button>
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

    export default {
        name: "Backed",
        data() {
            return {

                split1: 0.7,

                pageIndex: 1,
                pageSize: 10,
                dateType: 'day',
                countDate: [new Date(), new Date()],

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
                        title: '涉税类型',
                        key: 'sell_type',
                    },
                    {
                        title: '产权人',
                        key: 'owner',
                    },

                ],
                ajaxs: [],
                current: null,

                tableLoading: true,
            }
        },
        methods: {
            countDateClick() {
                this.current = null;
                this.tableLoading = true;

                let begin = xcon.dateFormat(this.countDate[0], 'yyyy-MM-dd');
                let end = xcon.dateFormat(this.countDate[1], 'yyyy-MM-dd');
                this.$.posts('/backed/find', {begin, end})
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

            pageChange(index) {
                this.pageIndex = index;
            },
            sizeChange(size) {
                this.pageSize = size;
            },

            selectChange(row) {
                this.current = row;
            },
            backedBack() {
                let row = this.current;
                if (row === null) {
                    this.$Message.error('没有选择反馈记录！');
                    return
                }
                this.$.posts('/backed/back', {uid: row.uid})
                    .then(res => {
                        // 删除
                        xcon.arrsDel(this.ajaxs, 'uid', row.uid);

                        this.current = null;
                        this.$Message.success(res + '条“反馈”标的已退回！');
                    })
                    .catch(error => {
                        this.$Message.error(error);
                    });
            },
            backedExam() {
                let row = this.current;
                if (row === null) {
                    this.$Message.error('没有选择标的记录！');
                    return
                }
                this.$.posts('/backed/exam', {uid: row.uid})
                    .then(res => {
                        // 复核
                        xcon.arrsDel(this.ajaxs, 'uid', row.uid);

                        this.current = null;
                        this.$Message.success(res + '条“反馈”标的已复核！');
                    })
                    .catch(error => {
                        this.$Message.error(error);
                    });
            }
        },
        computed: {
            datas() {
                return xcon.pageData(this.ajaxs, this.pageIndex, this.pageSize)
            },
        },
        created() {
            this.$.gets('/backed/index')
                .then(res => {
                    this.ajaxs = res;
                    this.tableLoading = false
                })
                .catch(error => {
                    this.$Message.error(error);
                });
        },
        mounted() {

        },
    }
</script>

<style scoped>

</style>