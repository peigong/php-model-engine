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
