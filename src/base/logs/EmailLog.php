<?php

namespace Ryssbowh\Activity\base\logs;

use craft\mail\Message;

abstract class EmailLog extends ActivityLog
{
    /**
     * Set message
     * 
     * @param Message $message
     */
    public function setMessage(Message $message)
    {
        $this->target_name = $message->key;
        $this->data = [
            'cc' => $message->getCc(),
            'bcc' => $message->getBcc(),
            'to' => $message->getTo(),
            'subject' => $message->getSubject()
        ];
    }

    /**
     * @inheritDoc
     */
    public function getDescription(): string
    {
        return \Craft::$app->view->renderTemplate('activity/descriptions/email', [
            'log' => $this
        ]);
    }

    /**
     * Get to emails
     * 
     * @return string
     */
    public function getTo(): string
    {
        return $this->getEmails($this->data['to']);
    }

    /**
     * Get cc emails
     * 
     * @return string
     */
    public function getCc(): string
    {
        return $this->getEmails($this->data['cc']);
    }

    /**
     * Get bcc emails
     * 
     * @return string
     */
    public function getBcc(): string
    {
        return $this->getEmails($this->data['bcc']);
    }

    /**
     * Get stringified emails
     * 
     * @param  array $emails
     * @return string
     */
    protected function getEmails(array $emails): string
    {
        $str = [];
        foreach ($emails as $email => $name) {
            if ($name) {
                $str[] = $name . ' (' . $email . ')';
            } else {
                $str[] = $email;
            }
        }
        return implode(', ', $str);
    }
}