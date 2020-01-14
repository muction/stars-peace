<?php
namespace Stars\Peace\Entity;

use Stars\Peace\Foundation\EntityEntity;

class NavEntity extends EntityEntity
{
    protected $table = 'navs';
    protected $fillable = ['title' ,'remark' ,'article' ,'theme'];

    /**
     * @param array $nav
     * @return mixed
     */
    public static function storage(array $nav ){
        return self::create($nav);
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
     * @param $navId
     * @return mixed
     */
    public static function info( $navId ){
        return self::find( $navId );
    }

    /**
     * remove one nav data
     * @param $navId
     * @return bool
     */
    public static function remove($navId){
        $info = self::info( $navId ) ;
        if( $info ){
            $navMenu= new NavMenu();
            if( $navMenu -> tree( $navId ) ){
                return false;
            }
            return $info->delete();
        }
        return false;
    }

    /**
     * relation navMenus
     * @param $navId
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function navMenus( $navId )
    {
        return $this->hasMany( NavMenu::class ,'nav_id' ,'id' )
            ->where('nav_id', $navId );
    }

    /**
     * edit a nav
     * @param $navId
     * @param $nav
     * @return bool
     */
    public static function edit( $navId, $nav ){
        $info = self::info( $navId ) ;
        if(!$info){
            return false;
        }
        return $info->update( $nav );
    }

    /**
     * article nav s
     * @return mixed
     */
    public static function articleNav(){
        return self::where('article', 1)->orderBy('id' ,'asc')->get();
    }
}
