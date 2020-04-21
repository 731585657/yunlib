define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {


    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'Product/index',
                    add_url: 'Product/add',
                    edit_url: 'Product/edit',
                    del_url: 'Product/del',
                    multi_url: 'Product/multi',
                    table: 'products',
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'prod_id',
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
                        {field: 'prod_id', title: __('Id'), sortable: true},
                        {field: 'title', title: __('产品名称')},
                        {field: 'release_time', title: __('上市时间')},
                        {field: 't_title', title: __('产品分类')},
                        {field: 'weigh', title: __('排序')},
                        {field: 'image', title: __('封面'),events: Table.api.events.image, formatter: Table.api.formatter.image, operate: false},
                        {field: 'status',title: __('状态'),formatter:function(value){
                                if (value == 1) {
                                    return '正常';
                                } else if (value == 0) {
                                    return '不显示';
                                }
                            },operate:false},

                        {field: 'clicks', title: __('点击量')},
                        {
                            field: 'buttons',
                            width: "120px",
                            title: __('产品案例'),
                            table: table,
                            events: Table.api.events.operate,
                            buttons: [
                                {
                                    name: 'addtabs',
                                    text: __('相关案例'),
                                    title: __('案例列表'),
                                    classname: 'btn btn-xs btn-warning btn-addtabs ',
                                   // classname: 'btn btn-xs btn-warning btn-ajax',
                                    //classname: 'btn btn-xs btn-warning btn-dialog',
                                    icon: 'fa fa-folder-o',
                                    url: 'Cases/index?ids={prod_id}'
                                }
                            ],
                            formatter: Table.api.formatter.buttons
                        },

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