/*- 用户自定义属性值可选列表数据表 -*/
DROP TABLE IF EXISTS "modelengine_core_custom_lists";
CREATE TABLE modelengine_core_custom_lists ( 
    list_id          INTEGER          PRIMARY KEY
                                      NOT NULL
                                      UNIQUE,
    list_name        NVARCHAR( 255 ),
    list_description NVARCHAR( 255 ),
    position_order   INTEGER          NOT NULL
                                      DEFAULT ( 0 ),
    update_time      INTEGER          NOT NULL,
    create_time      INTEGER          NOT NULL 
);

INSERT INTO [modelengine_core_custom_lists] ([list_id], [list_name], [list_description], [position_order], [update_time], [create_time]) VALUES (1, '广告皮肤类型', '广告皮肤类型', 0, 1373350069, 1367889515);
INSERT INTO [modelengine_core_custom_lists] ([list_id], [list_name], [list_description], [position_order], [update_time], [create_time]) VALUES (2, '表单控件大小', '表单控件大小', 0, 1367892523, 1367892523);
INSERT INTO [modelengine_core_custom_lists] ([list_id], [list_name], [list_description], [position_order], [update_time], [create_time]) VALUES (3, '表单验证方法', '表单验证方法', 0, 1367892523, 1367892523);
INSERT INTO [modelengine_core_custom_lists] ([list_id], [list_name], [list_description], [position_order], [update_time], [create_time]) VALUES (4, '移动设备广告实现机制类型', '移动设备广告实现机制类型', 0, 1375620482, 1375620482);
INSERT INTO [modelengine_core_custom_lists] ([list_id], [list_name], [list_description], [position_order], [update_time], [create_time]) VALUES (5, '移动设备广告点击效果', '移动设备广告点击效果', 0, 1375620517, 1375620517);
