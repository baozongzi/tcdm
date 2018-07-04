define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    'index_url': 'videos/index',
                    'add_url': 'videos/add',
                    'edit_url': 'videos/edit',
                    'del_url': 'videos/softDelete',
                    'import_url': 'videos/import',
                    'multi_url': 'videos/multi',
                    'table': 'videos'
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
                        {field: 'thumb', title: __('缩略图'), operate:false, formatter: Table.api.formatter.image},
                        {field: 'name', title: __('Name'),operate: false},
                        {field: 'gd_pid', title: __('一级分类'),operate: false},
                        {field: 'pid', title: __('二级分类'), operate: false},
                        {field: 'star', title: __('主演'),  operate: false},
                        {field: 'inputtime', title: __('上传时间'),formatter: Table.api.formatter.datetime, operate: 'BETWEEN', 
                        type: 'datetime', addclass: 'datetimepicker', data: 'data-date-format="YYYY-MM-DD"'},
                        {field: 'click', title: __('点击量'), operate: false},
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
        soft: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    'index_url': 'videos/soft',
                    'add_url': 'videos/add',
                    'edit_url': 'videos/edit',
                    'del_url': 'videos/softDelete',
                    'import_url': 'videos/import',
                    'multi_url': 'videos/multi',
                    'table': 'videos'
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
                        {field: 'name', title: __('Name'),operate: false},
                        {field: 'gd_pid', title: __('一级分类'),operate: false},
                        {field: 'pid', title: __('二级分类'), operate: false},
                        {field: 'star', title: __('主演'),  operate: false},
                        {field: 'inputtime', title: __('上传时间'),formatter: Table.api.formatter.datetime, operate: 'BETWEEN', 
                        type: 'datetime', addclass: 'datetimepicker', data: 'data-date-format="YYYY-MM-DD"'},
                        {field: 'click', title: __('点击量'), operate: false},
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
            // 为表格绑定事件
            Table.api.bindevent(table);

            // 批量还原
            $(document).on('click', '.btn-reduction', function () {
                var that = this;
                var ids = Table.api.selectedids(table);
                var index = Layer.confirm(
                    __('确定要还原选中的 %s 项?', ids.length),
                    {icon: 3, title: __('Warning'), offset: 0, shadeClose: true},
                    function () {
                        Table.api.multi("multi", ids, table, that);
                        Layer.close(index);
                    }
                );
            });

            $(document).on("click", ".btn-softone", function (e) {
                $(this).data('params', 'status=1');
                e.preventDefault();
                var id = Table.api.selectedids(table);
                var that = this;
                var index = Layer.confirm(
                    __('确定还原此项?'),
                    {icon: 3, title: __('Warning'), shadeClose: true},
                    function () {
                        Table.api.multi("multi", id, table, that);
                        Layer.close(index);
                    }
                );
            });

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