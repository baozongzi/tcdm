define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'statistics/questions/index',
                    add_url: 'statistics/questions/add',
                    edit_url: 'statistics/questions/edit',
                    del_url: 'statistics/questions/del',
                    multi_url: 'statistics/questions/multi',
                    table: 'statistics_questions',
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
                        {field: 'id', title: __('Id')},
                        {field: 'uid', title: __('Uid')},
                        {field: 'type', title: __('Type'), formatter: Controller.api.formatter.type},
                        {field: 'sum_questions', title: __('Sum_questions')},
                        {field: 'done_questions', title: __('Done_questions')},
                        {field: 'accuracy', title: __('Accuracy')},
                        {field: 'error_questions', title: __('Error_questions')},
                        {field: 'time', title: __('Time'), formatter: Table.api.formatter.datetime},
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
            },
            formatter: {
                type: function (value, row, index) {
                    if (value == 0){
                        return '<span class="label label-success">消防员</span>';
                    }else{
                        return '<span class="label label-danger">消防工程师</span>';
                    }
                },
            }
        }
    };
    return Controller;
});