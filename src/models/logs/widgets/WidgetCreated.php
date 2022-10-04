<?php

namespace Ryssbowh\Activity\models\logs\widgets;

use Ryssbowh\Activity\base\logs\ActivityLog;
use craft\base\Widget;

class WidgetCreated extends ActivityLog
{
    /**
     * Set widget
     *
     * @param Widget $widget
     */
    public function setWidget(Widget $widget)
    {
        $this->target_uid = $widget->id;
        $this->target_class = get_class($widget);
        $this->target_name = $widget->title;
    }

    /**
     * @inheritDoc
     */
    public function getTitle(): string
    {
        return \Craft::t('activity', 'Created widget {name}', ['name' => $this->target_name]);
    }
}