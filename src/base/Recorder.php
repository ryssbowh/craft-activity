<?php

namespace Ryssbowh\Activity\base;

use Ryssbowh\Activity\Activity;
use Ryssbowh\Activity\exceptions\ActivityTypeException;
use craft\base\Component;

abstract class Recorder extends Component
{
    public bool $stopRecording = false;
    public ?string $isTriggered;

    protected function saveLog(string $type, array $params)
    {
        $this->beforeTriggered($type);
        try {
            $class = Activity::$plugin->types->getTypeClassByHandle($type);
        } catch (ActivityTypeException $e) {
            \Craft::$app->errorHandler->logException($e);
            return;
        }
        $log = new $class($params);
        $log->save();
    }

    protected function isTypeIgnored(string $type): bool
    {
        return Activity::$plugin->settings->isTypeIgnored($type);
    }

    protected function shouldSaveLog(string $type): bool
    {
        return !$this->stopRecording and !$this->isTypeIgnored($type);
    }

    protected function beforeTriggered(string $type)
    {
        $this->isTriggered = $type;
    }
}