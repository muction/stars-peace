<?php


namespace Stars\Peace\Contracts;
use Illuminate\Http\Request;
use Stars\Peace\Entity\ArticleEntity;

interface ArticleHook
{
    /**
     * 已删除
     * @param string $sheetTableName
     * @param int $bindId
     * @param int $infoId
     * @return mixed
     */
    public function deleted( string $sheetTableName,int $bindId, int $infoId=0  );

    /**
     * 已保存
     * @param Request $request
     * @param string $sheetTableName
     * @param int $bindId
     * @param int $infoId
     * @param $storage
     * @return mixed
     */
    public function saved( Request $request, string $sheetTableName,int $bindId, ArticleEntity $storage , int $infoId=0 );
}
