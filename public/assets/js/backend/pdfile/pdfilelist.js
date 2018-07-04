define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'pdfile/pdfilelist/index',
                    add_url: 'pdfile/pdfilelist/add',
                    edit_url: 'pdfile/pdfilelist/edit',
                    del_url: 'pdfile/pdfilelist/del',
                    multi_url: 'pdfile/pdfilelist/multi',
                    table: 'pdfilelist',
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
                        {field: 'url', title: __('Url'), operate:false},
                        {field: 'name', title: __('Name')},
                        {field: 'category', title: __('Category'), formatter: Controller.api.formatter.category, searchList: $.getJSON('category/getCategoryTreeSelect')},
                        {field: 'createtime', title: __('Createtime'), formatter: Table.api.formatter.datetime, operate: 'BETWEEN', type: 'datetime', addclass: 'datetimepicker', data: 'data-date-format="YYYY-MM-DD"'},
                        {field: 'updatetime', title: __('Updatetime'), formatter: Table.api.formatter.datetime, operate: 'BETWEEN', type: 'datetime', addclass: 'datetimepicker', data: 'data-date-format="YYYY-MM-DD"'},
                        {field: 'operate', title: __('Operate'), table: table, buttons: [
                            {name: 'update', text: '更新', title: '更新', icon: 'fa fa-flash', classname: 'btn btn-xs btn-success btn-ajax', url: 'pdfile/pdfilelist/update', success:function(data, ret){}, error:function(){}},
                        ],events: Table.api.events.operate, formatter: Table.api.formatter.operate}
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
            formatter: {
                category: function (value, row, index) {
                    var text = '', html = '';
                    $.ajax({
                        url: "category/getCategroyList",
                        data: {categorylist: value},
                        async: false,
                        success: function (res) {
                            text = res;
                        }
                    });
                    for(var i=0;i<text.length;i++){
                        html += '<span class="label label-success">'+text[i]+'</span>&nbsp;';
                    }
                    return html;
                }
            },
            bindevent: function () {
                Form.api.bindevent($("form[role=form]"));
            }
        }
    };
    return Controller;
});