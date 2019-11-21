<template>
  <dev-article>
    <GeminiScrollbar class="xcon-split">
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
              <div v-if="current">
                <Table
                  :columns="count_cols"
                  :data="counts"
                  :loading="countLoading"
                  ref="count_sec"
                  size="small"
                  border
                  stripe
                ></Table>
                <br />
                <Row v-if="counts.length" class="hidden-nowrap">
                  <Tag color="success">测算合计：{{amounts}}</Tag>
                  <Button class="margin-left8" type="primary" size="small" @click="countUpto">提交复核</Button>
                </Row>
              </div>
            </TabPane>
            <!--表头附加相关操作：-->
            <template slot="extra">
              <Row class="hidden-nowrap">
                <Button
                  class="margin-left8"
                  type="primary"
                  size="small"
                  v-if="current"
                  @click="countAdd"
                >添加</Button>
              </Row>
            </template>
          </Tabs>
        </div>
      </Split>
    </GeminiScrollbar>
    <Modal
      title="税种添加"
      v-model="formModel"
      :mask-closable="false"
      :loading="formLoading"
      @on-ok="formOk('form')"
      @on-cancel="formCancel('form')"
      width="550"
    >
      <Form ref="form" :model="form" :rules="rule" label-position="top">
        <FormItem prop="tax_id" label="税种">
          <Select v-model="form.tax_id" placeholder="税种选择..." filterable transfer>
            <Option :value="tax.id" :key="tax.id" v-for="tax of taxs">{{tax.name}}</Option>
          </Select>
        </FormItem>
        <FormItem prop="tax_base" label="计税依据">
          <Input v-model="form.tax_base" placeholder="输入计税依据" />
        </FormItem>
        <FormItem prop="tax_percent" label="税率">
          <Input v-model="form.tax_percent" placeholder="输入对应税率" />
        </FormItem>
        <FormItem prop="tax_amount" label="税额">
          <Input v-model="form.tax_amount" placeholder="输入对应税额" />
        </FormItem>
      </Form>
    </Modal>
  </dev-article>
</template>

<script>
import xcon from "../libs/xcon";

const formConst = {
  tax_id: "",
  tax_base: "",
  tax_percent: "",
  tax_amount: ""
};

export default {
  name: "Count",
  data() {
    return {
      // split
      split1: 0.7,

      // count
      ajax_count: null,

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
        },
        {
          title: "建筑面积",
          key: "area_build"
        },
        {
          title: "土地面积",
          key: "area_soil"
        },
        {
          title: "使用年限",
          key: "use_year"
        },
        {
          title: "初始价格",
          key: "price_begin"
        },
        {
          title: "评价价格",
          key: "price_ass"
        },
        {
          title: "起拍价格",
          key: "price_shoot"
        }
      ],
      ajaxs: [],
      pageIndex: 1,
      pageSize: 10,
      tableLoading: true,
      current: null,

      // form
      taxs: [],
      formModel: false,
      formLoading: true,
      form: Object.assign({}, formConst),
      rule: {
        tax_id: [
          {
            required: true,
            message: "请选择相应的税种",
            trigger: "change"
          }
        ],
        tax_base: [
          {
            required: true,
            message: "计税依据不得为空",
            trigger: "blur"
          },
          {
            pattern: /^\d+(\.\d{1,2})?$/,
            message: "数值型，可以带2位小数",
            trigger: "change"
          }
        ],
        tax_percent: [
          {
            required: true,
            message: "税率不得为空",
            trigger: "blur"
          },
          {
            pattern: /^\d+(\.\d{1,4})?$/,
            message: "数值型，可以带4位小数",
            trigger: "change"
          }
        ],
        tax_amount: [
          {
            required: true,
            message: "税额不得为空",
            trigger: "blur"
          },
          {
            pattern: /^\d+(\.\d{1,2})?$/,
            message: "数值型，可以带2位小数",
            trigger: "change"
          }
        ]
      },

      countLoading: false,
      count_cols: [
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
        },
        {
          title: "操作",
          key: "action",
          width: 80,
          align: "center",
          render: (h, params) => {
            return h("div", [
              h(
                "Button",
                {
                  props: {
                    type: "warning",
                    size: "small"
                  },
                  style: {
                    marginRight: "5px"
                  },
                  on: {
                    click: () => {
                      this.countDel(params.index);
                    }
                  }
                },
                "删除"
              )
            ]);
          }
        }
      ],
      counts: []
    };
  },
  methods: {
    countDateClick() {
      this.current = null;
      this.tableLoading = true;

      let begin = xcon.dateFormat(this.countDate[0], "yyyy-MM-dd");
      let end = xcon.dateFormat(this.countDate[1], "yyyy-MM-dd");
      this.$.posts("/count/find", { begin, end })
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
      this.$.posts("/count/tax", { data_id: row.id })
        .then(res => {
          this.counts = res;
          this.countLoading = false;
        })
        .catch(error => {
          this.countLoading = false;
          this.$Message.error(error);
        });
    },

    // 添加测算税种
    countAdd() {
      this.formModel = true;
    },
    // 删除测算税种
    countDel(index) {
      let row = this.counts[index];
      this.countLoading = true;
      this.$.posts("/count/del", { uid: row.uid })
        .then(res => {
          xcon.arrsDel(this.counts, "uid", row.uid);

          this.countLoading = false;
          this.$Message.success(res + "条税种删除成功！");
        })
        .catch(error => {
          this.countLoading = false;
          this.$Message.error(error);
        });
    },

    formOk(name) {
      this.$refs[name].validate(valid => {
        if (valid) {
          // 构造提交数据
          let param = this.form;
          param.data_id = this.current.id;
          this.$.posts("/count/add", param)
            .then(res => {
              this.counts.push(res);

              this.formModel = false;
              this.$refs[name].resetFields();
              this.$Message.success("税种、税额添加成功！");
            })
            .catch(error => {
              this.formLoading = false;
              this.$nextTick(() => {
                this.formLoading = true;
              });
              this.$Message.error(error);
            });
        } else {
          this.formLoading = false;
          this.$nextTick(() => {
            this.formLoading = true;
          });
          this.$Message.error("表单验证无法通过，请重新输入");
        }
      });
    },
    formCancel(name) {
      this.$refs[name].resetFields();
      this.$Message.warning("表单添加取消！");
    },

    // 测算结束，提交复核
    countUpto() {
      let row = this.current;
      this.countLoading = true;

      this.$.posts("/count/upto", { uid: row.uid })
        .then(res => {
          this.current = null;
          this.countLoading = false;
          this.ajax_count.count--;

          xcon.arrsDel(this.ajaxs, "uid", row.uid);
          this.$Message.success(res + "条测算标的提交复核成功！");
        })
        .catch(error => {
          this.countLoading = false;
          this.$Message.error(error);
        });
    }
  },
  computed: {
    datas() {
      return xcon.pageData(this.ajaxs, this.pageIndex, this.pageSize);
    },
    amounts() {
      let total = 0;
      this.counts.forEach(item => {
        total += Number(item.tax_amount);
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
  },
  mounted() {}
};
</script>

<style scoped>
</style>