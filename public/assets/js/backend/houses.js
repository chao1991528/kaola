define(['jquery', 'bootstrap', 'backend', 'table', 'form', 'selectpage', 'template'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'houses/index',
//                    add_url: 'houses/add',
                    edit_url: 'houses/edit',
                    del_url: 'houses/del',
                    multi_url: 'houses/multi',
                    table: 'houses'
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
//                        {checkbox: true},
                        {field: 'id', title: __('Id'), operate:false},
                        {field: 'house_sn', title: __('House_sn'), operate:false},
                        {field: 'member.nick_name', title: __('Mem_nickname'), operate:false},
                        {field: 'title', title: __('Title')},
                        {field: 'resource_type_text', title: __('House_resource_type'), operate:false},
                        {field: 'house_resource_type', visible: false, searchList: {"1":__('ResourceType 1'),"2":__('ResourceType 2')}},
                        {field: 'rent_type_text', title: __('House_rent_type'), operate:false},
                        {field: 'house_rent_type', visible: false, searchList: {"1":__('RentType 1'),"2":__('RentType 2')}},
                        {field: 'room_type_text', title: __('House_room_type'), operate:false},
                        {field: 'house_room_type', visible: false, searchList: {"1":__('RoomType 1'),"2":__('RoomType 2'), "3":__('RoomType 3'),"4":__('RoomType 4')}},
                        {field: 'has_person_text', title: __('Is_parlor_resident'), operate:false},
                        {field: 'can_keep_pat_text', title: __('Can_keep_pat'), operate:false},
                        {field: 'have_separate_bathroom_text', title: __('Have_separate_bathroom'), operate:false},
                        {field: 'tenant_gender_text', title: __('Tenant_gender'), operate:false},
//                        {field: 'house_resource_city_id', title: __('House_resource_city_id')},
                        {field: 'district.name', title: __('districts_name'), operate:false},
                        {
                            field: 'house_resource_districts_id', title: __('districts_name'), visible: false, searchList: function (column) {
                                return Template('city_distirct', {});
                            }
                        },
                        {field: 'read_count', title: __('Read_count'), operate:false},
                        {field: 'status_text', title: __('Status'),operate:false},
                        {field: 'status', visible: false, searchList: {"0":__('Status 0'),"1":__('Status 1'),"2":__('Status 2'),"3":__('Status 3'),"4":__('Status 4'),"5":__('Status 5')}},
                        {
                            field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate,
                            buttons: [
                                {
                                    name: 'upload',
                                    text:'上传',
                                    title: __('上传'),
                                    classname: 'btn btn-xs btn-primary btn-ajax',
                                    refresh:true,
                                    url: '/admin/houses/uploadToProduct',
                                }],
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
            Controller.api.bindevent();
            //会员昵称填充
            $("#c-mem_id").blur(function(){
                var self = $(this);
                $.post('ajax/getMemberName', {id:self.val()}, function(res){
                    if(res.code === 1){
                        $("#c-mem_nickname").val(res.data.nick_name);
                    }
                });
            });          
            //房源区域选中
            var districtSelect = $("#c-house_resource_districts_id");
            var citySelect = $("select[name='row[house_resource_city_id]']");
            var city_id = citySelect.val();
            var optionStr = '';
            var arr = [];
            $.each(district_id_json, function(index, item){
                if(city_id == item.city_id){
                    arr.push(item);
                }
            });
            districtSelect.selectPage({
                showField: 'name',
                keyField: 'id',
                data: arr
            });

            citySelect.change(function(){
                var changeArr = [];
                var value = $(this).val();
                districtSelect.selectPageData([]);                
                $.each(district_id_json, function(index, item){
                    if(value == item.city_id){
                        changeArr.push(item);
                    }
                });
                districtSelect.selectPageData(changeArr);
            });
            //整租合租切换
            $("#rent_type").change(function(){
                var value = $(this).val();
                if(1 == value){
                    //合租
                    $('#part1').show();
                    $('#part2').hide();
                    $("input[name='parking_space_count']").val('');
                    $("input[name='bathroom_count']").val('');
                    $("input[name='room_count']").val('');
                    $("input[name='max_floor']").val(0);
                    $("input[name='floor']").val(0);
                }else{
                    //整租
                    $('#part1').hide();
                    $('#part2').show();
                    $("#gender").val(1);
                    $("#have_person").val(0);
                    $("#have_separate_bathroom").val(0);
                }
            });
            
            
        },
        api: {
            bindevent: function () {
                Form.api.bindevent($("form[role=form]"));
            }
        }
    };
    return Controller;
});