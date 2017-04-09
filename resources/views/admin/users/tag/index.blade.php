@extends('admin.layout.body')

@section('title', '标签列表')

@section('body')
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">标签列表</h3>
                </div>

                <div class="box-header with-border">
                    <a href="#" data-text="添加标签" data-url="{{ action('Admin\UsersController@postStoreTag') }}" class="change_add"><button class="btn btn-success btn-flat">+ 添加标签</button></a>
                </div>

                <div class="box-body">
                    <table class="table table-bordered table-hover">
                        <tr>
                            <th>ID</th>
                            <th>标签名称</th>
                            <th>用户数量</th>
                            <th>操作</th>
                        </tr>
                        @if(!empty($list))
                            @foreach($list as $item)
                                <tr class="delete_{{ $item->id }}">
                                    <td>{{ $item->id }}</td>
                                    <td class="update_{{ $item->id }}">{{ $item->name }}</td>
                                    <td>{{ $item->count }}</td>
                                    <td>
                                        <a href="#" data-update_class="update_{{ $item->id }}" data-text="确定修改【{{ $item->name }}】标签？" data-url="{{ action('Admin\UsersController@postUpdateTag') }}?tag_id={{ $item->id }}" class="change_update">编辑</a> |
                                        <a  href="#" data-delete_class="delete_{{ $item->id }}" data-text="确定删除【{{ $item->name }}】标签？" data-url="{{ action('Admin\UsersController@postDeleteTag') }}?tag_id={{ $item->id }}&wechat_tag_id={{ $item->tag_id }}" class="change_delete">删除</a>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection