/*- 用户自定义属性值可选列表项数据表 -*/
DROP TABLE IF EXISTS "modelengine_core_custom_listitems";
CREATE TABLE modelengine_core_custom_listitems ( 
    item_id        INTEGER          PRIMARY KEY
                                    NOT NULL
                                    UNIQUE,
    list_id        INTEGER          NOT NULL
                                    DEFAULT ( 0 ),
    item_value     NVARCHAR( 255 ),
    item_text      NVARCHAR( 255 ),
    position_order INTEGER          NOT NULL
                                    DEFAULT ( 0 ),
    update_time    INTEGER          NOT NULL,
    create_time    INTEGER          NOT NULL 
);
