<?php

namespace Ryssbowh\Activity\models\logs\users;

use Ryssbowh\Activity\base\logs\ActivityLog;

class UserLayoutSaved extends ActivityLog
{
    /**
     * @inheritDoc
     */
    public function getTitle(): string
    {
        return \Craft::t('activity', 'Saved user layout');
    }

    /**
     * @inheritDoc
     */
    public function getDescription(): string
    {
        return \Craft::$app->view->renderTemplate('activity/descriptions/user-layout', [
            'log' => $this
        ]);
    }
}