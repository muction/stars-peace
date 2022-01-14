<?php

namespace Stars\Peace\Entity;

use Illuminate\Support\Facades\Auth;
use Stars\Peace\Foundation\EntityEntity;

class LogEntity extends EntityEntity
{
    protected $table = 'logs';
    protected $fillable = ['type', 'title', 'remark' ,'create_user_id'];

    // 新增动作
    const TYPE_ACTION_INSERT = 1;
    const TYPE_ACTION_DELETE = 2;
    const TYPE_ACTION_EDIT = 3;
    const TYPE_ACTION_SELECT = 4;
    const TYPE_REQUEST_MIDDLE = 5;

    /**
     * @param int $bindId
     * @param int $type
     * @param string $title
     * @param string $remark
     * @return mixed
     */
    public static function storage(int $type, int $bindId, string $title, string $remark)
    {
        $body = [
            'bind_id' => $bindId,
            'type' => $type,
            'title' => $title,
            'remark' => $remark ,
            'create_user_id' => Auth::id()
        ];
        return self::create($body);
    }

    /**
     * 新增动作日志
     * @param int $bindId
     * @param string $title
     * @param string $remark
     * @return mixed
     */
    public static function insertActionLog(int $bindId, string $title, string $remark)
    {
        return self::storage(self::TYPE_ACTION_INSERT , $bindId, $title, $remark);
    }

    /**
     * 删除日志
     * @param int $bindId
     * @param string $title
     * @param string $remark
     * @return mixed
     */
    public static function deleteActionLog(int $bindId, string $title, string $remark)
    {
        return self::storage(self::TYPE_ACTION_DELETE , $bindId, $title, $remark);
    }

    /**
     * 编辑日志
     * @param int $bindId
     * @param string $title
     * @param string $remark
     * @return mixed
     */
    public static function editActionLog(int $bindId, string $title, string $remark)
    {
        return self::storage(self::TYPE_ACTION_EDIT , $bindId, $title, $remark);
    }

    /**
     * 查询日志
     * @param int $bindId
     * @param string $title
     * @param string $remark
     * @return mixed
     */
    public static function selectActionLog(int $bindId, string $title, string $remark)
    {
        return self::storage(self::TYPE_ACTION_SELECT , $bindId, $title, $remark);
    }

    /**
     * 中间件
     * @param string $title
     * @param string $remark
     * @return mixed
     */
    public static function middleLog( string $title, string $remark)
    {
        return self::storage(self::TYPE_REQUEST_MIDDLE , 0, $title, gzcompressBase64($remark));
    }

    /**
     * data list
     * @param int $size
     * @return mixed
     */
    public static function paginatePage($size = 15)
    {
        return self::orderByDesc('id')->paginate($size);
    }

}
