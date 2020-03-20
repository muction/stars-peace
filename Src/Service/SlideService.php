<?php
namespace Stars\Peace\Service;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Stars\Peace\Entity\SlideEntity;
use Stars\Peace\Foundation\ServiceService;
use Illuminate\Http\Request;

class SlideService extends ServiceService
{
    /**
     * @param Request $request
     * @param int $infoId
     * @return bool|mixed
     */
    public function storageType(Request $request, $infoId =0 ){
        $storage = $request->only(['title' ,'order']);
        if($infoId){
            return SlideEntity::edit( $storage, $infoId );
        }
        return SlideEntity::storage( $storage );
    }

    /**
     * @param Request $request
     * @param $typeId
     * @param int $infoId
     * @return bool|mixed
     * @throws \Exception
     */
    public function storageSide( Request $request, $typeId , $infoId=0 ){
        $storage = $request->only(['title' ,'order' ,'url' ,'summary' ,'status','attachment_id']);
        if( $request->hasFile( 'image' ) ){
            $attachment = new AttachmentService();
            $imageInfo = $attachment->upload( $request , 'slide' ,'image'  );
            $storage['attachment_id'] = $imageInfo->id ;
        }

        if($infoId){

            if( !isset($storage['attachment_id']) || !$storage['attachment_id']){
                $storage['attachment_id'] = 0;
            }
            return SlideEntity::edit( $storage, $infoId );
        }

        $storage['slide_type_id'] = $typeId;
        return SlideEntity::storage( $storage );
    }

    /**
     * @return SlideEntity[]|Collection
     */
    public function all(){

        return SlideEntity::all();
    }

    /**
     * @param $infoId
     * @return mixed
     */
    public function remove( $typeId, $infoId ){

        return SlideEntity::remove($typeId , $infoId  );
    }

    /**
     * @param $infoId
     * @return mixed
     */
    public function info( $infoId ){
        return SlideEntity::info($infoId );
    }

    /**
     * all
     * @param $slideTypeId
     * @return SlideEntity[]|Builder[]|Collection
     */
    public function allSlide( $slideTypeId ){
        return SlideEntity::allSlide( $slideTypeId );
    }


}
