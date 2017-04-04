<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
    <title>尊悦微信云平台 | 注册</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link href="http://cdn.bootcss.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
    <link href="http://cdn.bootcss.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('/static/admin/css/AdminLTE.min.css') }}">
    <link href="http://cdn.bootcss.com/iCheck/1.0.2/skins/square/blue.css" rel="stylesheet">
    <!--[if lt IE 9]>
    <script src="http://cdn.bootcss.com/html5shiv/r29/html5.min.js"></script>
    <script src="http://cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body class="hold-transition register-page">
<div class="register-box">
    <div class="register-logo">
        <a><b>尊悦微信云平台</b></a>
    </div>
    <div class="register-box-body">
        @if( $errors->has('failed_login') )
            <p class="login-box-msg" style="color: red;">{{ $errors->first('failed_login') }}</p>
        @endif
        <form method="post">
            {{ csrf_field() }}
            <div class="form-group has-feedback {{ $errors->has('mobile') ? ' has-error' : '' }}">
                <input type="text" name="mobile" class="form-control" placeholder="手机号码" value="{{ old('mobile') }}">
                <span class="glyphicon glyphicon-phone form-control-feedback"></span>
                @if ($errors->has('mobile'))
                    <span class="help-block">
                        <strong>{{ $errors->first('mobile') }}</strong>
                    </span>
                @endif
            </div>
            <div class="form-group has-feedback {{ $errors->has('nickname') ? ' has-error' : '' }}">
                <input type="text" name="nickname" class="form-control" placeholder="昵称" value="{{ old('nickname') }}">
                <span class="glyphicon glyphicon-user form-control-feedback"></span>
                @if ($errors->has('nickname'))
                    <span class="help-block">
                        <strong>{{ $errors->first('nickname') }}</strong>
                    </span>
                @endif
            </div>
            <div class="form-group has-feedback {{ $errors->has('password') ? ' has-error' : '' }}">
                <input type="password" name="password" class="form-control" placeholder="密码">
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                @if ($errors->has('password'))
                    <span class="help-block">
                        <strong>{{ $errors->first('password') }}</strong>
                    </span>
                @endif
            </div>
            <div class="form-group has-feedback">
                <input type="password" name="password_confirmation" class="form-control" placeholder="重新输入密码">
                <span class="glyphicon glyphicon-log-in form-control-feedback"></span>
            </div>
            <div class="row">
                <div class="col-xs-8"></div>
                <div class="col-xs-4">
                    <button type="submit" class="btn btn-primary btn-block btn-flat">注册</button>
                </div>
            </div>
        </form>
            <div class="social-auth-links text-center">
                <p>- OR -</p>
            </div>
            <a href="#">找回密码</a><br>
            <a href="{{ action('Admin\Auth\LoginController@getLogin') }}" class="text-center">有账号？点击登陆</a>
    </div>
</div>
<script src="http://cdn.bootcss.com/jquery/2.2.3/jquery.min.js"></script>
<script src="http://cdn.bootcss.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</body>
</html>