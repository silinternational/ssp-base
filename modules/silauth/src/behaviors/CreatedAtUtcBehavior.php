<?php
namespace Sil\SilAuth\behaviors;

use Sil\SilAuth\time\UtcTime;
use yii\behaviors\AttributeBehavior;

class CreatedAtUtcBehavior extends AttributeBehavior
{
    /**
     * @inheritdoc
     *
     * If the [[value]] is `null`, the current UTC date/time (as a string) will
     * be used as the value.
     */
    protected function getValue($event)
    {
        if ($this->value === null) {
            return UtcTime::format();
        }
        return parent::getValue($event);
    }
}
