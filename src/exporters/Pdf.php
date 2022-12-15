<?php

namespace Ryssbowh\Activity\exporters;

use Mpdf\Mpdf;
use Mpdf\Output\Destination;
use Ryssbowh\Activity\base\Exporter;
use Ryssbowh\Activity\base\logs\ActivityLog;

/**
 * @since 2.3.0
 */
class Pdf extends Exporter
{
    /**
     * @inheritDoc
     */
    public function getHandle(): string
    {
        return 'pdf';
    }

    /**
     * @inheritDoc
     */
    public function getLabel(): string
    {
        return \Craft::t('activity', 'Export as Pdf');
    }

    /**
     * @inheritDoc
     */
    public function getExportContent(array $logs): string
    {
        $content = '';
        foreach ($logs as $log) {
            $title = $log->title;
            $content .= '<p>' . $log->getUserName() . ' - ' . $log->requestName . ' - ' . $log->dateCreated->format('d/m/Y H:i:s') . ' - ' . $title . '</p>';
            $content .= $log->description;
        }
        $mpdf = new Mpdf;
        $mpdf->WriteHTML($content);
        return $mpdf->Output('', Destination::STRING_RETURN);
    }

    /**
     * @inheritDoc
     */
    public function getMimeType(): string
    {
        return 'application/pdf';
    }

    /**
     * @inheritDoc
     */
    public function getExtension(): string
    {
        return 'pdf';
    }
}