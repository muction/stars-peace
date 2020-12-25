<?php
namespace Stars\Peace\Controller;
use Illuminate\Support\MessageBag;

/**
 *
 * ------------------------------------------------------------------------
 *
 *  响应控制
 *
 *
 *
 * ------------------------------------------------------------------------
 *
 */
trait ResponseContent
{
    /**
     * 成功响应
     * @param array $data
     * @return array
     */
    public function responseSuccess( $data =[]  ){
        $data = !is_array($data) && !$data? [] : $data;
        return $this->responseStructure( 200 ,0 , "操作成功" , $data );
    }

    /**
     * 失败响应
     * @param array $data
     * @param int $errorCode
     * @param string $message
     * @param int $statusCode
     * @return array
     */
    public function responseError( $data = [] , $errorCode=1, $message="操作失败", $statusCode=200){
        return $this->responseStructure( $statusCode ,$errorCode , $message , $data );
    }

    /**
     * 响应校验器
     * @param MessageBag $error
     * @param int $errorCode
     * @param string $message
     * @return array
     */
    public function responseValidatorError(MessageBag $error , $errorCode=1 , $message="操作失败"){
        $error=$error->toArray();
        $erMsg=[];
        if($error && is_array($error)){
            foreach ($error as $msg){
                if(isset($msg[0]) && $msg[0]){
                    $erMsg[] = $msg[0];
                }
            }
            $message = $erMsg && is_array($erMsg) ? implode(',', $erMsg) : $message;
        }
        return $this->responseError( $error , $errorCode ,$message );
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
     * 成功响应 - 第三方
     * @param array $data
     * @return array
     */
    public function responseOutputSuccess( $data =[]  ){
        $data = !is_array($data) && !$data? [] : $data;
        return $this->responseOutputStructure(  1 , "操作成功" , $data );
    }


    /**
     * 响应API结构体 - 第三方
     * @param $errorCode 1 成功 0 失败
     * @param $msg
     * @param $body
     * @return array
     */
    private function responseOutputStructure( $errorCode , $msg, $body ){
        return [
            'status_code'=> $errorCode,
            'msg' => $msg ,
            'body'=> $body
        ];
    }
}
