define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'houses/index',
                    add_url: 'houses/add',
                    edit_url: 'houses/edit',
                    del_url: 'houses/del',
                    multi_url: 'houses/multi',
                    table: 'houses',
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
//                        {checkbox: true},
                        {field: 'id', title: __('Id')},
                        {field: 'house_sn', title: __('House_sn')},
                        {field: 'member.nick_name', title: __('Mem_nickname')},
                        {field: 'title', title: __('Title')},
//                        {field: 'sub_title', title: __('Sub_title')},
//                        {field: 'content', title: __('Content')},
//                        {field: 'mobile', title: __('Mobile')},
//                        {field: 'contact_person', title: __('Contact_person')},
//                        {field: 'weixin_no', title: __('Weixin_no')},
//                        {field: 'email', title: __('Email')},
                        {field: 'resource_type_text', title: __('House_resource_type'), searchList: {"1":__('ResourceType 1'),"2":__('ResourceType 2')}},
                        {field: 'rent_type_text', title: __('House_rent_type'),searchList: {"1":__('RentType 1'),"2":__('RentType 2')}},
                        {field: 'room_type_text', title: __('House_room_type'), searchList: {"1":__('RoomType 1'),"2":__('RoomType 2'), "3":__('RoomType 3'),"4":__('RoomType 4')}},
                        {field: 'has_person_text', title: __('Is_parlor_resident')},
                        {field: 'can_keep_pat_text', title: __('Can_keep_pat')},
                        {field: 'have_separate_bathroom_text', title: __('Have_separate_bathroom')},
                        {field: 'tenant_gender_text', title: __('Tenant_gender')},
//                        {field: 'house_resource_city_id', title: __('House_resource_city_id')},
                        {field: 'district.name', title: __('districts_name')},
//                        {field: 'house_resource_address', title: __('House_resource_address')},
//                        {field: 'house_resource_longitude', title: __('House_resource_longitude'), operate:'BETWEEN'},
//                        {field: 'house_resource_latitude', title: __('House_resource_latitude'), operate:'BETWEEN'},
//                        {field: 'house_type_id', title: __('House_type_id')},
//                        {field: 'room_count', title: __('Room_count')},
//                        {field: 'bathroom_count', title: __('Bathroom_count')},
//                        {field: 'parking_space_count', title: __('Parking_space_count')},
//                        {field: 'max_floor', title: __('Max_floor')},
//                        {field: 'floor', title: __('Floor')},
//                        {field: 'min_lease_period', title: __('Min_lease_period')},
//                        {field: 'rent_amount', title: __('Rent_amount'), operate:'BETWEEN'},
//                        {field: 'coupon_id', title: __('Coupon_id')},
//                        {field: 'images', title: __('Images'), formatter: Table.api.formatter.images},
//                        {field: 'image_thumbs_200', title: __('Image_thumbs_200')},
//                        {field: 'image_thumbs_750', title: __('Image_thumbs_750')},
//                        {field: 'house_tag', title: __('House_tag')},
//                        {field: 'house_config', title: __('House_config')},
//                        {field: 'can_reside_time', title: __('Can_reside_time'), operate:'RANGE', addclass:'datetimerange', formatter: Table.api.formatter.datetime},
//                        {field: 'collection_count', title: __('Collection_count')},
                        {field: 'read_count', title: __('Read_count')},
                        {field: 'status_text', title: __('Status'),searchList: {"0":__('Status 0'),"1":__('Status 1'),"2":__('Status 2'),"3":__('Status 3'),"4":__('Status 4'),"5":__('Status 5')}},
//                        {field: 'is_delete', title: __('Is_delete')},
//                        {field: 'check_time', title: __('Check_time'), operate:'RANGE', addclass:'datetimerange', formatter: Table.api.formatter.datetime},
//                        {field: 'add_time', title: __('Add_time'), operate:'RANGE', addclass:'datetimerange', formatter: Table.api.formatter.datetime},
//                        {field: 'rented_time', title: __('Rented_time'), operate:'RANGE', addclass:'datetimerange', formatter: Table.api.formatter.datetime},
//                        {field: 'update_time', title: __('Update_time'), operate:'RANGE', addclass:'datetimerange', formatter: Table.api.formatter.datetime},
//                        {field: 'delete_time', title: __('Delete_time'), operate:'RANGE', addclass:'datetimerange', formatter: Table.api.formatter.datetime},
//                        {field: 'email_img', title: __('Email_img')},
//                        {field: 'house_config_new', title: __('House_config_new')},
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