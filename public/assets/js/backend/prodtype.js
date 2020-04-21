define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {


    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'ProdType/index',
                   // add_url: 'ProdType/add',
                    edit_url: 'ProdType/edit',
                   // del_url: 'ProdType/del',
                    multi_url: 'ProdType/multi',
                    table: 'ProdType',
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 't_id',
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
                        {field: 't_id', title: __('Id'), sortable: true},
                        {field: 'title', title: __('分类名')},
                        {field: 'english_name', title: __('英文分类名')},
                        {field: 'image', title: __('封面'),events: Table.api.events.image, formatter: Table.api.formatter.image, operate: false},
                        {field: 'list_img', title: __('列表Banner'),events: Table.api.events.image, formatter: Table.api.formatter.image, operate: false},
                        {field: 'status',title: __('状态'),formatter:function(value){
                                if (value == 1) {
                                    return '正常';
                                } else if (value == 0) {
                                    return '不显示';
                                }
                            },operate:false},

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