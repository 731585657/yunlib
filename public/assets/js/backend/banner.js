define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {
    var type = Config.type;


    var Controller = {
        index: function () {
            // $(document).on('click', '.btn-tanchuang', function () {
            //     Layer.alert('弹窗！');
            // });
            $(document).on('click', '.btn-tanchuang', function () {
                Fast.api.open('Banner/add?type=2');
            });
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'Banner/index',
                    add_url: 'Banner/add/'+'?type='+type,
                    edit_url: 'Banner/edit',
                     del_url: 'Banner/del',
                    multi_url: 'Banner/multi',
                    table: 'webconfig',
                }
            });

            var table = $("#table");
            // 初始化表格
            table.bootstrapTable({



                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'id',
                // sortNaBanner: 'weigh',
                pageList: [10, 25, 50],
                pageNumber:1,
                pageSize:15,
                // searchFormVisible: true,
                queryParams: function(params){
                    params.filter = JSON.stringify({'type': type});
                    params.op = JSON.stringify({'type' : '='});
                    return {
                        search:params.search,
                        sort:params.sort,
                        order:params.order,
                        filter:params.filter,
                        op:params.op,
                        offset:params.offset,
                        limit:params.limit,
                    };
                },



                columns: [
                    [
                        // {checkbox: true},
                        {field: 'id', title: __('Id'), sortable: true},
                        {field: 'title', title: __('标题')},

                        {field: 'image', title: __('封面'),events: Table.api.events.image, formatter: Table.api.formatter.image, operate: false},
                        {field: 'video', title: __('视频'),formatter: function (value) {
                            if (value != null){
                                // return '<a href="' + value + '">' + value + '</a>';
                           return '<div class="input-group input-group-sm" style="width:250px;margin:0 auto;">' +
                                      '<input type="text" class="form-control input-sm" value="'+value+'">' +
                                         '<span class="input-group-btn input-group-sm">' +
                                            '<a href="'+value+'" target="_blank" class="btn btn-default btn-sm">' +
                                               '<i class="fa fa-link">' +
                                               '</i>' +
                                            '</a>' +
                                        '</span>' +
                                    '</div>';
                            }else {
                                return '无'
                            }

                            }, operate: false},
                        {field: 'p_title',title: __('关联产品'),formatter:function(value){
                                if (value != null) {
                                    return value;
                                } else if (value == null) {
                                    return '无';
                                }
                            },operate:false},
                        {field: 'release_time', title: __('添加时间')},

                        {
                            field: 'operate',
                            title: __('Operate'),
                            table: table,
                            events: Table.api.events.operate,
                            formatter: Table.api.formatter.operate
                        }
                    ]
                ]
            });

            // 为表格绑定事件
            Table.api.bindevent(table);
        },
        add: function () {
            Controller.api.bindevent();
        },
        edit: function () {
            Controller.api.bindevent();
        },
        api: {
            bindevent: function () {
                Form.api.bindevent($("form[role=form]"));
            }


        }

    };
    return Controller;
});