define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'livecategory/index',
                    add_url: 'livecategory/add',
                    edit_url: 'livecategory/edit',
                    del_url: 'livecategory/del',
                    multi_url: 'livecategory/multi',
                    table: 'live_category',
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
                        {field: 'city_id', title: __('City_id')},
                        {field: 'category_name', title: __('Category_name')},
                        {field: 'sort', title: __('Sort')},
                        {field: 'icon', title: __('Icon'), formatter: Table.api.formatter.icon},
                        {field: 'event_params', title: __('Event_params')},
                        {field: 'event_type', title: __('Event_type')},
                        {field: 'category_type', title: __('Category_type'), searchList: {"house":__('House'),"live":__('Live')}, formatter: Table.api.formatter.normal},
                        {field: 'type', title: __('Type')},
                        {field: 'is_valid', title: __('Is_valid')},
                        {field: 'add_time', title: __('Add_time'), operate:'RANGE', addclass:'datetimerange', formatter: Table.api.formatter.datetime},
                        {field: 'add_uid', title: __('Add_uid')},
                        {field: 'is_delete', title: __('Is_delete')},
                        {field: 'delete_time', title: __('Delete_time'), operate:'RANGE', addclass:'datetimerange', formatter: Table.api.formatter.datetime},
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
            }
        }
    };
    return Controller;
});