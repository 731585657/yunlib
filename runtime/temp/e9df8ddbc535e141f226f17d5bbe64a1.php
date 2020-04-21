<?php if (!defined('THINK_PATH')) exit(); /*a:5:{s:82:"D:\phpStudy\WWW\Wangzhan\yunlibx\public/../application/admin\view\product\add.html";i:1583976017;s:75:"D:\phpStudy\WWW\Wangzhan\yunlibx\application\admin\view\layout\default.html";i:1576638344;s:72:"D:\phpStudy\WWW\Wangzhan\yunlibx\application\admin\view\common\meta.html";i:1576638344;s:74:"D:\phpStudy\WWW\Wangzhan\yunlibx\application\admin\view\auth\rule\tpl.html";i:1576638344;s:74:"D:\phpStudy\WWW\Wangzhan\yunlibx\application\admin\view\common\script.html";i:1576638344;}*/ ?>
<!DOCTYPE html>
<html lang="<?php echo $config['language']; ?>">
    <head>
        <meta charset="utf-8">
<title><?php echo (isset($title) && ($title !== '')?$title:''); ?></title>
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
<meta name="renderer" content="webkit">

<link rel="shortcut icon" href="/assets/img/favicon.ico" />
<!-- Loading Bootstrap -->
<link href="/assets/css/backend<?php echo \think\Config::get('app_debug')?'':'.min'; ?>.css?v=<?php echo \think\Config::get('site.version'); ?>" rel="stylesheet">

<!-- HTML5 shim, for IE6-8 support of HTML5 elements. All other JS at the end of file. -->
<!--[if lt IE 9]>
  <script src="/assets/js/html5shiv.js"></script>
  <script src="/assets/js/respond.min.js"></script>
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
                            <?php if(!IS_DIALOG && !$config['fastadmin']['multiplenav']): ?>
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
    <?php echo token(); ?>

    <div class="form-group">
        <label for="name" class="control-label col-xs-12 col-sm-2">产品名称:</label>
        <div class="col-xs-12 col-sm-8">
            <input type="text" class="form-control" id="title" name="row[title]"  value="" data-rule="required" />
        </div>
    </div>
