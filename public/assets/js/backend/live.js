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
                        {field: 'id', title: __('Id'), operate: false},
                        // {field: 'city_id', title: __('City_id'), visible: false,searchList: $.getJSON('ajax/getAllCity')},
                        {field: 'city.name', title: __('City_id'), operate: false},
                        // {field: 'category_id', title: __('Category_id'), visible: false, operate: false},
                        {field: 'category.category_name', title: __('Live_category'), operate: false},
                        {
                            field: 'city_id', title: __('Live_category'), visible: false, searchList: function (column) {
                                return Template('categorytpl', {});
                            }
                        },
                        {field: 'title', title: __('Title'), operate: 'like'},
//                        {field: 'status_text', title: __('Status'), operate: false},
                        // {field: 'status', title: __('Status'), visible: false, searchList: {"0": __('Status 0'), "1": __('Status 1'), "2": __('Status 2')}},
                       {field: 'is_top_text', title: __('Is_top'), operate: false},
                       {field: 'is_top', title: __('Is_top'), visible: false, searchList: {"0": __('Is_top 0'), "1": __('Is_top 1')}},
//                        {field: 'top_end_date', title: __('Top_end_date'), operate: 'RANGE', addclass: 'datetimerange', formatter: Table.api.formatter.datetime, datetimeFormat:"YYYY-MM-DD"},
                        {field: 'is_ensure_text', title: __('Is_ensure'), operate: false},
                        {field: 'is_ensure', title: __('Is_ensure'), visible: false, searchList: {"0": __('Is_ensure 0'), "1": __('Is_ensure 1')}},
                        {field: 'source_url', title: __('Source_url'), formatter: Table.api.formatter.url, operate: false},
                        {field: 'add_time', title: __('Add_time'), operate: 'RANGE', addclass: 'datetimerange', formatter: Table.api.formatter.datetime, datetimeFormat:"YYYY-MM-DD", operate: false},
                        {field: 'live.add_time', title: __('Add_time'), operate: 'RANGE', addclass: 'datetimerange', formatter: Table.api.formatter.datetime, datetimeFormat:"YYYY-MM-DD", visible: false},
                        {field: 'user.user_number', title: __('Add_uid'), operate:false},
                        {field: 'user.id', title: __('Add_uid'), visible:false,searchList: $.getJSON('news/getAdmin')},
                        {field: 'is_uploaded_text', title: __('Is_uploaded'), operate: false},
                        {field: 'is_uploaded', title: __('Is_uploaded'), visible: false, searchList: {"0": __('Is_uploaded 0'), "1": __('Is_uploaded 1')}},
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
            //会员昵称填充
            $("#c-mem_id").blur(function(){
                var self = $(this);
                $.post('ajax/getMemberName', {id:self.val()}, function(res){
                    if(res.code === 1){
                        $("#c-mem_nickname").val(res.data.nick_name);
                    }
                });
            });
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
                Form.api.bindevent($("form[role=form]"), function(data, ret){
                    //如果我们需要在提交表单成功后做跳转，可以在此使用location.href="链接";进行跳转
                    Toastr.success("操作成功");
                }, function(data, ret){
                    Toastr.error("失败");
                }, function(success, error){
                    //bindevent的第三个参数为提交前的回调
                    //如果我们需要在表单提交前做一些数据处理，则可以在此方法处理
                    //注意如果我们需要阻止表单，可以在此使用return false;即可
                    //如果我们处理完成需要再次提交表单则可以使用submit提交,如下
                    //Form.api.submit(this, success, error);
                    if(!$('#c-mobile').val() && !$('#c-weixin_no').val() && !$('#c-email').val()){
                        Toastr.error("手机号、邮箱和微信号三项中至少有一项不为空！");
                        return false;
                    }
                });
            }

        }
    };
    return Controller;
});