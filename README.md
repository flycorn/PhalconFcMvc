# PhalconFcMvc

>####基于C扩展框架Phalcon构建的mvc框架结构,目前已实现应用模块化、Cli处理每个模块的异步任务。


    框架结构如下：

```
PhalconFcMvc/
     |----app
     |      |----frontend  //前台
     |      |      |----controllers
     |      |      |      |----IndexController.php
     |      |      |----views
     |      |      |      |----index
     |      |      |      |      |----index.phtml
     |      |      |----Module.php  //前台模块
     |      |----backend  //后台
     |      |      |----controllers
     |      |      |      |----IndexController.php
     |      |      |----views
     |      |      |      |----index
     |      |      |      |      |----index.phtml
     |      |      |----Module.php  //后台模块
     |      |----models  //数据模型
     |      |----Bootstrap.php  //应用入口
     |----public
     |      |----.htaccess
     |      |----index.php  //项目入口
     |      |----css
     |      |----files
     |      |----img
     |      |----js
     |      |----temp
     |----.htrouter.php
     |----.htaccess
```