<!--    <div class="form-group">-->
<!--        <label class="control-label col-xs-12 col-sm-2"><?php echo __('Title'); ?>:</label>-->
<!--        <div class="col-xs-12 col-sm-8">-->
<!--            <input type="text" class="form-control" id="title" name="row[title]" value="" data-rule="required" />-->
<!--        </div>-->
<!--    </div>-->
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2">封面图:</label>
        <div class="col-xs-12 col-sm-8">
            <div class="input-group">
                <input id="c-image" data-rule="required" class="form-control" size="50" name="row[image]" type="text" value="">
                <div class="input-group-addon no-border no-padding">
                    <button type="button" id="plupload-data" class="btn btn-danger plupload cropper" data-aspect-ratio="0.75" data-auto-crop-area="50%" data-cropped-width="300" data-cropped-height="300" data-input-id="c-image" data-mimetype="image/gif,image/jpeg,image/png,image/jpg,image/bmp" data-multiple="false" data-min-crop-box-width="0" data-min-crop-box-height="0" data-preview-id="p-image"><i class="fa fa-upload"></i> Upload</button>
                    <span><button type="button" id="fachoose-image" class="btn btn-primary fachoose" data-input-id="c-image" data-mimetype="image/*" data-multiple="false"><i class="fa fa-list"></i> Choose</button></span>
                </div>
                <span class="msg-box n-right" for="c-image"></span>
            </div>
            <ul class="row list-inline plupload-preview" id="p-image"></ul>
        </div>
    </div>
    <!--  多图上传功能
        <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2">多图上传:</label>
        <div class="col-xs-12 col-sm-8">
            <div class="input-group">
                <input id="c-images" data-rule="required" class="form-control" size="50" name="row[images]" type="text" value="">
                <div class="input-group-addon no-border no-padding">
                    <span><button type="button" id="plupload-images" class="btn btn-danger plupload" data-input-id="c-images" data-mimetype="image/gif,image/jpeg,image/png,image/jpg,image/bmp" data-multiple="true" data-preview-id="p-images"><i class="fa fa-upload"></i> <?php echo __('Upload'); ?></button></span>
                    <span><button type="button" id="fachoose-images" class="btn btn-primary fachoose" data-input-id="c-images" data-mimetype="image/*" data-multiple="true"><i class="fa fa-list"></i> <?php echo __('Choose'); ?></button></span>
                </div>
                <span class="msg-box n-right" for="c-images"></span>
            </div>
            <ul class="row list-inline plupload-preview" id="p-images"></ul>
        </div>
    </div>-->

    <!-- 视频上传功能
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('Vediofile'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <div class="input-group">
                <input id="c-vediofile" class="form-control" size="50" name="row[vediofile]" type="text">
                <div class="input-group-addon no-border no-padding">
                    <span><button type="button" id="plupload-vediofile" class="btn btn-danger plupload" data-input-id="c-vediofile" data-mimetype="mp4,mp3,avi,flv,wmv" data-multiple="false" data-maxsize="100M" data-preview-id="p-mp4"><i class="fa fa-upload"></i> <?php echo __('Upload'); ?></button></span>
                    <span><button type="button" id="fachoose-vediofile" class="btn btn-primary fachoose" data-input-id="c-vediofile" data-mimetype="mp4,mp3,avi,flv,wmv" data-multiple="false"><i class="fa fa-list"></i> <?php echo __('Choose'); ?></button></span>
                </div>
                <span class="msg-box n-right" for="c-vediofile"></span>
            </div>
            <ul class="row list-inline plupload-preview" id="p-mp4"></ul>
        </div>
    </div>
-->


    <div class="form-group">
        <label for="weigh" class="control-label col-xs-12 col-sm-2">产品分类:</label>
            <div class="col-xs-12 col-sm-8">
                <select class="form-control selectpicker" name="row[t_id]">
                    <?php if(is_array($type) || $type instanceof \think\Collection || $type instanceof \think\Paginator): $i = 0; $__LIST__ = $type;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?>
                        <option value="<?php echo $v['t_id']; ?>"><?php echo $v['title']; ?></option>
                    <?php endforeach; endif; else: echo "" ;endif; ?>
                </select>
            </div>
    </div>

    <div class="form-group">
        <label for="weigh" class="control-label col-xs-12 col-sm-2">产品型号:</label>
        <div class="col-xs-12 col-sm-8">
            <input type="text" class="form-control" id="prod_type" name="row[prod_type]" value="" placeholder="多个型号以 , 分隔,没有可以不填写" />
        </div>
    </div>

    <div class="form-group">
        <label for="weigh" class="control-label col-xs-12 col-sm-2">上市日期:</label>
        <div class="col-xs-12 col-sm-8">
            <input type="text" class="form-control datetimepicker" data-date-format=" YYYY-MM-DD" data-date-use-strict="true" id="release_time" name="row[release_time]" value="" data-rule="required" />
        </div>
    </div>
    <div class="form-group">
        <label for="weigh" class="control-label col-xs-12 col-sm-2">排序:</label>
        <div class="col-xs-12 col-sm-8">
            <input type="text" class="form-control" id="weigh" name="row[weigh]" value="<?php echo $max; ?>" data-rule="required" />
        </div>
    </div>
    <div class="form-group">
        <label for="remark" class="control-label col-xs-12 col-sm-2">内容:</label>
        <div class="col-xs-12 col-sm-8">
            <textarea class="form-control editor" id="condition" name="row[content]"></textarea>
        </div>
    </div>
    <div class="form-group">
        <label for="remark" class="control-label col-xs-12 col-sm-2">产品简介:</label>
        <div class="col-xs-12 col-sm-8">
            <textarea class="form-control" id="remark" name="row[intro]"></textarea>
        </div>
    </div>

    <div class="form-group">
        <label for="weigh" class="control-label col-xs-12 col-sm-2">价格:</label>
        <div class="col-xs-12 col-sm-8">
            <input type="text" class="form-control" id="price" name="row[price]" value="0.00" data-rule="required" />
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2">首页推荐:</label>
        <div class="col-xs-12 col-sm-8">
            <?php echo build_radios('row[is_push]', ['0'=>__('否'),'1'=>__('是')]); ?>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('Status'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <?php echo build_radios('row[status]', ['1'=>__('Normal'), '0'=>__('Hidden')]); ?>
        </div>
    </div>

    <div class="form-group hidden layer-footer">
        <div class="col-xs-2"></div>
        <div class="col-xs-12 col-sm-8">
            <button type="submit" class="btn btn-success btn-embossed disabled"><?php echo __('OK'); ?></button>
            <button type="reset" class="btn btn-default btn-embossed"><?php echo __('Reset'); ?></button>
        </div>
    </div>
</form>
<style>
    #chooseicon {
        margin:10px;
    }
    #chooseicon ul {
        margin:5px 0 0 0;
    }
    #chooseicon ul li{
        width:41px;height:42px;
        line-height:42px;
        border:1px solid #efefef;
        padding:1px;
        margin:1px;
        text-align: center;
        font-size:18px;
    }
    #chooseicon ul li:hover{
        border:1px solid #2c3e50;
        cursor:pointer;
    }
</style>
<script id="chooseicontpl" type="text/html">
    <div id="chooseicon">
        <div>
            <form onsubmit="return false;">
                <div class="input-group input-groupp-md">
                    <div class="input-group-addon"><?php echo __('Search icon'); ?></div>
                    <input class="js-icon-search form-control" type="text" placeholder="">
                </div>
            </form>
        </div>
        <div>
            <ul class="list-inline">
                <% for(var i=0; i<iconlist.length; i++){ %>
                    <li data-font="<%=iconlist[i]%>" data-toggle="tooltip" title="<%=iconlist[i]%>">
                    <i class="fa fa-<%=iconlist[i]%>"></i>
                </li>
                <% } %>
            </ul>
        </div>

    </div>
</script>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script src="/assets/js/require<?php echo \think\Config::get('app_debug')?'':'.min'; ?>.js" data-main="/assets/js/require-backend<?php echo \think\Config::get('app_debug')?'':'.min'; ?>.js?v=<?php echo htmlentities($site['version']); ?>"></script>
    </body>
</html>