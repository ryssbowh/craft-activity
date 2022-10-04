<?php

namespace Ryssbowh\Activity\models\logs\emails;

use Ryssbowh\Activity\base\logs\EmailLog;

class EmailFailed extends EmailLog
{
    /**
     * @inheritDoc
     */
    public function getTitle(): string
    {
        return \Craft::t('activity', 'Failed to send email "{subject}"', [
            'subject' => $this->data['subject']
        ]);
    }
}