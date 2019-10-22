<?php


namespace Stars\Peace\Lib;


class Helper
{
    /**
     * 转成树形结构
     * @param $list
     * @param string $pk
     * @param string $pid
     * @param string $child
     * @param int $root
     * @return array
     */
    public static function list2Tree($list, $pk='id', $pid = 'pid', $child = '_child', $root = 0) {
        if (!is_array($list)) {
            return [];
        }

        // 创建基于主键的数组引用
        $aRefer = [];
        foreach ($list as $key => $data) {
            $aRefer[$data[$pk]] = & $list[$key];
        }

        foreach ($list as $key => $data) {
            // 判断是否存在parent
            $parentId = $data[$pid];
            if ($root === $parentId) {
                $tree[] = & $list[$key];
            } else {
                if (isset($aRefer[$parentId])) {
                    $parent = & $aRefer[$parentId];
                    $parent[$child][] = & $list[$key];
                }
            }
        }

        return isset($tree) ? $tree : [];
    }

    /**
     * findAllChildrenNodes
     * @param $arrCat
     * @param int $parent_id
     * @param int $level
     * @return array|bool
     */
    public static function findAllChildrenNodes($arrCat, $parent_id = 0, $level = 0)
    {
        static  $arrTree = array();
        if( empty($arrCat)) return FALSE;
        $level++;
        foreach($arrCat as $key => $value)
        {
            if($value['parent_id' ] == $parent_id)
            {
                $value[ 'level'] = $level;
                $arrTree[] = $value;
                unset($arrCat[$key]); //注销当前节点数据，减少已无用的遍历
                self::findAllChildrenNodes($arrCat, $value[ 'id'], $level);
            }
        }

        return $arrTree;
    }
}