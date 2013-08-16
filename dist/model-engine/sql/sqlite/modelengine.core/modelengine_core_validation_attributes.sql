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

INSERT INTO [modelengine_core_validation_attributes] ([model_attribute_id], [model_code], [model_id], [attribute_id], [str_vlaue], [update_time], [create_time]) VALUES (1, 'validation', 52, 205, '#user_password', 1373870081, 1373857699);
INSERT INTO [modelengine_core_validation_attributes] ([model_attribute_id], [model_code], [model_id], [attribute_id], [str_vlaue], [update_time], [create_time]) VALUES (2, 'validation', 53, 205, '/ucenter/svr/check_user.php', 1373869713, 1373867665);
INSERT INTO [modelengine_core_validation_attributes] ([model_attribute_id], [model_code], [model_id], [attribute_id], [str_vlaue], [update_time], [create_time]) VALUES (3, 'validation', 55, 205, '', 1373971379, 1373971379);
INSERT INTO [modelengine_core_validation_attributes] ([model_attribute_id], [model_code], [model_id], [attribute_id], [str_vlaue], [update_time], [create_time]) VALUES (4, 'validation', 56, 205, '', 1374022537, 1374022537);
