<?php
namespace Stars\Peace\Service;

use Stars\Peace\Entity\OptionEntity;
use Stars\Peace\Foundation\ServiceService;
use Illuminate\Http\Request;
use Stars\Peace\Lib\Option;

class OptionService extends ServiceService
{

    public function option(Request $request ){

        $submit = $request->only(['key' ,'value']);

        $options = array_combine( $submit['key'] , $submit['value'] );

        foreach ($options as $k=>$v){
            if(!$k || !$v){
                unset( $options[$k] );
            }
        }

        if(!$options){
            return false ;
        }


        return OptionEntity::storage( $options , Option::OPTION_TYPE_SYS );
    }

    /**
     * 所有配置
     * @return \Illuminate\Database\Eloquent\Collection|OptionEntity[]
     */
    public function items(){

        return OptionEntity::all();

    }

}