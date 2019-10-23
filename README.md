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

    Unordered 无序列表：
		* 无序列表
		* 子项
		* 子项
		 
		+ 无序列表
		+ 子项
		+ 子项