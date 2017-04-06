<aside class="main-sidebar">
    <section class="sidebar">
        @if(!empty(session('wechat_account')))
            <div class="user-panel">
                <div class="pull-left image">
                    @if(!empty(session('wechat_account.headimgurl')))
                        <img class="img-circle" src="{{ \App\Http\Controllers\Helper::getQiniuDomain(). session('wechat_account.headimgurl') }}">
                    @else
                        <img class="img-circle" src="http://open.weixin.qq.com/qr/code/?username={{ session('wechat_account.wechat_id') }}">
                    @endif

                </div>
                <div class="pull-left info">
                    <p>{{ session('wechat_account.name') }}</p>
                    <a href="#"><i class="fa fa-circle text-success"></i> {{ $admin_config['account_type'][session('wechat_account.type')] }}</a>
                </div>
            </div>
        @endif

        <ul class="sidebar-menu">
            <li class="header">MAIN NAVIGATION</li>
            <li>
                <a href="{{ action('Admin\IndexController@index') }}">
                    <i class="fa fa-wechat"></i> <span>公众号列表</span>
                </a>
            </li>

            @if(!empty(session('wechat_account')))
            <li class="active treeview">
                <a href="#">
                    <i class="fa fa-dashboard"></i> <span>用户管理</span>
                    <span class="pull-right-container">
                      <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li class="active"><a href="{{ action('Admin\UsersController@getIndex') }}"><i class="fa fa-circle-o"></i>  用户列表</a></li>
                </ul>
            </li>
            @endif

        </ul>
    </section>
</aside>