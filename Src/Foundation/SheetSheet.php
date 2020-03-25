<?php
namespace Stars\Peace\Foundation;

use Stars\Peace\Contracts\Sheet ;
use Stars\Peace\Contracts\SheetColumn;
use Stars\Peace\Contracts\SheetWidget;
use Stars\Peace\Contracts\SheetOption;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * 模型
 * Class BaseSheet
 * @package Stars\Peace\Sheet
 */
abstract class SheetSheet implements Sheet,SheetOption,SheetWidget,SheetColumn
{
    use TraitSheet;

    /**
     * 模型初始化后的DB后的表名称
     * @var string
     */
    private $dbTableName = '';

    /**
     * 模型文件名
     * @var string
     */
    private $sheetClassName= '';

    /**
     * 模型名
     * @var string
     */
    private $sheetName= '';

    /**
     * 版本号
     * @var string
     */
    private $version = '';

    /**
     * 作者名字
     * @var string
     */
    private $author = '';

    /**
     * DB表前缀
     * @var string
     */
    private static $tablePrefix = '';

    /**
     * 模型字段集
     * @var array
     */
    private $columns = [] ;

    /**
     * 当前使用的绑定信息
     * @var array
     */
    private $bindInfo = [];

    /**
     * 文本输入框插件
     */
    const SUPPORT_WIDGET_TEXT = 'text';

    /**
     * 密码框插件
     */
    const SUPPORT_WIDGET_PASSWORD = 'password';

    /**
     * 时间选择器插件
     */
    const SUPPORT_WIDGET_TIME = 'time';

    /**
     * 多行文本插件
     */
    const SUPPORT_WIDGET_TEXTAREA = 'textarea';

    /**
     * 下拉框插件
     */
    const SUPPORT_WIDGET_SELECT = 'select';

    /**
     * 富文本编辑器插件
     */
    const SUPPORT_WIDGET_EDITOR = 'editor';

    /**
     * 代码编辑器
     */
    const SUPPORT_WIDGET_CODE_MIRROR = 'code_mirror';

    /**
     * 图片裁剪插件
     */
    const SUPPORT_WIDGET_CROPPER = 'cropper';

    /**
     * 单选按钮组插件
     */
    const SUPPORT_WIDGET_RADIOS = 'radios';

    /**
     * 复选框组插件
     */
    const SUPPORT_WIDGET_CHECKBOX = 'checkbox';

    /**
     * 多文件上传插件
     */
    const SUPPORT_WIDGET_UPLOAD = 'upload';

    /**
     * 数字数据框
     */
    const SUPPORT_WIDGET_NUMBER = 'number';

    /**
     * 百度地图
     */
    const SUPPORT_WIDGET_MAP_BAIDU = 'baidu_map';

    /**
     * 数字
     */
    const SUPPORT_COLUMN_NUMBER = 'number';

    /**
     * 文本类型
     *
     */
    const SUPPORT_COLUMN_CHAR = 'char';

    /**
     * 文本类型
     */
    const SUPPORT_COLUMN_VARCHAR = 'varchar';

    /**
     * int
     */
    const SUPPORT_COLUMN_INT = 'int';

    /**
     * tinyint
     */
    const SUPPORT_COLUMN_TINYINT ='tinyint';

    /**
     * datetime
     */
    const SUPPORT_COLUMN_DATETIME ='datetime';

    /**
     * 系统场景，系统自动增加的字段为系统场景
     */
    const SCENE_SYSTEM = 'system';

    /**
     * 应用程序场景，应用使用的为app场景
     */
    const SCENE_APP = 'app';

    /**
     * 系统作者
     */
    const AUTHOR = 'system';

    /**
     * 设置：图片缩略key
     */
    const OPTION_KEY_IMG = 'img_resize';

    /**
     * 设置：上传key
     */
    const OPTION_KEY_UPLOAD = 'upload';

    /**
     * 设置加密方式
     */
    const OPTION_KEY_ENCRYPT = 'encrypt';

    /**
     * 设置：下拉框key
     */
    const OPTION_KEY_SELECT = 'select';

    /**
     * 设置：从数据表读取数据
     */
    const OPTION_KEY_VALUE_TABLE = 'value_table';

    /**
     * 设置：默认值
     */
    const OPTION_KEY_DEFAULT_VALUE = 'default';

    /**
     * 设置：验证规则
     */
    const OPTION_KEY_VALIDATOR = 'validator';

