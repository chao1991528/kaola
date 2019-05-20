define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'live/index',
                    add_url: 'live/add',
                    edit_url: 'live/edit',
                    del_url: 'live/del',
                    multi_url: 'live/multi',
                    table: 'live'
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'id',
                sortName: 'id',
                showToggle: false,
                showExport: false,
                showColumns: false,
                search:false,
                columns: [
                    [
                        {checkbox: true},
                        {field: 'id', title: __('Id')},
                        {field: 'city_id', title: __('City_id'), visible: false, operate: false},
                        {field: 'city.name', title: __('City_id'), operate: 'like'},
                        {field: 'category_id', title: __('Category_id'), visible: false, operate: false},
                        {field: 'category.category_name', title: __('Live_category'), operate: 'like'},
                        {field: 'title', title: __('Title'), operate: 'like'},
//                        {field: 'status_text', title: __('Status'), operate: false},
                        {field: 'status', title: __('Status'), visible: false, searchList: {"0": __('Status 0'), "1": __('Status 1'), "2": __('Status 2')}},
//                        {field: 'is_top_text', title: __('Is_top'), operate: false},
//                        {field: 'is_top', title: __('Is_top'), visible: false, searchList: {"0": __('Is_top 0'), "1": __('Is_top 1')}},
//                        {field: 'top_end_date', title: __('Top_end_date'), operate: 'RANGE', addclass: 'datetimerange', formatter: Table.api.formatter.datetime, datetimeFormat:"YYYY-MM-DD"},
                        {field: 'is_ensure_text', title: __('Is_ensure'), operate: false},
                        {field: 'is_ensure', title: __('Is_ensure'), visible: false, searchList: {"0": __('Is_ensure 0'), "1": __('Is_ensure 1')}},
                        {field: 'source_url', title: __('Source_url'), formatter: Table.api.formatter.url},
                        {field: 'add_time', title: __('Add_time'), operate: 'RANGE', addclass: 'datetimerange', formatter: Table.api.formatter.datetime, datetimeFormat:"YYYY-MM-DD"},
                        {
                            field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate,
                            buttons: [
                                {
                                    name: 'upload',
                                    text:'上传',
                                    title: __('上传'),
                                    confirm: '确认上传',
                                    classname: 'btn btn-xs btn-primary btn-ajax',
                                    refresh:true,
                                    url: '/admin/live/uploadToProduct',
                                    success: function (data, ret) {
                                        $('.btn-refresh').click();
                                        // Layer.alert(ret.msg);
                                        //如果需要阻止成功提示，则必须使用return false;
                                        //return false;
                                    },
                                    error: function (data, ret) {
                                        console.log(data, ret);
                                        Layer.alert(ret.msg);
                                        return false;
                                    }
                                }
                            ],
                            formatter: Table.api.formatter.operate
                        }
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
            $("#c-category_id").data("params", function (obj) {
                return {city_id: $("#c-city_id").val()};
            });
            $('#is_top').on('changed.bs.select',function(e){
                if (e.target.value == 1) {
                    $('#top_date_div').show();
                } else {
                    $('#top_date_div').hide();
                    $('#c-top_end_date').val('');
                }
            });
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