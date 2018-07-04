define(['jquery', 'bootstrap', 'backend', 'table', 'form', 'jstree', 'template'], function ($, undefined, Backend, Table, Form, undefined, Template) {

    //读取选中的条目
    $.jstree.core.prototype.get_all_checked = function (full) {
        var obj = this.get_selected(), i, j;
        for (i = 0, j = obj.length; i < j; i++) {
            obj = obj.concat(this.get_node(obj[i]).parents);
        }
        obj = $.grep(obj, function (v, i, a) {
            return v != '#';
        });
        obj = obj.filter(function (itm, i, a) {
            return i == a.indexOf(itm);
        });
        return full ? $.map(obj, $.proxy(function (i) {
            return this.get_node(i);
        }, this)) : obj;
    };

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    'index_url': 'questions/index',
                    'add_url': 'questions/add',
                    'edit_url': 'questions/edit',
                    'del_url': 'questions/softDelete',
                    'import_url': 'questions/import',
                    'multi_url': 'questions/multi',
                    'table': 'questions'
                }
            });

            var table = $("#table");

            //在普通搜索提交搜索前
            table.on('common-search.bs.table', function (event, table, params, query) {
                //这里可以对params值进行修改,从而影响搜索条件
                params.filter = JSON.parse(params.filter);
                params.op = JSON.parse(params.op);

                // 判断联动选择项是否选择，进行有增/删
                if($.trim($("select[name='type']").val())!=''){
                    params.filter['type'] = ','+$.trim($("select[name='type']").val())+',';
                    params.op['type'] = "=";
                } else {
                    delete params.filter['type'];
                    delete params.op['type'];
                }
                if($.trim($("select[name='category1']").val())!=''){
                    params.filter['category'] = ','+$.trim($("select[name='category1']").val())+',';
                    params.op['category'] = "LIKE";
                }
                if($.trim($("select[name='category2']").val())!=''){
                    params.filter['category'] = ','+$.trim($("select[name='category2']").val())+',';
                    params.op['category'] = "LIKE";
                }
                if($.trim($("select[name='category3']").val())!=''){
                    params.filter['category'] = ','+$.trim($("select[name='category3']").val())+',';
                    params.op['category'] = "LIKE";
                }
                if($.trim($("select[name='category4']").val())!=''){
                    params.filter['category'] = ','+$.trim($("select[name='category4']").val())+',';
                    params.op['category'] = "LIKE";
                }
                if($.trim($("select[name='category5']").val())!=''){
                    params.filter['category'] = ','+$.trim($("select[name='category5']").val())+',';
                    params.op['category'] = "LIKE";
                }


                params.filter = JSON.stringify(params.filter);
                params.op = JSON.stringify(params.op);

                return params;
            });

            //在普通搜索渲染后
            table.on('post-common-search.bs.table', function (event, table) {
                $("input[name='title']").addClass("selectpage").data("source", "auth/adminlog/selectpage").data("primaryKey", "title").data("field", "title").data("orderBy", "id desc");
                Form.events.cxselect($("form", table.$commonsearch));
                Form.events.selectpage($("form", table.$commonsearch));
            });

            //在表格内容渲染完成后回调的事件
            table.on('post-body.bs.table', function (e, settings, json, xhr) {

            });

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                escape: false,
                columns: [
                    [
                        {checkbox: true},
                        {field: 'id', title: __('Id'), operate: false},
                        {field: 'type', title: __('Type'), searchList: $.getJSON('questions/getTypeListAjax'), formatter: Controller.api.formatter.type},
                        {field: 'answer', title: __('Answer'), formatter: Controller.api.formatter.answer, operate: false},
                        {field: 'difficulty', title: __('Difficulty'), operate: false},
                        {field: 'category', title: __('Category'), searchList: function () {
                            return Template('categorytpl', {});
                        }},
                        {field: 'images', title: __('Images'), operate: false},
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
        add: function () {
            Controller.api.bindevent();
        },
        edit: function () {
            Controller.api.bindevent();
        },
        api: {
            formatter: {
                type: function (value, row, index) {
                    switch(value)
                    {
                        case 0:
                            return '<span class="label label-success">单选题</span>';
                            break;
                        case 1:
                            return '<span class="label label-info">多选题</span>';
                            break;
                        case 2:
                            return '<span class="label label-warning">判断题</span>';
                            break;
                        case 3:
                            return '<span class="label label-danger">主观题</span>';
                            break;
                        default:
                            return "";
                    }
                },
                answer: function (value, row, index) {
                    switch(value)
                    {
                        case '1':
                            return '✔️';
                            break;
                        case '2':
                            return '❌';
                            break;
                        default:
                            return value;
                    }
                }
            },
            bindevent: function () {
                Form.api.bindevent($("form[role=form]"), null, null, function () {
                    if ($("#treeview").size() > 0) {
                        var r = $("#treeview").jstree("get_all_checked");
                        r.reverse();
                        $("input[name='row[category]']").val(r.join(','));
                    }
                    return true;
                });
                var id = $("#treeview").data("id");
                //渲染权限节点树
                //变更级别后需要重建节点树
                $.ajax({
                    url: "category/categoryTree",
                    type: 'get',
                    dataType: 'json',
                    data: {ids: id},
                    success: function (ret) {
                        if (ret.hasOwnProperty("code")) {
                            var data = ret.hasOwnProperty("data") && ret.data != "" ? ret.data : "";
                            if (ret.code === 1) {
                                //销毁已有的节点树
                                $("#treeview").jstree("destroy");
                                Controller.api.rendertree(data);
                            } else {
                                Backend.api.toastr.error(ret.data);
                            }
                        }
                    }, error: function (e) {
                        Backend.api.toastr.error(e.message);
                    }
                });
                //全选和展开
                $(document).on("click", "#checkall", function () {
                    $("#treeview").jstree($(this).prop("checked") ? "check_all" : "uncheck_all");
                });
                $(document).on("click", "#expandall", function () {
                    $("#treeview").jstree($(this).prop("checked") ? "open_all" : "close_all");
                });
            },
            rendertree: function (content) {
                $("#treeview")
                    .on('redraw.jstree', function (e) {
                        $(".layer-footer").attr("domrefresh", Math.random());
                    })
                    .jstree({
                        "themes": {"stripes": true},
                        "checkbox": {
                            "keep_selected_style": false,
                        },
                        "types": {
                            "root": {
                                "icon": "fa fa-folder-open",
                            },
                            "menu": {
                                "icon": "fa fa-folder-open",
                            },
                            "file": {
                                "icon": "fa fa-file-o",
                            }
                        },
                        "plugins": ["checkbox", "types"],
                        "core": {
                            'check_callback': true,
                            "data": content
                        }
                    });
            }
        }
    };
    return Controller;
});