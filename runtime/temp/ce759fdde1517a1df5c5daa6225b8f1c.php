<?php if (!defined('THINK_PATH')) exit(); /*a:7:{s:70:"D:\phpStudy\WWW\fire\public/../application/admin\view\videos\edit.html";i:1529650708;s:73:"D:\phpStudy\WWW\fire\public/../application/admin\view\layout\default.html";i:1515575204;s:70:"D:\phpStudy\WWW\fire\public/../application/admin\view\common\meta.html";i:1527563835;s:45:"../application/admin/view/public/upvideo.html";i:1530683938;s:41:"../application/admin/view/public/fee.html";i:1529650325;s:44:"../application/admin/view/public/artist.html";i:1529739022;s:72:"D:\phpStudy\WWW\fire\public/../application/admin\view\common\script.html";i:1527563882;}*/ ?>
<!DOCTYPE html>
<html lang="<?php echo $config['language']; ?>">
    <head>
        <meta charset="utf-8">
<title><?php echo (isset($title) && ($title !== '')?$title:''); ?></title>
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
<meta name="renderer" content="webkit">

<link rel="shortcut icon" href="__CDN__/assets/img/favicon.ico" />
<!-- Loading Bootstrap -->
<link href="__CDN__/assets/css/backend<?php echo \think\Config::get('app_debug')?'':'.min'; ?>.css?v=<?php echo \think\Config::get('site.version'); ?>" rel="stylesheet">

<!-- HTML5 shim, for IE6-8 support of HTML5 elements. All other JS at the end of file. -->
<!--[if lt IE 9]>
  <script src="__CDN__/assets/js/html5shiv.js"></script>
  <script src="__CDN__/assets/js/respond.min.js"></script>
<![endif]-->
<script type="text/javascript">
    var require = {
        config:  <?php echo json_encode($config); ?>
    };
</script>
    </head>

    <body class="inside-header inside-aside <?php echo defined('IS_DIALOG') && IS_DIALOG ? 'is-dialog' : ''; ?>">
        <div id="main" role="main">
            <div class="tab-content tab-addtabs">
                <div id="content">
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <section class="content-header hide">
                                <h1>
                                    <?php echo __('Dashboard'); ?>
                                    <small><?php echo __('Control panel'); ?></small>
                                </h1>
                            </section>
                            <?php if(!IS_DIALOG): ?>
                            <!-- RIBBON -->
                            <div id="ribbon">
                                <ol class="breadcrumb pull-left">
                                    <li><a href="dashboard" class="addtabsit"><i class="fa fa-dashboard"></i> <?php echo __('Dashboard'); ?></a></li>
                                </ol>
                                <ol class="breadcrumb pull-right">
                                    <?php foreach($breadcrumb as $vo): ?>
                                    <li><a href="javascript:;" data-url="<?php echo $vo['url']; ?>"><?php echo $vo['title']; ?></a></li>
                                    <?php endforeach; ?>
                                </ol>
                            </div>
                            <!-- END RIBBON -->
                            <?php endif; ?>
                            <div class="content">
                                
<div class="form-group">
    <label for="c-answer" class="control-label col-xs-12 col-sm-2" style="text-align: right;"><?php echo __('上传视频'); ?>:</label>
    <div class="col-xs-12 col-sm-8">
        
        <form id="sc" action="/admin/ajax/upvideo" method="post" enctype="multipart/form-data" target="shangchuan" >
            <div id="yl">
                <span class="upspan" >上传视频</span>
                <input type="file" name="file" id="file" onchange="document.getElementById('sc').submit()" />  <!-- 当点击上传就会提交表单内容 -->
                <img src="<?php if($row['video'] != ""): ?>/assets/img/videos.jpg<?php endif; ?>" alt="" id="videotp" ><span id="explain" style="padding: 10px;color: green;display: none;">上传成功^_^</span>
            </div>
        </form>
        <iframe style="display:none" name="shangchuan" id="shangchuan"></iframe>
    </div>
</div>



