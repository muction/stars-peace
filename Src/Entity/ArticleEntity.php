<?php
namespace Stars\Peace\Entity;

use Stars\Peace\Foundation\EntityEntity;

class ArticleEntity extends EntityEntity
{

    //开启黑名单
    protected $guarded = [];


    /**
     *
     * @param string $sheetTableName
     * @param int $bindId
     * @param array $storage
     * @return mixed
     */
    public function storage(string $sheetTableName ,int $bindId, array $storage  ){
        $this->setTable( $sheetTableName );
        return  $this->create( array_merge( $storage, ['bind_id' => $bindId ] ) );
    }

    /**
     * @param string $sheetTableName
     * @param $bindId
     * @param int $id
     * @param array $storage
     * @return mixed
     */
    public function edit(string $sheetTableName, int $bindId, int $id, array $storage ){
        $info =$this->info( $sheetTableName , $id, $bindId ) ;
        $info->update( $storage );
        return $info;
    }

    /**
     * 删除
     * @param string $sheetTableName
     * @param int $id
     * @param int $bindId
     * @return mixed
     */
    public function remove( string $sheetTableName, int $id , int $bindId){

        $this->setTable( $sheetTableName );
        return $this->where('id',$id)->where('bind_id', $bindId)->delete();
    }

    /**
     * 分页获取数据
     * @param string $sheetTableName
     * @param $keyword
     * @param $bindListSearchColumns
     * @param $bindId
     * @param int $size
     * @return mixed
     */
    public function pageList(string $sheetTableName, $searchOption , $bindListSearchColumns ,  $bindId, $size=15 ){
        $index= $this->setTable( $sheetTableName )->where( 'bind_id', $bindId );
        if($searchOption){
            $index = $index->where(function( $query ) use ($searchOption, $bindListSearchColumns){
                if($bindListSearchColumns && $searchOption['keyword']){
                    foreach ($bindListSearchColumns as $__column){
                         $query->orWhere( $__column , 'like' , "%{$searchOption['keyword']}%" );
                    }
                }
                if( isset($searchOption['startTime']) && isset($searchOption['endTime']) && $searchOption['startTime'] && $searchOption['endTime'] ){
                    $query->whereBetween('created_at' , [$searchOption['startTime'].' 00:00:00' , $searchOption['endTime'].' 23:59:59']);
                }
            });
        }
        return $index->orderBy('id', 'DESC')->paginate( $size );
    }

    /**
     * 详情
     * @param string $sheetTableName
     * @param int $id
     * @param int $bindId
     * @return mixed
     */
    public function info( string $sheetTableName, int $id , int $bindId){

        $this->setTable( $sheetTableName );
        return $this->where('id',$id)->where('bind_id', $bindId)->first();
    }
}
