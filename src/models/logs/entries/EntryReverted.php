<?php

namespace Ryssbowh\Activity\models\logs\entries;

use Ryssbowh\Activity\base\logs\EntryLog;

class EntryReverted extends EntryLog
{
    /**
     * Revision num setter
     * 
     * @param int $revisionNum
     */
    public function setRevisionNum(int $revisionNum)
    {
        $this->data['revisionNum'] = $revisionNum;
    }

    /**
     * @inheritDoc
     */
    public function getTitle(): string
    {
        $title = $this->_getTitle() . ' {title}';
        $params = ['title' => $this->elementTitle];
        if ($this->includeSiteNameInTitle and \Craft::$app->isMultiSite) {
            $title .= ' in site {site}';
            $params['site'] = $this->elementSiteName;
        }
        $title .= ' to revision {revision}';
        $params['revision'] = $this->data['revisionNum'];
        return \Craft::t('activity', $title, $params);
    }
}