/*- 模型的类别数据表 -*/
DROP TABLE IF EXISTS "modelengine_core_model_categories";
CREATE TABLE modelengine_core_model_categories ( 
    category_id          INTEGER          PRIMARY KEY
                                          NOT NULL
                                          UNIQUE,
    category_name        NVARCHAR( 255 ),
    category_description NVARCHAR( 255 ),
    parent_id            INTEGER          NOT NULL
                                          DEFAULT ( 0 ),
    position_order       INTEGER          NOT NULL
                                          DEFAULT ( 0 ),
    update_time          INTEGER          NOT NULL,
    create_time          INTEGER          NOT NULL 
);

INSERT INTO [modelengine_core_model_categories] ([category_id], [category_name], [category_description], [parent_id], [position_order], [update_time], [create_time]) VALUES (1, '基本模型', '基本模型。', 0, 0, 1364974632, 1364974632);
INSERT INTO [modelengine_core_model_categories] ([category_id], [category_name], [category_description], [parent_id], [position_order], [update_time], [create_time]) VALUES (2, '表单模型', '表单模型。', 0, 0, 1364974632, 1364974632);
INSERT INTO [modelengine_core_model_categories] ([category_id], [category_name], [category_description], [parent_id], [position_order], [update_time], [create_time]) VALUES (3, '广告模型', '广告模型。', 0, 0, 1364974632, 1364974632);
INSERT INTO [modelengine_core_model_categories] ([category_id], [category_name], [category_description], [parent_id], [position_order], [update_time], [create_time]) VALUES (4, '账户系统模型', '账户系统模型', 0, 0, 1364974632, 1364974632);
