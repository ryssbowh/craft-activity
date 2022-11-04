<?php

namespace Ryssbowh\Activity\models\fieldHandlers\projectConfig;

use Ryssbowh\Activity\base\fieldHandlers\FieldHandler;
use craft\base\Field;
use craft\helpers\ProjectConfig as ProjectConfigHelper;
use craft\services\Fields;
use typedlinkfield\Plugin;

/**
 * @since 1.2.0
 */
class AllowedLinkFieldTypes extends DefaultHandler
{
    /**
     * @var array
     */
    protected $_dirty;

    /**
     * @inheritDoc
     */
    public function init()
    {
        if ($this->value == '*') {
            $this->fancyValue = \Craft::t('app', 'All');
        } else {
            $types = Plugin::getInstance()->getLinkTypes();
            $this->fancyValue = implode(', ', array_map(function ($type) use ($types) {
                return $types[$type]->displayName;
            }, $this->value));
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
     * Get the Craft field
     * 
     * @return Field
     */
    protected function getField(): Field
    {
        $field = \Craft::$app->fields->createField([
            'type' => 'typedlinkfield\\fields\\LinkField'
        ]);
        return $field;
    }

    /**
     * @inheritDoc
     */
    protected static function _getTargets(): array
    {
        return [
            Fields::CONFIG_FIELDS_KEY . '.{uid}.settings[typedlinkfield\\fields\\LinkField].allowedLinkNames'
        ];
    }
}