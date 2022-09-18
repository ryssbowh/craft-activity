<?php

namespace Ryssbowh\Activity\models\logs\widgets;

class WidgetDeleted extends WidgetCreated
{
    /**
     * @inheritDoc
     */
    public function getTitle(): string
    {
        return \Craft::t('activity', 'Deleted widget {name}', ['name' => $this->target_name]);
    }
}