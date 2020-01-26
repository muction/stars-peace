<?php

namespace Stars\Peace\Entity;

use Spatie\Permission\Models\Permission;
use Stars\Peace\Foundation\EntityEntity;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

/**
 * 初始化 stars系统所需基本数据
 * Class StarsInit
 * @package Stars\Peace\Entity
 */
class StarsInit extends EntityEntity
{

    /**
     * 初始化入口
     * @param $rootName
     * @param $rootPassword
     * @throws \Exception
     */
    public function start($rootName, $rootPassword)
    {

        try {
            DB::beginTransaction();

            $navId = $this->createNav();
            $adminMenus =$this->adminMenus($navId );
            $this->createNavMenus($adminMenus);
            //创建权限分类
            $this->createPermissionType();
            $roleId = $this->createRole();
            $userId = $this->createUser($rootName, $rootPassword);
            $this->createRoleUser($roleId, $userId);

            DB::commit();
        } catch (\Exception $exception) {

            DB::rollBack();
            throw $exception;
        }

    }

    /**
     * 创建导航
     */
    private function createNav()
    {

        if (DB::table('navs')->find(1)) return 1;

        return DB::table('navs')->insertGetId(
            array_merge([
                'title' => '后台操作菜单',
                'remark' => '后台菜单集'
            ], $this->nowTime())
        );
    }

    /**
     * 创建后台初始化菜单
     * @param $nodes
     * @param int $parentId
     * @return void
     */
    private function createNavMenus($nodes, $parentId=0)
    {
        $navMenus =new NavMenuEntity();
        $permissions = new PermissionEntity();
        foreach ($nodes as $node){
            $node['parent_id'] = $parentId;
            $no = $navMenus-> creteNodes($node );
            //同样创建权限
            if($node['route_name'] ){
                $permissions->create( [
                    'type'=> 0 ,
                    'title'=> $node['route_name'] ,//判断权限使用
                    'display_name'=> $node['title' ],
                    'description' => $node['title'] ,

                ] );
            }

            if(isset($node['children'])){
                $this->createNavMenus($node['children'], $no->id);
            }
        }

    }

    /**
     * 创建一个超级管理员角色
     */
    private function createRole()
    {
        if (DB::table('roles')->find(1)) return 1;

        return DB::table('roles')->insertGetId(array_merge(
            [
                'title' => 'root',
                'display_name' => '系统顶级管理员',
                'description' => "拥有系统所有权限",
                'status' => 1,

            ], $this->nowTime()
        ));
    }

    /**
     * 创建超级管理员
     * @param $username
     * @param $password
     * @return int
     */
    private function createUser($username, $password)
    {
        if (DB::table('users')->find(1)) return 1;

        return DB::table('users')
            ->insertGetId(
                array_merge([
                    'username' => $username,
                    'password' => Hash::make($password),
                    'email' => 'muction@yeah.net',
                    'phone' => '',
                    'portrait' => 0,
                    'branch' => 0,
                    'status' => 1,
                    'last_login_time' => date('Y-m-d H:i:s')
                ],
                    $this->nowTime())
            );
    }

    /**
     * 创建超级管理员角色关系
     * @param $roleId
     * @param $userId
     * @return int
     */
    private function createRoleUser($roleId, $userId)
    {
        if (DB::table('role_users')->count()) return 1;

        return DB::table('role_users')->insertGetId(
            array_merge([
                    'role_id' => $roleId,
                    'user_id' => $userId
                ]
                , $this->nowTime())
        );
    }

    /**
     * @return array
     */
    private function nowTime()
    {
        $time = date('Y-m-d H:i:s');
        return [
            'created_at' => $time,
            'updated_at' => $time
        ];
    }

    /**
     * 初始化菜单
     * @param $navId
     * @return array
     */
    private function adminMenus( $navId ){
        return [
            ['nav_id'=>$navId ,'title'=>'后台首页' ,'route_name'=> 'rotate.dashboard.index' ,'href'=>'', 'icon'=>'mdi mdi-home', 'level'=>1 ,'order'=>10 ,'status'=>1 ] ,
            ['nav_id'=>$navId ,'title'=>'导航管理' ,'route_name'=> ''  ,'href'=>'', 'icon'=>'mdi mdi-menu', 'level'=>1 ,'order'=>10 ,'status'=>1 ,
                'children'=>[
                    ['nav_id'=>$navId ,'title'=>'导航列表' ,'route_name'=> 'rotate.nav.index'  ,'href'=>'', 'icon'=>'', 'level'=>2 ,'order'=>10 ,'status'=>1 ] ,
                    ['nav_id'=>$navId ,'title'=>'新增导航' ,'route_name'=> 'rotate.nav.add'  ,'href'=>'', 'icon'=>'', 'level'=>2 ,'order'=>10 ,'status'=>1 ] ,
                ]
            ] ,
            ['nav_id'=>$navId ,'title'=>'鉴权设置' ,'route_name'=> ''  ,'href'=>'', 'icon'=>'mdi mdi-tune', 'level'=>1 ,'order'=>10 ,'status'=>1 ,
                'children'=>[
                    ['nav_id'=>$navId ,'title'=>'角色管理' ,'route_name'=> 'rotate.role.index'  ,'href'=>'', 'icon'=>'', 'level'=>2 ,'order'=>10 ,'status'=>1 ] ,
                    ['nav_id'=>$navId ,'title'=>'账号管理' ,'route_name'=> 'rotate.user.index'  ,'href'=>'', 'icon'=>'', 'level'=>2 ,'order'=>10 ,'status'=>1 ] ,
                    ['nav_id'=>$navId ,'title'=>'权限管理' ,'route_name'=> 'rotate.permission.index'  ,'href'=>'', 'icon'=>'', 'level'=>2 ,'order'=>10 ,'status'=>1 ] ,
                ]
            ] ,
            ['nav_id'=>$navId ,'title'=>'系统设置' ,'route_name'=> ''  ,'href'=>'', 'icon'=>'mdi mdi-settings', 'level'=>1 ,'order'=>10 ,'status'=>1 ,
                'children'=>[
                    ['nav_id'=>$navId ,'title'=>'模板管理' ,'route_name'=> 'rotate.template.index'  ,'href'=>'', 'icon'=>'', 'level'=>2 ,'order'=>10 ,'status'=>1 ] ,
                    ['nav_id'=>$navId ,'title'=>'附件管理' ,'route_name'=> 'rotate.attachment.index'  ,'href'=>'', 'icon'=>'', 'level'=>2 ,'order'=>10 ,'status'=>1 ] ,
                    ['nav_id'=>$navId ,'title'=>'动态设置' ,'route_name'=> 'rotate.option.site'   ,'href'=>'', 'icon'=>'', 'level'=>2 ,'order'=>10 ,'status'=>1 ] ,
                ]
            ] ,
        ];
    }

    /**
     * 创建后台权限，并更新所有前后台权限设置
     */
    private function createPermissionType(){
        $permissionType=  PermissionTypeEntity::create(['title'=>"后台权限" ,'order'=>10 ]);

        //增加一个文章管理权限
        $permissions = new PermissionEntity();
        $articlePermission = $permissions->where(['title'=> 'rotate.article.articles'])->first();
        if(!$articlePermission ){
            $permissions->create( [
                'type'=> 0 ,
                'title'=> 'rotate.article.articles' ,//判断权限使用
                'display_name'=> "文章管理",
                'description' =>"文章管理" ,

            ] );
        }

        //更新权限
        \Stars\Rbac\Entity\PermissionEntity::where('id','>',0)->update(['type'=>$permissionType->id]);


    }
}