    /**
     * 设置：共用
     */
    const OPTION_KEY_PUBLIC = 'public';

    /**
     * 构造方法
     * SheetSheet constructor.
     */
    public function __construct( array $bindInfo =[] )
    {
        /**
         * 设置绑定信息
         */
        $this->setBindInfo( $bindInfo );

        /**
         * 设置基本信息
         */
        $this->sheetInfo() ;

        /**
         * 设置字段信息
         */
        $this->sheetColumn() ;
    }

    /**
     * 定义模型信息
     * @return mixed
     */
    abstract protected function sheetInfo();

    /**
     * 定义字段集，支持方法请参考SheetField方法
     * @return mixed
     */
    abstract protected function sheetColumn();

    /**
     * 设置绑定信息
     * @param array $bindInfo
     */
    final public function setBindInfo(array $bindInfo ){

        $this->bindInfo = $bindInfo ;
    }

    /**
     * 只有当sheet真实应用到系统时才能有绑定信息
     * 获取绑定信息
     * @return array
     */
    final public function getBindInfo(){

        return $this->bindInfo;
    }

    /**
     * 获取当前模型的详细信息
     * @return mixed
     */
    final public function detail(){

        return [
            'info'=>[
                'tableName' => $this->dbTableName,
                'sheetName' =>  $this->sheetName ,
                'sheetClassName' => $this->sheetClassName ,
                'version'=>$this->version ,
                'author' =>$this->author ,
                'bindInfo'=> $this->getBindInfo()
            ],
            'columns'=> $this->columns
        ];
    }

    /**
     * 定义模型信息
     * @param $sheetName
     * @param $tableName
     * @param $version
     * @param string $author
     */
    final public function makeSheetInfo($sheetName , $tableName , $version,  $author = self::AUTHOR )
    {
        $this->dbTableName = self::dbTableName( $tableName );
        $this->sheetName = $sheetName ;
        $this->version = $version ;
        $this->author = $author ;
        $this->sheetClassName = class_basename( static::class ) ;
    }

    /**
     * 获取最终模型db表名
     * @param $sheetTableName
     * @return string
     */
    final public function dbTableName( $sheetTableName )
    {
        return strtolower( self::$tablePrefix . $sheetTableName );
    }

    /**
     * 统一设置图片缩放配置格式
     * @param $name
     * @param $width
     * @param $height
     * @return array
     */
    final public function optionImageResize($name, $width, $height)
    {
        return [
            self::OPTION_KEY_IMG =>[
                'name'=>$name,
                'width'=>$width,
                'height'=>$height
            ]
        ];
    }

    /**
     * 统一设置多重值选格式
     * @param $title
     * @param $value
     * @param bool $default
     * @return array
     */
    final public function optionSelectValue($title, $value, $default=false)
    {
        return [
            self::OPTION_KEY_SELECT =>[
                'title' => $title,
                'value' => $value,
                'default'=>$default
            ]
        ];
    }

    /**
     * 数据从另一个表中获得
     * @param $tableName
     * @param $titleField
     * @param $valueField
     * @param $whereRaw
     * @param $orderRaw
     * @param int $limit
     * @return mixed|void
     */
    final public function optionValueTable($tableName, $titleField, $valueField, $whereRaw, $orderRaw, $limit=100)
    {
        if(Schema::hasTable($tableName)){
            $data= DB::table($tableName)->whereRaw($whereRaw)->orderByRaw($orderRaw)->limit($limit)->get([$titleField, $valueField])->map(function ($value) {
                return (array)$value;
            });
            $data = $data ? $data->toArray() : [];

            return [self::OPTION_KEY_VALUE_TABLE=> [ 'column'=>['title'=>$titleField, 'value'=>$valueField ] , 'data'=>$data ]  ];
        }
        return [];
    }

    /**
     * 上传文件配置
     * @param int $fileSize
     * @param array $fileType
     * @return array|mixed
     */
    final public function optionUploadFile(int $fileSize, array $fileType)
    {

        return [
            self::OPTION_KEY_UPLOAD=>[
                'maxSize' => $fileSize,
                'fileType'=>$fileType
            ]
        ];
    }

    /**
     * Hash加密
     * @return mixed|void
     */
    final public function optionEncryptionHash()
    {
        return [
            self::OPTION_KEY_ENCRYPT => 'Hash'
        ];
    }

