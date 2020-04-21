window.UMEDITOR_HOME_URL = Config.__CDN__ + "/assets/addons/umeditor/";
require.config({
    paths: {
        'umeditor': '../addons/umeditor/umeditor',
        'umeditor.config': '../addons/umeditor/umeditor.config',
        'umeditor.lang': '../addons/umeditor/lang/zh-cn/zh-cn',
    },
    shim: {
        'umeditor': {
            deps: [
                'umeditor.config',
                'css!../addons/umeditor/themes/default/css/umeditor.min.css'
            ],
            exports: 'UM',
        },
        'umeditor.lang': ['umeditor']
    }
});

require(['form', 'upload'], function (Form, Upload) {
    //监听上传文本框的事件
    $(document).on("edui.file.change", ".edui-image-file", function (e, up, me, input, callback) {
        for (var i = 0; i < this.files.length; i++) {
            Upload.api.send(this.files[i], function (data) {
                var url = data.url;
                me.uploadComplete(JSON.stringify({url: url, state: "SUCCESS"}));
            });
        }
        up.updateInput(input);
        me.toggleMask("Loading....");
        callback && callback();
    });
    var _bindevent = Form.events.bindevent;
    Form.events.bindevent = function (form) {
        _bindevent.apply(this, [form]);
        require(['umeditor', 'umeditor.lang'], function (UME, undefined) {

            //重写编辑器加载
            UME.plugins['autoupload'] = function () {
                var me = this;
                me.setOpt('pasteImageEnabled', true);
                me.setOpt('dropFileEnabled', true);
                /* var sendAndInsertImage = function (file, editor) {
                     try {
                         Upload.api.send(file, function (data) {
                             //var url = Fast.api.cdnurl(data.url, true); //加域名
                             var url = Fast.api.cdnurl(data.url); //不加域名
                             url.replace(url,"")
                             //console.log(url);
                             editor.execCommand('insertimage', {
                                 src: url,
                                 _src: url
                             });
                         });
                     } catch (er) {
                     }
                };*/

                function getPasteImage(e) {
                    return e.clipboardData && e.clipboardData.items && e.clipboardData.items.length == 1 && /^image\//.test(e.clipboardData.items[0].type) ? e.clipboardData.items : null;
                }

                function getDropImage(e) {
                    return e.dataTransfer && e.dataTransfer.files ? e.dataTransfer.files : null;
                }

                me.addListener('ready', function () {
                    if (window.FormData && window.FileReader) {
                        var autoUploadHandler = function (e) {
                            var hasImg = false,
                                items;
                            //获取粘贴板文件列表或者拖放文件列表
                            items = e.type == 'paste' ? getPasteImage(e.originalEvent) : getDropImage(e.originalEvent);
                            if (items) {
                                var len = items.length,
                                    file;
                                while (len--) {
                                    file = items[len];
                                    if (file.getAsFile)
                                        file = file.getAsFile();
                                    if (file && file.size > 0 && /image\/\w+/i.test(file.type)) {
                                        sendAndInsertImage(file, me);
                                        hasImg = true;
                                    }
                                }
                                if (hasImg)
                                    return false;
                            }

                        };
                        me.getOpt('pasteImageEnabled') && me.$body.on('paste', autoUploadHandler);
                        me.getOpt('dropFileEnabled') && me.$body.on('drop', autoUploadHandler);

                        //取消拖放图片时出现的文字光标位置提示
                        me.$body.on('dragover', function (e) {
                            if (e.originalEvent.dataTransfer.types[0] == 'Files') {
                                return false;
                            }
                        });
                    }
                });

            };
            $(".editor", form).each(function () {
                var id = $(this).attr("id");
                $(this).removeClass('form-control');
                UME.list[id] = UME.getEditor(id, {
                    serverUrl: Fast.api.fixurl('/addons/umeditor/api/'),
                    initialFrameWidth: '100%',
                    zIndex: 90,
                    xssFilterRules: false,
                    outputXssFilter: false,
                    inputXssFilter: false,
                    autoFloatEnabled: false,
                    imageUrl: '',
                    imagePath: Config.upload.cdnurl
                });
            });
        });
    }
});
