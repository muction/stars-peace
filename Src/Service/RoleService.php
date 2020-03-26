<?php
namespace Stars\Peace\Service;

use Stars\Peace\Entity\NavMenuEntity;
use Stars\Rbac\Entity\RoleEntity;
use Stars\Peace\Foundation\ServiceService;
use Illuminate\Http\Request;

class RoleService extends ServiceService
{
    /**
     * @return mixed
     */
    public function index(){

        return RoleEntity::index();

    }

    /**
     * 保存或更新
     * @param Request $request
     * @param int $infoId
     * @return mixed
     */
    public function storage(Request $request , $infoId=0 )
    {
        if($infoId){
            return RoleEntity::edit( $request->all() , $infoId );
        }
        return RoleEntity::storage( $request->all() );
    }

    /**
     * 删除
     * @param $infoId
     * @return bool
     */
    public function remove( $infoId ){

        return RoleEntity::remove( $infoId );
    }

    /**
     * @param $infoId
     * @return mixed
     */
    public function info( $infoId){
        $roleInfo= RoleEntity::info( $infoId );
        $roleInfo = $roleInfo? $roleInfo->toArray() : [];
        if($roleInfo){
            $roleInfo['permissions'] = $roleInfo['permissions'] ? array_column($roleInfo['permissions']  ,'id'  ) : [];
            $roleInfo['menus'] = $roleInfo['menus'] ? array_column($roleInfo['menus']  ,'id'  ) : [];
        }

        return $roleInfo;
    }

    /**
     * 角色ID
     * @param Request $request
     * @param $roleId
     * @return bool|mixed
     */
    public function bindPermission( Request $request , $roleId ){

        $permissions = [];
        $menus = [];
        foreach ( $request->input('items') as $item ){
            if( $item['dataType'] == 'permissions' ){
                $permissions[] = $item['id'];
            }elseif ( $item['dataType'] == 'menus'){
                $menus[] = $item['id'] ;
            }
        }

        return RoleEntity::bindPermission( $roleId , $permissions , $menus );
    }

    /**
     * 取得系统内所有文章管理导航的菜单
     * @return array
     */
    public function allNavMenusTree( array $role=[]){

        $tree = [];
        $navs = new NavService();
        $navs = $navs->articleNav();
        if( $navs ){
            foreach ($navs as $index=> $nav){
                $menus=  NavMenuEntity::where('nav_id', $nav->id)
                    ->select(['id','parent_id as pId','title as name'])
                    ->with([])
                    ->get();
                if($menus){
                    $tree[ $index ]['nav'] = [
                        'id'=>1110* $nav->id,
                        'pId'=>0,
                        'name'=>$nav->title
                    ];

                    $items= $menus->toArray();
                    foreach ($items as $in=>$it){
                        $hasChecked = isset( $role['menus']) && is_array( $role['menus']) ? in_array(
                            $it['id'] , $role['menus']
                        ) : false ;
                        $items[$in]['open'] = true;
                        $items[$in]['checked'] = $hasChecked;
                        $items[$in]['dataType'] = "menus";
                    }
                    $tree[ $index]['menus'] = $items;
                }
            }
        }

        return $tree;
    }

    /**
     * @param array $allTypePermissions
     * @param array $allNavMenus
     * @param array $role
     * @return array
     */
    public function mergePermissionNavMenus( array $allTypePermissions ,array $allNavMenus ,array $role){
        //重新封装格式
        $permissions=[];
        foreach ($allTypePermissions as $index=>$item){
            $permissions[$index]['nav'] = [
                'id'=>$item['id'] ,
                'pId'=> 22*$index ,
                'name'=>$item['title']
            ];
            if( $item['permissions'] && is_array($item['permissions'])){

                foreach ($item['permissions'] as $per ){
                    $hasChecked = isset( $role['permissions']) && is_array( $role['permissions']) ? in_array(
                        $per['id'] , $role['permissions']
                    ) : false ;

                    $permissions[$index]['menus'][] = [
                        'id'=>$per['id'] ,
                        'pId'=> 0 ,
                        'name'=>$per['display_name'] ,
                        'open'=> true ,
                        'checked'=> $hasChecked,
                        'dataType'=>'permissions'
                    ];
                }
            }
        }

        $s = array_merge( $permissions , $allNavMenus );
       return $s;
        dd( $s ,$allTypePermissions );
    }
}
