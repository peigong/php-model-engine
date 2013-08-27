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
