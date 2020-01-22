<?php

namespace Stars\Peace\Controller;

use Illuminate\Http\Request;

class HelpController extends PeaceController
{
    //
    public function index(){


        return $this->view( "help.index" );
    }
}
