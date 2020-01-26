<?php

namespace Stars\Peace\Controller;

use Illuminate\Http\Request;

/**
 * 系统帮助页
 * Class HelpController
 * @package Stars\Peace\Controller
 */
class HelpController extends PeaceController
{
    //
    public function index(){


        return $this->view( "help.index" );
    }
}
