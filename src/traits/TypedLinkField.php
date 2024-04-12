<?php

namespace Ryssbowh\Activity\traits;

use Ryssbowh\Activity\models\fieldHandlers\elements\LinkField;
use Ryssbowh\Activity\models\fieldHandlers\projectConfig\LinkFieldTypes;
use Ryssbowh\Activity\services\FieldHandlers;
use Ryssbowh\Activity\services\Fields;
use yii\base\Event;

/**
 * @since 2.2.0
 * @deprecated in 3.0.0
 */
trait TypedLinkField
{
    /**
     * Register everything needed for Typed link fields tracking
     */
    protected function initTypedLinkField()
    {
        Event::on(FieldHandlers::class, FieldHandlers::EVENT_REGISTER_ELEMENT_HANDLERS, function (Event $event) {
            $event->add(LinkField::class);
        });
        Event::on(FieldHandlers::class, FieldHandlers::EVENT_REGISTER_PROJECTCONFIG_HANDLERS, function (Event $event) {
            $event->add(LinkFieldTypes::class);
        });
    }
}
