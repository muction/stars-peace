<?php
namespace Stars\Peace\Entity;

class TemplateCode extends ApplyContentEntity
{
    /**
     * 表名称
     * @var string
     */
    protected $table = 'template_codes';

    //禁用了
    const STATUS_STOP = 0;

    //使用中
    const STATUS_USE_ING = 1;

    //禁用，与使用中，中间过度状态
    const STATUS_TMP = 2;

    protected $fillable = ['nav_id', 'template_filename' ,'template_code' ,'status'];

    /**
     * 设置状态
     * @param array $where
     * @param $update
     * @return mixed
     */
    public static function setStatus( array $where, $update ){
        return self::where( $where )->update( $update );
    }

    /**
     * 创建
     * @param $navId
     * @param $templateName
     * @param $templateCode
     * @param $status
     * @return mixed
     */
    public static function storage( $navId, $templateName, $templateCode, $status ){
        return self::create([
            'nav_id'=>$navId ,
            'template_filename'=>$templateName ,
            'template_code'=>$templateCode ,
            'status'=>$status
        ]);
    }

    /**
     * 获取指定模板的所有可选版本
     * @param string $templateName
     * @param int $navId
     * @param int $limit
     * @return mixed
     */
    public static function templateAllVersion( string $templateName , int $navId=0 , int $limit = 15){
        return self::where(function( $query ) use ($templateName, $navId){
            $query->where('template_filename', $templateName );
            if( $navId>0){
                $query->where('nav_id', $navId);
            }
        })
            ->select(['id' ,'template_filename' ,'status','created_at' ,'updated_at'])
            ->limit( $limit )
            ->orderByDesc('updated_at')->get();
    }
}
