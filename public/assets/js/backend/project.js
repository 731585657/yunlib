define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {
    var Controller = {
        index: function () {

            var type_id = Config.type_id;
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'Project/index',
                    add_url: 'Project/add/type_id/'+type_id,
                    edit_url: 'Project/edit',
                    del_url: 'Project/del',
                    multi_url: 'Project/multi',
                    table: 'project',
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
                queryParams:function(params){

                    params.filter=JSON.stringify({'type_id': type_id});
                    params.op=JSON.stringify({'type_id':'='});
                    return{
                        search:params.search,
                        sort:params.sort,
                        order:params.order,
                        filter:params.filter,
                        op:params.op,
                        offset:params.offset,
                        limit : params.limit,
                    };
                },
                columns: [
                    [
                        // {checkbox: true},
                        {field: 'id', title: __('Id'), sortable: true},
                        {field: 'title', title: __('案例标题')},
                        {field: 'release_time', title: __('发布时间')},

                        {field: 'image', title: __('封面'),events: Table.api.events.image, formatter: Table.api.formatter.image, operate: false},
                        {field: 'status',title: __('状态'),formatter:function(value){
                                if (value == 1) {
                                    return '正常';
                                } else if (value == 0) {
                                    return '不显示';
                                }
                            },operate:false},

                        {field: 'clicks', title: __('点击量')},
                        {field: 'weigh', title: __('排序')},
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