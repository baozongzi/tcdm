define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'diamond/index/',
                    add_url: 'diamond/add/',
                    edit_url: 'diamond/edit/',
                    // del_url: 'diamond/del/',
                    del_url: 'diamond/softDelete/',
                    multi_url: 'diamond/multi/',
                    table: 'diamond',
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'id',
                sortName: 'id',
                columns: [
                    [
                        {checkbox: true},
                        {field: 'id', title: __('Id'), operate:false},
                        // {field: 'thumb', title: __('缩略图'), operate:false, formatter: Table.api.formatter.image},
                        {field: 'title', title: __('title')},
                        {field: 'price', title: __('价格')},
                        // {field: 'inputtime', title: __('上传时间'),formatter: Table.api.formatter.datetime, operate: 'BETWEEN', 
                        // type: 'datetime', addclass: 'datetimepicker', data: 'data-date-format="YYYY-MM-DD"'},
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate}
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