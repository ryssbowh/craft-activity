<?php

namespace Ryssbowh\Activity\controllers;

use Ryssbowh\Activity\Activity;
use craft\web\Controller;

/**
 * @since 2.3.0
 */
class ExportController extends Controller
{
    /**
     * Index action
     */
    public function actionIndex()
    {
        $this->requirePermission('viewActivityLogs');
        $type = $this->request->getRequiredParam('type');
        $filters = $this->request->getParam('filters', []);
        $exporter = Activity::$plugin->exporters->getByHandle($type);
        $query = Activity::$plugin->logs->getLogsQuery($filters);
        Activity::$plugin->twigContext = 'export';
        $logs = array_map(function ($record) {
            return $record->toModel();
        }, $query->all());
        $content = $exporter->getExportContent($logs);
        $name = 'export-' . (new \DateTime())->format('YmdHis') . '.' . $exporter->extension;
        return $this->response->sendContentAsFile($content, $name, ['mimeType' => $exporter->mimeType]);
    }
}
