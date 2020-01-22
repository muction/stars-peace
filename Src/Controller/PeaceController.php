<?php
namespace Stars\Peace\Controller;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PeaceController extends Controller
{
    protected $tableHead = [];

    /**
     * 响应视图
     * @param $template
     * @param array $data
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function view($template, $data=[]){

        if( $this->tableHead ){
            $data = array_merge( ['tableHead'=>$this->tableHead ] , $data );
        }
        if( stristr( static::class , 'Stars\Peace' ) ){
           return view( "StarsPeace::".$template, $data );
        }
        return view( $template, $data );
    }

    /**
     * 成功响应
     * @param array $data
     * @return array
     */
    public function responseSuccess( $data =[]  ){

         return $this->responseStructure( 200 ,0 , "操作成功" , $data );
    }

    /**
     * 失败响应
     * @param array $data
     * @param int $errorCode
     * @return array
     */
    public function responseError( $data = [] , $errorCode=0 ){
        return $this->responseStructure( 200 ,$errorCode , "操作失败" , $data );

    }

    /**
     * 富文本编辑器上传
     * @param $error
     * @param $url
     * @return array
     */
    public function responseKindUpload( $error, $url ){
        return [
            'error'=> $error ,
            'url' => $url
        ];
    }

    /**
     * 响应API结构体
     * @param $statusCode
     * @param $errorCode
     * @param $msg
     * @param $body
     * @return array
     */
    private function responseStructure( $statusCode, $errorCode , $msg, $body ){
        return [
            'status'=>$statusCode ,
            'error'=> $errorCode,
            'msg' => $msg ,
            'body'=> $body
        ];
    }

    /**
     * 增加头部定义
     * @param $key
     * @param $title
     * @param bool $isHide
     * @return array
     */
    public function addTableHead( $key, $title, $isHide = false ){
        $this->tableHead[]= [ 'key'=> $key , 'title'=> $title ,'hide'=>$isHide  ] ;
        return $this->tableHead;
    }
}
