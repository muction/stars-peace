<?php


namespace Stars\Peace\Contracts;


interface Sheet
{
    /**
     * 定义sheet标准信息
     * @param $sheetName
     * @param $tableName
     * @param $version
     * @param string $author
     * @return mixed
     */
    public function makeSheetInfo( $sheetName ,$tableName , $version,  $author = '' );

    /**
     * 初始化一个sheet
     * @param $mode
     * @return mixed
     */
    public function initialize();

    /**
     * 获取sheets
     * @param bool $isCore
     * @return mixed
     */
    public static function sheets(bool $isCore);

}