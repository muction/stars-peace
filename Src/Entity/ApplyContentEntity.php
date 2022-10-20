<?php

namespace Stars\Peace\Entity;

use Illuminate\Database\Eloquent\Model;

/**
 * App内容抽象类
 * Class AppContent
 * @package App\Entity
 */
class ApplyContentEntity extends Model
{
    protected $table = "";

    /**
     * @param $bindId
     * @return mixed
     */
    protected static function last($bindId)
    {
        return self::where('bind_id', '=', $bindId)
              ->where('is_delete',0)
            ->orderByDesc('order')
            ->orderBy('id')
            ->first();
    }

    /**
     * 详情
     * @param $bindId
     * @param $infoId
     * @return mixed
     */
    protected static function info($bindId, $infoId)
    {
        $info = self::where('bind_id', '=', $bindId)
              ->where('is_delete',0)
            ->where('id', '=', $infoId)
            ->first();
        if ($info) {
            $info->_previous = self::previous($bindId, $infoId);
            $info->_next = self::next($bindId, $infoId);
        }
        return $info;
    }

    /**
     * @param $bindId
     * @return mixed
     */
    protected static function items($bindId)
    {
        return self::where('bind_id', '=', $bindId)
              ->where('is_delete',0)
            ->orderByDesc('order')
            ->orderBy('id')
            ->get();

    }

    /**
     * 分页
     * @param $bindId
     * @param $aliasName
     * @param int $pageSize
     * @return mixed
     */
    protected static function paginate($bindId, $aliasName, $pageSize = 15)
    {

        return self::where('bind_id', '=', $bindId)
              ->where('is_delete',0)
            ->orderByDesc('order')
            ->orderBy('id')
            ->paginate($pageSize);
    }

    /**
     * 上一条
     * @param int $bindId
     * @param int $infoId
     * @param string $sortColumn
     * @return mixed
     */
    protected static function previous(int $bindId, int $infoId, string $sortColumn = 'id')
    {
        return self::where('bind_id', '=', $bindId)
              ->where('is_delete',0)
            ->where('id', '<', $infoId)
            ->orderBy($sortColumn, 'Desc')
            ->orderBy('id', 'Desc')
            ->first();
    }

    /**
     * 下一条
     * @param int $bindId
     * @param int $infoId
     * @param string $sortColumn
     * @return mixed
     */
    protected static function next(int $bindId, int $infoId, string $sortColumn = 'id')
    {
        return self::where('bind_id', '=', $bindId)
              ->where('is_delete',0)
            ->where('id', '>', $infoId)
            ->orderBy($sortColumn, 'ASC')
            ->orderBy('id', 'ASC')
            ->first();
    }
}
