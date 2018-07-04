<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:92:"/home/wwwroot/yingshi.oyaoyin.com/public/../application/admin/view/health/interview_add.html";i:1528522568;s:86:"/home/wwwroot/yingshi.oyaoyin.com/public/../application/admin/view/layout/default.html";i:1515575204;s:83:"/home/wwwroot/yingshi.oyaoyin.com/public/../application/admin/view/common/meta.html";i:1527563835;s:85:"/home/wwwroot/yingshi.oyaoyin.com/public/../application/admin/view/common/script.html";i:1527563882;}*/ ?>
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
    
    <div class="form-group">
        <label for="c-answer" class="control-label col-xs-12 col-sm-2"><?php echo __('name'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-name" class="form-control" name="row[name]" type="text" value="" >
        </div>
    </div>

    
    <div class="form-group">
        <label for="c-answer" class="control-label col-xs-12 col-sm-2"><?php echo __('上传者'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-author" class="form-control" name="row[author]" type="text" value="" >
        </div>
    </div>

    <div class="form-group">
        <label for="c-answer" class="control-label col-xs-12 col-sm-2"><?php echo __('主演'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-star" class="form-control" name="row[star]" type="text" value="" >
        </div>
    </div>

    <div class="form-group">
        <label for="c-answer" class="control-label col-xs-12 col-sm-2"><?php echo __('价格'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-answer" class="form-control" name="row[price]" type="text" value="" >
        </div>
    </div>

<!--     <div class="form-group">
        <label for="c-image" class="control-label col-xs-12 col-sm-2"><?php echo __('Image'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <div class="input-group">
                <input id="c-image" class="form-control" size="50" name="row[image]" type="text">
                <div class="input-group-addon no-border no-padding">
                    <span><button type="button" id="plupload-image" class="btn btn-danger plupload" data-input-id="c-image" data-mimetype="image/gif,image/jpeg,image/png,image/jpg,image/bmp" data-multiple="false" data-preview-id="p-image"><i class="fa fa-upload"></i> <?php echo __('Upload'); ?></button></span>
                    <span><button type="button" id="fachoose-image" class="btn btn-primary fachoose" data-input-id="c-image" data-mimetype="image/*" data-multiple="false"><i class="fa fa-list"></i> <?php echo __('Choose'); ?></button></span>
                </div>
                <span class="msg-box n-right" for="c-image"></span>
            </div>
            <ul class="row list-inline plupload-preview" id="p-image"></ul>
        </div>
    </div> -->


    <div class="form-group">
        <label for="c-answer" class="control-label col-xs-12 col-sm-2"><?php echo __('选择艺人'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <a href = "javascript:void(0);" onclick ="artist()">选择艺人</a>
            <div class="artist" style="display: block;"><form action="">
                <a href="javascript:void(0);" onclick ="close_artist()" class="close_artist">X</a>
                <div class="col-xs-12 col-sm-8 box">
                    <label for="c-analysis" class="control-label col-sm-1"><?php echo __('艺人姓名'); ?>:</label>
                    <div class="col-xs-12 col-sm-2">
                        <input class="form-control normal_name" name="data[normal_name]" type="text" value="" >
                    </div>
                    <label for="c-sex" class="control-label col-sm-1">性别:</label>
                    <div class="col-xs-12 col-sm-1">
                        <select name="data[sex]" id="sex" class="form-control">
                            <option value="1">男</option>
                            <option value="0">女</option>
                        </select>
                    </div>
                    <div class="col-xs-12 col-sm-1">
                        <a href="javascript:void(0);" class="btn btn-success btn-embossed" onclick ="search_artist()" ><?php echo __('搜索'); ?></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    

    <div class="form-group">
        <label for="c-analysis" class="control-label col-xs-12 col-sm-2"><?php echo __('详情'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <!-- <textarea id="c-analysis" class="form-control summernote edit" rows="5" name="row[analysis]" cols="50"></textarea> -->
            <textarea name="row[content]" id="ueditors" cols="30" rows="10"></textarea>
        </div>
    </div>

    <div class="form-group">
        <label for="content" class="control-label col-xs-12 col-sm-2"><?php echo __('Status'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <label for="row[status]-normal"><input name="row[status]" checked type="radio" value="1"> 正常</label>
            <label for="row[status]-normal"><input name="row[status]" type="radio" value="0"> 隐藏</label>
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

<style>
    .artist{width: 97.3%;height: 386px;background: #cccccc;position: absolute;z-index: 999;}
    .artist .close_artist{position: absolute;right: 10px;top: 10px;}
    .artist .box{background: red;width: 100%;height: 92%;margin-top: 30px;}
</style>

<link rel="stylesheet" href="__CDN__/assets/js/kindeditor/themes/default/default.css" />
<script charset="utf-8" src="__CDN__/assets/js/kindeditor/jquery-1.7.1.min.js"></script>
<script charset="utf-8" src="__CDN__/assets/js/kindeditor/kindeditor-min.js"></script>
<script charset="utf-8" src="__CDN__/assets/js/kindeditor/lang/zh_CN.js"></script>
<script>
    var editor;
    KindEditor.ready(function(K) {
        editor = KindEditor.create('#ueditors',{allowImageUpload:true,resizeType : 1,width:"100%",height:"360px"});

    });

    function artist(){
        $('.artist').show();
    }
    function close_artist(){
        $('.artist').hide();
    }

    function search_artist(){
        var normal_name = $('.normal_name').val();
        var sex = $('#sex').val();
        //构造ajax、
        $.ajax({
            url:"/admin/health/search_artist",
            type:'post',
            // data:{'name名':val值}
            data:{'normal_name':normal_name,'sex':sex},
            dataType:'json',
            success:function(data){
              if(data){
                alert('数据提交成功');
              }else{
                alert('数据提交失败');
              }
            }
        })
    }

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