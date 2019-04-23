define(['jquery', 'bootstrap', 'backend', 'table', 'form', 'template', 'summernote'], function ($, undefined, Backend, Table, Form) {

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
                search: false,
                showToggle: false,
                showExport: false,
                columns: [
                    [
                        {checkbox: true},
                        {field: 'id', title: __('Id'), operate:false},
                        {field: 'category.category_name', title: __('Category_id'), operate:false},
                        {field: 'category_id', title: __('Category_id'), visible:false, align: 'left',searchList: $.getJSON('news/getCategory')},
                        {field: 'source.source_name',title: __('Source_id'),operate:false},
                        {field: 'source_id',title: __('Source_id'), visible:false, align: 'left', searchList: $.getJSON('news/getSource')},
                        // {field: 'title_tag_id', title: __('Title_tag_id')},
                        // {field: 'news_tag_id', title: __('News_tag_id')},
                        {field: 'layout.layout_name', title: __('Layout_id'), operate:false},
                        {field: 'type.type_name', title: __('Type_id'), operate:false},
                        {field: 'type_id', title: __('Type_id'), visible:false, align: 'left', searchList: $.getJSON('news/getType')},
                        {field: 'news_title', title: __('News_title')},
                        // {field: 'news_picture', title: __('News_picture')},
                        // {field: 'content', title: __('Content')},
                        // {field: 'news_url', title: __('News_url'), formatter: Table.api.formatter.url},
                        // {field: 'search_key', title: __('Search_key')},
                        // {field: 'remark', title: __('Remark')},
                        {field: 'declare_text', title: __('Declare_id'), operate:false},
                        {field: 'is_valid_text', title: __('Is_valid'), operate:false},
                        {field: 'is_recommend_text', title: __('Is_recommend'),operate:false},
                        {field: 'is_hot_text', title: __('Is_hot'), operate:false},
                        {field: 'is_top_text', title: __('Is_top'),operate:false},
                        {field: 'is_applet_text', title: __('Is_applet'), operate:false},
                        {field: 'add_time', title: __('Add_time'), operate:'RANGE', addclass:'datetimerange', formatter: Table.api.formatter.datetime},
                        // {field: 'is_applet', visible: false, searchList: {"1":__('Is_applet 1'),"2":__('Is_applet 2')}},
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate}
                    ]
                ]
            });

            // 为表格绑定事件
            Table.api.bindevent(table);
        },
        add: function () {
            Controller.api.bindevent();
            $("#c-is_top_no").change(function(){
                $("#c-top_end_date").hide();
                $("#c-top_end_date").val('');
                // 
            });              
            $("#c-is_top_yes").change(function(){             
                $("#c-top_end_date").show();      
            });
            $("#c-is_publish_no").change(function(){
                $("#c-publish_time").hide();
                $("#c-publish_time").val('');
                // 
            });              
            $("#c-is_publish_yes").change(function(){             
                $("#c-publish_time").show();      
            });

            $('#collect').on('click', function(){
                var index = layer.load(1, {
                      shade: [0.1,'#fff'] //0.1透明度的白色背景
                    });
                $.ajax({ 
                    url:"News/ajax_collect_wechat",
                    type:'post',
                    dataType:'json',
                    data:{'url':$('#wx_url').val()},
                    success:function(data){
                        layer.close(index);
                        if(data.code == 1){                          
                            $('#c-news_title').val(data.data.title);
                            // $('#c-content').html(data.data.content);
                            $('#c-content').summernote('code', data.data.content);
                            $('#c-images').val(data.data.news_picture);
                            // ue.setContent(data.data.content);
                        }else{
                            layer.msg(data.msg,{icon: 5,time:1000});
                        }
                    }
                });	
            });


        },
        edit: function () {
            Controller.api.bindevent();
            $("#c-is_top_no").change(function(){
                $("#c-top_end_date").hide();
                $("#c-top_end_date").val('');
                // 
            });              
            $("#c-is_top_yes").change(function(){             
                $("#c-top_end_date").show();      
            });
            $("#c-is_publish_no").change(function(){
                $("#c-publish_time").hide();
                $("#c-publish_time").val('');
                // 
            })              
            $("#c-is_publish_yes").change(function(){             
                $("#c-publish_time").show();      
            })
        },
        api: {
            bindevent: function () {
                Form.api.bindevent($("form[role=form]"));
            }
        }
    };
    return Controller;
});