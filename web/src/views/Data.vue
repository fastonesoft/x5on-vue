<template>
    <dev-article>
        <Row :gutter="16">
            <i-col span="5">
                <Card title="标的清单">
                    <Tag color="green" slot="extra">的</Tag>
                    <Row class="data-collect hidden-nowrap">总计：{{ dataCount.total }}</Row>
                    <Divider size="small" dashed></Divider>
                    <Progress :percent="dataCount.data_per" stroke-color="#19be6b" hide-info></Progress>
                </Card>
            </i-col>
            <i-col span="5">
                <Card title="税率测算">
                    <Tag color="red" slot="extra">算</Tag>
                    <Row class="data-collect hidden-nowrap">测算：{{ dataCount.count }}</Row>
                    <Divider size="small" dashed></Divider>
                    <Progress :percent="dataCount.count_per" stroke-color="#2db7f5" hide-info></Progress>
                </Card>
            </i-col>
            <i-col span="5">
                <Card title="测算反馈">
                    <Tag color="geekblue" slot="extra">馈</Tag>
                    <Row class="data-collect hidden-nowrap">反馈：{{ dataCount.back }}</Row>
                    <Divider size="small" dashed></Divider>
                    <Progress :percent="dataCount.back_per" stroke-color="#ff9900" hide-info></Progress>
                </Card>
            </i-col>
            <i-col span="5">
                <Card title="协作成果">
                    <Tag color="blue" slot="extra">果</Tag>
                    <Row class="data-collect hidden-nowrap">完成：{{ dataCount.backed }}</Row>
                    <Divider size="small" dashed></Divider>
                    <Progress :percent="dataCount.result_per" stroke-color="#ed4014" hide-info></Progress>
                </Card>
            </i-col>
            <i-col span="4">
                <Card title="快捷操作">
                    <Row class="data-collect align-center margin-bottom22 hidden-nowrap">添加标的</Row>
                    <Button type="primary" icon="md-add" @click="formAdd" long>添加</Button>
                </Card>
            </i-col>
        </Row>
        <Row class="margin-top16">
            <Card>
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
                        <Row class="margin-top16">
                            <i-col span="12" class="hidden-nowrap align-left">
                                <Button type="primary" @click="uptoExam">提交审核</Button>
                                <Button type="error" class="margin-left16" v-if="ajaxs.length>0" @click="delData">删除记录
                                </Button>
                            </i-col>
                            <i-col span="12" class="hidden-nowrap align-right">
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
                            <Button class="margin-left8" type="primary" size="small" @click="countDateClick">查询</Button>
                        </Row>
                    </template>
                </Tabs>
            </Card>
        </Row>
        <Modal
                :title="formTitle"
                v-model="formModel"
                :mask-closable="false"
                :loading="formLoading"
                @on-ok="formOk('form')"
                @on-cancel="formCancel('form')"
                width="550"
        >
            <Form ref="form" :model="form" :rules="rule" label-position="top" inline>
                <FormItem prop="id" label="编号">
                    <Input v-model="form.id" placeholder="输入标的编号" :maxlength="20" :disabled="inputDisable"/>
                </FormItem>
                <FormItem prop="name" label="标的名称">
                    <Input v-model="form.name" placeholder="输入标的名称相关说明" :maxlength="20" :disabled="inputDisable"/>
                </FormItem>
                <FormItem prop="sell_type" label="涉税类型">
                    <Select v-model="form.sell_type" placeholder="涉税类型选择..." style="width: 162px;" transfer>
                        <Option value="拍卖" key="拍卖">拍卖</Option>
                        <Option value="变卖" key="变卖">变卖</Option>
                    </Select>
                </FormItem>
                <FormItem prop="owner" label="产权人">
                    <Input v-model="form.owner" placeholder="输入产权人姓名" :maxlength="30"/>
                </FormItem>
                <FormItem prop="area_type" label="产权性质">
                    <Select v-model="form.area_type" placeholder="产权性质选择..." style="width: 162px;" transfer>
                        <Option value="国有" key="国有">国有</Option>
                        <Option value="集体" key="集体">集体</Option>
                        <Option value="有限公司" key="有限公司">有限公司</Option>
                        <Option value="其它个人" key="其它个人">其它个人</Option>
                    </Select>
                </FormItem>
                <FormItem prop="area_id" label="所属地区">
                    <Select v-model="form.area_id" placeholder="地区选择..." style="width: 162px;" transfer>
                        <Option :value="area.id" :key="area.id" v-for="area of areas">{{area.up_name}},{{area.name}}
                        </Option>
                    </Select>
                </FormItem>
                <FormItem prop="area_build" label="建筑面积">
                    <Input v-model="form.area_build" placeholder="输入建筑面积" :maxlength="10"/>
                </FormItem>
                <FormItem prop="area_soil" label="土地面积">
                    <Input v-model="form.area_soil" placeholder="输入土地面积" :maxlength="10"/>
                </FormItem>
                <FormItem prop="use_year" label="使用年限">
                    <Input v-model="form.use_year" placeholder="输入使用年限" :maxlength="2"/>
                </FormItem>
                <br>
                <FormItem prop="price_begin" label="初始价格">
                    <Input v-model="form.price_begin" placeholder="输入标的初始价格" :maxlength="16"/>
                </FormItem>
                <FormItem prop="price_ass" label="评估价格">
                    <Input v-model="form.price_ass" placeholder="输入标的评估价格" :maxlength="16"/>
                </FormItem>
                <FormItem prop="price_shoot" label="起拍价格">
                    <Input v-model="form.price_shoot" placeholder="输入标的起拍价格" :maxlength="16"/>
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
        owner: '',
        sell_type: '',
        area_type: '',
        area_id: '',
        area_build: '',
        area_soil: '',
        use_year: '',
        price_begin: '',
        price_ass: '',
        price_shoot: '',
    };

    export default {
        name: "Data",
        data() {
            return {
                ajax_count: null,

                dateType: 'day',
                countDate: [new Date(), new Date()],

                // 表格加载
                cols: [
                    {
                        width: 50,
                        type: 'selection',
                        align: 'center',
                    },
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
                    {
                        title: '产权性质',
                        key: 'area_type',
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
                    {
                        title: '操作',
                        key: 'action',
                        width: 80,
                        align: 'center',
                        render: (h, params) => {
                            return h('div', [
                                h('Button', {
                                    props: {
                                        type: 'primary',
                                        size: 'small'
                                    },
                                    on: {
                                        click: () => {
                                            this.formEdit(params.index)
                                        }
                                    }
                                }, '修改'),
                            ]);
                        }
                    }
                ],
                ajaxs: [],
                areas: [],
                pageIndex: 1,
                pageSize: 10,
                tableLoading: true,

                // 表单加载
                formType: 'add',
                formModel: false,
                formLoading: true,
                form: Object.assign({}, formConst),

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
                    owner: [
                        {
                            required: true, message: '产权人姓名不得为空', trigger: 'blur'
                        },
                        {
                            pattern: /^[\u4e00-\u9fa5]{2,30}$/,
                            message: '2-30位，汉字，不得有空格',
                            trigger: 'change'
                        },
                    ],
                    sell_type: [
                        {
                            required: true, message: '请选择相应的涉税类型', trigger: 'change'
                        }
                    ],
                    area_type: [
                        {
                            required: true, message: '请选择相应的产权性质', trigger: 'change'
                        }
                    ],
                    area_id: [
                        {
                            required: true, message: '请选择产权所属地区', trigger: 'change'
                        }
                    ],
                    area_build: [
                        {
                            required: true, message: '建筑面积不得为空', trigger: 'blur'
                        },
                        {
                            pattern: /^\d+(\.\d{1,2})?$/,
                            message: '数值型，可以带两位小数',
                            trigger: 'change'
                        }
                    ],
                    area_soil: [
                        {
                            required: true, message: '土地面积不得为空', trigger: 'blur'
                        },
                        {
                            pattern: /^\d+(\.\d{1,2})?$/,
                            message: '数值型，可以带两位小数',
                            trigger: 'change'
                        }
                    ],
                    use_year: [
                        {
                            required: true, message: '使用年限不得为空', trigger: 'blur'
                        },
                        {
                            pattern: /^\d{1,2}$/,
                            message: '最少1年，最多99年',
                            trigger: 'change'
                        }
                    ],
                    price_begin: [
                        {
                            required: true, message: '初始价格不得为空', trigger: 'blur'
                        },
                        {
                            pattern: /^\d+(\.\d{1,2})?$/,
                            message: '数值型，可以带两位小数',
                            trigger: 'change'
                        }
                    ],
                    price_ass: [
                        {
                            required: true, message: '评估价格不得为空', trigger: 'blur'
                        },
                        {
                            pattern: /^\d+(\.\d{1,2})?$/,
                            message: '数值型，可以带两位小数',
                            trigger: 'change'
                        }
                    ],
                    price_shoot: [
                        {
                            required: true, message: '起拍价格不得为空', trigger: 'blur'
                        },
                        {
                            pattern: /^\d+(\.\d{1,2})?$/,
                            message: '数值型，可以带两位小数',
                            trigger: 'change'
                        }
                    ],
                },
            }
        },
        methods: {
            countDateClick() {
                let begin = xcon.dateFormat(this.countDate[0], 'yyyy-MM-dd');
                let end = xcon.dateFormat(this.countDate[1], 'yyyy-MM-dd');

                this.tableLoading = true;
                this.$.posts('/data/find', {begin, end})
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

            // page
            pageChange(index) {
                this.pageIndex = index;
            },
            sizeChange(size) {
                this.pageSize = size;
            },

            formAdd() {
                this.formType = 'add';
                this.formModel = true;
                this.form = Object.assign({}, formConst)
            },
            formEdit(index) {
                this.formType = 'edit';
                this.formModel = true;
                let data = this.datas[index];
                let {id, uid, name, owner, sell_type, area_type, area_id, area_build, area_soil, use_year, price_begin, price_ass, price_shoot} = data;
                this.form = Object.assign({}, {
                    id,
                    uid,
                    name,
                    owner,
                    sell_type,
                    area_type,
                    area_id,
                    area_build,
                    area_soil,
                    use_year,
                    price_begin,
                    price_ass,
                    price_shoot
                });
            },
            formOk(name) {
                this.$refs[name].validate((valid) => {
                    if (valid) {
                        this.$.posts('/data/' + this.formType, this.form)
                            .then(res => {
                                if (this.formType === 'add') {
                                    xcon.isNotNull(res) && this.ajaxs.push(res);
                                    // refresh data
                                    this.ajax_count.total++
                                } else {
                                    xcon.arrsEdit(this.ajaxs, 'uid', res.uid, res);
                                }

                                this.formModel = false;
                                // 重置表单
                                this.$refs[name].resetFields();
                                this.$Message.success(this.formTitle + '成功！');
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

            uptoExam() {
                let select = this.$refs.table.getSelection();
                if (select.length === 0) {
                    this.$Message.error('先选择相应“标的”');
                    return;
                }
                let arrs = [];
                select.forEach(function (item) {
                    arrs.push(item.uid)
                });
                let uids = arrs.join(',');

                // 提交审核
                this.$.posts('/data/upto', {uids})
                    .then(res => {
                        let that = this;
                        arrs.forEach(function (item) {
                            xcon.arrsDel(that.ajaxs, 'uid', item);
                        });
                        this.$Message.success(res + '条数据提交成功！');
                    })
                    .catch(error => {
                        this.$Message.error(error);
                    });
            },

            delData() {
                let select = this.$refs.table.getSelection();
                if (select.length === 0) {
                    this.$Message.error('先选择相应“标的”');
                    return;
                }
                let arrs = [];
                select.forEach(function (item) {
                    arrs.push(item.uid)
                });
                let uids = arrs.join(',');

                // 删除数据
                this.$.posts('/data/del', {uids})
                    .then(res => {
                        let that = this;
                        arrs.forEach(function (item) {
                            xcon.arrsDel(that.ajaxs, 'uid', item);
                        });
                        // refresh data
                        this.ajax_count.total -= res;
                        this.$Message.success(res + '条数据删除成功！');
                    })
                    .catch(error => {
                        this.$Message.error(error);
                    });
            },
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
                        result_per: 0,
                    }
                } else {
                    let {total, dataed, count, counted, back, backed} = this.ajax_count;
                    total = Number(total);
                    dataed = Number(dataed);
                    count = Number(count);
                    counted = Number(counted);
                    back = Number(back);
                    backed = Number(backed);
                    let data_per = total > 0 ? dataed / total * 100 : 0;
                    let count_per = dataed ? counted / dataed * 100 : 0;
                    let back_per = counted ? backed / counted * 100 : 0;
                    let result_per = total ? backed / total * 100 : 0;
                    return {
                        total,
                        count,
                        counted,
                        back,
                        backed,
                        data_per,
                        count_per,
                        back_per,
                        result_per,
                    };
                }
            },
            datas() {
                return xcon.pageData(this.ajaxs, this.pageIndex, this.pageSize)
            },
            inputDisable() {
                return this.formType !== 'add'
            },
            formTitle() {
                return this.formType === 'add' ? '标的添加' : '标的修改';
            }
        },

        created() {
            this.$.gets('/data/index')
                .then(res => {
                    this.ajaxs = res.datas;
                    this.areas = res.areas;
                    this.ajax_count = res.count;
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