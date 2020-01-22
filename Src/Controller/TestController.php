<?php

namespace Stars\Peace\Controller;

use Stars\Peace\Service\AttachmentService;
use Illuminate\Http\Request;

class TestController extends PeaceController
{

    public function test(AttachmentService $attachmentService){

        $attachmentService->crop();


    }
}
