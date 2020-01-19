<?php


namespace Stars\Peace\Console\Commands;


use Illuminate\Console\Command;

abstract class AppPatch extends Command
{
    public function handle(){
        $this->working();
    }

    abstract function working();

    /**
     * 校验文件是否存在
     * @param array $files
     * @return array
     */
    public function validFiles(array $files ){
        $return = ['valid'=>[] , 'inValid'=>[] ];
        foreach ($files as $file){
            if( file_exists( base_path( $file)) ){
                $return['valid'][] = $file;
            }else{
                $return['inValid'][] = $file;
            }
        }

        return $return;
    }

    /**
     * 生成补丁包名
     * @return string
     */
    public function makePatchName(){
        return 'PATCH.'.date('Ymd');
    }
}
