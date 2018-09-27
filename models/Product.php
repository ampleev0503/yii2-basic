<?php

namespace app\models;

use Yii;
use yii\base\Model;

class Product extends Model
{
    public $id;
    public $name;
    public $category;
    public $cost;
}