<style>
.upspan{position: absolute;color: #fff;background-color: #18bc9c;width: 75px; height: 30px;line-height: 30px;text-align: center;border-radius: 5px;left: 10px;}
#fileupload{opacity: 0;}
.vids{width: 118px;height: 118px;margin-top: 16px;}
.vids img{width: 118px;}
#yl{ height:30px;margin-bottom: 20px;}  /*定义上传文件外层div样式 ，默认背景图片，大小*/
#file{width: 70px;float: left; opacity: 0; /* z-index: 9; */position: relative;left: -5px;height: 30px;cursor: pointer;}    /*定义上传文件的标签本身样式，标签的大小和外层div一致*/
#videotp{width: 30px;margin-left: 20px;}
</style>

<script charset="utf-8" src="__CDN__/assets/js/kindeditor/jquery-1.7.1.min.js"></script>
<link rel="stylesheet" href="__CDN__/assets/js/kindeditor/themes/default/default.css" />
<script charset="utf-8" src="__CDN__/assets/js/kindeditor/kindeditor-all.js"></script>
<script charset="utf-8" src="__CDN__/assets/js/kindeditor/lang/zh_CN.js"></script>

<script>
    var editor;
    KindEditor.ready(function(K) {
        editor = KindEditor.create('#ueditors',{allowImageUpload:true,resizeType : 1,width:"100%",height:"360px"});

    });
    //回调函数,调用该方法传一个文件路径，该变背景图
function showimg(url)
{
    img = '__CDN__/assets/img/videos.jpg';
    $("#videotp").attr('src',img); 
    $("#videos").val(url)
    $("#videotp").css("width","30px");
    $("#explain").show();
}
</script>

<form id="add-form" class="form-horizontal form-ajax" role="form" data-toggle="validator" method="POST" action="" style="float: left;">
    
    <input type="hidden" name="row[video]" id="videos" value="<?php echo $row['video']; ?>">
    <div class="form-group">
        <label for="c-answer" class="control-label col-xs-12 col-sm-2"><?php echo __('name'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-answer" class="form-control" name="row[name]" type="text" value="<?php echo $row['name']; ?>" >
        </div>
    </div>

    <div class="form-group">
        <label for="pid" class="control-label col-xs-12 col-sm-2"><?php echo __('Parent'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <select name="row[pid]" id=""  class="form-control">
                <option <?php if($row['pid'] == 0): ?>selected<?php endif; ?> value="0">无</option>
                <?php if(is_array($list) || $list instanceof \think\Collection || $list instanceof \think\Paginator): if( count($list)==0 ) : echo "" ;else: foreach($list as $key=>$vo): ?>
                    <option <?php if($row['pid'] == $vo['id']): ?>selected<?php endif; ?> value="<?php echo $vo['id']; ?>"><?php echo $vo['title']; ?></option>
                <?php endforeach; endif; else: echo "" ;endif; ?>
            </select>
        </div>
    </div>
    
    <div class="form-group">
        <label for="c-answer" class="control-label col-xs-12 col-sm-2"><?php echo __('上传者'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-answer" class="form-control" name="row[author]" type="text" value="<?php echo $row['author']; ?>" >
        </div>
    </div>

    <div class="form-group">
        <label for="c-answer" class="control-label col-xs-12 col-sm-2"><?php echo __('主演'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-answer" class="form-control" name="row[star]" type="text" value="<?php echo $row['star']; ?>" >
        </div>
    </div>

    
<div class="form-group">
    <label for="content" class="control-label col-xs-12 col-sm-2"><?php echo __('是否收费'); ?>:</label>
    <div class="col-xs-12 col-sm-8">
        <label for="row[is_fee]-normal"><input class="is_fee-normal" name="row[is_fee]" <?php if($row['is_fee'] == '1'): ?>checked<?php endif; ?> type="radio" value="1"> 是</label>
        <label for="row[is_fee]-normal"><input class="is_fee-normal" name="row[is_fee]" <?php if($row['is_fee'] == '0'): ?>checked<?php endif; ?> type="radio" value="0"> 否</label>
    </div>
</div>
<div class="form-group" id="prices" style="<?php if($row['is_fee'] == '0'): ?>display: none;<?php endif; ?>">
    <label for="price" class="control-label col-xs-12 col-sm-2"><?php echo __('收费价格'); ?>:</label>
    <div class="col-xs-12 col-sm-8">
        <input type="text" class="form-control" id="price" name="row[price]" value="<?php echo $row['price']; ?>"/>
    </div>
</div>
<script>

$(function(){
    $('.is_fee-normal').change(function(){
        var fee = $(this).val();
        if(fee == 1){
            $('#prices').show()
        }
        if(fee == 0){
            $('#prices').hide()
        }
    });
})
</script>

    <div class="form-group">
        <label for="c-image" class="control-label col-xs-12 col-sm-2"><?php echo __('Image'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <div class="input-group">
                <input id="c-image" class="form-control" size="50" name="row[thumb]" type="text" value="<?php echo $row['thumb']; ?>">
                <div class="input-group-addon no-border no-padding">
                    <span><button type="button" id="plupload-image" class="btn btn-danger plupload" data-input-id="c-image" data-mimetype="image/gif,image/jpeg,image/png,image/jpg,image/bmp" data-multiple="false" data-preview-id="p-image"><i class="fa fa-upload"></i> <?php echo __('Upload'); ?></button></span>
                    <span><button type="button" id="fachoose-image" class="btn btn-primary fachoose" data-input-id="c-image" data-mimetype="image/*" data-multiple="false"><i class="fa fa-list"></i> <?php echo __('Choose'); ?></button></span>
                </div>
                <span class="msg-box n-right" for="c-image"></span>
            </div>
            <ul class="row list-inline plupload-preview" id="p-image"></ul>
        </div>
    </div>

    <div class="form-group">
    <label for="c-answer" class="control-label col-xs-12 col-sm-2"><?php echo __('选择艺人'); ?>:</label>
    <div class="col-xs-12 col-sm-8">
        <div class="col-xs-12 col-sm-8">
            <a href="javascript:void(0);" class="btn btn-success btn-embossed" onclick ="artist()" ><?php echo __('选择艺人'); ?></a>
        </div>

        <div class="col-xs-12 col-sm-12 show_artist" >
            <?php if($artists): if(is_array($artists) || $artists instanceof \think\Collection || $artists instanceof \think\Paginator): $i = 0; $__LIST__ = $artists;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                        <div class="remove_artist_<?php echo $vo['userid']; ?> remove_artist"> <a href="javascript:void(0);" onclick="close_artist_img('<?php echo $vo['userid']; ?>')">X</a><input type="hidden" name="user[userid][]" value="<?php echo $vo['userid']; ?>"/> <img src="<?php echo $vo['head']; ?>"><span><?php echo $vo['normal_name']; ?></span><input class="form-control cosplay" name="user[cosplay][]" placeholder="扮演角色" value="<?php echo $vo['cosplay']; ?>"></div>
                <?php endforeach; endif; else: echo "" ;endif; endif; ?>
        </div>
        <div class="artist" style="display: none;">
            <div class="col-xs-12 col-sm-8 box">
            <a href="javascript:void(0);" onclick ="close_artist()" class="close_artist">X</a>
                <div class="col-xs-12" style="margin-top: 10px;">
                    <label for="c-analysis" class="control-label col-xs-12 col-sm-1"><?php echo __('姓名'); ?>:</label>
                    <div class="col-xs-12 col-sm-2">
                        <input class="form-control normal_name" name="data[normal_name]" type="text" value="" >
                    </div>
                    <label for="c-sex" class="control-label col-xs-12 col-sm-1">性别:</label>
                    <div class="col-xs-12 col-sm-2">
                        <select name="data[sex]" id="sex" class="form-control">
                            <option value="1">男</option>
                            <option value="0">女</option>
                        </select>
                    </div>
                    <div class="col-xs-12 col-sm-2">
                        <a href="javascript:void(0);" class="btn btn-success btn-embossed" onclick ="search_artist()" ><?php echo __('搜索'); ?></a>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-10 tables" >
                    <table id="table" class="table table-striped table-bordered table-hover artist_tab" data-operate-edit="1" data-operate-del="1" width="100%">
                        <thead>
                            <tr data-index="0">
                                <th width="20"></th>
                                <th>头像</th>
                                <th>姓名</th>
                                <th>性别</th>
                                <th>年龄</th>
                            </tr>
                        </thead>
                    </table>
                </div>
                <div class="col-xs-12 col-sm-2">
                    <a href="javascript:void(0);" class="btn btn-success btn-embossed" onclick ="sure_artist()" ><?php echo __('确定'); ?></a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .artist{width: 96.3%;height: 386px;background: #f4f4f4;position: absolute;z-index: 999;border: 1px solid #f4f4f4;margin-top: 36px;}
    .artist .close_artist{position: absolute;right: 10px;top: 10px;font-weight: 700;}
    .artist .box{width: 100%;height: 100%;border-top: 1px solid #d2d6de;}
    .tables{width: 100%;height: 70%;margin-top: 2%;overflow: auto;}
    .tables table th,td{text-align: center;}
    .tables table img{width: 36px;}
    .show_artist{margin-top: 10px; width: 100%;}
    .remove_artist{height:158px;width:106px;float:left;margin-right:10px;margin-top:10px;border:1px solid #cccccc;position:relative;padding:2px;}
    .remove_artist img{width:100px;height:100px}
    .remove_artist span{display:inherit;text-align:center;margin-top:5px}
    .remove_artist a{position:absolute;right:2px;color:red;font-weight: 700;font-size: 16px;}
    .cosplay{border-radius:5px;border-color:#18bc9c;text-align: center;})
</style>
<script>
function artist(){
    $('.artist').show();
    $('.subms').addClass('disabled');
}
function close_artist(){
    $('.artist').hide();
    $('.subms').removeClass('disabled');
}

function search_artist(){
    $('.removes').remove();
    var normal_name = $('.normal_name').val();
    var sex = $('#sex').val();
    var cid = $('#cid').val();
    //构造ajax、
    $.ajax({
        url:"/admin/health/search_artist/cid/"+cid,
        type:'post',
        // data:{'name名':val值}
        data:{'normal_name':normal_name,'sex':sex},
        dataType:'json',
        success:function(data){
          if(data){
            var len = data.length;
            var artist_tab = $('.artist_tab');
            for(var i = 0; i < len; i++){
                artist_tab.append('<tr class="removes" data-index="0"><td><input type="checkbox" name="checkbox[]" value="'+data[i].id+'"  ><input type="hidden" value="'+data[i].id+'"  name="ids[]" class="ids_'+data[i].id+'"/></td><td ><img class="img_'+data[i].id+'" src="'+data[i].head+'" /></td><td >'+data[i].normal_name+'</td><td class="name_'+data[i].id+'">'+data[i].normal_name+'</td><td >'+data[i].normal_name+'</td></tr>');
            }
          }else{
            alert('数据读取失败');
          }
        }
    })
}

//
function sure_artist(){
    // $('.remove_artist').remove();
    var chk_values =[];    
    $('input[name="checkbox[]"]:checked').each(function(){    
        chk_values.push($(this).val());    
    });
    if(chk_values.length==0){
        alert('你还没有选择任何内容！');
        return false;
    }else{
        var str = new Array(); //定义一数组 
        str = chk_values.toString().split(","); //字符分割 
        for (i=0;i<str.length ;i++ ) { 
            ids = $(".ids_"+str[i]+"").val();
            img = $(".img_"+str[i]+"").attr('src');
            name = $(".name_"+str[i]+"").text();
            var html = '<div class="remove_artist_'+ids+'"> <a href="javascript:void(0);" onclick="close_artist_img('+ids+')">X</a><input type="hidden" name="user[userid][]" value="'+ids+'"/> <img src="'+img+'"><span>'+name+'</span><input class="form-control cosplay" name="user[cosplay][]" placeholder="扮演角色"></div>';
            $(".show_artist").append(html);
            $('.artist').hide();
            $('.subms').removeClass('disabled');
            $('.remove_artist_'+ids+'').css({'height':'158px','width':'106px','float':'left','margin-right':'10px','margin-top':'10px','border':'1px solid #cccccc','position':'relative','padding':'2px'});
            $('.remove_artist_'+ids+' img').css({'width':'100px','height':'100px'});
            $('.remove_artist_'+ids+' span').css({'display':'inherit','text-align':'center','margin-top':'5px'});
            $('.remove_artist_'+ids+' a').css({'position':'absolute','right':'2px','color':'red','font-weight':'700','font-size':'16px'});
            $('.cosplay').css({'border-radius':'5px','border-color':'#18bc9c','text-align':'center'})
        } 
    }
}
function close_artist_img(ids){
    $(".remove_artist_"+ids+"").remove();
}
</script>

    <div class="form-group">
        <label for="c-description" class="control-label col-xs-12 col-sm-2"><?php echo __('简介'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <textarea id="c-description" class="form-control summernote editor" rows="5" name="row[description]" cols="50"><?php echo $row['description']; ?></textarea>
        </div>
    </div>

    <div class="form-group">
        <label for="c-analysis" class="control-label col-xs-12 col-sm-2"><?php echo __('详情'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <!-- <textarea id="c-analysis" class="form-control summernote edit" rows="5" name="row[analysis]" cols="50"></textarea> -->
            <textarea name="row[content]" id="ueditors" cols="30" rows="10"><?php echo $row['content']; ?></textarea>
        </div>
    </div>

    <div class="form-group">
        <label for="content" class="control-label col-xs-12 col-sm-2"><?php echo __('Status'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <label for="row[status]-normal"><input id="row[status]-normal" name="row[status]" <?php if($row['status'] == '1'): ?>checked<?php endif; ?> type="radio" value="1"> 正常</label>
            <label for="row[status]-normal"><input id="row[status]-normal" name="row[status]" <?php if($row['status'] == '0'): ?>checked<?php endif; ?> type="radio" value="0"> 隐藏</label>
        </div>
    </div>

    <div class="form-group layer-footer">
        <label class="control-label col-xs-12 col-sm-2"></label>
        <div class="col-xs-12 col-sm-8">
            <button type="submit" class="btn btn-success btn-embossed disabled"><?php echo __('OK'); ?></button>
            <button type="reset" class="btn btn-default btn-embossed"><?php echo __('Reset'); ?></button>
        </div>
    </div>
</form>



                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script src="__CDN__/assets/js/require.js" data-main="__CDN__/assets/js/require-backend<?php echo \think\Config::get('app_debug')?'':'.min'; ?>.js?v=<?php echo $site['version']; ?>"></script>
    </body>
</html>