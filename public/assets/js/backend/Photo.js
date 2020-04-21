define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {


    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'Photo/index',
                    add_url: 'Photo/add',
                    edit_url: 'Photo/edit',
                    del_url: 'Photo/del',
                    multi_url: 'Photo/multi',
                    table: 'photo',
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'id',
                sortName: 'weigh',
                pageList: [10, 25, 50],
                pageNumber:1,
                pageSize:15,
                // searchFormVisible: true,
                //  queryParams: function(params){
                //     params.filter = JSON.stringify({'pid': Config.pid});
                //     params.op = JSON.stringify({'pid' : '='});
                //     return {
                //          search:params.search,
                //          sort:params.sort,
                //         order:params.order,
                //          filter:params.filter,
                //          op:params.op,
                //          offset:params.offset,
                //          limit:params.limit,
                //     };
                //  },

                columns: [
                    [
                        // {checkbox: true},
                        {field: 'id', title: __('Id'), sortable: true},
                        {field: 'title', title: __('相册名称')},
                        {field: 'image', title: __('封面'),events: Table.api.events.image, formatter: Table.api.formatter.image, operate: false},
                        {field: 'images', title: __('相册集'),events: Table.api.events.image, formatter: Table.api.formatter.images, operate: false},
                        {field: 'release_time', title: __('发布时间')},
                        {field: 'weigh', title: __('排序')},

                        {field: 'status',title: __('状态'),formatter:function(value){
                                if (value == 1) {
                                    return '正常';
                                } else if (value == 0) {
                                    return '不显示';
                                }
                            },operate:false},

                        {field: 'clicks', title: __('点击量')},

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