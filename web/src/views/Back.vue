<template>
    <dev-article>
        <div id="Split">
            <Split v-model="split1" class="split" min="600" max="300">
                <div slot="left" class="slot-left">
                    <Tabs value="table">
                        <TabPane label="标的反馈" name="table">
                            <Table
                                    :columns="cols"
                                    :data="datas"
                                    :loading="tableLoading"
                                    ref="selection"
                                    size="small"
                                    @on-current-change="selectChange"
                                    highlight-row border stripe>
                            </Table>
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
                                        style="width: 180px"
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
                    <Tabs value="count1">
                        <TabPane label="税费清单" name="count1">
                            <div v-if="current">
                                <Table
                                        :columns="count_cols"
                                        :data="counts"
                                        :loading="countLoading"
                                        ref="count_sec"
                                        size="small"
                                        border stripe>
                                </Table>
                                <br>
                                <Row v-if="counts.length" class="hidden-nowrap">
                                    <Tag color="success">税费合计：{{amounts}}</Tag>
                                    <Button class="margin-left16" type="primary" @click="endPrice">成交价</Button>
                                </Row>
                            </div>

                        </TabPane>
                        <!--表头附加相关操作：-->
                        <template slot="extra">
                            <Row class="hidden-nowrap">
                                <Button type="error" size="small" @click="backUpto"
                                        v-if="current && current.price_end>0">提交审核
                                </Button>
                            </Row>
                        </template>
                    </Tabs>
                </div>
            </Split>
        </div>
        <Modal
                title="成交价格"
                v-model="formModel"
                :mask-closable="false"
                :loading="formLoading"
                @on-ok="formOk('form')"
                @on-cancel="formCancel('form')"
                width="550"
        >
            <Form ref="form" :model="form" :rules="rule" label-position="top">
                <FormItem prop="id" label="编号">
                    <Input v-model="form.id" placeholder="输入标的编号" :maxlength="20" disabled="disabled"/>
                </FormItem>
                <FormItem prop="name" label="标的名称">
                    <Input v-model="form.name" placeholder="输入标的名称相关说明" :maxlength="20" disabled="disabled"/>
                </FormItem>
                <FormItem prop="price_end" label="成交价格">
                    <Input v-model="form.price_end" placeholder="输入标的成交价格" :maxlength="16"/>
                </FormItem>
            </Form>
        </Modal>
    </dev-article>
</template>

