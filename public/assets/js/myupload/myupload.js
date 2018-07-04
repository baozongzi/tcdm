$(function () {
	var bar = $('.bar');
	var percent = $('.percent');
	var showimg = $('#showimg');
	var progress = $(".progress");
	var files = $(".files");
	var btn = $(".btnup span");
	var thumbtype = $("#thumbtype");
	var cutthumb = $("#cutthumb");
	var cutpics = $("cutpics");
	$("#fileupload").wrap("<form id='myupload' action='admin.php/ajax/upload' method='post' enctype='multipart/form-data'></form>");
    $("#fileupload").change(function(){
	    $("#myupload").ajaxSubmit({
	      	dataType:  'json',
	      	beforeSend: function() {
	            showimg.empty();
	            progress.show();
	            var percentVal = '0%';
	            bar.width(percentVal);
	            percent.html(percentVal);
	            // btn.html("上传中...");
	        },
	      	uploadProgress: function(event, position, total, percentComplete) {
	            var percentVal = percentComplete + '%';
	            bar.width(percentVal);
	            percent.html(percentVal);
	        },
		    success: function(data) {
		        // files.html("<b>"+data.name+"("+data.size+"k)</b>");
		        var img = data.imgpath;
		        showimg.html("<img src='"+img+"'>");
		        thumbtype.val(""+img+"");
		        // alert("上传成功");
		    },
	      	error:function(xhr){
		        btn.html("上传失败");
		        bar.width('0')
		        files.html(xhr.responseText);
		    }
	    });
	});
});