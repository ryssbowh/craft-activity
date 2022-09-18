<?php

namespace Ryssbowh\Activity\recorders;

use Ryssbowh\Activity\Activity;
use Ryssbowh\Activity\base\Recorder;
use craft\services\Routes as CraftRoutes;
use yii\base\Event;

class Routes extends Recorder
{
    /**
     * @inheritDoc
     */
    public function init()
    {
        Event::on(CraftRoutes::class, CraftRoutes::EVENT_AFTER_SAVE_ROUTE, function ($event) {
            Activity::getRecorder('routes')->onSaved($event->uriParts, $event->template, $event->siteUid);
        });
        Event::on(CraftRoutes::class, CraftRoutes::EVENT_AFTER_DELETE_ROUTE, function ($event) {
            Activity::getRecorder('routes')->onDeleted();
        });
    }
        
    /**
     * Save a log when a route is saved
     * 
     * @param array  $uriParts
     * @param string $template
     * @param string $siteUid
     */
    public function onSaved(array $uriParts, string $template, ?string $siteUid)
    {
        if (!$this->shouldSaveLog('routeSaved')) {
            return;
        }
        $site = null;
        if ($siteUid) {
            $site = \Craft::$app->sites->getSiteByUid($siteUid);
        }
        $this->saveLog('routeSaved', [
            'changedFields' => [[
                'uriParts' => $uriParts,
                'template' => $template,
                'siteUid' => $siteUid,
                'siteName' => $site ? $site->name : \Craft::t('app', 'Global')
            ]]
        ]);
    }

    /**
     * Save a log when a route is deleted
     */
    public function onDeleted()
    {
        if (!$this->shouldSaveLog('routeDeleted')) {
            return;
        }
        $this->saveLog('routeDeleted', []);
    }
}