<?php

namespace Ryssbowh\Activity\recorders;

use Ryssbowh\Activity\Activity;
use Ryssbowh\Activity\base\recorders\Recorder;
use craft\mail\Mailer as CraftMailer;
use craft\mail\Message;
use yii\base\Event;

class Mailer extends Recorder
{
    /**
     * @inheritDoc
     */
    public function init(): void
    {
        Event::on(CraftMailer::class, CraftMailer::EVENT_AFTER_SEND, function(Event $event) {
            Activity::getRecorder('mailer')->onSend($event->message, $event->isSuccessful);
        });
    }
    
    /**
     * Record log when an email is sent
     */
    public function onSend(Message $message, bool $success)
    {
        $type = $success ? 'emailSent' : 'emailFailed';
        if (!$this->shouldSaveLog($type)) {
            return;
        }
        $this->commitLog($type, [
            'message' => $message
        ]);
    }
}