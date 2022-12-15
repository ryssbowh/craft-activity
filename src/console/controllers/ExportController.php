<?php

namespace Ryssbowh\Activity\console\controllers;

use Ryssbowh\Activity\Activity;
use craft\console\Controller;
use craft\helpers\FileHelper;
use yii\console\ExitCode;

/**
 * @since 2.3.0
 */
class ExportController extends Controller
{
    /**
     * @var array Log types filter (log type handles, comma separated). eg pluginEnabled,pluginDisabled
     */
    public array $types = [];

    /**
     * @var string Date from filter (d/m/Y)
     */
    public string $dateFrom = '';

    /**
     * @var string Date to filter (d/m/Y)
     */
    public string $dateTo = '';

    /**
     * @var array User filter (user ids, comma separated) eg 1,2
     */
    public array $users = [];

    /**
     * @var string Name of the file. [date] will be replaced by a datetime
     */
    public string $name = 'export-[date]';

    /**
     * @var string Export folder
     */
    public string $folder = '@storage/activity-exports';

    /**
     * Export activity logs to a file
     * 
     * @param  string $type type of export (text, pdf etc)
     * @return int
     */
    public function actionIndex(string $type)
    {
        $exporter = Activity::$plugin->exporters->getByHandle($type);
        $filters = $this->parseFilters();
        $query = Activity::$plugin->logs->getLogsQuery($filters);
        $this->name = str_replace('[date]', (new \DateTime)->format('YmdHis'), $this->name) . '.' . $exporter->extension;
        $this->folder = \Craft::getAlias($this->folder);
        $logs = $query->all();
        if (!$logs) {
            $this->stdOut(\Craft::t('activity', "There was no logs to export\n"));
            return ExitCode::OK;
        }
        $logs = array_map(function ($record) {
            return $record->toModel();
        }, $logs);
        $content = $exporter->getExportContent($logs);
        FileHelper::createDirectory($this->folder);
        $file = $this->folder . DIRECTORY_SEPARATOR . $this->name;
        file_put_contents($file, $content);
        $this->stdOut(\Craft::t('activity', "Exported " . sizeof($logs) . " logs to $file\n"));
        return ExitCode::OK;
    }

    /**
     * @inheritDoc
     */
    public function options($actionID): array
    {
        return array_merge(parent::options($actionID), [
            'types',
            'dateFrom',
            'dateTo',
            'users',
            'name',
            'folder'
        ]);
    }

    /**
     * Parse filters from options
     * 
     * @return array
     */
    protected function parseFilters(): array
    {
        $filters = [];
        if ($this->types) {
            $filters['types'] = $this->types;
        }
        if ($this->users) {
            $filters['users'] = $this->users;
        }
        if ($this->dateFrom) {
            $filters['dateFrom'] = $this->dateFrom;
        }
        if ($this->dateTo) {
            $filters['dateTo'] = $this->dateTo;
        }
        return $filters;
    }
}