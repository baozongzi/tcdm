define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'activity/index',
                    add_url: 'activity/add',
                    edit_url: 'activity/edit',
                    del_url: 'activity/del',
                    multi_url: 'activity/multi',
                    table: 'activity',
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
                        {field: 'type', title: __('Type'), searchList: $.getJSON('activity/getTypeListAjax'), formatter: Controller.api.formatter.type},
                        {field: 'time', title: __('Time'), formatter: Table.api.formatter.datetime, operate: 'BETWEEN', type: 'datetime', addclass: 'datetimepicker', data: 'data-date-format="YYYY-MM-DD"'},
                        {field: 'title', title: __('Title')},
                        {field: 'image', title: __('Image'), operate:false, formatter: Table.api.formatter.image},
                        {field: 'describe', title: __('Describe')},
                        {field: 'top', title: __('Top'), operate:false, formatter: Controller.api.formatter.top},
                        {field: 'label', title: __('Label'), operate:false},
                        {field: 'id', title: '详情', operate:false, formatter: Controller.api.formatter.aid},
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
                aid: function (value, row, index) {
                    //这里手动构造URL
                    url = "/admin/activityUser/index?aid=" + value;

                    //方式一,直接返回class带有addtabsit的链接,这可以方便自定义显示内容
                    return '<a href="' + url + '" class="btn btn-xs btn-info btn-detail btn-dialog" title="报名列表"><i class="fa fa-list"></i> 报名列表</a>';
                },
                type: function (value, row, index) {
                    if (value == 0){
                        return '<span class="label label-success">线上</span>';
                    }else{
                        return '<span class="label label-danger">线下</span>';
                    }
                },
                top: function (value, row, index) {
                    return value ? '是' : '否';
                }
            }
        }
    };
    return Controller;
});