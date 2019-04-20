define(['jquery', 'bootstrap', 'backend', 'table', 'form', 'selectpage'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'news/index',
                    add_url: 'news/add',
                    edit_url: 'news/edit',
                    del_url: 'news/del',
                    multi_url: 'news/multi',
                    table: 'news',
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
                        {field: 'category_id', title: __('Category_id')},
                        {field: 'source_id', title: __('Source_id')},
                        {field: 'title_tag_id', title: __('Title_tag_id')},
                        {field: 'news_tag_id', title: __('News_tag_id')},
                        {field: 'layout_id', title: __('Layout_id')},
                        {field: 'type_id', title: __('Type_id')},
                        {field: 'news_title', title: __('News_title')},
                        // {field: 'news_picture', title: __('News_picture')},
                        // {field: 'content', title: __('Content')},
                        {field: 'news_url', title: __('News_url'), formatter: Table.api.formatter.url},
                        {field: 'search_key', title: __('Search_key')},
                        {field: 'remark', title: __('Remark')},
                        {field: 'declare_id', title: __('Declare_id')},
                        {field: 'is_valid', title: __('Is_valid')},
                        {field: 'is_recommend', title: __('Is_recommend')},
                        {field: 'is_hot', title: __('Is_hot')},
                        {field: 'read_count', title: __('Read_count')},
                        {field: 'read_count_share', title: __('Read_count_share')},
                        {field: 'like_count', title: __('Like_count')},
                        {field: 'discuss_count', title: __('Discuss_count')},
                        {field: 'send_5xini', title: __('Send_5xini')},
                        {field: 'is_top', title: __('Is_top')},
                        {field: 'top_end_date', title: __('Top_end_date')},
                        {field: 'add_time', title: __('Add_time'), operate:'RANGE', addclass:'datetimerange', formatter: Table.api.formatter.datetime},
                        {field: 'add_uid', title: __('Add_uid')},
                        {field: 'is_applet', title: __('Is_applet')},
                        {field: 'is_delete', title: __('Is_delete')},
                        {field: 'delete_time', title: __('Delete_time'), operate:'RANGE', addclass:'datetimerange', formatter: Table.api.formatter.datetime},
                        {field: 'is_publish', title: __('Is_publish')},
                        {field: 'publish_time', title: __('Publish_time'), operate:'RANGE', addclass:'datetimerange', formatter: Table.api.formatter.datetime},
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate}
                    ]
                ]
            });

            // 为表格绑定事件
            Table.api.bindevent(table);
        },
        add: function () {
            $("select[name='source_id']").selectPage({
                pageSize : 5,
            });
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