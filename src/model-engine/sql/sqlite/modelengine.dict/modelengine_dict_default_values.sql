/*- 模型属性值的默认值定义数据表 -*/
DROP TABLE IF EXISTS "modelengine_dict_default_values";
CREATE TABLE modelengine_dict_default_values ( 
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

INSERT INTO [modelengine_dict_default_values] ([type_id], [type_code], [type_name], [type_description], [position_order], [update_time], [create_time]) VALUES (1, 'autoincrement', '自增ID', '主键类自增ID。', 4, 1364974632, 1364974632);
INSERT INTO [modelengine_dict_default_values] ([type_id], [type_code], [type_name], [type_description], [position_order], [update_time], [create_time]) VALUES (2, 'empty', '空字符串', '空字符串。', 1, 1364974632, 1364974632);
INSERT INTO [modelengine_dict_default_values] ([type_id], [type_code], [type_name], [type_description], [position_order], [update_time], [create_time]) VALUES (3, 'now', '当前时间', '当前时间戳数字表示。', 3, 1364974632, 1364974632);
INSERT INTO [modelengine_dict_default_values] ([type_id], [type_code], [type_name], [type_description], [position_order], [update_time], [create_time]) VALUES (4, 'zero', '数字零', '数字零。', 1364974632, 2, 1364974632);

