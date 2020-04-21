define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {
    var type = Config.type;


    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'Webimg/index',
                    add_url: 'Webimg/add/'+'?type='+type,
                    edit_url: 'Webimg/edit',
                    del_url: 'Webimg/del',
                    multi_url: 'Webimg/multi',
                    table: 'webconfig',
                }
            });

            var table = $("#table");
            // 初始化表格
            table.bootstrapTable({



                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'id',
                // sortNaWebimg: 'weigh',
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