    /**
     * 验证规则
     * @param $type
     * @return mixed|void
     */
    final public function optionValidator($type='')
    {
        return [
            self::OPTION_KEY_VALIDATOR => $type
        ];
    }

    /**
     * 设置字段默认值
     * @param $defaultValue
     * @return mixed|void
     */
    final public function optionDefault($defaultValue)
    {
        return [self::OPTION_KEY_DEFAULT_VALUE => $defaultValue ];
    }

    /**
     * 增加一个文本输入框
     * @param $title
     * @param $dbName
     * @param $dbLength
     * @param $index
     * @param $scene
     * @param  $option
     * @return mixed
     */
    final public function addTextWidget($title, $dbName, $dbLength,   $option=[] ,$index=false )
    {

        $this->columns[$dbName] = [
            'title'  => $title ,
            'plug'   => self::SUPPORT_WIDGET_TEXT  ,
            'scene'  => self::SCENE_APP ,
            'db_name'=> $dbName,
            'db_length'  => $dbLength ,
            'db_index'   => $index,
            'options'    => $option
        ];
    }

    /**
     * 增加一个秘密输入框
     * @param $title
     * @param $dbName
     * @param $dbLength
     * @param $index
     * @param $scene
     * @param  $option
     * @return mixed
     */
    final public function addPasswordWidget($title, $dbName, $dbLength,   $option=[],$index=false)
    {

        $this->columns[$dbName] = [
            'title'  => $title ,
            'plug'   => self::SUPPORT_WIDGET_PASSWORD ,
            'scene'  => self::SCENE_APP ,
            'db_name'=> $dbName,
            'db_length'  => $dbLength ,
            'db_index'   => $index,
            'options'    => $option
        ];
    }

    /**
     * 增加一个时间选择器
     * @param $title
     * @param $dbName
     * @param $index
     * @param $scene
     * @param  $option
     * @return mixed
     */
    final public function addTimeWidget($title, $dbName, $option=[],$index=false )
    {
        $this->columns[$dbName] = [
            'title'  => $title ,
            'plug'   => self::SUPPORT_WIDGET_TIME  ,
            'scene'  =>self::SCENE_APP ,
            'db_name'=> $dbName,
            'db_length'  => '' ,
            'db_index'   => $index,
            'options'    => $option
        ];
    }

    /**
     * 整形输入框
     * @param $title
     * @param $dbName
     * @param $dbLength
     * @param $option
     * @param $index
     * @return mixed|void
     */
    final public function addNumberWidget($title, $dbName, $dbLength, $option=[], $index=false )
    {
        $this->columns[$dbName] = [
            'title'  => $title ,
            'plug'   => self::SUPPORT_WIDGET_NUMBER  ,
            'scene'  =>self::SCENE_APP ,
            'db_name'=> $dbName,
            'db_length'  => '' ,
            'db_index'   => $index,
            'options'    => $option
        ];
    }

    /**
     * 增加一个下拉框
     * @param $title
     * @param $dbName
     * @param $dbLength
     * @param $index
     * @param $scene
     * @param  $option
     * @return mixed
     */
    final public function addSelectWidget($title, $dbName, $dbLength, $option=[],$index=false)
    {
        $this->columns[$dbName] = [
            'title'  => $title ,
            'plug'   => self::SUPPORT_WIDGET_SELECT ,
            'scene'  =>self::SCENE_APP ,
            'db_name'=> $dbName,
            'db_length'  => $dbLength ,
            'db_index'   => $index,
            'options'    => $option
        ];
    }

    /**
     * 增加一个上传组件
     * @param $title
     * @param $dbName
     * @param $dbLength
     * @param $index
     * @param $scene
     * @param  $option
     * @return mixed
     */
    final public function addUploadWidget($title, $dbName, $dbLength, $option=[],$index=false)
    {

        $this->columns[$dbName] = [
            'title'  => $title ,
            'plug'   => self::SUPPORT_WIDGET_UPLOAD  ,
            'scene'  =>self::SCENE_APP ,
            'db_name'=> $dbName,
            'db_length'  => $dbLength ,
            'db_index'   => $index,
            'options'    => $option
        ];
    }

    /**
     * 增加一个裁剪组件
     * @param $title
     * @param $dbName
     * @param $dbLength
     * @param $index
     * @param $scene
     * @param  $option
     * @return mixed
     */
    final public function addCropperWidget($title, $dbName, $dbLength, $option=[],$index=false)
    {
        $this->columns[$dbName] = [
            'title'  => $title ,
            'plug'   => self::SUPPORT_WIDGET_CROPPER,
            'scene'  =>self::SCENE_APP ,
            'db_name'=> $dbName,
            'db_length'  => $dbLength ,
            'db_index'   => $index,
            'options'    => $option
        ];
    }

