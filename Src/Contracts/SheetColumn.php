<?php


namespace Stars\Peace\Contracts;


interface SheetColumn
{
    /**
     * char
     * @param $title
     * @param $dbName
     * @param $dbLength
     * @param $option
     * @param $index
     * @return mixed
     */
    public function addCharColumn( $title, $dbName, $dbLength ,$option , $index);

    /**
     * varchar
     * @param $title
     * @param $dbName
     * @param $dbLength
     * @param $option
     * @param $index
     * @return mixed
     */
    public function addVarCharColumn( $title, $dbName, $dbLength ,$option , $index );

    /**
     * int
     * @param $title
     * @param $dbName
     * @param $index
     * @return mixed
     */
    public function addIntColumn( $title, $dbName,  $index );

    /**
     * tinyint
     * @param $title
     * @param $dbName
     * @param $index
     * @return mixed
     */
    public function addTinyintColumn( $title, $dbName, $index );

    /**
     * datetime
     * @param $title
     * @param $dbName
     * @param $index
     * @return mixed
     */
    public function addDatetimeColumn ( $title, $dbName , $index );

}