define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'train/video/index',
                    add_url: 'train/video/add',
                    edit_url: 'train/video/edit',
                    del_url: 'train/video/del',
                    multi_url: 'train/video/multi',
                    table: 'train_video',
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'id',
                sortName: 'weigh',
                columns: [
                    [
                        {checkbox: true},
                        {field: 'id', title: __('Id')},
                        {field: 'title', title: __('Title')},
                        {field: 'flag', title: __('Flag'), formatter: Table.api.formatter.flag},
                        {field: 'image', title: __('Image'), formatter: Table.api.formatter.image},
                        {field: 'tags', title: __('Tags'), formatter: Controller.api.formatter.tags},
                        {field: 'money', title: __('Money')},
                        {field: 'url', title: __('Url'), formatter: Controller.api.formatter.url},
                        {field: 'weigh', title: __('Weigh')},
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate}
                    ]
                ],
                commonSearch: false
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
                tags: function (value, row, index) {
                    var arr  = new Array();
                    var html = '';
                    arr     = value.split(',');
                    for(var i=0;i<arr.length;i++){
                        html += '<span class="label label-warning">'+arr[i]+'</span>&nbsp;';
                    }
                    return html;
                },
                url: function (value, row, index) {
                    return '<a href="'+value+'" class="searchit" target="_blank">'+value+'</a>';
                }
            }
        }
    };
    return Controller;
});