@extends('admin.layout.body')

@section('title', '微信用户列表')

@section('body')
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">微信用户列表</h3>
                </div>
                <div class="box-header with-border">
                </div>
                <div class="box-body">
                <div class="col-md-9">

                    <table class="table table-bordered table-hover">
                        <tr>
                            <th>ID</th>
                            <th>头像</th>
                            <th>微信昵称</th>
                            <th>性别</th>
                            <th>省份</th>
                            <th>城市</th>
                            <th>标签</th>
                            <th>是否关注</th>
                            <th>关注时间</th>
                        </tr>
                        @if(!empty($list))
                            @foreach($list as $item)
                                <tr>
                                    <td>{{ $item->id }}</td>
                                    <td>
                                        @if(!empty($item->headimgurl))
                                            <img src="{{ substr($item->headimgurl,0,-2) }}/64" class="img-circle" height="36">
                                        @endif
                                    </td>
                                    <td>{{ $item->nickname }}</td>
                                    <td>{{ $admin_config['sex'][$item->sex] }}</td>
                                    <td>{{ $item->province }}</td>
                                    <td>{{ $item->city }}</td>
                                    <td>
                                        @if(!empty($item->tags))
                                            @foreach($item->tags as $tag)
                                                <span class="badge bg-light-blue">{{ $tag->name }}</span>
                                            @endforeach
                                        @endif
                                    </td>
                                    <td>
                                        @if($item->subscribe == 1)
                                            <span class="label label-success">已关注</span>
                                        @else
                                            <span class="label label-danger">未关注</span>
                                        @endif
                                    </td>
                                    <td>{{ date('Y-m-d H:i:s', $item->subscribe_time) }}</td>
                                </tr>
                            @endforeach
                        @endif
                    </table>
                </div>
                <div class="col-md-3">
                    <table class="table table-bordered table-hover">
                        <tr>
                            <th>标签名称</th>
                            <th>用户数</th>
                        </tr>
                        @if(!empty($tags_list))
                            @foreach($tags_list as $tag)
                                <tr>
                                    <td><a href="{{ action('Admin\UsersController@getIndex').'?'.http_build_query(array_merge(array_except($condition,['page']),['tag_id' => $tag->id])) }}"><span class="badge bg-green">{{ $tag->name }}</span></a></td>
                                    <td>{{ $tag->count }}</td>
                                </tr>
                            @endforeach    
                        @endif
                    </table>
                </div>
                </div>

                <div class="box-footer clearfix">
                    <ul class="pagination pagination-sm no-margin pull-right">
                        {!! $list->appends($condition)->links() !!}
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection