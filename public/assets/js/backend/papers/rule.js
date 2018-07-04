define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'papers/rule/index',
                    add_url: 'papers/rule/add',
                    edit_url: 'papers/rule/edit',
                    del_url: 'papers/rule/del',
                    multi_url: 'papers/rule/multi',
                    table: 'papers_rule',
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
                        {field: 'type', title: __('Type')},
                        {field: 'name', title: __('Name')},
                        {field: 'time', title: __('Time')},
                        {field: 'totalscore', title: __('Totalscore')},
                        {field: 'radio', title: __('Radio')},
                        {field: 'radio_score', title: __('Radio_score')},
                        {field: 'checkbox', title: __('Checkbox')},
                        {field: 'checkbox_score', title: __('Checkbox_score')},
                        {field: 'judge', title: __('Judge')},
                        {field: 'judge_score', title: __('Judge_score')},
                        {field: 'rule1_end', title: __('Rule1_end')},
                        {field: 'rule1_name', title: __('Rule1_name')},
                        {field: 'rule2_start', title: __('Rule2_start')},
                        {field: 'rule2_end', title: __('Rule2_end')},
                        {field: 'rule2_name', title: __('Rule2_name')},
                        {field: 'rule3_start', title: __('Rule3_start')},
                        {field: 'rule3_end', title: __('Rule3_end')},
                        {field: 'rule3_name', title: __('Rule3_name')},
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate}
                    ]
                ],
                search: false
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