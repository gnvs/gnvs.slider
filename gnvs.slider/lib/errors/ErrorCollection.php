<?php

namespace Gnvs\Slider\Errors;

use Bitrix\Main\ErrorCollection as BitrixErrorCollection;

class ErrorCollection extends BitrixErrorCollection implements \Serializable
{

    public function serialize()
    {
        return serialize($this->values);
    }

    public function unserialize($data)
    {
        return unserialize($data, ['allowed_classes' => [static::class]]);
    }
}