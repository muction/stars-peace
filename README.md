# Clusters of stars Of Stars


### 安装

 项目基于laravel 框架，推荐提前安装好laravel框架

 1、执行命令
 
    composer require laravel-stars/peace
 
 2、配置
    
    providers 增加 Stars\Peace\StarsPeaceProvider::class
    
 3、发布静态文件
 
    php artisan vendor:publish
    
 4、系统初始化  
 
    系统初始化前，必须配置好数据库信息，此处不再赘述。
    
    1、执行初始化命令：
        
        php artisan stars:init
        
      输入创始人账号密码
      
    2、系统初始化完成后，登录地址 http:xxx/rotate
    
        
###  系统命令  

> 我们还是非常推荐您仔细阅读并理解系统提供的命令，它将为您节省更多的开发时间。

#### stars:init 

> 初始化后台系统

    命令开始会要求输入创始人账号名，密码，确认无误后命令开始创建 Sheet/Core 下所有数据文件

#### stars:forge 

> 伪造数据，用于对开发完成的站点进行数据填充

    模式一：指定菜单绑定模式
        格式1 ：[menuId1:bindId1,bindId2,bindId3 menuId2:bindId1,bindId2]
        解释：
            必须指定菜单ID值，冒号后指定绑定ID值，多个绑定用逗号隔开，多组用空格隔开
        
        格式2 ：[menuId1]
            必须指定菜单ID值，如果不指定绑定值，则系统默认会将此菜单里所有绑定进行伪造数据
        
    模式二：指定导航模式
        格式1：[navId1 navId2]
        解释：
            只需输入导航Id即可，系统将自动为该导航下所有的菜单绑定进行伪造数据。
       
        格式2：[navId1:bindId1,bindId2 navId2]
        解释：
            有时候我们需要排除具体某个绑定，您只需在导航后用冒号隔开写入绑定Id即可

####  make:sheet

> 创建 sheet 文件 [配置参考](#Sheet配置)

    系统会在 app/sheet 目录下创建文件 自动继承 Stars\Peace\Foundation\SheetSheet，
    
   

#### init:sheet

> 初始化 sheet 文件
   
    根据您的配置，系统会创建表，如果表已经存在，系统将停止创建，由您人工处理。
   
### Sheet配置

##### Sheet 契约

##### SheetColumn 契约

##### SheetOption 契约

##### SheetWidget 契约  
   
### 使用
    
    1、控制器： 继承控制器 Stars\Peace\Controller\AppContentController 实现 page custom两个抽象方法
     
     //如果不需要特殊处理，page方法只需这样既可
     public function page(){
     
        return $this->view();
     }

     //如果有单独处理的页面请求，需在 custom 方法中解决
     public function custom(){
        $templateName = '';
        
            // 其他逻辑代码
            // ... 
        
        
        $this->appendPageData ( $newCustomData ) ;
        
        return $this->view( $templateName ' );
     }
     
     2、页面获取变量数据
    
     您可以在模板中这样获取变量值
        $paegData[ 您在后台操作绑定时的别名 ] 
        
     3、"您在后台操作绑定时的别名" 推荐命名规则
        3.1 单条最新数据命名： single.xxx.xxx
        3.2 全部获取数据命名： list.xxx.xx
        3.3 分页获取数据命名： paginate.xxx.xx
   
   
### 感谢

[笔下光年](http://lyear.itshubao.com/)

[笔下光年](http://lyear.itshubao.com/)


   
####  文档完善中 ...  