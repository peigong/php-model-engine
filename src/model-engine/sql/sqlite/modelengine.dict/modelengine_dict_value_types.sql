/*- 模型属性值的类型数据表 -*/
DROP TABLE IF EXISTS "modelengine_dict_value_types";
CREATE TABLE modelengine_dict_value_types ( 
    type_id          INTEGER          PRIMARY KEY
                                      NOT NULL
                                      UNIQUE,
    type_code        NVARCHAR( 20 )   NOT NULL
                                      NOT NULL,
    type_name        NVARCHAR( 255 ),
    type_description NVARCHAR( 255 ),
    position_order    INTEGER          NOT NULL
                                       DEFAULT ( 0 ),
    update_time      INTEGER          NOT NULL,
    create_time      INTEGER          NOT NULL 
);

INSERT INTO [modelengine_dict_value_types] ([type_id], [type_code], [type_name], [type_description], [position_order], [update_time], [create_time]) VALUES (1, 'int', '数字', '数字类型的属性值。', 2, 1364974632, 1364974632);
INSERT INTO [modelengine_dict_value_types] ([type_id], [type_code], [type_name], [type_description], [position_order], [update_time], [create_time]) VALUES (2, 'bool', '布尔', '布尔类型的属性值。', 3, 1364974632, 1364974632);
INSERT INTO [modelengine_dict_value_types] ([type_id], [type_code], [type_name], [type_description], [position_order], [update_time], [create_time]) VALUES (3, 'str', '文本', '文本类型的属性值。', 1, 1364974632, 1364974632);

