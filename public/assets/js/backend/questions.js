define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    'index_url': 'questions/index',
                    'add_url': 'questions/add',
                    'edit_url': 'questions/edit',
                    'del_url': 'questions/softDelete',
                    'import_url': 'questions/import',
                    'multi_url': 'questions/multi',
                    'table': 'questions'
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                escape: false,
                columns: [
                    [
                        {checkbox: true},
                        {field: 'id', title: __('Id'), operate: false},
                        {field: 'title', title: __('问题'),operate: false},
                        {field: 'inputtime', title: __('上传时间'),formatter: Table.api.formatter.datetime, operate: 'BETWEEN', 
                        type: 'datetime', addclass: 'datetimepicker', data: 'data-date-format="YYYY-MM-DD"'},
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate}
                    ]
                ],

                //可以控制是否默认显示搜索单表,false则隐藏,默认为false
                queryParams: function (params) {
                    params.filter = JSON.stringify(params.filter);
                    params.op = JSON.stringify(params.op);
                    return params;
                },

            });

            //禁用默认搜索

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