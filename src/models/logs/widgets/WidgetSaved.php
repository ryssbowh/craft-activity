<?php

namespace Ryssbowh\Activity\models\logs\widgets;

class WidgetSaved extends WidgetCreated
{
    /**
     * @inheritDoc
     */
    public function getTitle(): string
    {
        return \Craft::t('activity', 'Saved widget {name}', ['name' => $this->target_name]);
    }
}