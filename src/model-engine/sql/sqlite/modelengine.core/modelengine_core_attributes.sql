/*- 模型属性的数据表 -*/
DROP TABLE IF EXISTS modelengine_core_attributes;
CREATE TABLE modelengine_core_attributes ( 
    attribute_id      INTEGER          PRIMARY KEY
                                       NOT NULL
                                       UNIQUE,
    attribute_name    NVARCHAR( 20 )   NOT NULL,
    attribute_comment NVARCHAR( 255 )  NOT NULL,
    value_type        NVARCHAR( 20 )   NOT NULL,
    default_value     NVARCHAR( 20 )   NOT NULL,
    model_code        NVARCHAR( 20 )   NOT NULL,
    category_id       INTEGER          NOT NULL
                                       DEFAULT ( 0 ),
    list_id           INTEGER          NOT NULL
                                       DEFAULT ( 0 ),
    is_ext            BOOLEAN          NOT NULL
                                       DEFAULT ( 0 ),
    is_editable       BOOLEAN          NOT NULL
                                       DEFAULT ( 0 ),
    is_autoupdate     BOOLEAN          NOT NULL
                                       DEFAULT ( 0 ),
    is_primary        BOOLEAN          NOT NULL
                                       DEFAULT ( 0 ),
    position_order    INTEGER         NOT NULL
                                       DEFAULT ( 0 ),
    update_time       INTEGER          NOT NULL,
    create_time       INTEGER          NOT NULL 
);
