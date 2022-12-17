<?php

namespace Ryssbowh\Activity\models\fieldHandlers\projectConfig;

use benf\neo\Plugin;
use craft\services\ProjectConfig;

/**
 * @since 1.3.1
 */
class NeoGroup extends DefaultHandler
{
    /**
     * @inheritDoc
     */
    public function init(): void
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
    protected static function _getTargets(): array
    {
        return [
            'neoBlockTypes.{uid}.group'
        ];
    }

    /**
     * Get a group name by uid
     * 
     * @param  ?string $uid
     * @return string
     */
    protected function getGroupName(?string $uid): string
    {
        if (!$uid) {
            return '';
        }
        foreach (Plugin::$plugin->blockTypes->getAllBlockTypeGroups() as $group) {
            if ($group->uid == $uid) {
                return $group->name;
            }
        }
        return '';
    }
}