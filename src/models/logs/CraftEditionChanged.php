<?php

namespace Ryssbowh\Activity\models\logs;

use Ryssbowh\Activity\base\ActivityLog;

class CraftEditionChanged extends ActivityLog
{
    /**
     * @inheritDoc
     */
    public function getTitle(): string
    {
        $changed = $this->changedFields[0] ?? null;
        if (!$changed) {
            return Craft::t('activity', 'Changed Craft edition');
        }
        $pro = \Craft::t('activity', 'Pro');
        $solo = \Craft::t('activity', 'Solo');
        return \Craft::t('activity', 'Changed Craft edition from {from} to {to}', [
            'from' => $changed->data['f'] == 'pro' ? $pro : $solo,
            'to' => $changed->data['t'] == 'pro' ? $pro : $solo
        ]);
    }
}