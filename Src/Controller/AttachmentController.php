<?php

namespace Stars\Peace\Controller;

use Stars\Peace\Service\AttachmentService;
use Stars\Peace\Service\MenuBindService;
use Illuminate\Http\Request;

/**
 * 系统附件上传控制器
 * Class AttachmentController
 * @package Stars\Peace\Controller
 */
class AttachmentController extends PeaceController
{

    private $allowClient = [ 'KindEditorImg' ,'KindEditor' ,'uploader' ];

    /**
     * 上传文件接口
     * @param Request $request
     * @param AttachmentService $attachmentService
     * @param $client
     * @return array
     * @throws \Exception
     */
    public function upload( Request $request, AttachmentService $attachmentService , $client)
    {

        if (!in_array( $client , $this->allowClient ))
            throw new \Exception("未授权的客户端上传，{$client}");

        $attachment= $attachmentService->upload( $request , $client) ;
        if($client == 'uploader')
            return $this->responseSuccess( $attachment );
        return $this->responseKindUpload( $attachment ? 0 :1 , $attachment['url'] );
    }

    /**
     * @param AttachmentService $attachmentService
     * @param $bindId
     * @param $attachmentIds
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function imgPreview(AttachmentService $attachmentService, $bindId, $attachmentIds )
    {

        $files = $attachmentService->files($bindId, $attachmentIds );
        return $this->view( "attachment.img.preview" , ['files'=>$files ]);
    }

    /**
     * @param AttachmentService $attachmentService
     * @param Request $request
     * @param MenuBindService $menuBindService
     * @param $column
     * @param $navId
     * @param $menuId
     * @param $bindId
     * @param $attachmentId
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function cropper(AttachmentService $attachmentService, Request $request, MenuBindService $menuBindService, $column, $navId, $menuId, $bindId, $attachmentId)
    {
        $result = [];
        $result['column'] = $column;
        $result['menuId'] = $menuId;
        $result['bindId'] = $bindId;
        $result['attachmentId'] = $attachmentId;
        $result['navId'] = $navId;
        $result['attachment'] = $attachmentService->info( $bindId , $attachmentId );
        $result['bindInfo'] = $menuBindService->bindSheetInfo( $menuId, $bindId ) ;
        $result['columnOption'] = isset( $result['bindInfo']['sheet']['columns'][$column] ) ? $result['bindInfo']['sheet']['columns'][$column] : [];

        return $this->view( "attachment.cropper" , $result );
    }

    /**
     * @param AttachmentService $attachmentService
     * @param Request $request
     * @return array
     */
    public function shear( AttachmentService $attachmentService, Request $request){
        $result = $attachmentService->shear( $request ,$attachmentService );
        return $this->responseSuccess($result );
    }

    /**
     * @param AttachmentService $attachmentService
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index( AttachmentService $attachmentService ){

        $datas = $attachmentService->pagination();
        return $this->view( "attachment.index" , ['datas'=>$datas ]);
    }

    /**
     * 文件管理
     * @param Request $request
     * @param AttachmentService $attachmentService
     * @return array
     */
    public function manager( Request $request, AttachmentService $attachmentService ){
        return json_encode($attachmentService->manager( $request )) ;
    }
}
