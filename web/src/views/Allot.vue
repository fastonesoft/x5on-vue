<template>
  <dev-article>
    <GeminiScrollbar class="xcon-split">
      <Row :gutter="6">
        <i-col span="6">
          <Card title="标的清单">
            <Tag color="green" slot="extra">的</Tag>
            <Row class="data-collect hidden-nowrap">总计：{{ dataCount.total }}</Row>
            <Divider size="small" dashed></Divider>
            <Progress :percent="dataCount.data_per" stroke-color="#19be6b" hide-info></Progress>
          </Card>
        </i-col>
        <i-col span="6">
          <Card title="税率测算">
            <Tag color="red" slot="extra">算</Tag>
            <Row class="data-collect hidden-nowrap">测算：{{ dataCount.count }}</Row>
            <Divider size="small" dashed></Divider>
            <Progress :percent="dataCount.count_per" stroke-color="#2db7f5" hide-info></Progress>
          </Card>
        </i-col>
        <i-col span="6">
          <Card title="测算反馈">
            <Tag color="geekblue" slot="extra">馈</Tag>
            <Row class="data-collect hidden-nowrap">反馈：{{ dataCount.back }}</Row>
            <Divider size="small" dashed></Divider>
            <Progress :percent="dataCount.back_per" stroke-color="#ff9900" hide-info></Progress>
          </Card>
        </i-col>
        <i-col span="6">
          <Card title="协作成果">
            <Tag color="blue" slot="extra">果</Tag>
            <Row class="data-collect hidden-nowrap">完成：{{ dataCount.backed }}</Row>
            <Divider size="small" dashed></Divider>
            <Progress :percent="dataCount.result_per" stroke-color="#ed4014" hide-info></Progress>
          </Card>
        </i-col>
      </Row>
      <Split
        v-model="split1"
        class="split margin-top8"
        min="600"
        max="300"
        @on-move-end="splitMoved"
      >
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
                highlight-row
                border
                stripe
              ></Table>
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
                  transfer
                ></DatePicker>
                <Button class="margin-left8" type="primary" size="small" @click="countDateClick">查询</Button>
              </Row>
            </template>
          </Tabs>
        </div>
        <div slot="right" class="slot-right">
          <Tabs value="table">
            <TabPane label="任务分配" name="table">
              <CellGroup style="height:500px;">
                <div>
                  <div id="demo1">税费测算：</div>
                </div>
                <div>
                  <div id="demo2">测算复核：</div>
                </div>
                <div>
                  <div id="demo3">案件审批：</div>
                </div>
                <div>
                  <div id="demo4">文书制作：</div>
                </div>
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
import xcon from "../libs/xcon";
import print from "../libs/print";

