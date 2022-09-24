<?php

namespace Ryssbowh\Activity\models\fieldHandlers\projectConfig;

use craft\services\Fields;

class FieldGroup extends DefaultHandler
{
    /**
     * @inheritDoc
     */
    public function init()
    {
        $this->fancyValue = $this->getGroupName($this->value);
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
    public static function getTargets(): array
    {
        return [
            Fields::CONFIG_FIELDS_KEY . '.{uid}.fieldGroup'
        ];
    }

    /**
     * Get a group name by uid
     * 
     * @param  string $uid
     * @return string
     */
    protected function getGroupName(string $uid): string
    {
        $group = \Craft::$app->fields->getGroupByUid($uid);
        return $group ? $group->name : '';
    }
}