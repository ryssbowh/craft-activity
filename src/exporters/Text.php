<?php

namespace Ryssbowh\Activity\exporters;

use Ryssbowh\Activity\base\Exporter;
use Ryssbowh\Activity\base\logs\ActivityLog;

/**
 * @since 2.3.0
 */
class Text extends Exporter
{
    /**
     * @inheritDoc
     */
    public function getHandle(): string
    {
        return 'text';
    }

    /**
     * @inheritDoc
     */
    public function getLabel(): string
    {
        return \Craft::t('activity', 'Export as Text');
    }

    /**
     * @inheritDoc
     */
    public function getExportContent(array $logs): string
    {
        $content = '';
        foreach ($logs as $log) {
            $title = strip_tags($log->title);
            $content .= $this->getUserName($log) . ' - ' . $log->requestName . ' - ' . $log->dateCreated->format('d/m/Y H:i:s') . ' - ' . $title . PHP_EOL;
            $description = trim(strip_tags($log->description));
            if ($description) {
                $description = preg_replace('/[ ]{2}/', '', $description);
                $description = preg_replace('/[\n]+/', PHP_EOL, $description);
                $content .= $description . PHP_EOL;
            }
            $content .= PHP_EOL;
        }
        return $content;
    }

    /**
     * @inheritDoc
     */
    public function getMimeType(): string
    {
        return 'text/plain';
    }

    /**
     * @inheritDoc
     */
    public function getExtension(): string
    {
        return 'txt';
    }

    /**
     * User name getter
     *
     * @return string
     */
    protected function getUserName(ActivityLog $log): string
    {
        if ($log->user_id === 0) {
            return \Craft::t('app', 'System');
        }
        if ($log->user) {
            return $log->user->friendlyName;
        }
        return \Craft::t('activity', '{user} (deleted)', [
            'user' => $log->user_name
        ]);
    }
}
