<?php

namespace Ryssbowh\Activity\models\fieldHandlers\projectConfig;

use craft\ckeditor\Plugin;
use craft\services\ProjectConfig;

class CkEditorConfig extends DefaultHandler
{
    /**
     * @inheritDoc
     */
    public function init(): void
    {
        parent::init();
        if ($this->value) {
            $this->fancyValue = $this->getConfigName($this->value);
        }
    }

    /**
     * @inheritDoc
     */
    protected static function _getTargets(): array
    {
        return [
            ProjectConfig::PATH_FIELDS . '.{uid}.settings[craft\\ckeditor\\Field].ckeConfig'
        ];
    }

    /**
     * @inheritDoc
     */
    public function hasFancyValue(): bool
    {
        return true;
    }

    /**
     * Get config name
     *
     * @param string $uid
     * @return string
     */
    protected function getConfigName(string $uid): string
    {
        $config = Plugin::getInstance()->getCkeConfigs()->getByUid($uid);
        return $config ? $config->name : '';
    }
}
