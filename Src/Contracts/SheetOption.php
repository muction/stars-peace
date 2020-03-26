<?php


namespace Stars\Peace\Contracts;


interface SheetOption
{
    /**
     * 字段配置
     * @return array
     */
    public function columnOptions();

    /**
     * 统一设置图片缩放配置格式
     * @param $name
     * @param $width
     * @param $height
     * @return array
     */
    public function optionImageResize( $name, $width, $height );

    /**
     * 统一设置多重值选格式
     * @param $title
     * @param $value
     * @param $default
     * @return array
     */
    public function optionSelectValue( $title, $value ,$default);

    /**
     * 数据从另外一个表查询
     * @param $tableName
     * @param $titleField
     * @param $valueField
     * @param $where
     * @param $order
     * @param $limit
     * @return mixed
     */
    public function optionValueTable( $tableName, $titleField, $valueField, $where, $order, $limit);

    /**
     * 上传文件配置
     * @param int $fileSize
     * @param array $fileType
     * @return mixed
     */
    public function optionUploadFile( int $fileSize , array $fileType);

    /**
     * 设置字段默认值
     * @param $defaultValue
     * @return mixed
     */
    public function optionDefault($defaultValue);

    /**
     * Hash加密
     * @return mixed
     */
    public function optionEncryptionHash();

    /**
     * 验证规则
     * @param $type
     * @return mixed
     */
    public function optionValidator( $type );

    /**
     * 时间日期格式
     * @return mixed
     */
    public function optionDateTimeFormat();

    /**
     * 日期格式
     * @return mixed
     */
    public function optionDateFormat();
}
