define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'feedback/index',
                    add_url: '',
                    edit_url: '',
                    del_url: 'feedback/del',
                    multi_url: 'feedback/multi',
                    table: 'feedback',
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
                        {field: 'uid', title: __('Uid'), operate:false},
                        {field: 'title', title: __('Title')},
                        {field: 'add_time', title: __('Add_time'), operate: 'BETWEEN', type: 'datetime', addclass: 'datetimepicker', data: 'data-date-format="YYYY-MM-DD"'},
                        {
                            field: 'operate', title: __('Operate'),
                            buttons: [
                                {name: 'detail', text: __('Detail'), classname: 'btn btn-xs btn-warning btn-detail btn-dialog', icon: 'fa fa-list', url: 'feedback/detail'}
                            ],
                            table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate
                        }
                    ]
                ],
                commonSearch: true
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