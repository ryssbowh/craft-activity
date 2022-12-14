<?php

namespace Ryssbowh\Activity\models\logs;

use Ryssbowh\Activity\base\logs\ActivityLog;

class MissingType extends ActivityLog
{
    /**
     * @inheritDoc
     */
    public function getTitle(): string
    {
        return '<i>' . \Craft::t('activity', 'Data missing') . '</i>';
    }
}