    /**
     * 增加一个编辑器组件
     * @param $title
     * @param $dbName
     * @param $index
     * @param  $option
     * @return mixed
     */
    final public function addEditorWidget($title, $dbName, $option=[],$index=false)
    {
        $this->columns[$dbName] = [
            'title'  => $title ,
            'plug'   => self::SUPPORT_WIDGET_EDITOR,
            'scene'  =>self::SCENE_APP ,
            'db_name'=> $dbName,
            'db_length'  => 0 ,
            'db_index'   => $index,
            'options'    => $option
        ];
    }

    /**
     * 增加一个文本域
     * @param $title
     * @param $dbName
     * @param $dbLength
     * @param $index
     * @param $scene
     * @param  $option
     * @return mixed
     */
    final public function addTextAreaWidget($title, $dbName, $dbLength, $option=[],$index=false)
    {
        $this->columns[$dbName] = [
            'title'  => $title ,
            'plug'   => self::SUPPORT_WIDGET_TEXTAREA ,
            'scene'  =>self::SCENE_APP ,
            'db_name'=> $dbName,
            'db_length'  => $dbLength ,
            'db_index'   => $index,
            'options'    => $option
        ];
    }

    /**
     * 增加一个单选按钮组
     * @param $title
     * @param $dbName
     * @param $dbLength
     * @param $index
     * @param $scene
     * @param  $option
     * @return mixed
     */
    final public function addRadioGroupWidget($title, $dbName, $dbLength, $option=[],$index=false )
    {
        $this->columns[$dbName] = [
            'title'  => $title ,
            'plug'   => self::SUPPORT_WIDGET_RADIOS ,
            'scene'  =>self::SCENE_APP ,
            'db_name'=> $dbName,
            'db_length'  => $dbLength ,
            'db_index'   => $index,
            'options'    => $option
        ];
    }

    /**
     * 增加一个多选框按钮组
     * @param $title
     * @param $dbName
     * @param $dbLength
     * @param $index
     * @param $scene
     * @param  $option
     * @return mixed
     */
    final public function addCheckboxGroupWidget($title, $dbName, $dbLength, $option=[],$index=false)
    {
        $this->columns[$dbName] = [
            'title'  => $title ,
            'plug'   => self::SUPPORT_WIDGET_CHECKBOX  ,
            'scene'  => self::SCENE_APP ,
            'db_name'=> $dbName,
            'db_length'  => $dbLength ,
            'db_index'   => $index,
            'options'    => $option
        ];
    }

    /**
     * 增加一个百度地图插件
     * @param $title
     * @param $dbName
     * @param array $option
     * @return mixed|void
     */
    final public function addMapBaiDuWidget($title, $dbName, $option=[] )
    {
        $this->columns[$dbName] = [
            'title'  => $title ,
            'plug'   => self::SUPPORT_WIDGET_MAP_BAIDU  ,
            'scene'  => self::SCENE_APP ,
            'db_name'=> $dbName,
            'db_length'  => 255 ,
            'db_index'   => false ,
            'options'    => $option
        ];
    }

    /**
     * 增加一个char类型
     * @param $title
     * @param $dbName
     * @param $dbLength
     * @param $option
     * @param bool $index
     * @return mixed|void
     */
    final public function addCharColumn($title, $dbName, $dbLength, $option=[] , $index=false )
    {
        $this->columns[$dbName] = [
            'title'  => $title ,
            'plug'   => self::SUPPORT_COLUMN_CHAR  ,
            'scene'  => self::SCENE_SYSTEM ,
            'db_name'=> $dbName,
            'db_length'  => $dbLength ,
            'db_index'   => $index,
            'options'    => $option
        ];
    }

    /**
     * @param $title
     * @param $dbName
     * @param bool $index
     * @return mixed|void
     */
    final public function addIntColumn($title, $dbName,  $index=false)
    {
        $this->columns[$dbName] = [
            'title'  => $title ,
            'plug'   => self::SUPPORT_COLUMN_INT  ,
            'scene'  => self::SCENE_SYSTEM ,
            'db_name'=> $dbName,
            'db_length'  => 0 ,
            'db_index'   => $index,
            'options'    => []
        ];
    }

