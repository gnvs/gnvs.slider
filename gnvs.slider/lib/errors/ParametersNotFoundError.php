<?php

namespace Gnvs\Slider\Errors;

use Bitrix\Main\Error as BitrixError;
use Gnvs\Slider\GnvsLocale;

class ParametersNotFoundError extends BitrixError implements \Serializable
{

    public function serialize()
    {
        return serialize($this);
    }

    public function unserialize($data)
    {
        return unserialize($data, ['allowed_classes' => [static::class]]);
    }
}

