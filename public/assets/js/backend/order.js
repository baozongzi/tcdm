define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        vip: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'order/vip',
                    // add_url: 'story/add/',
                    // edit_url: 'story/edit/',
                    // del_url: 'story/del/',
                    del_url: 'order/softDelete/tb/vip',
                    multi_url: 'order/multi',
                    table: 'order',
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
                        {field: 'ordersn', title: __('订单编号'), operate:false},
                        {field: 'userid', title: __('用户ID')},
                        {field: 'username', title: __('用户名')},
                        {field: 'paystatus', title: __('支付状态')},
                        {field: 'paytype', title: __('支付方式')},
                        {field: 'paytime', title: __('支付时间'),formatter: Table.api.formatter.datetime, operate: 'BETWEEN', 
                        type: 'datetime', addclass: 'datetimepicker', data: 'data-date-format="YYYY-MM-DD"'},
                        {field: 'endtime', title: __('到期时间'),formatter: Table.api.formatter.datetime, operate: 'BETWEEN', 
                        type: 'datetime', addclass: 'datetimepicker', data: 'data-date-format="YYYY-MM-DD"'},
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate}
                    ]
                ]
            });

            // 为表格绑定事件
            Table.api.bindevent(table);
        },
        diamond: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'order/diamond',
                    // add_url: 'story/add/',
                    // edit_url: 'story/edit/',
                    // del_url: 'story/del/',
                    del_url: 'order/softDelete/tb/diamond',
                    multi_url: 'order/multi',
                    table: 'order',
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
                        {field: 'ordersn', title: __('订单编号'), operate:false},
                        {field: 'userid', title: __('用户ID')},
                        {field: 'username', title: __('用户名')},
                        {field: 'paystatus', title: __('支付状态')},
                        {field: 'paytype', title: __('支付方式')},
                        {field: 'paytime', title: __('支付时间'),formatter: Table.api.formatter.datetime, operate: 'BETWEEN', 
                        type: 'datetime', addclass: 'datetimepicker', data: 'data-date-format="YYYY-MM-DD"'},
                        {field: 'endtime', title: __('到期时间'),formatter: Table.api.formatter.datetime, operate: 'BETWEEN', 
                        type: 'datetime', addclass: 'datetimepicker', data: 'data-date-format="YYYY-MM-DD"'},
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate}
                    ]
                ]
            });

            // 为表格绑定事件
            Table.api.bindevent(table);
        },
        gifts: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'order/diamond',
                    // add_url: 'story/add/',
                    // edit_url: 'story/edit/',
                    // del_url: 'story/del/',
                    del_url: 'order/softDelete/tb/gifts',
                    multi_url: 'order/multi',
                    table: 'order',
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
                        {field: 'ordersn', title: __('订单编号'), operate:false},
                        {field: 'userid', title: __('用户ID')},
                        {field: 'username', title: __('用户名')},
                        {field: 'paystatus', title: __('支付状态')},
                        {field: 'paytype', title: __('支付方式')},
                        {field: 'paytime', title: __('支付时间'),formatter: Table.api.formatter.datetime, operate: 'BETWEEN', 
                        type: 'datetime', addclass: 'datetimepicker', data: 'data-date-format="YYYY-MM-DD"'},
                        {field: 'endtime', title: __('到期时间'),formatter: Table.api.formatter.datetime, operate: 'BETWEEN', 
                        type: 'datetime', addclass: 'datetimepicker', data: 'data-date-format="YYYY-MM-DD"'},
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
                paystatus: function (value, row, index) {
                    if (value == 0){
                        return '<span class="label label-success">未支付</span>';
                    }else if (value == 1){
                        return '<span class="label label-danger">已支付</span>';
                    }
                }
            }
        }
    };
    return Controller;
});