    /**
     * @param $title
     * @param $dbName
     * @param bool $index
     * @return mixed|void
     */
    final public function addTinyintColumn($title, $dbName , $index=false)
    {
        $this->columns[$dbName] = [
            'title'  => $title ,
            'plug'   => self::SUPPORT_COLUMN_TINYINT  ,
            'scene'  => self::SCENE_SYSTEM ,
            'db_name'=> $dbName,
            'db_length'  => 0 ,
            'db_index'   => $index,
            'options'    => [],
        ];
    }

    /**
     * @param $title
     * @param $dbName
     * @param $dbLength
     * @param $option
     * @param bool $index
     * @return mixed|void
     */
    final public function addVarCharColumn($title, $dbName, $dbLength, $option=[] , $index=false)
    {
        $this->columns[$dbName] = [
            'title'  => $title ,
            'plug'   => self::SUPPORT_COLUMN_VARCHAR  ,
            'scene'  => self::SCENE_SYSTEM ,
            'db_name'=> $dbName,
            'db_length'  => $dbLength ,
            'db_index'   => $index,
            'options'    => $option
        ];
    }

    /**
     * datetime
     * @param $title
     * @param $dbName
     * @param $index
     * @return mixed|void
     */
    final public function addDatetimeColumn($title, $dbName, $index =false )
    {
        $this->columns[$dbName] = [
            'title'  => $title ,
            'plug'   => self::SUPPORT_COLUMN_DATETIME  ,
            'scene'  => self::SCENE_SYSTEM ,
            'db_name'=> $dbName,
            'db_length'  => '' ,
            'db_index'   => $index,
            'options'    => []
        ];
    }

    /**
     * 代码编辑器插件
     * @param $title
     * @param $dbName
     * @param array $option
     * @return mixed|void
     */
    final public function addCodeMirrorWidget($title, $dbName, $option=[] )
    {
        $this->columns[$dbName] = [
            'title'  => $title ,
            'plug'   => self::SUPPORT_WIDGET_CODE_MIRROR,
            'scene'  =>self::SCENE_APP ,
            'db_name'=> $dbName,
            'db_length'  => 0 ,
            'db_index'   => false ,
            'options'    => $option
        ];
    }


    /**
     * 当前系统支持的插件
     * @return array|bool|string
     */
    final public static function supportPlugs()
    {

        return [
            self::SUPPORT_WIDGET_CHECKBOX,
            self::SUPPORT_WIDGET_RADIOS,
            self::SUPPORT_WIDGET_TEXTAREA,
            self::SUPPORT_WIDGET_TEXT,
            self::SUPPORT_WIDGET_EDITOR,
            self::SUPPORT_WIDGET_CROPPER,
            self::SUPPORT_WIDGET_UPLOAD,
            self::SUPPORT_WIDGET_SELECT,
            self::SUPPORT_WIDGET_PASSWORD,
            self::SUPPORT_WIDGET_CODE_MIRROR ,
            self::SUPPORT_COLUMN_CHAR ,
            self::SUPPORT_COLUMN_INT,
            self::SUPPORT_COLUMN_NUMBER,
            self::SUPPORT_COLUMN_TINYINT,
            self::SUPPORT_COLUMN_VARCHAR,
        ];
    }

    /**
     * 字段配置
     * @return mixed
     */
    final public function columnOptions()
    {

        $return= [];
        $input= func_get_args() ;

        if($input){
            foreach ($input as $item){
                foreach ($item as $key=>$option){

                    if($key != SheetSheet::OPTION_KEY_PUBLIC){
                        $return[$key][]=$option;
                    }else{
                        foreach ($option as $it){
                            foreach ($it as $_key=>$val){
                                $return[$_key][] = $val;
                            }
                        }
                    }
                }
            }
            foreach ($return as $index=>$value){
                if(count($value) == 1){
                    $return[$index] = $value[0];
                }
            }
        }

        return $return;
    }

    /**
     * 获取sheets
     * @param bool $isCore
     * @return array
     */
    final public static function sheets(bool $isCore = false )
    {
        $result=[];
        $filesystem =new Filesystem();
        $sheetPath = $isCore === true ?  dirname(__DIR__) .'/Sheet/Core' : app_path('Sheet') ;
        $files = $filesystem->files( $sheetPath );
        if($files){
            foreach ($files as $file){
                $result[] =rtrim($file->getFilename(), '.php');
            }
        }
        return $result;
    }

}
