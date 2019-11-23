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
                <div v-if="current">
                  <h2 style="text-align: center; letter-spacing: 5px;">涉税处理意见书</h2>
                  <br />
                  <Row class="h3">
                    <iCol span="24" class="align-right">编号：{{ current.id }}</iCol>
                  </Row>
                  <br />
                  <div class="docu-fang-song">
                    <p class="margin-bottom16">
                      <span class="under-line">&nbsp;姜堰区&nbsp;</span>人民法院：
                    </p>
                    <p style="text-indent:36px;" class="margin-bottom8">
                      <span class="under-line">&nbsp;{{ current.name }}&nbsp;</span>，
                      <span class="under-line">&nbsp;{{ current.sell_type }}&nbsp;</span>一案，经我局审核研究，现提出以下涉税处理意见，请协助执行。涉案当事人
                      <span
                        class="under-line"
                      >&nbsp;{{ current.owner }}&nbsp;</span>应申报缴纳的税款如下：
                    </p>
                    <Table
                      :columns="count_cols"
                      :data="counts"
                      :loading="countLoading"
                      ref="count_sec"
                      size="large"
                      class="docu-fang-song"
                      border
                      show-summary
                      :summary-method="taxSummary"
                    ></Table>
                  </div>
                </div>
                <Row class="bottom-right docu-fang-song" v-if="current">
                  <p class="margin-bottom16">泰州市姜堰区税务局第二税务分局</p>
                  <p>{{ '2019 年 11 月 9 日' }}</p>
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
  name: "Docu",
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
          title: "依据",
          key: "tax_base",
          align: "right",
          notsum: true,
        },
        {
          title: "税率",
          key: "tax_percent",
          align: "right",
          notsum: true,
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
      this.$.posts("/docued/find", { begin, end })
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
      this.$.posts("/docued/tax", { data_id: row.id })
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
      print.printImage("print", "打印内容");
    },
    // 测算结束，提交复核
    countExam() {
      let row = this.current;
      if (row === null) {
        this.$Message.error("没有选择测算标的！");
        return;
      }
      this.countLoading = true;

      this.$.posts("/docued/exam", { uid: row.uid })
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
    },

        // 统计合计数
    taxSummary({ columns, data }) {
      const sums = {};
      columns.forEach((column, index) => {
        const key = column.key;
        if (index === 0) {
          sums[key] = {
            key,
            value: "合计："
          };
          return;
        }
        if (column.notsum) {
          sums[key] = {
            key,
            value: ""
          };
          return;
        }
        const values = data.map(item => Number(item[key]));
        if (!values.every(value => isNaN(value))) {
          const v = values.reduce((prev, curr) => {
            const value = Number(curr);
            if (!isNaN(value)) {
              return prev + curr;
            } else {
              return prev;
            }
          }, 0);
          sums[key] = {
            key,
            value: v.toFixed(2)
          };
        } else {
          sums[key] = {
            key,
            value: ""
          };
        }
      });

      return sums;
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
    this.$.gets("/docued/index")
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
.docu-fang-song {
  font-size: 18px;
  font-family: "FangSong", "STFangsong", "FangSong_GB2312";
}
</style>