<?php

namespace Ryssbowh\Activity\exporters;

use League\Csv\Writer;
use Ryssbowh\Activity\base\Exporter;

/**
 * @since 3.1.8
 */
class Csv extends Exporter
{
    /**
     * @inheritDoc
     */
    public function getHandle(): string
    {
        return 'csv';
    }

    /**
     * @inheritDoc
     */
    public function getLabel(): string
    {
        return \Craft::t('activity', 'Export as CSV');
    }

    /**
     * @inheritDoc
     */
    public function getExportContent(array $logs): string
    {
        $format = \Craft::$app->getFormattingLocale()->getDateTimeFormat('short', 'php');
        $writer = Writer::fromString('');
        $writer->insertOne([
            \Craft::t('app', 'User'),
            \Craft::t('app', 'Site'),
            \Craft::t('activity', 'Request'),
            \Craft::t('activity', 'Activity'),
            \Craft::t('app', 'Date'),
        ]);
        foreach ($logs as $log) {
            $writer->insertOne([
                strip_tags($log->userName),
                $log->siteName,
                $log->requestName,
                strip_tags($log->title),
                $log->dateCreated->format($format),
            ]);
        }
        return $writer->toString();
    }

    /**
     * @inheritDoc
     */
    public function getMimeType(): string
    {
        return 'text/csv';
    }

    /**
     * @inheritDoc
     */
    public function getExtension(): string
    {
        return 'csv';
    }
}