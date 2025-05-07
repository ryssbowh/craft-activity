<?php

namespace Ryssbowh\Activity\models\fieldHandlers\projectConfig;

use craft\services\ProjectConfig;
use craft\helpers\ProjectConfig as ProjectConfigHelper;

/**
 * @since 3.0.0
 */
class EntryTypes extends DefaultHandler
{
    /**
     * @inheritDoc
     */
    public function init(): void
    {
        if ($this->value) {
            $this->fancyValue = [];
            $this->value = ProjectConfigHelper::unpackAssociativeArrays($this->value);
            foreach ($this->value as $data) {
                $this->fancyValue[] = $this->getTypeName(is_array($data) ? $data['uid'] : $data);
            }
            $this->fancyValue = implode(', ', $this->fancyValue);
        }
    }

    /**
     * @inheritDoc
     */
    public function hasFancyValue(): bool
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    protected static function _getTargets(): array
    {
        return [
            ProjectConfig::PATH_SECTIONS . '.{uid}.entryTypes',
            ProjectConfig::PATH_FIELDS . '.{uid}.settings[craft\\fields\\Matrix].entryTypes',
        ];
    }

    /**
     * Get an entry type name by uid
     *
     * @param  string $uid
     * @return string
     */
    protected function getTypeName(string $uid): string
    {
        $type = \Craft::$app->entries->getEntryTypeByUid($uid);
        return $type ? $type->name : '';
    }
}