export default {
  name: "Allot",
  data() {
    return {
      // split
      split1: 0.6,

      // count
      ajax_count: null,

      // date
      dateType: "day",
      countDate: [new Date(), new Date()],

      // table
      cols: [
        {
          width: 50,
          type: "index",
          align: "center"
        },
        {
          title: "编号",
          key: "id"
        },
        {
          title: "标的名称",
          key: "name"
        },
        {
          title: "产权人",
          key: "owner"
        },
        {
          title: "产权性质",
          key: "area_type"
        },
        {
          title: "所属地区",
          key: "area_name"
        }
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
          type: "index",
          align: "center"
        },
        {
          title: "税种",
          key: "tax_name"
        },
        {
          title: "依据",
          key: "tax_base",
          align: "right"
        },
        {
          title: "税率",
          key: "tax_percent",
          align: "right"
        },
        {
          title: "税额",
          key: "tax_amount",
          align: "right"
        }
      ],
      counts: [],

      // select.data
      selectData: [
        { name: "张三", value: 1 },
        { name: "李四", value: 2 },
        { name: "王五", value: 3 }
      ]
    };
  },
  methods: {
    countDateClick() {
      this.current = null;
      this.tableLoading = true;

      let begin = xcon.dateFormat(this.countDate[0], "yyyy-MM-dd");
      let end = xcon.dateFormat(this.countDate[1], "yyyy-MM-dd");
      this.$.posts("/counted/find", { begin, end })
        .then(res => {
          this.ajaxs = res;
          this.tableLoading = false;
        })
        .catch(error => {
          this.tableLoading = false;
          this.$Message.error(error);
        });
    },
    dateChange(val) {
      this.dateType = "";
      this.countDate = val;
    },
    dateTypeChange(val) {
      const today = new Date().getTime();
      let date;
      switch (val) {
        case "day":
          date = today;
          break;
        case "week":
          date = today - 86400000 * 7;
          break;
        case "month":
          date = today - 86400000 * 30;
          break;
        case "year":
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
      this.$.posts("/counted/tax", { data_id: row.id })
        .then(res => {
          this.counts = res;
          this.countLoading = false;
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
        this.$Message.error("没有选择测算标的！");
        return;
      }
      this.$.posts("/counted/back", { uid: row.uid })
        .then(res => {
          // 删除
          xcon.arrsDel(this.ajaxs, "uid", row.uid);

          this.current = null;
          this.$Message.success(res + "条“标的”测算已退回！");
        })
        .catch(error => {
          this.$Message.error(error);
        });
    },
    // 测算结束，提交复核
    countExam() {
      let row = this.current;
      if (row === null) {
        this.$Message.error("没有选择测算标的！");
        return;
      }
      this.countLoading = true;

      this.$.posts("/counted/exam", { uid: row.uid })
        .then(res => {
          this.current = null;
          this.countLoading = false;

          xcon.arrsDel(this.ajaxs, "uid", row.uid);
          this.$Message.success(res + "条测算标的复核通过！");
        })
        .catch(error => {
          this.countLoading = false;
          this.$Message.error(error);
        });
    },

    // 分隔拖动
    splitMoved() {
      window.console.log(this.split1);
    }
  },
  computed: {
    dataCount() {
      if (this.ajax_count === null) {
        return {
          total: 0,
          count: 0,
          counted: 0,
          back: 0,
          backed: 0,
          data_per: 0,
          count_per: 0,
          back_per: 0,
          result_per: 0
        };
      } else {
        let { total, dataed, count, counted, back, backed } = this.ajax_count;
        total = Number(total);
        dataed = Number(dataed);
        count = Number(count);
        counted = Number(counted);
        back = Number(back);
        backed = Number(backed);
        let data_per = total > 0 ? (dataed / total) * 100 : 0;
        let count_per = dataed ? (counted / dataed) * 100 : 0;
        let back_per = counted ? (backed / counted) * 100 : 0;
        let result_per = total ? (backed / total) * 100 : 0;
        return {
          total,
          count,
          counted,
          back,
          backed,
          data_per,
          count_per,
          back_per,
          result_per
        };
      }
    },
    datas() {
      return xcon.pageData(this.ajaxs, this.pageIndex, this.pageSize);
    },
    amounts() {
      let total = 0.0;
      this.counts.forEach(item => {
        total += parseFloat(item.tax_amount);
      });
      return total.toFixed(2);
    }
  },
  created() {
    this.$.gets("/count/index")
      .then(res => {
        this.ajaxs = res.datas;
        this.taxs = res.taxs;
        this.ajax_count = res.count;
        this.tableLoading = false;
      })
      .catch(error => {
        this.tableLoading = false;
        this.$Message.error(error);
      });
    this.$.gets("/counted/index")
      .then(res => {
        this.ajaxs = res;
        this.tableLoading = false;
      })
      .catch(error => {
        this.tableLoading = false;
        this.$Message.error(error);
      });
  },
  mounted() {
    var demo1 = xmSelect.render({
      el: "#demo1",
      radio: true,
      clickClose: true,
      tips: "测算人员选择",
      theme: {
        color: "#2d8cf0"
      },
      data: this.selectData
    });
    var demo2 = xmSelect.render({
      el: "#demo2",
      radio: true,
      clickClose: true,
      tips: "复核人员选择",
      theme: {
        color: "#2d8cf0"
      },
      data: this.selectData
    });
    var demo3 = xmSelect.render({
      el: "#demo3",
      tips: "审批人员选择",
      theme: {
        color: "#2d8cf0"
      },
      data: this.selectData
    });
    var demo4 = xmSelect.render({
      el: "#demo4",
      radio: true,
      clickClose: true,
      tips: "文书人员选择",
      theme: {
        color: "#2d8cf0"
      },
      data: this.selectData
    });
  }
};
</script>

<style scoped>
</style>