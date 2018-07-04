<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:83:"/home/wwwroot/yingshi.oyaoyin.com/public/../application/admin/view/videos/edit.html";i:1527503734;s:86:"/home/wwwroot/yingshi.oyaoyin.com/public/../application/admin/view/layout/default.html";i:1515575204;s:83:"/home/wwwroot/yingshi.oyaoyin.com/public/../application/admin/view/common/meta.html";i:1527563835;s:85:"/home/wwwroot/yingshi.oyaoyin.com/public/../application/admin/view/common/script.html";i:1527563882;}*/ ?>
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
                                <form id="edit-form" class="form-horizontal" role="form" data-toggle="validator" method="POST" action="">
    
    <div class="form-group">
        <label for="c-answer" class="control-label col-xs-12 col-sm-2"><?php echo __('name'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-answer" class="form-control" name="row[name]" type="text" value="<?php echo $row['name']; ?>" >
        </div>
    </div>

    <div class="form-group">
        <label for="pid" class="control-label col-xs-12 col-sm-2"><?php echo __('Parent'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <!-- <?php echo build_select('row[pid]', $list, $row['pid'], ['class'=>'form-control selectpicker', 'required'=>'']); ?> -->
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
        <label for="c-answer" class="control-label col-xs-12 col-sm-2"><?php echo __('价格'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-answer" class="form-control" name="row[price]" type="text" value="<?php echo $row['price']; ?>" >
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

<link rel="stylesheet" href="__CDN__/assets/js/kindeditor/themes/default/default.css" />
<script charset="utf-8" src="__CDN__/assets/js/kindeditor/jquery-1.7.1.min.js"></script>
<script charset="utf-8" src="__CDN__/assets/js/kindeditor/kindeditor-min.js"></script>
<script charset="utf-8" src="__CDN__/assets/js/kindeditor/lang/zh_CN.js"></script>
<script>
    var editor;
    KindEditor.ready(function(K) {
        editor = KindEditor.create('#ueditors',{allowImageUpload:true,resizeType : 1,width:"100%",height:"360px"});

    });
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