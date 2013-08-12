# PHP版本的模型和模型表单引擎 #

## 项目依赖 ##

在构建当前项目前，需要先在当前目录下的GIT命令行中clone(或SVN checkout)以下依赖项目的分支：

 * PHP版本的缓存机制框架：git clone https://github.com/peigong/php-cache.git --branch 0.0.2 ./libs/php-cache-0.0.2
 * PHP版本的IOC框架：git clone https://github.com/peigong/php-ioc.git --branch 0.0.6 ./libs/php-ioc-0.0.6
 * PHP版本的数据库访问框架：git clone https://github.com/peigong/php-db.git --branch 0.0.1 ./libs/php-db-0.0.1
 * PHP网站系统核心基类和接口库：git clone https://github.com/peigong/php-site-core.git --branch 0.0.1 ./libs/php-site-core-0.0.1
 * PHP WEB系统的常用工具库：git clone https://github.com/peigong/php-utils.git --branch 0.0.1 ./libs/php-utils-0.0.1
 * jquery框架(http://jquery.com/)：git clone https://github.com/jquery/jquery.git --branch 2.0.3 ./libs/jquery-2.0.3
 * bootstrap框架(http://twitter.github.io/bootstrap/)：git clone https://github.com/twbs/bootstrap.git --branch v3.0.0-rc1 ./libs/bootstrap-v3.0.0-rc1
 * RequireJS库(http://requirejs.org/)：git clone https://github.com/jrburke/requirejs.git --branch 2.1.8 ./libs/requirejs-2.1.8
 * jQuery File Upload(blueimp-file-upload：git clone https://github.com/blueimp/jQuery-File-Upload.git --branch 8.7.1 ./libs/jquery-file-upload-8.7.1
 * Smarty模板引擎(http://www.smarty.net/)：svn checkout http://smarty-php.googlecode.com/svn/tags/Smarty_3_1_8 ./libs/Smarty_3_1_8


## 使用说明 ##

 * 需要在模型和模型表单引擎库的上级目录，放置config.inc.php配置文件，定义WEB系统的静态常量。需要的常量如下：
 	* ROOT：整个WEB系统的根目录，用于引用全局的类库。
	* 如果需要文件上传表单对象，则需要指定静态常量，如下：
	 * STATIC_PATH：上传的物理目录
	 * STATIC_HOST：外部访问的地址
 * 需要定义常量jQueryFileUploadLibPath，指向jQuery-File-Upload的类库目录。并且，所有需要用到的JS\CSS\PHP文件在构建后，都要平级存放。
 * 在正式的商业系统中使用时，需要在WEB服务器上（如Apache、Ngix），把模型和模型表单引擎的IOC配置目录（conf）设置为禁止外部访问。

## 版本的更新记录 ##
### 0.0.1 ###
 * 实现模型和模型表单引擎基本的框架机制。

## 作者信息 ##
 * 电子邮件：peigong@foxmail.com
 * 微博地址：[http://weibo.com/u/3030510210](http://weibo.com/u/3030510210)
 * 博客地址：[http://www.peigong.tk](http://www.peigong.tk)
 * 项目地址：[https://github.com/peigong/php-db](https://github.com/peigong/php-db)
