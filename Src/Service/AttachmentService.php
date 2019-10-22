<?php
namespace Stars\Peace\Service;

use Stars\Peace\Entity\AttachmentEntity;
use Stars\Peace\Foundation\ServiceService;
use Stars\Peace\Lib\Option;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Intervention\Image\ImageManager;

class AttachmentService extends ServiceService
{
    /**
     * @return mixed
     */
    public function pagination(){

        return AttachmentEntity::pagination();
    }


    /**
     * 执行文件上传
     * @param Request $request
     * @param $client
     * @return mixed
     * @throws \Exception
     */
    public function upload(  Request $request , $client, $filePostName ='uploader')
    {
        $bindId=$request->input('bindId', 0 ) ;
        $uploader= $request->file($filePostName) ;

        if (!$uploader->isValid() || !$request->hasFile($filePostName)) throw new \Exception("文件无效");

        $saveFilePath = 'upload/'.date('Y/m/d');
        $saveAttachment = $this->attachmentStorageStruck(
            $bindId,
            $uploader->getClientOriginalName() ,
            $uploader->getSize() ,$saveFilePath ,
            $uploader->getClientOriginalExtension(),
            "{$client}文件上传" ,
            Option::ATTACHMENT_UPLOAD_CLIENT
        );

        if(! $uploader->storePubliclyAs( $saveFilePath, $saveAttachment['save_file_name'] , ['disk'=> 'public'] )){
            throw new \Exception( "保存文件时失败了，请检查目录是否有可写权限" );
        }

        $attachment= AttachmentEntity::storage( $saveAttachment );
        $attachment->url = $this->attachmentSetUrl( $attachment->save_file_path, $attachment->save_file_name );
        return $attachment;
    }

    /**
     * @param $bindId
     * @param $originalName
     * @param $fileSize
     * @param $saveFilePath
     * @param $type
     * @param $summary
     * @param $client
     * @param null $newSaveFileName
     * @return array
     */
    protected function attachmentStorageStruck( $bindId, $originalName, $fileSize,  $saveFilePath , $type, $summary, $client , $newSaveFileName= null ){

        return [
            'bind_id'=> $bindId,
            'original_name' => $originalName,
            'size' => $fileSize,
            'save_file_path' => $saveFilePath ,
            'save_file_name' => $newSaveFileName ? $newSaveFileName : md5( $originalName. rand( 0,10000 ) ). '.'. $type ,
            'type' => $type ,
            'summary'=> $summary ,
            'mime' => '',
            'status' => 1,
            'source' => $client ,
            'created_at' => CommonService::now(),
            'updated_at' => CommonService::now()
        ];
    }

    /**
     * 设置附件预览地址
     * @param $saveFilePath
     * @param $saveFileName
     * @return string
     */
    protected function attachmentSetUrl( $saveFilePath, $saveFileName ) {
        return '/storage/' . $saveFilePath.'/'. $saveFileName;
    }

    /**
     * 取得指定绑定的附件内容
     * @param $bindId
     * @param $attachmentIds
     * @return mixed
     */
    public function files($bindId, $attachmentIds ){
        $attachmentids = explode(',' , $attachmentIds);
        return AttachmentEntity::files( $bindId , $attachmentids );
    }

    /**
     * 单个文件
     * @param $bindId
     * @param $attachmentId
     * @return mixed
     */
    public function info( $bindId, $attachmentId ){
        return AttachmentEntity::info( $bindId, $attachmentId );
    }

    /**
     * @param Request $request
     * @return array|mixed
     */
    public function shear( Request $request ){

        try{
            $boundWidth = $request->input('boundx') ;
            $boundHeight = $request->input('boundy') ;
            $bindId     = $request->input('bindId') ;
            $attachmentId = $request->input('attachmentId') ;
            $requestAll = $request->input('tell_select');
            $attachment = $this->info( $bindId  , $attachmentId) ;
            $attachment = $attachment->toArray() ;
            $targetWidth = $requestAll['w'] ;
            $targetHeight=$requestAll['h'] ;

            $fileBasePath = 'public/'.$attachment['save_file_path'];
            $saveFileBasePath = 'app/'.$fileBasePath ;
            $file = storage_path( $saveFileBasePath .'/'.$attachment['save_file_name'] );

            $manager = new ImageManager();
            $image = $manager->make($file );
            $sourceWidth = $image->getWidth();
            $sourceHeight = $image->getHeight() ;
            $ratio= $boundWidth/$sourceWidth;
            $sourceX = intval($requestAll['x']/$ratio);
            $sourceY = intval($requestAll['y']/$ratio );
            $saveWidth= intval($targetWidth/$ratio);
            $saveHeight= intval( $targetHeight/$ratio);
            $newSaveFileName=  $saveWidth .'x'. $saveHeight .'_'.$attachment['save_file_name'] ;
            $newFileStorage=storage_path( $saveFileBasePath.'/'. $newSaveFileName );
            $image = $image->crop( $saveWidth, $saveHeight , $sourceX , $sourceY) ->save( $newFileStorage );

            $newManager= $manager->make( $newFileStorage );
            //写入数据库
            $saveAttachment = $this->attachmentStorageStruck(
                $bindId,
                $attachment['original_name'] ,
                $newManager->filesize() ,
                $attachment['save_file_path'] ,
                $attachment['type'] ,
                "裁剪工具生成" , Option::ATTACHMENT_UPLOAD_CROPPER ,
                $newSaveFileName
            );
            $attachment= AttachmentEntity::storage( $saveAttachment );
            $attachment->url = $this->attachmentSetUrl( $attachment->save_file_path, $attachment->save_file_name );
            return $attachment;

        }catch (\Exception $exception){
            Log::error('SHearImageFail', ['msg'=>$exception->getMessage() , 'req'=>$request->all() ]);
            return  [];
        }

    }

}