<?php
namespace Stars\Peace\Foundation;

use Stars\Peace\Contracts\Entity;

use Illuminate\Database\Eloquent\Model;

class EntityEntity  extends Model implements Entity
{
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }

    /**
     * @return array
     */
    public static function now(){
        return [
            'created_at'=>date('Y-m-d H:i:s') ,
            'updated_at'=>date('Y-m-d H:i:s')
        ];
    }
}