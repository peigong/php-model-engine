/*- 模型表单输入域的验证方法数据表 -*/
DROP TABLE IF EXISTS "modelengine_core_validation";
CREATE TABLE modelengine_core_validation ( 
    validation_id      INTEGER          PRIMARY KEY
                                        NOT NULL
                                        UNIQUE,
    model_code         NVARCHAR( 20 )   NOT NULL
                                        NOT NULL,
    form_id            INTEGER          NOT NULL
                                        DEFAULT ( 0 ),
    validation_method  NVARCHAR( 20 )   NOT NULL
                                        NOT NULL,
    validation_message NVARCHAR( 255 ),
    position_order     INTEGER          NOT NULL
                                        DEFAULT ( 0 ),
    update_time        INTEGER          NOT NULL,
    create_time        INTEGER          NOT NULL 
);
