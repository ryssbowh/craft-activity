<?php

namespace Ryssbowh\Activity\models\logs\widgets;

use Ryssbowh\Activity\base\ActivityLog;

class WidgetCreated extends ActivityLog
{
    public $widget;

    /**
     * @inheritDoc
     */
    public function getDbData(): array
    {
        return array_merge(parent::getDbData(), [
            'target_id' => $this->widget->id,
            'target_class' => get_class($this->widget),
            'target_name' => $this->widget->title
        ]);
    }

    /**
     * @inheritDoc
     */
    public function getTitle(): string
    {
        return \Craft::t('activity', 'Created widget {name}', ['name' => $this->target_name]);
    }
}