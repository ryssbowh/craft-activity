<?php

namespace Ryssbowh\Activity\models\fieldHandlers\projectConfig;

use craft\services\ProjectConfig;

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
            foreach ($this->value as $uid) {
                if (is_array($uid)) {
                    $uid = $uid['uid'];
                }
                $this->fancyValue[] = $this->getTypeName($uid);
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
