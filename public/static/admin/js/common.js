$('.getWechaQrCode').click(function () {
    var data = $(this).data();
    swal({
        title: '<img src="http://open.weixin.qq.com/qr/code/?username=' + data.wechat_id + '" width="360" />',
        text: "点击鼠标右键，选择图片另存为,进行保存二维码",
        html: true
    });
    $('.sweet-alert h2').height(360);
});

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

$('.change_update').click(function () {
    var $data = $(this).data();
    swal({
            title: $data.text,
            type: "input",
            showCancelButton: true,
            cancelButtonText: '取消',
            confirmButtonText: '修改',
            closeOnConfirm: false,
            showLoaderOnConfirm: true,
            timer: 2000
        },
        function (inputValue) {
            if (inputValue === null || inputValue === false) {
                return false;
            }

            if (inputValue == "") {
                swal.showInputError('输入内容不能为空！');
                return false
            }
            $.post($data.url, {'name': inputValue}, function ($result) {
                if ($result.error_code == 0) {
                    $('.' + $data.update_class).html(inputValue);
                    swal('修改成功', '', "success");
                } else {
                    swal($result.error_msg, '', "error");
                }
            });
        });
});

$('.change_add').click(function () {
    var $data = $(this).data();
    swal({
            title: $data.text,
            type: "input",
            showCancelButton: true,
            cancelButtonText: '取消',
            confirmButtonText: '添加',
            closeOnConfirm: false,
            showLoaderOnConfirm: true,
            timer: 2000
        },
        function (inputValue) {
            if (inputValue === null || inputValue === false) {
                return false;
            }

            if (inputValue == "") {
                swal.showInputError('输入内容不能为空！');
                return false
            }
            $.post($data.url, {'name': inputValue}, function ($result) {
                if ($result.error_code == 0) {
                    $('.' + $data.update_class).html(inputValue);
                    swal('添加成功', '', "success");
                } else {
                    swal($result.error_msg, '', "error");
                }
            });
        });
});

$('.change_delete').click(function () {
    var $data = $(this).data();
    swal({
            title: $data.text,
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "删除",
            cancelButtonText: "取消",
            closeOnConfirm: false,
            closeOnCancel: false,
            showLoaderOnConfirm: true
        },
        function (isConfirm) {
            if (isConfirm) {
                $.post($data.url, null, function ($result) {
                    if ($result.error_code == 0) {
                        $('.' + $data.delete_class).remove();
                        swal("删除成功！", "", "success");
                    } else {
                        swal($result.error_msg, '', "error");
                    }
                });

            } else {
                swal("取消删除", '', "error");
            }
        });
});