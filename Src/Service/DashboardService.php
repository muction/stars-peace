<?php
namespace Stars\Peace\Service;

use Stars\Peace\Entity\AttachmentEntity;
use Stars\Peace\Foundation\ServiceService;
use Illuminate\Http\Request;
use Stars\Rbac\Entity\UserEntity;

class DashboardService extends ServiceService
{

    /**
     * 附件总数
     * @return mixed
     */
    public function attachmentTotal(){

        return AttachmentEntity::count();
    }

    /**
     * 后台账号
     * @return mixed
     */
    public function adminTotal(){

        return UserEntity::count();
    }
    
}
