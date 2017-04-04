$('.getWechaQrCode').click(function () {
    var data = $(this).data();
    swal({
        title: '<img src="http://open.weixin.qq.com/qr/code/?username=' + data.wechat_id + '" width="360" />',
        text: "点击鼠标右键，选择图片另存为,进行保存二维码",
        html:true
    });
});