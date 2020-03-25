<?php
namespace Stars\Peace\Service;

use Stars\Peace\Foundation\ServiceService;
use Stars\Peace\Foundation\SheetSheet;

class SheetService extends ServiceService
{
    /**
     * @return array|bool
     */
    public function sheets(){
        $return =[];
        $allSheets= SheetSheet::sheets() ;
        foreach ($allSheets as $sheet) {
            $sheetObj = $this->info( $sheet );
            $return[$sheet] = $sheetObj ? $sheetObj->detail() : $sheetObj;
        }
        return $return;
    }

    /**
     * @param $sheetName
     * @return |null
     */
    public function info( $sheetName , array $bindInfo = [] ){
        $sheetNamespace = 'App\\Sheet\\'.$sheetName;
        if(class_exists( $sheetNamespace )){
            return new $sheetNamespace( $bindInfo ) ;
        }
        return null ;
    }
}
