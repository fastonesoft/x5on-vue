<template>
  <div class="cloudBody">
    <div id="cloud1" class="login-cloud"></div>
    <div id="cloud2" class="login-cloud"></div>

    <div class="login-body">
      <div class="login-code">
        <div class="login-form">
          <i-col class="login-form-title">用户登录</i-col>
          <Form label-position="top">
            <FormItem>
              <Input v-model="form.id" placeholder="输入帐号，5-20字符"/>
            </FormItem>
            <FormItem>
              <Input v-model="form.pass" placeholder="输入密码，6-20个字符"/>
            </FormItem>
          </Form>
          <Button type="primary" size="large" long>登录</Button>

        </div>
      </div>
    </div>
  </div>
</template>

<script>
    export default {
        name: "Login",
        data() {
            return {
                form: {
                    id: '',
                    pass: '',
                }
            }

        }
    }
</script>

<style>

  html, body {
    width: 100%;
    height: 100%;
    margin: 0;
    padding: 0;
  }

  body {
    background-color: #1c77ac;
    background-image: url("../assets/login_light.png");
    background-repeat: no-repeat;
    background-position: center top;
    overflow: hidden;
  }

  #app {
    width: 100%;
    height: 100%;
  }

  .cloudBody {
    width: 100%;
    height: 100%;
    position: relative;
    z-index: -1;
  }

  .login-cloud {
    top: 0px;
    left: 0px;
    width: 100%;
    height: 100%;
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
    background-color: white;
    border-radius: 5px;
    box-shadow: 1px 1px 5px #333333, -1px -1px 5px #333333;
  }

  .login-form {
    margin-left: 206px;
    width: 380px;
    height: 322px;
    padding: 20px 40px;
  }

  .login-form-title {
    height: 70px;
    text-align: center;
    letter-spacing: 8px;
    text-shadow: 2px 2px 3px #666;
    color: #1c77ac;
    font-size: 24px;
  }

</style>