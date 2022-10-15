<?php

namespace Ryssbowh\Activity\models\logs\addresses;

use Ryssbowh\Activity\base\logs\ActivityLog;

class AddressLayoutSaved extends ActivityLog
{
    /**
     * @inheritDoc
     */
    public function getTitle(): string
    {
        return \Craft::t('activity', 'Saved address layout');
    }

    /**
     * @inheritDoc
     */
    public function getDescription(): string
    {
        return \Craft::$app->view->renderTemplate('activity/descriptions/address-layout', [
            'log' => $this
        ]);
    }
}