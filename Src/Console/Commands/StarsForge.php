<?php

namespace Stars\Peace\Console\Commands;

use Exception;
use Illuminate\Support\Facades\DB;
use Stars\Peace\Entity\ArticleEntity;
use Stars\Peace\Foundation\SheetSheet;
use Stars\Peace\Service\MenuBindService;
use Stars\Peace\Service\NavMenuService;
use Stars\Peace\Service\NavService;
use Stars\Peace\Service\SheetService;

/**
 * 给特定绑定填入测试数据
 * Class StarsForge
 * @package Stars\Peace\Console\Commands
 */
class StarsForge extends PeacePeace
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stars:forge';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '给 stars系统绑定模生成测试数据';

    /**
     * 定义参数名称
     * @var string
     */
    protected $entityName = '';

    /**
     * 指定类型
     * @var string
     */
    protected $type = 'Forge';

    /**
     * @var bool
     */
    protected $isCore = true;

    /**
     * 生成语言
     * @var string
     */
    private $language = 'zh';

    /**
     * @var array
     */
    private $formatInputString = [] ;

    /**
     * Execute the console command.
     *
     * @return false|void
     * @throws Exception
     */
    public function handleCommand()
    {
        $allowModel = [1,2];
        $selectModel= 0;

        if( env('APP_ENV') !='local'  ){
            if( $this->confirm("警告：当前为非开发环境，为了安全起见建议您终止操作!" , true )){
                return false;
            }
        }

        while ( $selectModel == 0){

            $this->info("=============================================================================================================");
            $this->info( '请选择运行模式: ');
            $this->info( '1、 指定菜单，绑定 模式: [menuId1:bindId1,bindId2,bindId3 OR menuId2:bindId1 OR menuId OR menuId1 menuId2]');
            $this->info( '2、 指定导航模式： [navId1 navId2] ');
            $this->info("=============================================================================================================");

            $selectModel= $this->ask('请输入运行模式代号') ;

            if(!in_array($selectModel, $allowModel)){
                $selectModel= 0;
            }
        }

        while (!$this->entityValue){
          $this->entityValue =  $this->ask("请输入参数");
        }

        $this->info("伪造数据现在开始");

        $this->formatInputString() ;

        //伪造数据运行模式
        switch ( $selectModel ){

            //绑定模式
            case 1 :

                $this->forgeByBindIds() ;
                break;

            //导航模式
            case 2 :

                $this->forgeByNavIds();
                break;
            default:
        }

        $this->info("伪造数据完成");
    }

    /**
     * 替换模板内容
     * @return mixed
     * @throws Exception
     */
    protected function replaceStubContent()
    {
        return '';
    }

    /**
     * 根据绑定ID生成伪造数据
     * 仅指定某个绑定 140:47,48,49 250:47,48,49
     * 140:47,48,49
     * 140:
     * 140
     * 140 165:99,22 1:2
     * 140:6633...
     */
    private function forgeByBindIds(){

        return $this->makeFormatData( $this->formatInputString );
    }

    /**
     * 根据导航ID自动生成所有菜单数据
     *  常规： 1 2 34 5
     *  排除某个绑定： 12 2:48,49 99
     */
    private function forgeByNavIds(){

        $formatInputData = [];
        $navMenuService = new NavMenuService();
        $navService = new NavService();
        foreach ($this->formatInputString as $navId=>$excludeBindId )
        {
            $navInfo = $navService->info( $navId );
            if($navInfo && $navInfo->article ==0 ){
                $this->info("数据保护，跳过导航 {$navId}");
                continue;
            }

            $menus = $navMenuService->navMenus( $navId ,true );
            if( $menus ){
                foreach ( $menus as $menu){
                    $bindIds = array_column( $menu['binds'] , 'id' ) ;
                    $formatInputData[$menu['id']] = array_diff( $bindIds ,$excludeBindId ) ;
                }
            }
        }

        return $this->makeFormatData( $formatInputData );
    }

    /**
     * 格式化输入的字符串
     * @return array
     */
    private  function formatInputString(){

        $formatInputValue = explode( ' ', $this->entityValue) ;

        foreach ($formatInputValue as $command){
            if(stristr( $command , ':') )
            {
                $findPosition = stripos($command , ':');
                $navId  = substr( $command , 0, $findPosition );
                $this->formatInputString[$navId] = [];

                $last = substr( $command , $findPosition +1 );
                $this->formatInputString[$navId] = $last ? explode(',' ,$last) : [] ;

            }else{
                $this->formatInputString[$command] = [];
            }
        }
        return $this->formatInputString;
    }
    /**
     * 对格式化后的数据开始生成数据
     * @param array $formatInput
     * @return array
     */
    private function makeFormatData(array $formatInput )
    {
        $result = [];
        $menuBindService = new MenuBindService();
        foreach ($formatInput as $menuId=>$bindIds){

            if( !$bindIds ){
                $allBindInfos = $menuBindService->bindAllInfo( $menuId );
                $bindIds = $allBindInfos ? array_column( $allBindInfos , 'id') : [];
            }

            if($bindIds){
                $bindIdInfo = implode(',',  $bindIds );
                $this->info( "开始处理菜单：{$menuId} ，绑定: {$bindIdInfo}" );
                foreach ($bindIds as $bindId ){
                    if( !is_numeric( $bindId)) {
                        $result[$menuId][] = "数据错误，略过:{$bindId}" ;
                        continue ;
                    }
                    $makeResult = $this->makeForgeData( $menuId, $bindId );
                    if( $makeResult === true ){
                        $this->info("成功 : {$menuId}:{$bindId} ");
                    }else{
                        $this->warn("失败: {$menuId}:{$bindId} - {$makeResult}");
                    }

                    $result[$menuId][] = $makeResult === true ? "成功" : $makeResult ;
                }
            }
        }
        return $result;
    }

    /**
     * 生成伪造数据
     * @param $menuId
     * @param $bindId
     * @return bool
     */
    private function makeForgeData( $menuId, $bindId ){

        try{
            $menuBindService = new MenuBindService() ;
            $bindInfo = $menuBindService->bindInfo( $menuId, $bindId ) ;
            if(!$bindInfo){
                throw new Exception( "绑定信息没有找到" );
            }

            $sheetService = new SheetService();
            $sheetInfo = $sheetService->info( $bindInfo['sheet_name'] );
            if(!$sheetInfo){
                throw new Exception( 'sheet信息没有找到');
            }

            $sheetInfo = $sheetInfo->detail();
            $bindInfo = $bindInfo->toArray();

            $forgeData = [];
            $dataLimit =10 ;
            if(stristr(  $bindInfo['alias_name'] , 'single' ) ){
                $dataLimit = 1;
            }

            for($i=0; $i<$dataLimit ; $i++){
                $tmp = [
                    'created_at'=>date('Y-m-d H:i:s') ,
                    'updated_at'=>date('Y-m-d H:i:s') ,
                ];
                foreach ($sheetInfo['columns'] as $columnName=>$columnInfo ){
                    if( $columnName == 'bind_id' ){
                        $value = $bindId ;
                    }else if ($columnName == 'order'){
                        $value = 10;
                    }else if($columnInfo['plug'] == SheetSheet::SUPPORT_WIDGET_TEXT ){
                        $value = $this->makeChars() ;
                    }else if( $columnInfo['plug'] == SheetSheet::SUPPORT_COLUMN_DATETIME ){
                        $value= date('Y-m-d H:i:s');
                    }else if( $columnInfo['plug'] == SheetSheet::SUPPORT_WIDGET_TIME ){
                        $value= date('Y-m-d H:i:s');
                    }else if( $columnInfo['plug'] == SheetSheet::SUPPORT_WIDGET_TEXTAREA ){
                        $value = $this->makeChars();
                    }else if( $columnInfo['plug'] == SheetSheet::SUPPORT_WIDGET_EDITOR ){
                        $value = $this->makeChars();
                    }else if( $columnInfo['plug'] == SheetSheet::SUPPORT_WIDGET_UPLOAD  ){
                        $value = 1;
                    }else if( $columnInfo['plug'] == SheetSheet::SUPPORT_WIDGET_CROPPER ){
                        $value = 1;
                    }else if( $columnInfo['plug'] == SheetSheet::SUPPORT_WIDGET_RADIOS ){
                        $value = 1;
                    }else if( $columnInfo['plug'] == SheetSheet::SUPPORT_COLUMN_INT ){
                        $value = rand(0,100);
                    }else{
                        $value = '';
                    }

                    $tmp[$columnName] = $value ;
                }
                $forgeData[] =$tmp;
            }

            if( is_array($forgeData) && $forgeData ){
               //clear old 数据
                DB::table( $bindInfo['table_name']  )->where('bind_id', $bindId )->delete();

                //批量写入
                DB::table( $bindInfo['table_name']  )->insert($forgeData ) ;
            }

            return true;

        }catch (Exception $exception){

            return $exception->getMessage() ;
        }
    }

    /**
     * 生成汉字或英文单词
     * @return string
     */
    private function makeChars(){

        if( $this->language == 'zh'){
            return $this->makeZhString( 15 );
        }elseif ( $this->language == 'en'){
            return $this->makeEnString( 30 );
        }else{
            return '';
        }
    }

    /**
     * 生成汉字
     * @param $num
     * @return string
     */
    private function makeZhString($num){
        $b = '';
        for ($i=0; $i<$num; $i++) {
            // 使用chr()函数拼接双字节汉字，前一个chr()为高位字节，后一个为低位字节
            $a = chr(mt_rand(0xB0,0xD0)).chr(mt_rand(0xA1, 0xF0));
            // 转码
            $b .= iconv('GB2312', 'UTF-8', $a);
        }
        return $b;
    }

    /**
     * 生成英文单词
     * @param int $num
     * @return string
     */
    private function makeEnString( $num=4){
        $code = '';
        for($k=1;$k<= $num ;$k++){
            $word='';
            for($i=1;$i<= 6 ;$i++){
                $word .= chr(rand(97,122));
            }
            $code .= ' '. ucwords($word) ;
        }
        return $code;
    }
}
