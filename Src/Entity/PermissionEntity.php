<?php
namespace Stars\Peace\Entity;

use Stars\Peace\Foundation\EntityEntity;

class PermissionEntity extends EntityEntity
{
    protected $table = 'permissions';

    protected $fillable = ['type' ,'title' ,'display_name' ,'description' ,'status'];

}
