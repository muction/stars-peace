<?php
namespace Stars\Peace\Entity;

use Stars\Peace\Foundation\EntityEntity;

class SlideEntity extends EntityEntity
{
    protected $table = 'slides';

    protected $fillable = ['title' ,'order' ,'attachment_id' ,'summary' ,'status','slide_type_id' ,'url'];

    protected $with = ['attachment' ,'type'];


    /**
     * 存储类型
     * @param array $storage
     * @return mixed
     */
    public static function storage(array $storage ){

        return self::create( $storage );
    }

    /**
     * @param $typeId
     * @param $infoId
     * @return mixed
     */
    public static function remove( $typeId, $infoId){
        return self::where( 'slide_type_id', $typeId )->where('id', $infoId )->delete();

    }

    /**
     *
     * @param $infoId
     * @return mixed
     */
    public static function info( $infoId ){
        return self::find( $infoId) ;
    }

    /**
     * 编辑
     * @param $storage
     * @param $infoId
     * @return bool
     */
    public static function edit( $storage , $infoId ){

        $info = self::info($infoId );
        if(!$info){
            return false;
        }

        return $info->update( $storage );
    }

    /**
     * @param $slideTypeId
     * @return PermissionTypeEntity[]|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public static function allSlide( $slideTypeId ){
        return self::where('slide_type_id', $slideTypeId )->get();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function attachment(){
        return $this->hasOne( AttachmentEntity::class , 'id' ,'attachment_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function type(){
        return $this->hasOne( SlideTypeEntity::class , 'id' ,'slide_type_id' );
    }
}
