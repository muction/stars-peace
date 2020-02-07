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
     * @param bool $withMenuBind
     * @return array
     */
    public function tree( $navId=0, $withMenuBind=false){

        $root = $this->getNodes( $navId , $withMenuBind);
        return Helper::list2Tree( $root ? $root->toArray() : [] , 'id', 'parent_id' , 'nodes' , 0 );
    }

    /**
     * 获取所有菜单
     * @param $navId
     * @param bool $withMenuBind
     * @return mixed
     */
    public function getNodes( $navId, $withMenuBind=false , $status = null ){
        $result= self::where( 'nav_id', $navId );
        if($withMenuBind){
            $result = $result->with('binds');
        }
        if( is_numeric( $status ) ){
            $result = $result->where('status' ,'=' , $status );
        }

        $result = $result
            ->orderBy( 'order','DESC' )
            ->orderBy('id', 'ASC')
            ->get()  ;
        return $result ;
    }
}
