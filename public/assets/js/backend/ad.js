define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'ad/index',
                    add_url: 'ad/add',
                    edit_url: 'ad/edit',
                    // del_url: 'ad/del',
                    multi_url: 'ad/multi',
                    table: 'ad',
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
                        {field: 'thumb', title: __('Images'), formatter: Table.api.formatter.images, operate:false},
                        {field: 'url', title: __('Url'), formatter: Controller.api.formatter.url},
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
                url: function (value, row, index) {
                    return '<a href="'+value+'" class="searchit" target="_blank">'+value+'</a>';
                },
                type: function (value, row, index) {
                    if (value == '0'){
                        return '<span class="label label-success">banner广告</span>'
                    }else if (value == '1'){
                        return '<span class="label label-info">插屏广告</span>'
                    }
                },
                page: function (value, row, index) {
                    if(value == '0'){
                        return '试题页面';
                    }else if (value == '1'){
                        return '活动页面';
                    }else if(value == '2'){
                        return '培训页面';
                    }else if(value == '3'){
                        return '启动页面'
                    }
                }
            }
        }
    };
    return Controller;
});