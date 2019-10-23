<?php
namespace Stars\Peace\Entity;

use Stars\Peace\Foundation\EntityEntity;
use Stars\Peace\Lib\Helper;
use Stars\Peace\Service\NavMenuService;
use Illuminate\Support\Facades\Log;

class NavMenu extends EntityEntity
{
    use TraitCategory;

    protected $with =  [ 'image'] ;

    protected static $pathMenus=[];
    
    /**
     * 批量添加
     * @var array
     */
    protected $fillable = [ 'nav_id' , 'parent_id' ,'image_id' , 'title' ,'route_name' ,'href' ,'icon' , 'level' ,'template_name' ,'template_type'];

    /**
     * @param array $menu
     * @return mixed
     */
    public static function storage(array $menu ){
        return self::create($menu);
    }

    /**
     * data list
     * @param int $size
     * @return mixed
     */
    public static function paginatePage( $size= 15){
        return self::orderByDesc('id')->paginate( $size ) ;
    }

    /**
     * get a nav info
     * @param $menuId
     * @return mixed
     */
    public static function info( $menuId ){
        return self::find( $menuId );
    }

    /**
     * @param $navId
     * @param $menuId
     * @return
     */
    public static function detail( $navId, $menuId ){
        return self::where('nav_id', $navId)
            ->where('id', $menuId)->first();
    }

    /**
     * remove one nav data
     * @param $navId
     * @return bool
     */
    public static function remove($navId , $menuId ){
        $info = self::detail( $navId , $menuId) ;
        if( $info ){
            return $info->delete();
        }
        return false;
    }

    /**
     * edit a nav
     * @param $navId
     * @param $menuId
     * @param $menu
     * @return bool
     */
    public static function edit( $navId, $menuId, $menu ){
        $detail = self::detail( $navId , $menuId) ;
        if(!$detail){
            return false;
        }
        $levelChange= $menu['level']-$detail['level'];
        $result= $detail->update( $menu );
        $navMenuService = new NavMenuService() ;
        if($result){
            $allChildrenMenus= Helper::findAllChildrenNodes( $navMenuService->navMenus( $navId ) , $menuId) ;
            self::levelChange(array_column($allChildrenMenus, 'id' ) ,  $levelChange);
        }
        return true;
    }

    /**
     * move menu level
     * @param $menuIds
     * @param $levelChange
     * @return mixed
     */
    public static function levelChange( $menuIds , $levelChange ){
        return self::whereIn('id', $menuIds)->increment('level', $levelChange);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function binds(){
        return $this->hasMany( MenuBind::class , 'menu_id' ,'id' );
    }

    /**
     * @param $navId
     * @return mixed
     */
    public static function menus( $navId ){
        return self::where('nav_id', $navId)
            ->orderByDesc('order')
            ->orderBy('id')
            ->get();
    }

    /**
     * @param $where
     * @param $data
     * @return mixed
     */
    public static function matchUpdate( $where, $data ){
        return self::where( $where )->update($data);
    }

    /**
     * @param $navId
     * @return
     */
    public static function clearNavMenus( $navId ){
        return self::where('nav_id', $navId)->delete();
    }

    /**
     * 一对一
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function image(){
        return $this->hasOne( AttachmentEntity::class , 'id' ,'image_id' );
    }

    /**
     * 获取path
     * @param $lastItemId
     * @return array
     */
    public static function pathMenus($lastItemId){
        $item = self::where('id', $lastItemId)
            ->first()
            ->toArray();
        array_unshift(self::$pathMenus , $item);
        if(isset($item['parent_id']) && $item['parent_id'] > 0){
            self::pathMenus($item['parent_id']);
        }
        return self::$pathMenus;
    }
}
