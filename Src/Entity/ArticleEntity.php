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
    public function pageList(string $sheetTableName, $keyword, $bindListSearchColumns ,  $bindId, $size=15 ){


        $index= $this->setTable( $sheetTableName )->where( 'bind_id', $bindId );
        if($keyword){
            $index = $index->where(function( $query ) use ($keyword, $bindListSearchColumns){
                if($bindListSearchColumns){
                    foreach ($bindListSearchColumns as $__column){
                         $query->orWhere( $__column , 'like' , "%{$keyword}%" );
                    }
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
