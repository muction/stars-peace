<?php


namespace Stars\Peace\Contracts;


interface SheetWidget
{
    /**
     * 增加一个文本输入框
     * @param $title
     * @param $dbName
     * @param $dbLength
     * @param $index
     * @param  $option
     * @return mixed
     */
    public function addTextWidget( $title, $dbName, $dbLength ,$option, $index   );

    /**
     * 增加一个秘密输入框
     * @param $title
     * @param $dbName
     * @param $dbLength
     * @param $index
     * @param  $option
     * @return mixed
     */
    public function addPasswordWidget( $title, $dbName, $dbLength ,$option, $index  );

    /**
     * 增加一个时间选择器
     * @param $title
     * @param $dbName
     * @param $index
     * @param  $option
     * @return mixed
     */
    public function addTimeWidget( $title, $dbName ,$option, $index  );

    /**
     * 增加一个下拉框
     * @param $title
     * @param $dbName
     * @param $dbLength
     * @param  $option
     * @param $index
     * @return mixed
     */
    public function addSelectWidget( $title, $dbName, $dbLength ,$option, $index  );

    /**
     * 增加一个上传组件
     * @param $title
     * @param $dbName
     * @param $dbLength
     * @param  $option
     * @param $index
     * @return mixed
     */
    public function addUploadWidget( $title, $dbName, $dbLength ,$option, $index  );

    /**
     * 增加一个裁剪组件
     * @param $title
     * @param $dbName
     * @param $dbLength
     * @param  $option
     * @param $index
     * @return mixed
     */
    public function addCropperWidget( $title, $dbName, $dbLength ,$option, $index  );

    /**
     * 增加一个编辑器组件
     * @param $title
     * @param $dbName
     * @param  $option
     * @param $index
     * @return mixed
     */
    public function addEditorWidget( $title, $dbName, $option, $index  );

    /**
     * 增加一个文本域
     * @param $title
     * @param $dbName
     * @param $dbLength
     * @param  $option
     * @param $index
     * @return mixed
     */
    public function addTextAreaWidget( $title, $dbName, $dbLength ,$option, $index  );

    /**
     * 增加一个单选按钮组
     * @param $title
     * @param $dbName
     * @param $dbLength
     * @param  $option
     * @param $index
     * @return mixed
     */
    public function addRadioGroupWidget( $title, $dbName, $dbLength ,$option, $index  );

    /**
     * 增加一个多选框按钮组
     * @param $title
     * @param $dbName
     * @param $dbLength
     * @param  $option
     * @param $index
     * @return mixed
     */
    public function addCheckboxGroupWidget( $title, $dbName, $dbLength ,$option, $index  );

    /**
     * 增加一个数字输入框
     * @param $title
     * @param $dbName
     * @param $dbLength
     * @param $option
     * @param $index
     * @return mixed
     */
    public function addNumberWidget(  $title, $dbName, $dbLength ,$option, $index  );

    /**
     * 添加百度地图
     * @param $title
     * @param $dbName
     * @param $option
     * @return mixed
     */
    public function addMapBaiDuWidget( $title, $dbName , $option);

}