<?php
namespace Stars\Peace\Service;

use Stars\Peace\Entity\AttachmentEntity;
use Stars\Peace\Foundation\ServiceService;
use Illuminate\Http\Request;
use Stars\Rbac\Entity\UserEntity;

class DashboardService extends ServiceService
{

    public function attachmentTotal(){

        return AttachmentEntity::count();
    }

    public function adminTotal(){

        return UserEntity::count();
    }

    public function messageTotal(){

        return  0;
    }
}