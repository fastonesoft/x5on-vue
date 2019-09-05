<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>校务在线</title>
  <script src="https://cdn.bootcss.com/jquery/3.4.1/jquery.min.js"></script>
  <script type="text/javascript">

    $(function () {

      $(window).resize(function () {
        var awid = $(window).width();
        var ahei = $(window).height();

        $('#cloud1').css({'left': awid / 4 * Math.random(), 'top': ahei / 4 * Math.random()});
        $('#cloud2').css({'left': awid / 2 + awid / 4 * Math.random(), 'top': ahei / 4 * Math.random()});
      });
      $(window).resize();

    });

  </script>

  <style type="text/css">

    body {
      background-color: #1c77ac;
      background-image: url(/content/images/light.png);
      background-repeat: no-repeat;
      background-position: center top;
      overflow: hidden;
    }

    #cloudBody {
      width: 100%;
      height: 100%;
      position: absolute;
      z-index: -1;
    }

    .login-cloud {
      top: 0px;
      left: 0px;
      width: 100%;
      height: 100%;
      position: absolute;
      background: url(/content/images/logincloud.png) no-repeat;
      z-index: 1;
      opacity: 0.5;
    }

    .login-body {
      width: 100%;
      height: 100%;
      overflow: hidden;
      position: absolute;
      background: url(/content/images/loginbg3.png) no-repeat center center;
    }

    .login-code {
      position: fixed;
      left: 50%;
      top: 50%;
      width: 360px;
      height: 360px;
      margin-top: -180px;
      margin-left: -180px;
      background-color: white;
      border-radius: 5px;
      box-shadow: 1px 1px 5px #333333, -1px -1px 5px #333333;
    }

  </style>

</head>
<body>

<div id="cloudBody">
  <div id="cloud1" class="login-cloud"></div>
  <div id="cloud2" class="login-cloud"></div>
</div>
<div class="login-body">
  <div class="login-code" id="login_code">
  </div>
</div>

</body>
</html>