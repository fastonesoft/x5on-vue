<template>
  <dev-article>
    <GeminiScrollbar class="xcon-split" id="xconSplit">
      <Split v-model="split1" class="split" min="300" max="300" @on-move-end="splitMoved">
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
            <TabPane label="税费清单" name="table">
              <div id="print">
                <h2 style="text-align: center;">涉税集体审议材料</h2>
                <br />
                <Row class="h3">
                  <iCol span="12">案件名称：{{ '什么有限公司' }}</iCol>
                  <iCol span="12" class="align-right">编号：{{ '20190001' }}</iCol>
                </Row>
                <br />
                <p style="text-indent:28px;">
                  产权人
                  <span class="under-line">{{ '王XXX' }}</span>，
                  <span class="under-line">{{ '拍卖' }}</span>，
                  <span class="under-line">{{ '三水街道' }}</span>的一处
                  <span class="under-line">{{ '国有' }}</span>类房产，该房产建筑面积
                  <span class="under-line">{{ '112' }}</span>M
                  <sup>2</sup>，土地面积
                  <span class="under-line">{{ '23' }}</span>M
                  <sup>2</sup>，使用年限
                  <span class="under-line">{{ '20' }}</span>年，初始价格
                  <span class="under-line">{{ '1120000' }}</span>元，评价价格
                  <span class="under-line">{{ '1000000' }}</span>元，起拍价格
                  <span class="under-line">{{ '1100000' }}</span>元。
                </p>
                <br />
                <h4>经初步测算，须缴纳税款如下：</h4>
                  <Table
                    :columns="count_cols"
                    :data="counts"
                    :loading="countLoading"
                    ref="count_sec"
                    size="small"
                    border
                    stripe
                    show-summary
                  ></Table>
                  <br>
                  <h3>
                    审议组成员：王东、李晓
                  </h3>
                  <br>
                  <h3>
                    审议组成员意见（签名）
                  </h3>
                <Row class="h4 bottom">
                  <iCol span="8">税费测算：<span class="under-line">{{ '什么有限公司' }}</span></iCol>
                  <iCol span="8" class="align-center">测算复核：<span class="under-line">{{ '20190001' }}</span></iCol>
                  <iCol span="8" class="align-right">审议类型：<span class="under-line">{{ '集体审议' }}</span></iCol>
                </Row>
              </div>
            </TabPane>
            <!--表头附加相关操作：-->
            <template slot="extra">
              <Row class="hidden-nowrap">
                <Button type="primary" size="small" @click="toPrint">打印</Button>
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
import { on, off } from "view-design/src/utils/dom";

export default {
  name: "C2Exam",
  data() {
    return {
      // split
      split1: 0.45,

      // date
      dateType: "day",
      countDate: [new Date(), new Date()],

      // table
      cols: [
        {
          width: 55,
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
          title: "税种",
          key: "tax_name"
        },
        {
          title: "计税依据",
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
      counts: []
    };
  },
  beforeDestroy() {
    off(window, "resize", this.splitMoved);
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

    // 打印送审
    toPrint() {
      print.printImage("print", "案件送审记录");
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
      // 用比例的方式获得打印窗口的长度
      // 不用直接拉，直拉的数值可能有偏差，显示的是你窗体的大小
      let xconSplit = document.getElementById("xconSplit");
      let height = ((xconSplit.clientWidth * (1 - this.split1)) / 210) * 297;
      let print_form = document.getElementById("print");
      print_form.style.height = `${height}px`;
    }
  },
  computed: {
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
    on(window, "resize", this.splitMoved);

    // 初始化打印容器宽度
    let xconSplit = document.getElementById("xconSplit");
    const width = xcon.page.width96;
    this.split1 = 1 - width / xconSplit.clientWidth;

    // 打印内容高度初始化
    this.splitMoved();
  }
};
</script>

<style scoped>
#print {
  background: #fff;
  position: relative;
}
</style>