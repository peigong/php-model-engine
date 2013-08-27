/*- 模型表单输入域的验证方法与扩展属性的关联关系数据表 -*/
DROP TABLE IF EXISTS modelengine_core_validation_attributes;
CREATE TABLE modelengine_core_validation_attributes ( 
    model_attribute_id INTEGER         PRIMARY KEY
                                       NOT NULL
                                       UNIQUE,
    model_code         NVARCHAR( 20 )  NOT NULL,
    model_id           INTEGER         NOT NULL,
    attribute_id       INTEGER         NOT NULL,
    str_vlaue          NVARCHAR( 20 )  NOT NULL,
    update_time        INTEGER         NOT NULL,
    create_time        INTEGER         NOT NULL 
);
