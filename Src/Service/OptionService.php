<?php
namespace Stars\Peace\Service;

use Stars\Peace\Entity\OptionEntity;
use Stars\Peace\Foundation\ServiceService;
use Illuminate\Http\Request;
use Stars\Peace\Lib\Option;

class OptionService extends ServiceService
{

    /**
     * 快速配置
     * @param Request $request
     * @return array|bool
     */
    public function option(Request $request ){

        $submit = $request->only(['key' ,'value']);
        $options = array_combine( $submit['key'] , $submit['value'] );
        $storage=[] ;
        foreach ($options as $k=>$v){
            if($k){
                $storage[] = ['key'=>$k ,'value'=>$v ,'type'=>Option::OPTION_TYPE_SYS ];
            }
        }
        return OptionEntity::storage( $storage  );
    }

    /**
     * 所有配置
     * @return \Illuminate\Database\Eloquent\Collection|OptionEntity[]
     */
    public function items(){
        return OptionEntity::all();
    }

    /**
     * 获取配置
     * @param $key
     * @param $type
     * @return mixed
     */
    public function getItem( $key , $type ){
        return OptionEntity::where('key', $key)
            ->where('type', $type)
            ->get();
    }
}
