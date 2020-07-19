<?php
namespace Stars\Peace\Abstracts;
use Illuminate\Http\Request;

/**
 * App枢纽站规则定义
 * Interface AppHub
 * @package Stars\Peace\Contracts
 */
abstract class AppHub
{
    protected $viewPrefixName = '';
    protected $templateName = "";
    protected $assign = [];
    protected $useModel = false;
    public function __construct(Request $request, array $assign){
        $this->assign = $assign;
    }

    /**
     * 枢纽站路由
     * @param Request $request
     * @return mixed
     */
    abstract function router(Request $request);

    /**
     * 追加模板变量
     * @param $key
     * @param $value
     * @return mixed
     */
    protected function appendAssignData($key, $value){
        return $this->assign[$key] = $value;
    }

    /**
     * 设置模板名
     * @param $templateName
     * @return string
     */
    protected function setTemplateName($templateName){
        return $this->templateName = $templateName;
    }
    /**
     * 获取要使用的模板名
     * @return mixed
     */
    public function getTemplateName(){
        return $this->viewPrefixName.$this->templateName;
    }

    /**
     * 时是否使用模型
     * @return bool
     */
    public function getIsUseModel(){
        return $this->useModel;
    }

    /**
     * 设置时是否使用模型
     * @param bool $isUseModel
     * @return bool
     */
    public function setIsUseModel(bool $isUseModel){
        return $this->useModel = $isUseModel;
    }

    /**
     * 获取模板变量
     * @return mixed
     */
    public function getAssign(){
        return $this->assign;
    }
}
