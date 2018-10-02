<?php

namespace app\components;

use yii\base\Component;

class TestService extends Component
{
    public $prop = "yes";

    public function turnOn()
    {
        return $this->prop;
    }

}