<?php
namespace Stars\Peace\Entity;

use Stars\Peace\Foundation\EntityEntity;

/**
 * 无限分类
 * Class Limit
 * @package Stars\Peace\Entity
 */
class CategoryEntity extends EntityEntity
{
    use TraitCategory;

    protected $table ="categories";

    protected $fillable = [ 'title' ,'summary' ,'icon' ,'parent_id' ,'path' ,'type' ,'order' ,'status' , 'level' ];
}
