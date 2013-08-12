/*- 模型表单数据表 -*/
DROP TABLE IF EXISTS modelengine_core_forms;
CREATE TABLE modelengine_core_forms ( 
    form_id          INTEGER          PRIMARY KEY
                                      NOT NULL
                                      UNIQUE,
    model_code       NVARCHAR( 20 )   NOT NULL,
    form_name        NVARCHAR( 255 )  NOT NULL,
    form_description NVARCHAR( 255 )  NOT NULL,
    form_mode_code   NVARCHAR( 20 )   NOT NULL,
    parent_id        INTEGER          NOT NULL
                                      DEFAULT ( 0 ),
    position_order   INTEGER          NOT NULL
                                      DEFAULT ( 0 ),
    update_time      INTEGER          NOT NULL,
    create_time      INTEGER          NOT NULL 
);
