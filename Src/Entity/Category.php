<?php
namespace Stars\Peace\Entity;

use Stars\Peace\Foundation\EntityEntity;

/**
 * 无限分类
 * Class Limit
 * @package Stars\Peace\Entity
 */
class Category extends EntityEntity
{
    use TraitCategory;

    protected $fillable = [ 'title' ,'summary' ,'icon' ,'parent_id' ,'path' ,'type' ,'order' ,'status' , 'level' ];
}