<script>
    import xcon from '../libs/xcon'

    const formConst = {
        id: '',
        uid: '',
        name: '',
        price_end: '',
    };

    export default {
        name: "Count",
        data() {
            return {
                // split
                split1: 0.7,

                // date
                dateType: 'day',
                countDate: [new Date(), new Date()],

                // table
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
                        key: 'area_type',
                    },
                    {
                        title: '所属地区',
                        key: 'area_name',
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
                    {
                        title: '成交价格',
                        key: 'price_end',
                    },
                    {
                        title: '应征税费',
                        key: 'price_tax',
                    },
                    {
                        title: '最终价格',
                        key: 'price',
                    },
                ],
                ajaxs: [],
                pageIndex: 1,
                pageSize: 10,
                tableLoading: true,
                current: null,

                // form
                form: Object.assign({}, formConst),
                formModel: false,
                formLoading: true,
                rule: {
                    id: [
                        {
                            required: true, message: '编号不得为空，且为数字', trigger: 'blur'
                        },
                        {
                            pattern: /^\d{4,20}$/,
                            message: '4-20位，数字编号',
                            trigger: 'change'
                        },
                    ],
                    name: [
                        {
                            required: true, message: '拍卖标的名称不得为空', trigger: 'blur'
                        },
                        {
                            pattern: /^[\x21-\x7f\u4e00-\u9fa5]{6,50}$/,
                            message: '6-50位，字母、数学、汉字',
                            trigger: 'change'
                        },
                    ],
                    price_end: [
                        {
                            required: true, message: '成交价格不得为空', trigger: 'blur'
                        },
                        {

                            pattern: /^\d+(\.\d{1,2})?$/,
                            message: '数值型，可以带两位小数',
                            trigger: 'change'
                        },
                        {
                            validator(rule, value, callback) {
                                let errors = [];
                                if (value <= 0.00) {
                                    errors.push('成交价格不得<零');
                                }
                                callback(errors);
                            }
                        },
                    ],
                },

                // counted
                countLoading: false,
                count_cols: [
                    {
                        width: 50,
                        type: 'index',
                        align: 'center',
                    },
                    {
                        title: '税种',
                        key: 'tax_name',
                    },
                    {
                        title: '税率',
                        key: 'tax_percent',
                        align: 'right',
                    },
                    {
                        title: '税额',
                        key: 'tax_amount',
                        align: 'right',
                    },
                ],
                counts: [],
            }
        },
        methods: {
            countDateClick() {
                this.current = null;
                this.tableLoading = true;

                let begin = xcon.dateFormat(this.countDate[0], 'yyyy-MM-dd');
                let end = xcon.dateFormat(this.countDate[1], 'yyyy-MM-dd');
                this.$.posts('/back/find', {begin, end})
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
                this.$.posts('/back/tax', {data_id: row.id})
                    .then(res => {
                        this.counts = res;
                        this.countLoading = false
                    })
                    .catch(error => {
                        this.countLoading = false;
                        this.$Message.error(error);
                    });
            },

            // 成交价格添加
            endPrice() {
                if (this.current === null) {
                    this.$Message.error('先选择反馈标的！');
                    return
                }
                // 构造表单数据
                let {id, uid, name, price_end} = this.current;
                this.form = {id, uid, name, price_end, price_tax: this.amounts};
                this.formModel = true;
            },
            formOk(name) {
                this.$refs[name].validate((valid) => {
                    if (valid) {
                        this.$.posts('/back/edit', this.form)
                            .then(res => {
                                xcon.arrsEdit(this.ajaxs, 'uid', res.uid, res);

                                this.formModel = false;
                                this.$refs[name].resetFields();
                                this.$Message.success('成交价格添加成功！');
                            })
                            .catch(error => {
                                // 修改按钮状态
                                this.formLoading = false;
                                this.$nextTick(() => {
                                    this.formLoading = true;
                                });
                                // 出错提示
                                this.$Message.error(error);
                            });
                    } else {
                        this.formLoading = false;
                        this.$nextTick(() => {
                            this.formLoading = true;
                        });
                        this.$Message.error('表单验证无法通过，请重新输入');
                    }
                });
            },
            formCancel(name) {
                this.$refs[name].resetFields();
                this.$Message.warning('表单添加取消！');
            },
            // 提交审核
            backUpto() {
                let row = this.current;
                this.countLoading = true;

                this.$.posts('/back/upto', {uid: row.uid})
                    .then(res => {
                        this.current = null;
                        this.countLoading = false;

                        xcon.arrsDel(this.ajaxs, 'uid', row.uid);
                        this.$Message.success(res + '条反馈标的提交审核成功！');
                    })
                    .catch(error => {
                        this.countLoading = false;
                        this.$Message.error(error);
                    })
            },
        },
        computed: {
            datas() {
                return xcon.pageData(this.ajaxs, this.pageIndex, this.pageSize)
            },
            amounts() {
                let total = 0.0;
                this.counts.forEach(item => {
                    total += parseFloat(item.tax_amount)
                });
                return total
            },
        },
        created() {
            this.$.gets('/back/index')
                .then(res => {
                    this.ajaxs = res;
                    this.tableLoading = false
                })
                .catch(error => {
                    this.tableLoading = false
                    this.$Message.error(error);
                })
        },
        mounted() {
            const that = this;
            window.onresize = function () {
                if (that.$route.path !== '/vback') return;

                // 分割条高度计算
                let height = document.body.clientHeight - 60 - 36;
                document.getElementById('Split').style.height = height + 'px';
            };
            window.onresize();
        },
    }
</script>

<style scoped>

</style>