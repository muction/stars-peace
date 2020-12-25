<?php
namespace Stars\Peace\Service;

use Stars\Peace\Entity\ArticleEntity;
use Stars\Peace\Entity\AttachmentEntity;
use Stars\Peace\Foundation\ArticleHookFoundation;
use Stars\Peace\Foundation\ServiceService;
use Stars\Peace\Foundation\SheetSheet;
use Stars\Peace\Lib\Option;
use Illuminate\Http\Request;

class ArticleService extends ServiceService
{

    /**
     * 表单必填项字段名称
     * @var array
     */
    public $bindRequiredColumns =[];

    /**
     * 列表字段名称
     * @var array
     */
    public $bindListColumns = [];

    /**
     * 列表搜索字段名称
     * @var array
     */
    public $bindListSearchColumns = [];

    /**
     * sheet表名
     * @var string
     */
    public $sheetTableName = '';

    /**
     * 全部原始sheet信息
     * @var array
     */
    public $sheetInfo = [];

    /**
     * sheet所有字段信息
     * @var array
     */
    public $sheetColumns = [];

    private $hook = null;

    /**
     * 初始化，解析当前shee所有相关信息
     * @param array $bindSheetInfo
     * @return $this
     */
    public function init( $bindSheetInfo=[] )
    {
        //绑定所有配置
        $this->sheetInfo = $bindSheetInfo ;

        //必填字段
        $this->bindRequiredColumns = isset( $bindSheetInfo['options']['column_required']) ?
            $bindSheetInfo['options']['column_required'] : [] ;

        //列表字段
        $this->bindListColumns = isset( $bindSheetInfo['options']['column_list']) ?
            $bindSheetInfo['options']['column_list'] : [] ;

        //搜索字段
        $this->bindListSearchColumns = isset( $bindSheetInfo['options']['column_search']) ?
            $bindSheetInfo['options']['column_search'] : [] ;

        //存储表名称
        $this->sheetTableName = isset( $bindSheetInfo['sheet']['info']['tableName'] ) ?
            $bindSheetInfo['sheet']['info']['tableName'] : '' ;

        //字段
        $this->sheetColumns = isset( $bindSheetInfo['sheet']['columns']) ?
            $bindSheetInfo['sheet']['columns'] : '' ;

        //是否配置了钩子
        $this->hook = config('stars.hook.articleHook');

        return $this;
    }

    /**
     * 存储操作
     * @param Request $request
     * @param array $assign
     * @param int $infoId
     * @return mixed
     * @throws \Exception
     */
    public function storage( Request $request,array $assign , $infoId=0 )
    {

        if( !$this->sheetTableName )
        {
            throw new \Exception( "您的SHEET配置出现严重问题，请联系管理员!" );
        }

        $storage = $request->only( array_keys( $this->sheetInfo['sheet']['columns'] ) );

        foreach ($storage as $index=>$item){
            if( is_array( $item)){
                $storage[$index] = implode( ',', $item );
            }
        }

        $article = new ArticleEntity();
        if($infoId > 0){
            $affect= $article->edit($this->sheetTableName, $assign['bindId'], $infoId, $storage );
            if( $this->hook ){
                //文章钩子
                $hook = new ArticleHookFoundation( new $this->hook() );
                $hook->saved( $request , $this->sheetTableName, $assign['bindId'], $affect, $infoId  );
            }

            return $affect;
        }


        $storage  =  $article->storage( $this->sheetTableName, $assign['bindId'] , $storage );
        if( $this->hook ){
            //文章钩子
            $hook = new ArticleHookFoundation( new $this->hook()  );
            $hook->saved(  $request , $this->sheetTableName, $assign['bindId'], $storage );
        }

        return $storage;
    }

    /**
     * 分页
     * @param $bindId
     * @param null $assign
     * @param null $bindListSearchColumns
     * @return mixed
     */
    public function pagation( $bindId, $assign=null , $bindListSearchColumns=null ){

        $article = new ArticleEntity();
        return $article->pageList(
            $this->sheetTableName,
            ['keyword'=>$assign['keyword'] ,'startTime'=>$assign['startTime'] ,'endTime'=>$assign['endTime' ]],
            $bindListSearchColumns,
            $bindId ,
            Option::ARTICLE_PAGE_SIZE );
    }

    /**
     * 删除
     * @param $bindId
     * @param $infoId
     * @return mixed
     */
    public function remove( $bindId, $infoId ){

        $article = new ArticleEntity();
        $remove= $article->remove( $this->sheetTableName, $infoId , $bindId  );
        if( $this->hook ){
            $hook = new ArticleHookFoundation( new $this->hook()  );
            $hook->deleted( $this->sheetTableName, $infoId , $bindId );
        }
        return $remove ;
    }

    /**
     * 获取一个信息
     * @param $bindId
     * @param $infoId
     * @return mixed
     */
    public function info( $bindId, $infoId ){
        $article = new ArticleEntity();
        return $article->info( $this->sheetTableName, $infoId , $bindId  );
    }

    /**
     * 合并
     * @param $bindId
     * @param $infoId
     * @return array
     */
    public function mergeColumnNowValue( $bindId, $infoId ){

        $info = $this->info( $bindId ,$infoId );
        if( $info ){
            $info = $info->toArray();
            foreach ( $info as $column=>$value){
                if(!isset($this->sheetInfo['sheet']['columns'][$column])){
                    continue;
                }
                //加入附件内容
                if($value && in_array($this->sheetInfo['sheet']['columns'][$column]['plug'] , [ SheetSheet::SUPPORT_WIDGET_UPLOAD , SheetSheet::SUPPORT_WIDGET_CROPPER ] ) ){
                    $attachmentInfo = AttachmentEntity::files($bindId, explode(',' , $value ) );
                    $value = $attachmentInfo ? $attachmentInfo->toArray() : [];
                }
                $this->sheetInfo['sheet']['columns'][$column]['now_value'] = $value;
            }
        }

        return $this->sheetInfo ;
    }
}
