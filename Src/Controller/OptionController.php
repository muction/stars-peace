<?php

namespace Stars\Peace\Controller;

use Stars\Peace\Service\OptionService;
use Illuminate\Http\Request;

class OptionController extends PeaceController
{
    //

    public function site( OptionService $optionService ){

        $options = $optionService->items();
        return $this->view ( "option.site" , ['options'=> $options]) ;
    }

    /**
     * @param Request $request
     * @param OptionService $optionService
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function storage( Request $request , OptionService $optionService ){

        $optionService->option( $request );
        return redirect( route( 'rotate.option.site' ));
    }


}
