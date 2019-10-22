<?php


namespace Stars\Peace\Entity;


use Stars\Peace\Lib\Helper;

trait TraitCategory
{
    /**
     * 新增一个节点
     * @param array $node
     * @return array
     */
    public function creteNodes(array $node )
    {
        if(!$node) return [];
        return  self::create($node);
    }

    /**
     * 获取树形菜单
     * @param int $navId
     * @return array
     */
    public function tree( $navId=0){

        $root = $this->getNodes( $navId );
        return Helper::list2Tree( $root ? $root->toArray() : [] , 'id', 'parent_id' , 'nodes' , 0 );
    }

    /**
     * 获取所有菜单
     * @param $navId
     * @param bool $withMenuBind
     * @return mixed
     */
    public function getNodes( $navId, $withMenuBind=false ){
        $result= self::where( 'nav_id', $navId );
        if($withMenuBind){
            $result = $result->with('binds');
        }

        $result = $result
            ->orderBy( 'order','DESC' )
            ->orderBy('id', 'ASC')
            ->get()  ;
        return $result ;
    }
}