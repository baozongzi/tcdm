<?php if (!defined('THINK_PATH')) exit(); /*a:5:{s:70:"D:\phpStudy\WWW\fire\public/../application/admin\view\banner\edit.html";i:1530511513;s:73:"D:\phpStudy\WWW\fire\public/../application/admin\view\layout\default.html";i:1515575204;s:70:"D:\phpStudy\WWW\fire\public/../application/admin\view\common\meta.html";i:1527563835;s:45:"../application/admin/view/banner/selects.html";i:1531537298;s:72:"D:\phpStudy\WWW\fire\public/../application/admin\view\common\script.html";i:1527563882;}*/ ?>
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
                                <form id="add-form" class="form-horizontal form-ajax" role="form" data-toggle="validator" method="POST" action="">
    
    <div class="form-group" id="title" style="<?php if($row['is_link'] == 0): ?>display:none;<?php endif; ?>">
        <label for="c-answer" class="control-label col-xs-12 col-sm-2"><?php echo __('title'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-title" class="form-control" name="row[mytitle]" type="text" value="<?php echo $row['title']; ?>" >
        </div>
    </div>

    <div class="form-group">
        <label for="content" class="control-label col-xs-12 col-sm-2"><?php echo __('是否链接'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <label for="row[is_link]-normal"><input class="is_link" name="row[is_link]" <?php if($row['is_link'] == 1): ?>checked<?php endif; ?>  type="radio" value="1"> 是</label>
            <label for="row[is_link]-normal"><input class="is_link" name="row[is_link]" <?php if($row['is_link'] == 0): ?>checked<?php endif; ?> type="radio" value="0"> 否</label>
        </div>
    </div>

    <div class="form-group" id="prices" style="<?php if($row['is_link'] == 0): ?>display:none;<?php endif; ?>">
        <label for="url" class="control-label col-xs-12 col-sm-2"><?php echo __('链接地址'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input type="text" class="form-control" id="url" name="row[url]" value="<?php echo $row['url']; ?>"/>
        </div>
    </div>

    <div class="form-group">
        <label for="c-image" class="control-label col-xs-12 col-sm-2"><?php echo __('缩略图'); ?>:</label>
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
    <label for="c-answer" class="control-label col-xs-12 col-sm-2"><?php echo __('选择栏位'); ?>:</label>
    <div class="col-xs-12 col-sm-8">

        <div class="show_catname" >
            <select  id="selects" class="form-control">
                <option  value="">无</option>
                <?php if(is_array($ruledata) || $ruledata instanceof \think\Collection || $ruledata instanceof \think\Paginator): $i = 0; $__LIST__ = $ruledata;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                    <option data="<?php echo $vo['title']; ?>" <?php if($vo['title'] == $row['catname']): ?>selected<?php endif; ?>  value="<?php echo $vo['table']; ?>"><?php echo $vo['sign']; ?><?php echo $vo['title']; ?></option>
                <?php endforeach; endif; else: echo "" ;endif; ?>
            </select>
        </div>

        <div class="show_select" >
            <table id="table" class="table table-striped table-bordered table-hover show_video" data-operate-edit="1" data-operate-del="1" width="100%">
                <tr data-index="0">
                    <th style="text-align: center;">ID</th>
                    <th style="text-align: center;">标题</th>
                    <th style="text-align: center;">所属栏目</th>
                </tr>
                <?php if($row['vid']): ?>
                    <tr class="removes" data-index="0">
                        <td><input type="hidden" value="<?php echo $row['vid']; ?>" name="row[vid]" /> <?php echo $row['vid']; ?> <input type="hidden" value="<?php echo $row['catname']; ?>" name="row[catname]" /></td> 
                        <td ><?php echo $row['title']; ?></td>
                        <td ><?php echo $row['catname']; ?><input value="<?php echo $row['table']; ?>" name="row[table]" type="hidden"></td>
                    </tr>
                <?php endif; ?>
            </table>
        </div>

        <div class="col-xs-12 col-sm-12 catname" style="display: none;overflow: auto;height: 200px;margin-bottom: 20px;">
            <div class="col-xs-12 col-sm-8 box">
            <a href="javascript:void(0);" onclick ="close_catname()" style="position: absolute;right: 10px;top: 10px;font-weight: 700;">X</a>
                
                <div class="col-xs-12 col-sm-10 tables" >
                    <table id="table" class="table table-striped table-bordered table-hover catname_tab" data-operate-edit="1" data-operate-del="1" width="100%">
                        <thead>
                            <tr data-index="0">
                                <th width="20"></th>
                                <th>id</th>
                                <th>标题</th>
                                <th>所属栏目</th>
                            </tr>
                        </thead>
                    </table>
                </div>
                <div class="col-xs-12 col-sm-2">
                    <a href="javascript:void(0);" class="btn btn-success btn-embossed" onclick ="sure_catname()" ><?php echo __('确定'); ?></a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .catname/*width: 96.3%;*/height: 386px;background: #f4f4f4;position: absolute;z-index: 999;border: 1px solid #f4f4f4;margin-top: 1%;left: 0px;
    .catname .box{width: 100%;height: 100%;border-top: 1px solid #d2d6de;}
    .tables{width: 100%;height: 70%;margin-top: 3%;overflow: auto;}
    .tables table th,td{text-align: center;}
    .tables table img{width: 36px;}
    .show_catname/*margin-top: 10px; */width: 100%;
    .remove_catname{height:158px;width:106px;float:left;margin-right:10px;margin-top:10px;border:1px solid #cccccc;position:relative;padding:2px;}
    .remove_catname img{width:100px;height:100px}
    .remove_catname span{display:inherit;text-align:center;margin-top:5px}
    .remove_catname a{position:absolute;right:2px;color:red;font-weight: 700;font-size: 16px;}
    .cosplay{border-radius:5px;border-color:#18bc9c;text-align: center;}
    .show_select{margin-top: 20px;}
    .btn{margin-bottom: 10px; }
</style>

<script charset="utf-8" src="__CDN__/assets/js/kindeditor/jquery-1.7.1.min.js"></script>
<script>

$(function(){
    $("#selects").change(function(){
        $('.catname').show();
        $('.subms').addClass('disabled');
        $('.removes').remove();
        var table = $("#selects").val();
        var table_name = $("#selects option:selected").attr("data");
        $.ajax({
            url:"/admin/banner/search_catname",
            type:'post',
            // data:{'name名':val值}
            data:{'table':table,'table_name':table_name},
            dataType:'json',
            success:function(data){
              if(data){
                var len = data.length;
                var catname_tab = $('.catname_tab');
                for(var i = 0; i < len; i++){
                    catname_tab.append('<tr class="removes" data-index="0"> <td> <input type="radio" name="radio[]" value="'+data[i].id+'"  >  <input type="hidden" value="'+data[i].id+'"  name="ids[]" class="ids_'+data[i].id+'"/>  <input type="hidden" value="'+table+'"   class="tab_'+data[i].id+'"/> </td> <td >'+data[i].id+'</td> <td class="title_'+data[i].id+'">'+data[i].title+'</td>   <td class="catname_'+data[i].id+'">'+data[i].catname+'</td> </tr>');
                }
              }else{
                alert('数据读取失败');
              }
            }
        })
    });
})
function catname(){
    $('.catname').show();
    $('.subms').addClass('disabled');
}
function close_catname(){
    $('.catname').hide();
    $('.subms').removeClass('disabled');
}

function sure_catname(){
    // $('.remove_artist').remove();
    var chk_values =[];    
    $('input[name="radio[]"]:checked').each(function(){    
        chk_values.push($(this).val());    
    });
    if(chk_values.length == 0){
        alert('你还没有选择任何内容！');
        return false;
    }else if(chk_values.length > 1){
        alert('每次只能选一个哈');
        return false;
    }else{
        var str = new Array(); //定义一数组 
        str = chk_values.toString().split(","); //字符分割 
        for (i=0;i<str.length ;i++ ) { 
            ids = $(".ids_"+str[i]+"").val();
            table = $(".tab_"+str[i]+"").val();
            title = $(".title_"+str[i]+"").text();
            catname = $(".catname_"+str[i]+"").text();
            var html = '<tr class="removes" data-index="0"><td><input type="hidden" value="'+ids+'" name="row[vid]" class="ids_'+ids+'"/> '+ids+' <input type="hidden" value="'+catname+'" name="row[catname]" class="tab_n_'+ids+'"/> <input type="hidden" value="'+title+'" name="row[title]" class="tab_n_'+ids+'"/> </td>  <td class="title_'+ids+'">'+title+'</td> <td >'+catname+'<input value="'+table+'" name="row[table]" type="hidden"></td></tr>'
            $(".show_video").append(html);
            $('.catname').hide();
            $('.subms').removeClass('disabled');
        } 
    }
}


</script>


    <div class="form-group">
        <label for="content" class="control-label col-xs-12 col-sm-2"><?php echo __('推荐首页'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <label for="row[is_index]-normal"><input class="is_index" name="row[is_index]" <?php if($row['is_index'] == 1): ?>checked<?php endif; ?> type="radio" value="1"> 是</label>
            <label for="row[is_index]-normal"><input class="is_index" name="row[is_index]" <?php if($row['is_index'] == 0): ?>checked<?php endif; ?> type="radio" value="0"> 否</label>
        </div>
    </div>

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

    <div class="form-group layer-footer">
        <label class="control-label col-xs-12 col-sm-2"></label>
        <div class="col-xs-12 col-sm-8">
            <button type="submit" class="btn btn-success btn-embossed subms"><?php echo __('OK'); ?></button>
            <button type="reset" class="btn btn-default btn-embossed"><?php echo __('Reset'); ?></button>
        </div>
    </div>

</form>


<link rel="stylesheet" href="__CDN__/assets/js/kindeditor/themes/default/default.css" />
<script charset="utf-8" src="__CDN__/assets/js/kindeditor/jquery-1.7.1.min.js"></script>
<script charset="utf-8" src="__CDN__/assets/js/kindeditor/kindeditor-min.js"></script>
<script charset="utf-8" src="__CDN__/assets/js/kindeditor/lang/zh_CN.js"></script>
<script>
    var editor;
    KindEditor.ready(function(K) {
        editor = KindEditor.create('#ueditors',{allowImageUpload:true,resizeType : 1,width:"100%",height:"360px"});
    });
    $(function(){
        $('.is_link').change(function(){
            var fee = $(this).val();
            if(fee == 1){
                $('#prices').show();
                $('#title').show()
            }
            if(fee == 0){
                $('#prices').hide();
                $('#title').hide()
            }
        });
    })
</script>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script src="__CDN__/assets/js/require.js" data-main="__CDN__/assets/js/require-backend<?php echo \think\Config::get('app_debug')?'':'.min'; ?>.js?v=<?php echo $site['version']; ?>"></script>
    </body>
</html>