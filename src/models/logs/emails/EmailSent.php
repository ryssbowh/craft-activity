<?php

namespace Ryssbowh\Activity\models\logs\emails;

use Ryssbowh\Activity\base\logs\EmailLog;

class EmailSent extends EmailLog
{
    /**
     * @inheritDoc
     */
    public function getTitle(): string
    {
        return \Craft::t('activity', 'Sent email "{subject}"', [
            'subject' => $this->data['subject']
        ]);
    }
}