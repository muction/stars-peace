<?php



Route::group(['prefix'=>"rotate" ,'middleware'=>['web'] , 'namespace'=> "Stars\Peace\Controller" ], function(){

    Route::group( ['prefix'=>'auth'  , 'namespace'=> "Auth" ] , function(){

        Route::get('/login', 'AuthController@login')->name( 'rotate.auth.login.page' );
        Route::post('/login', 'AuthController@loginHandle')->name( 'rotate.auth.login.handle' );
        Route::get('/logout', 'AuthController@logout')->name( 'rotate.auth.logout' );

    } );


    Route::group( ['middleware'=>'stars.peace.auth'] , function(){

        //后台首页
        Route::get('/' , 'RotateController@index') ->name( 'rotate.home.index' );

        //控制面板
        Route::get('/dashboard' , 'DashboardController@dashboard')->name( 'rotate.dashboard.index' );

        //鉴权
        //角色管理
        //账号管理
        //权限管理
        Route::get('/permission' ,'PermissionController@index')->name( 'rotate.permission.index' );
        Route::get('/permission/add' , 'PermissionController@addPage')->name( 'rotate.permission.addPage' );
        Route::post('/permission/storage/{infoId?}' , 'PermissionController@storage')->name( 'rotate.permission.storage' );
        Route::get('/permission/remove/{infoId}' , 'PermissionController@remove')->name( 'rotate.permission.remove' );
        Route::get('/permission/edit/{infoId}' , 'PermissionController@editPage')->name( 'rotate.permission.editPage' );

        Route::get('/permission/types' , 'PermissionController@type')->name( 'rotate.permission.types' );
        Route::get('/permission/type/add' , 'PermissionController@addTypePage')->name( 'rotate.permission.addTypePage' );
        Route::get('/permission/type/remove/{infoId}' , 'PermissionController@removeType')->name( 'rotate.permission.typeRemove' );
        Route::get('/permission/type/edit/{infoId}' , 'PermissionController@editTypePage')->name( 'rotate.permission.editTypePage' );
        Route::post('/permission/type/storage/{infoId?}' , 'PermissionController@addTypePageStorage')->name( 'rotate.permission.addTypePageStorage' );


        //用户
        Route::get('/user' , 'UserController@index')->name( 'rotate.user.index' );
        Route::get('/user/add' , 'UserController@addPage')->name( 'rotate.user.addPage' );
        Route::post('/user/storage/{infoId?}' , 'UserController@storage')->name( 'rotate.user.storage' );
        Route::get('/user/remove/{infoId}' , 'UserController@remove')->name( 'rotate.user.remove' );
        Route::get('/user/edit/{infoId}' , 'UserController@editPage')->name( 'rotate.user.editPage' );
        Route::get('/user/editProfile/{infoId}' , 'UserController@editProfile')->name( 'rotate.user.editProfilePage' );

        //角色
        Route::get('/role' , 'RoleController@index')->name( 'rotate.role.index' );
        Route::get('/role/add' , 'RoleController@addPage')->name( 'rotate.role.addPage' );
        Route::post('/role/storage/{infoId?}' , 'RoleController@storage')->name( 'rotate.role.storage' );
        Route::get('/role/remove/{infoId}' , 'RoleController@remove')->name( 'rotate.role.remove' );
        Route::get('/role/edit/{infoId}' , 'RoleController@editPage')->name( 'rotate.role.editPage' );
        Route::get('/role/bind/role/{infoId}' , 'RoleController@bindRolePage')->name( 'rotate.role.bindRolePage' );
        Route::post('/role/bind/storage/{infoId}' , 'RoleController@bindRoleStorage')->name( 'rotate.role.bindRoleStorage' );

        //导航
        //导航管理
        //导航菜单
        //菜单绑定
        Route::get('/nav' ,'NavController@index')->name( 'rotate.nav.index' );
        Route::get('/nav/add/page' ,'NavController@add')->name( 'rotate.nav.add' );
        Route::post('/nav/add/storage' ,'NavController@storage')->name( 'rotate.nav.storage' );
        Route::get('/nav/remove/{navId}' ,'NavController@remove')->name( 'rotate.nav.remove' );
        Route::get('/nav/edit/{navId}' ,'NavController@edit')->name( 'rotate.nav.edit' );

        Route::get('/nav/menus/{navId}' ,'NavMenuController@index')->name( 'rotate.menu.index' );
        Route::get('/nav/menus/{navId}/add' ,'NavMenuController@add')->name( 'rotate.menu.add' );
        Route::post('/nav/menus/{navId}/add' ,'NavMenuController@storage')->name( 'rotate.menu.add.storage' );
        Route::get('/nav/menus/remove/{navId}-{menuId}' ,'NavMenuController@remove')->name( 'rotate.menu.remove' );
        Route::get('/nav/menus/edit/{navId}-{menuId}' ,'NavMenuController@edit')->name( 'rotate.menu.edit' );

        Route::post('/nav/menus/bind/storage/{navId}/{menuId}/{bindId?}' ,'NavMenuController@bindStorage')->name( 'rotate.menu.bind.storage' );
        Route::get('/nav/menus/bind/bind/{navId}-{menuId}' ,'NavMenuController@bind')->name( 'rotate.menu.bind' );
        Route::get('/nav/menus/bind/edit/{navId}-{menuId}-{bindId}' ,'NavMenuController@bind')->name( 'rotate.menu.bind.edit' );
        Route::get('/nav/menus/bind/remove/{navId}-{menuId}-{bindId}' ,'NavMenuController@bindRemove')->name( 'rotate.menu.bind.remove' );

        //复制绑定
        Route::get('/nav/menus/bind/copy/{navId}/{targetNavId?}' ,'NavMenuController@bindCopy')->name( 'rotate.nav.menu.copy' );

        //内容管理
        //幻灯片
        Route::get('/slides/{typeId}', 'SlideController@index')->name( 'rotate.slide.index' );
        Route::get('/slide/add/{typeId}', 'SlideController@addPage')->name( 'rotate.slide.addPage' );
        Route::get('/slide/edit/{typeId}/{infoId}', 'SlideController@editPage')->name( 'rotate.slide.editPage' );
        Route::get('/slide/remove/{typeId}/{infoId}', 'SlideController@remove')->name( 'rotate.slide.remove' );
        Route::post('/slide/storage/{typeId}/{infoId?}', 'SlideController@addPageStorage')->name( 'rotate.slide.addPageStorage' );

        //幻灯片分类
        Route::get('/slide/types', 'SlideController@slideType')->name( 'rotate.slide.index.type' );

        Route::get('/slide/type/add', 'SlideController@addTypePage')->name( 'rotate.slide.addTypePage' );
        Route::get('/slide/type/remove/{infoId}', 'SlideController@typeRemove')->name( 'rotate.slide.typeRemove' );
        Route::get('/slide/type/edit/{infoId}', 'SlideController@editTypePage')->name( 'rotate.slide.editTypePage' );
        Route::post('/slide/type/handle/{infoId?}', 'SlideController@addTypePageStorage')->name( 'rotate.slide.addTypePageStorage' );

        //文章管理
        //单连接跳转到内页管理
        Route::get('/articles/{navId}/menus/{menuId?}/{bindId?}/{action?}' , 'ArticleController@menus')->name( 'rotate.article.articles' );
        Route::post('/articles/{navId}/menus/{menuId}/{bindId}/{action}' , 'ArticleController@menus')->name( 'rotate.article.articles.storage' );

        //系统设置
        Route::get('/option/attachments' , 'OptionController@index')->name( 'rotate.option.index' );
        Route::get('/option/sites' , 'OptionController@site')->name( 'rotate.option.sites' );
        Route::post('/option/sites' , 'OptionController@storage')->name( 'rotate.option.sites.storage' );


        //附件管理
        Route::get('/attachment' , 'DashboardController@main')->name( 'rotate.attachment.index' );
        //网站设置
        Route::get('/option/site' , 'OptionController@site')->name( 'rotate.option.site' );
        Route::post('/option/storage' , 'OptionController@storage')->name( 'rotate.option.storage' );
        //日志管理
        Route::get('/log' , 'LogController@index')->name( 'rotate.log.index' );

        //文件上传
        Route::post('/attachments/upload/{client}' , 'AttachmentController@upload' )->name( 'rotate.attachment.upload' );
        Route::get('/attachments' , 'AttachmentController@index' )->name( 'rotate.attachment.index' );
        Route::get('/attachments/handle/cropper/{column}/{navId}/{menuId}/{bindId}/{attachmentId}' , 'AttachmentController@cropper' )->name( 'rotate.attachment.handle.cropper.page' );
        Route::post('/attachments/handle/cropper' , 'AttachmentController@shear' )->name( 'rotate.attachment.handle.cropper' );

        Route::get('/attachments/preview/{bindId}/{attachmentIds}' , 'AttachmentController@imgPreview')->name( 'rotate.attachment.img.preview' );

    } );



    //系统帮助页面
    Route::get('/help' , 'HelpController@index')->name( 'rotate.help' );

    // 测试路由
    Route::get( '/test' , 'TestController@test' )->name( 'rotate.test.test' );
});