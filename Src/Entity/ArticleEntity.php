<?php

namespace Stars\Peace\Entity;

use Illuminate\Support\Facades\Auth;
use Stars\Peace\Foundation\EntityEntity;
use Stars\Peace\Lib\StarsConstant;

class ArticleEntity extends EntityEntity
{

    //开启黑名单
    protected $guarded = [];

    /**
     * 信息新增时共有信息
     * @return array
     */
    private static function infoCreateCommon(): array
    {
        return ['create_user_id' => Auth::id(), 'is_delete' => StarsConstant::IS_DELETE_FALSE];
    }

    /**
     * 修改信息时公用内容
     * @return array
     */
    private static function infoModifyCommon(): array
    {
        return ['update_user_id' => Auth::id()];
    }

    /**
     * 删除信息时共有内容
     * @return array
     */
    private static function infoDeleteCommon(): array
    {
        return ['delete_user_id' => Auth::id(), 'deleted_at' => date('Y-m-d H:i:s')];
    }

    /**
     *
     * @param string $sheetTableName
     * @param int $bindId
     * @param array $storage
     * @return mixed
     */
    public function storage(string $sheetTableName, int $bindId, array $storage)
    {
        LogEntity::insertActionLog($bindId, '用户新增信息', $sheetTableName);
        $this->setTable($sheetTableName);
        return $this->create(array_merge($storage, ['bind_id' => $bindId], self::infoCreateCommon()));
    }

    /**
     * @param string $sheetTableName
     * @param int $bindId
     * @param int $id
     * @param array $storage
     * @return mixed
     */
    public function edit(string $sheetTableName, int $bindId, int $id, array $storage)
    {
        LogEntity::editActionLog($bindId, '用户编辑信息', $sheetTableName);
        $info = $this->info($sheetTableName, $id, $bindId);
        $info->update(array_merge($storage, self::infoModifyCommon()));
        return $info;
    }

    /**
     * 删除
     * @param string $sheetTableName
     * @param int $id
     * @param int $bindId
     * @return mixed
     */
    public function remove(string $sheetTableName, int $id, int $bindId)
    {
        LogEntity::deleteActionLog($bindId, '用户删除信息', $sheetTableName);
        $this->setTable($sheetTableName);
        return $this->where('id', $id)->where('bind_id', $bindId)
            ->update(array_merge(['is_delete' => StarsConstant::IS_DELETE_TRUE], self::infoDeleteCommon()));
    }

    /**
     * 分页获取数据
     * @param string $sheetTableName
     * @param $searchOption
     * @param $bindListSearchColumns
     * @param $bindId
     * @param int $size
     * @return mixed
     */
    public function pageList(string $sheetTableName, $searchOption, $bindListSearchColumns, $bindId, int $size = 15)
    {
        LogEntity::selectActionLog($bindId, '用户查询信息', $sheetTableName);
        $index = $this->setTable($sheetTableName)->where('bind_id', $bindId)->where('is_delete', StarsConstant::IS_DELETE_FALSE);
        if ($searchOption) {
            $index = $index->where(function ($query) use ($searchOption, $bindListSearchColumns) {
                if ($bindListSearchColumns && $searchOption['keyword']) {
                    foreach ($bindListSearchColumns as $__column) {
                        $query->orWhere($__column, 'like', "%{$searchOption['keyword']}%");
                    }
                }

            })->where(function ($query) {
                $_select_table_column = request()->input('_select_table_column');
                $_select_table_value_ = request()->input('_select_table_value_');
                if ($_select_table_value_ && $_select_table_column) {
                    $query->where($_select_table_column, $_select_table_value_);
                }
            })->where(function ($query) use ($searchOption) {
                if (isset($searchOption['startTime']) && isset($searchOption['endTime']) && $searchOption['startTime'] && $searchOption['endTime']) {
                    $query->whereBetween('created_at', [$searchOption['startTime'] . ' 00:00:00', $searchOption['endTime'] . ' 23:59:59']);
                }
            });
        }
        return $index->orderBy('id', 'DESC')->paginate($size);
    }

    /**
     * 详情
     * @param string $sheetTableName
     * @param int $id
     * @param int $bindId
     * @return mixed
     */
    public function info(string $sheetTableName, int $id, int $bindId)
    {
        $this->setTable($sheetTableName);
        return $this->where('id', $id)->where('bind_id', $bindId)->first();
    }
}
