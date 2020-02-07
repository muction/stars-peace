<?php
namespace Stars\Peace\Entity;

use Stars\Peace\Foundation\EntityEntity;

class PermissionTypeEntity extends EntityEntity
{
    protected $table = 'permission_types';

    protected $fillable = ['title' ,'order'];



}
