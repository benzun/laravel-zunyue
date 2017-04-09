@extends('admin.layout.body')

@section('title', '添加微信公众号')

@section('body')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">添加微信公众号</h3>
                </div>
                <form method="post" class="form-horizontal">
                    {!! csrf_field() !!}
                    <div class="box-body">
                        <blockquote>
                            登录 <a href="https://mp.weixin.qq.com/" target="_blank">微信公众平台</a>，左侧菜单[ <span class="guide">公众号设置</span> ]中获取，包括：名称，微信号，原始ID等信息
                            <br>
                            AppId 和 AppSecret 则在左侧菜单[ <span class="guide">基本配置</span> ]中获取
                        </blockquote>
                        @if ($errors->has('failed'))
                            <div class="alert alert-danger" role="alert">
                                {{ $errors->first('failed') }}
                            </div>
                        @endif

                        <div class="form-group {{ $errors->has('name') ? ' has-error' : '' }}">
                            <label class="col-sm-2 control-label">公众号名称:</label>
                            <div class="col-sm-10">
                                <input type="text" name="name" value="{{ old('name') }}" class="form-control" placeholder="公众号名称">
                                @if ($errors->has('name'))
                                    <span class="help-block">
                                        {{ $errors->first('name') }}
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group {{ $errors->has('wechat_id') ? ' has-error' : '' }}">
                            <label class="col-sm-2 control-label">微信号:</label>
                            <div class="col-sm-10">
                                <input type="text" name="wechat_id" value="{{ old('wechat_id') }}" class="form-control" placeholder="微信号">
                                @if ($errors->has('wechat_id'))
                                    <span class="help-block">
                                        {{ $errors->first('wechat_id') }}
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group {{ $errors->has('original_id') ? ' has-error' : '' }}">
                            <label class="col-sm-2 control-label">原始ID:</label>
                            <div class="col-sm-10">
                                <input type="text" name="original_id" value="{{ old('original_id') }}" class="form-control" placeholder="原始ID" maxlength="15">
                                <span class="help-block fa fa-exclamation-circle">原始ID为gh_开头15位数，如：gh_49f7e7645547</span>
                            </div>
                        </div>

                        <div class="form-group {{ $errors->has('app_id') ? ' has-error' : '' }}">
                            <label class="col-sm-2 control-label">AppId(应用ID):</label>
                            <div class="col-sm-10">
                                <input type="text" name="app_id" value="{{ old('app_id') }}" class="form-control" placeholder="AppId(应用ID)" maxlength="18">
                                @if ($errors->has('app_id'))
                                    <span class="help-block">
                                        {{ $errors->first('app_id') }}
                                    </span>
                                @endif
                                <span class="help-block fa fa-exclamation-circle">AppId(应用ID)为wx开头18位数，如：wx3655b881ee54d327</span>
                            </div>
                        </div>

                        <div class="form-group {{ $errors->has('secret') ? ' has-error' : '' }}">
                            <label class="col-sm-2 control-label">AppSecret(应用密钥):</label>
                            <div class="col-sm-10">
                                <input type="text" name="secret" value="{{ old('secret') }}" class="form-control" placeholder="AppSecret(应用密钥)">
                                <span class="help-block fa fa-exclamation-circle">AppSecret(应用密钥)为32位数，如：a5b5ba284e2bb8096d1cfb8a3062fadg</span>
                            </div>
                        </div>
                    </div>
                    <div class="box-footer">
                        <div class="form-group">
                            <label class="col-sm-2 control-label"></label>
                            <div class="col-sm-10">
                                <button type="submit" class="btn btn-flat btn-primary"> 提 交 </button>
                                <button type="button" style="margin-left: 5px;" class="btn btn-flat btn-success" onclick="history.back();"> 返 回 </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection