<?php
namespace SimpleSAML\Module\silauth\Auth\Source\behaviors;

use SimpleSAML\Module\silauth\Auth\Source\time\UtcTime;
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
