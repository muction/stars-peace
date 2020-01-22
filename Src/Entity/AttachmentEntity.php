<?php
namespace Stars\Peace\Entity;

use Stars\Peace\Foundation\EntityEntity;

class AttachmentEntity extends EntityEntity
{
    protected $table = "attachments";

    protected $fillable = ['bind_id' ,'original_name' ,'size' ,'save_file_path'  ,'save_file_name'   ,
        'type'  ,'summary' ,'mime'  ,'status'  ,'source'  ,'created_at' ,'updated_at'];

    protected $hidden = [ 'created_at' ,'updated_at' ];

    /**
     * create
     *
     * @param array $attachment
     * @return mixed
     */
    public static function storage(array $attachment){
        return self::create( $attachment );
    }

    /**
     * @param int $bindId
     * @param array $attachments
     * @return mixed
     */
    public static function files(int $bindId, array $attachments ){
        return self::whereIn('id', $attachments)->orderBy('id', 'DESC')->get();
    }

    /**
     * 单个文件
     * @param $bindId
     * @param $attachmentId
     * @return mixed
     */
    public static function info( $bindId, $attachmentId ){
        return self::where( 'bind_id', $bindId )->where('id', $attachmentId)->first();
    }

    /**
     * @return mixed
     */
    public static function pagination(){
        return self::orderBy('id' ,'DESC')->paginate( 15 );
    }
}