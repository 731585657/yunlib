define([], function () {
    if ($('.cropper', $('form[role="form"]')).length > 0) {
    var allowAttr = [
        'aspectRatio', 'autoCropArea', 'cropBoxMovable', 'cropBoxResizable', 'minCropBoxWidth', 'minCropBoxHeight', 'minContainerWidth', 'minContainerHeight',
        'minCanvasHeight', 'minCanvasWidth', 'croppedWidth', 'croppedHeight', 'croppedMinWidth', 'croppedMinHeight', 'croppedMaxWidth', 'croppedMaxHeight', 'fillColor'
    ];
    String.prototype.toLineCase = function () {
        return this.replace(/[A-Z]/g, function (match) {
            return "-" + match.toLowerCase();
        });
    };

    var btnAttr = [];
    $.each(allowAttr, function (i, j) {
        btnAttr.push('data-' + j.toLineCase() + '="<%=data.' + j + '%>"');
    });
    var btn = '<button class="btn btn-success btn-cropper btn-xs" data-input-id="<%=data.inputId%>" ' + btnAttr.join(" ") + ' style="position:absolute;top:10px;right:15px;">裁剪</button>';
    require(['upload'], function (Upload) {
        //图片裁剪
        $(document).on('click', '.btn-cropper', function () {
            var image = $(this).closest("li").find('.thumbnail').data('url');
            var input = $("#" + $(this).data("input-id"));
            var url = image;
            var data = $(this).data();
            var params = [];
            $.each(allowAttr, function (i, j) {
                if (typeof data[j] !== 'undefined' && data[j] !== '') {
                    params.push(j + '=' + data[j]);
                }
            });
            (parent ? parent : window).Fast.api.open('/addons/cropper/index/cropper?url=' + image + (params.length > 0 ? '&' + params.join('&') : ''), '裁剪', {
                callback: function (data) {
                    if (typeof data !== 'undefined') {
                        var arr = data.dataURI.split(','), mime = arr[0].match(/:(.*?);/)[1],
                            bstr = atob(arr[1]), n = bstr.length, u8arr = new Uint8Array(n);
                        while (n--) {
                            u8arr[n] = bstr.charCodeAt(n);
                        }
                        var urlArr = url.split('.');
                        var suffix = 'png';
                        url = urlArr.join('');
                        var filename = url.substr(url.lastIndexOf('/') + 1);
                        var exp = new RegExp("\\." + suffix + "$", "i");
                        filename = exp.test(filename) ? filename : filename + "." + suffix;
                        var file = new File([u8arr], filename, {type: mime});
                        Upload.api.send(file, function (data) {
                            input.val(input.val().replace(image, data.url)).trigger("change");
                        }, function (data) {
                        });
                    }
                },
                area: ["880px", "520px"],
            });
            return false;
        });

        var insertBtn = function () {
            return arguments[0].replace(arguments[2], btn + arguments[2]);
        };
        Upload.config.previewtpl = Upload.config.previewtpl.replace(/<li(.*?)>(.*?)<\/li>/, insertBtn);
        $(".cropper").each(function () {
            var preview = $("#" + $(this).data("preview-id"));
            if (preview.size() > 0 && preview.data("template")) {
                var tpl = $("#" + preview.data("template"));
                tpl.text(tpl.text().replace(/<li(.*?)>(.*?)<\/li>/, insertBtn));
            }
        });
    });
}
require.config({
    paths: {
        'async': '../addons/example/js/async',
        'BMap': ['//api.map.baidu.com/api?v=2.0&ak=mXijumfojHnAaN2VxpBGoqHM'],
    },
    shim: {
        'BMap': {
            deps: ['jquery'],
            exports: 'BMap'
        }
    }
});

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

});