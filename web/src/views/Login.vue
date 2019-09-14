<template>
    <div class="login">
        <div class="cloudBody">
            <div id="cloud1" class="login-cloud"></div>
            <div id="cloud2" class="login-cloud"></div>

            <div class="login-body">
                <div class="login-code">
                    <div class="login-form">
                        <i-col class="login-form-title">司法涉税综合管理</i-col>
                        <Form ref="form" :model="form" :rules="rule">
                            <FormItem prop="id">
                                <Input type="text" prefix="md-contact" v-model="form.id" size="large" :maxlength="20"
                                       placeholder="输入帐号，5-20个字符"/>
                            </FormItem>
                            <FormItem prop="pass">
                                <Input type="password" prefix="ios-eye" v-model="form.pass" size="large" :maxlength="20"
                                       placeholder="输入密码，6-20个字符"/>
                            </FormItem>
                            <FormItem>
                                <Button type="primary" size="large" @click="loginClick('form')" long
                                        style="background-color: #378CBE;">登录
                                </Button>
                            </FormItem>
                        </Form>

                    </div>
                </div>
            </div>
        </div>
    </div>

</template>

<script>
    import xcon from '../libs/xcon'

    export default {
        name: "Login",
        data() {
            return {
                token: '',
                form: {
                    id: 'admin',
                    pass: 'stone',
                },
                rule: {
                    id: [
                        {
                            required: true, message: '请输入用户账号', trigger: 'blur'
                        },
                        {
                            type: 'string',
                            min: 5,
                            max: 20,
                            message: '帐号长度最小5位，最大20位',
                            trigger: 'change'
                        }
                    ],
                    pass: [
                        {
                            required: true, message: '请输入用户密码', trigger: 'blur'
                        },
                        {
                            type: 'string',
                            min: 6,
                            max: 20,
                            message: '密码长度最小6位，最大20位',
                            trigger: 'change'
                        }
                    ]
                },
            }


        },
        methods:
            {
                loginClick(name) {

                    this.$refs[name].validate((valid) => {
                        if (valid) {
                            // 提交this.form服务器端认证
                            this.$.posts('/home/login', {
                                id: this.form.id,
                                pass: this.$md5(this.form.pass),
                                token: this.$md5(this.token + this.$md5(this.form.pass))
                            })
                                .then(res => {
                                    // 记录用户信息
                                    this.$store.commit('userSet', res.user);
                                    this.$store.commit('menuSet', res.menus);
                                    this.$store.commit('itemSet', res.items);
                                    this.$store.commit('roleSet', res.roles);
                                    // 写入本地
                                    xcon.stateWrite(this.$store.state);
                                    this.$router.replace('/vuehome');
                                })
                                .catch(error => {
                                    this.$Message.error(error);
                                });

                        } else {
                            this.$Message.error('登录信息无法通过验证，请重新输入');
                        }
                    })

                }
            },
        mounted() {
            let that = this;
            window.onresize = function () {
                // 登录页面才要进行位置计算
                if (that.$route.path !== '/vuelogin') return;

                let awid = document.body.clientWidth;
                let ahei = document.body.clientHeight;
                let left1 = awid / 4 * Math.random();
                let top1 = ahei / 4 * Math.random();
                let left2 = awid / 2 + awid / 4 * Math.random();
                let top2 = ahei / 4 * Math.random();
                document.getElementById('cloud1').style.left = left1 + 'px';
                document.getElementById('cloud1').style.top = top1 + 'px';
                document.getElementById('cloud2').style.left = left2 + 'px';
                document.getElementById('cloud2').style.top = top2 + 'px';
            };
            window.onresize();
        },
        created() {
            this.$.gets('/home/token')
                .then(res => {
                    // token
                    this.token = res;
                })
                .catch(error => {
                    this.$Message.error(error);
                });
        }
    }
</script>

<style scoped>

    .login {
        height: 100%;
        background: #1c77ac;
        overflow: hidden;
    }

    .cloudBody {
        width: 100%;
        height: 100%;
        background: url(../assets/login_light.png) no-repeat center center;
        position: relative;
        overflow: hidden;
    }

    .login-cloud {
        top: 0px;
        left: 0px;
        width: 405px;
        height: 165px;
        position: absolute;
        background: url("../assets/login_cloud.png") no-repeat;
        z-index: 1;
        opacity: 0.5;
    }

    .login-body {
        width: 100%;
        height: 100%;
        overflow: hidden;
        position: absolute;
        background: url("../assets/login_bg.png") no-repeat center center;
        z-index: 2;
    }

    .login-code {
        position: fixed;
        left: 50%;
        top: 50%;
        width: 586px;
        height: 322px;
        margin-left: -293px;
        margin-top: -161px;
        background: url("../assets/login_auth.png");
        border-radius: 5px;
        box-shadow: 1px 1px 5px #333333, -1px -1px 5px #333333;
    }

    .login-form {
        margin-left: 206px;
        width: 380px;
        height: 322px;
        padding: 30px 40px;
    }

    .login-form-title {
        height: 70px;
        text-align: center;
        letter-spacing: 5px;
        text-shadow: 1px 1px 3px #666;
        color: #378CBE;
        font-size: 24px;
    }

</style>