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
    /**
     * @param $bindId
     * @return mixed
     */
    protected static function last( $bindId ){
        return self::where( 'bind_id', '=', $bindId )
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
        return self::where( 'bind_id', '=', $bindId )
            ->where('id', '=', $infoId )
            ->first();
    }

    /**
     * @param $bindId
     * @return mixed
     */
    protected static function items( $bindId ){
        return self::where( 'bind_id', '=', $bindId )
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
    protected static function paginate( $bindId , $aliasName , $pageSize=15 ){

        return self::where( 'bind_id', '=', $bindId )
            ->orderByDesc('order')
            ->orderBy('id')
            ->paginate( $pageSize );
    }
}
