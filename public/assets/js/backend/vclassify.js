define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    'index_url': 'vclassify/index',
                    'add_url': 'vclassify/add',
                    'edit_url': 'vclassify/edit',
                    'del_url': 'vclassify/softDelete',
                    'import_url': 'vclassify/import',
                    'multi_url': 'vclassify/multi',
                    'table': 'vclassify'
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
                        {field: 'title', title: __('Title'),operate: false},
                        {field: 'createtime', title: __('添加时间'),formatter: Table.api.formatter.datetime, operate: 'BETWEEN', 
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