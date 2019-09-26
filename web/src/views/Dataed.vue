<template>
  <dev-article>
    <div id="roleSplit">
      <Split v-model="split1" class="split">
        <div slot="left" class="slot-left">
          <Tabs value="table">
            <TabPane label="标的清单" name="table">
              <Table
                :columns="cols"
                :data="datas"
                :loading="tableLoading"
                ref="table"
                size="small"
                border stripe>
              </Table>
              <Row class="margin-top16 hidden-nowrap align-right">
                <Page
                  :total="ajax_datas.length"
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
                  style="width: 180px"
                  @on-change="dateChange"
                  transfer>
                </DatePicker>
                <Button class="margin-left8" type="primary" size="small" @click="countDateClick">查询</Button>
              </Row>
            </template>
          </Tabs>
        </div>
        <div slot="right" class="slot-right">
          <Tabs value="menus">
            <TabPane label="模块鉴权" name="menus">
              <Tree :data="action_datas" show-checkbox @on-check-change="actionCheck"
                    @on-select-change="actionSelect"></Tree>
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
        name: "Role",
        data() {
            return {

                split1: 0.4,

                groups: [],
                group_uid: '',

                menu_datas: [],
                action_datas: [],

                pageIndex: 1,
                pageSize: 10,
                dateType: 'day',
                countDate: [new Date(), new Date()],

                cols: [],
                datas: [],
                ajax_datas: [],
                tableLoading: true,
            }
        },
        methods: {
            countDateClick() {
                let begin = xcon.dateFormat(this.countDate[0], 'yyyy-MM-dd');
                let end = xcon.dateFormat(this.countDate[1], 'yyyy-MM-dd');

                this.tableLoading = true;
                this.$.posts('/data/find', {begin, end})
                    .then(res => {
                        this.datas = res;
                        this.tableLoading = false;
                    })
                    .catch(error => {
                        this.$Message.error(error);
                    })
            },
            dateChange(val) {
                // 自定义日期列表，清除radio选项
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


            menuCheck(arr, curr) {
                // 提交分组uid、curr menu.uid
                let uid = this.group_uid;
                let uids = {};
                uids[curr.uid] = curr.checked;

                this.$.posts('/role/upto', {uid, uids: JSON.stringify(uids)})
                    .then(res => {
                        this.$Message.success(res + '条记录状态变更！');
                    })
                    .catch(error => {
                        this.$Message.error(error);
                    });
            },
            menuSelect(val) {
                window.console.log(val)
            },
            actionCheck(val) {
                window.console.log(val)
            },
            actionSelect(val) {
                window.console.log(val)
            },
            groupChange(uid) {
                this.$.posts('/role/menus', {uid})
                    .then(res => {
                        let {types, menus, group_menus} = res;
                        // 合并分组菜单和所有菜单项
                        menus.forEach(menu => {
                            menu.checked = group_menus.filter(item => item.menu_id === menu.id).length > 0;
                        });
                        let datas = [];
                        // 合并菜单分类和菜单项
                        types.forEach(type => {
                            // 构造子菜单
                            let children = [];
                            let childs = menus.filter(menu => menu.type_id === type.type_id);
                            childs.forEach(child => {
                                let sub_item = {};
                                sub_item.title = child.title;
                                sub_item.uid = child.uid;
                                sub_item.is_menu = true;
                                sub_item.checked = child.checked;
                                children.push(sub_item)
                            });
                            // 创建菜单项
                            let item = {};
                            item.expand = true;
                            item.disabled = true;
                            item.is_menu = false;
                            item.title = type.title;
                            item.children = children;
                            datas.push(item)
                        });

                        this.menu_datas = datas;
                    })
                    .catch(error => {
                        this.$Message.error(error);
                    });
            },

            pageChange(index) {
                this.pageIndex = index;
            },

            sizeChange(size) {
                this.pageSize = size;
            },
        },
        computed: {},
        created() {
            this.$.gets('/role/')
                .then(res => {
                    this.groups = res;
                })
                .catch(error => {
                    this.$Message.error(error);
                });
        },
        mounted() {
            const that = this;
            window.onresize = function () {
                if (that.$route.path !== '/vdataed') return;

                // 分割条高度计算
                let height = document.body.clientHeight - 60 - 36;
                document.getElementById('roleSplit').style.height = height + 'px';
            };
            window.onresize();
        },
    }
</script>

<style scoped>

  .split {
    border: 1px solid #e8eaec;
    border-radius: 4px;
    background: #fff;
    transition: all .2s ease-in-out;
  }

  .split:hover {
    border-color: #eee;
    box-shadow: 0 1px 6px rgba(0, 0, 0, .2);
    transition: all .2s ease-in-out;
  }

  .slot-left {
    padding: 16px;
  }

  .slot-right {
    padding: 16px 16px 16px 20px;
  }

</style>