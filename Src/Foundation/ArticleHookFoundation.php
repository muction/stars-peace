<?php


namespace Stars\Peace\Foundation;


use Illuminate\Http\Request;
use Stars\Peace\Contracts\ArticleHook;
use Stars\Peace\Entity\ArticleEntity;

class ArticleHookFoundation implements ArticleHook
{
    private $hook = null;

    public function __construct( ArticleHook $hook )
    {
        $this->hook = $hook ;
    }


    /**
     * 已删除
     * @param string $sheetTableName
     * @param int $bindId
     * @param int $infoId
     * @return mixed
     */
    public function deleted(string $sheetTableName, int $bindId, int $infoId = 0)
    {
        return $this->hook->deleted( $sheetTableName , $bindId, $infoId );
    }

    /**
     * 已保存
     * @param Request $request
     * @param string $sheetTableName
     * @param int $bindId
     * @param ArticleEntity $storage
     * @param int $infoId
     * @return mixed
     */
    public function saved(Request $request, string $sheetTableName, int $bindId, ArticleEntity $storage, int $infoId = 0)
    {
        return $this->hook->saved( $request , $sheetTableName , $bindId,  $storage ,$infoId );
    }
}
