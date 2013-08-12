/*- 系统内置属性值下拉列表数据表 -*/
DROP TABLE IF EXISTS "modelengine_core_system_lists";
CREATE TABLE modelengine_core_system_lists ( 
    list_id          INTEGER          PRIMARY KEY
                                      NOT NULL
                                      UNIQUE,
    list_name        NVARCHAR( 255 ),
    list_description NVARCHAR( 255 ),
    list_clazz       NVARCHAR( 255 ),
    position_order   INTEGER          NOT NULL
                                      DEFAULT ( 0 ),
    update_time      INTEGER          NOT NULL,
    create_time      INTEGER          NOT NULL 
);

INSERT INTO [modelengine_core_system_lists] ([list_id], [list_name], [list_description], [list_clazz], [position_order], [update_time], [create_time]) VALUES (1, '模型可编辑属性列表', '模型可编辑属性列表。', 'modelengine.modules.business.attribute', 0, 1364974632, 1364974632);
INSERT INTO [modelengine_core_system_lists] ([list_id], [list_name], [list_description], [list_clazz], [position_order], [update_time], [create_time]) VALUES (2, '系统属性值列表', '系统属性值列表', 'modelengine.modules.business.systemlist', 0, 1373373415, 1364974632);
INSERT INTO [modelengine_core_system_lists] ([list_id], [list_name], [list_description], [list_clazz], [position_order], [update_time], [create_time]) VALUES (3, '模型类别列表', '模型类别列表。', 'modelengine.modules.business.modelcategory', 0, 1364974632, 1364974632);
INSERT INTO [modelengine_core_system_lists] ([list_id], [list_name], [list_description], [list_clazz], [position_order], [update_time], [create_time]) VALUES (4, '属性值的数据类型列表', '属性值的数据类型列表。', 'modelengine.modules.business.valuetype', 0, 1364974632, 1364974632);
INSERT INTO [modelengine_core_system_lists] ([list_id], [list_name], [list_description], [list_clazz], [position_order], [update_time], [create_time]) VALUES (5, '模型属性的默认值列表', '模型属性的默认值列表。', 'modelengine.modules.business.defaultvalue', 0, 1364974632, 1364974632);
INSERT INTO [modelengine_core_system_lists] ([list_id], [list_name], [list_description], [list_clazz], [position_order], [update_time], [create_time]) VALUES (6, '广告位的类别列表', '广告位的类别列表', 'modules.adengine.business.slotcategory', 0, 1372650301, 1372650301);
