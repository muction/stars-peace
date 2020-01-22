<?php
namespace Stars\Peace\Service;

use Stars\Rbac\Entity\UserEntity;
use Stars\Peace\Foundation\ServiceService;
use Stars\Peace\Lib\Option;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserService extends ServiceService
{

    /**
     * 分页
     * @return mixed
     */
    public function paginatePage(){

        return UserEntity::paginatePage( Option::USER_PAGE_SIZE );
    }

    /**
     * 存储
     * @param Request $request
     * @param int $infoId
     * @param bool $isEditProfile
     * @return bool
     * @throws \Exception
     */
    public function storage( Request $request , $infoId=0, $isEditProfile=false ){


        $roles = $request->input('role' , []);
        $storage = $request->only(['username' ,'password' ,'email' ,'phone' ,'branch' ,'status']);

        //有文件上传时进行上传
        if( $request->hasFile('portrait') ){
            $attachmentService = new AttachmentService();
            $fileUploadInfo = $attachmentService->upload( $request ,'user', 'portrait' );
            if( isset($fileUploadInfo->url) ){
                $storage['portrait'] = $fileUploadInfo->id;
            }
        }

        if($infoId){
            if(!$storage['password']){
                unset($storage['password']);
            }else{
                $storage['password'] = Hash::make( $storage['password'] );
            }

            if( ! $request->input('portrait_id')  && !isset($storage['portrait'] ) ){
                $storage['portrait'] = 0;
            }


            return UserEntity::edit( $storage , $infoId , $roles, $isEditProfile );
        }

        $storage['password'] = Hash::make( $storage['password'] );
        return UserEntity::storage( $storage , $roles );
    }

    /**
     * 删除
     * @param $infoId
     * @return bool
     */
    public function remove($infoId ){
        return UserEntity::remove( $infoId );
    }

    /**
     * @param int $infoId
     * @return mixed
     */
    public function info(  $infoId =0){
        return UserEntity::info( $infoId );
    }

}
