<?php


namespace Stars\Peace\Foundation;


use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

trait TraitSheet
{
    /**
     * 初始化模型
     * @return bool
     * @throws \Exception
     */
    final public function initialize()
    {
        try {
           // dd( $this->columns );
            if(Schema::hasTable($this->dbTableName)){
                throw new \Exception( $this->dbTableName .' table exists !' );
            }

            $columns = $this->columns;
            Schema::create($this->dbTableName, function (Blueprint $table) use ($columns){

                //字段
                $table->increments('id')->comment("自增ID");

                foreach ($columns as $item){
                    switch ($item['plug']){

                        //char
                        case self::SUPPORT_WIDGET_PASSWORD:
                        case self::SUPPORT_COLUMN_CHAR:
                            $column = $table->char($item['db_name'] , $item['db_length']);
                            break;

                        //整形
                        case self::SUPPORT_WIDGET_NUMBER:
                        case self::SUPPORT_WIDGET_SELECT:
                        case self::SUPPORT_WIDGET_CROPPER:
                        case self::SUPPORT_COLUMN_INT :
                           $column = $table->integer($item['db_name'] ,false,true ) ;
                            break;

                        //小整形
                        case self::SUPPORT_COLUMN_TINYINT:
                            $column = $table->tinyInteger( $item['db_name'] );
                            break;

                        //varchar
                        case self::SUPPORT_WIDGET_CHECKBOX:
                        case self::SUPPORT_WIDGET_RADIOS:
                        case self::SUPPORT_WIDGET_TEXTAREA:
                        case self::SUPPORT_WIDGET_TEXT:
                        case self::SUPPORT_WIDGET_UPLOAD:
                        case self::SUPPORT_WIDGET_MAP_BAIDU:
                        case self::SUPPORT_COLUMN_VARCHAR:
                            $column = $table->string($item['db_name'], $item['db_length']) ;
                            break;

                        //text
                        case self::SUPPORT_WIDGET_EDITOR:
                            $column = $table->text( $item['db_name'] );
                            break;

                        //datetime
                        case self::SUPPORT_WIDGET_TIME:
                        case self::SUPPORT_COLUMN_DATETIME:
                            $column = $table->dateTime( $item['db_name'] );
                            break;

                        default:
                            throw new \Exception("initialize plug error for :".$item['plug'] );

                    }

                    //加入comment
                    $column->comment($item['title'] );

                    //是否需要字段支持索引
                    if($item['db_index'] === true ){

                        $column ->index($item['db_name'], $item['db_name'].'_index');
                    }
                    //是否有默认值
                    if(isset($item['options'][self::OPTION_KEY_DEFAULT_VALUE]) && $item['options'][self::OPTION_KEY_DEFAULT_VALUE]){
                        $column->default( $item['options'][self::OPTION_KEY_DEFAULT_VALUE] );
                    }else{
                        $column->nullable();
                    }
                }


                $table->timestamps();
                //设置引擎级字符集
                $table->engine = 'InnoDB';
                $table->charset = 'utf8mb4';
                $table->collation = 'utf8mb4_unicode_ci';
            });

            return true;
        }catch (\Exception $exception){
            throw new \Exception( 'Init Sheet Fail ,'.$exception->getMessage());
        }

    }

    /**
     * 公共的
     * @return array
     */
    public function optionPublicStatic()
    {
        return [
            self::OPTION_KEY_PUBLIC => [
                $this->optionSelectValue('是', '1',true ),
                $this->optionSelectValue('否', '0' )
            ]
        ];
    }
}