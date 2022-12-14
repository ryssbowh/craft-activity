<?php

namespace Ryssbowh\Activity\models\fieldHandlers\projectConfig;

use Ryssbowh\Activity\base\fieldHandlers\FieldHandler;
use craft\errors\SiteNotFoundException;
use craft\helpers\ProjectConfig as ProjectConfigHelper;
use craft\services\ProjectConfig;

class SiteSettings extends DefaultHandler
{
    /**
     * @var array
     */
    protected $_dirty;

    /**
     * @inheritDoc
     */
    public function init(): void
    {
        parent::init();
        $this->value = $this->buildValues($this->value);
    }

    /**
     * @inheritDoc
     */
    protected static function _getTargets(): array
    {
        return [
            ProjectConfig::PATH_SECTIONS . '.{uid}.siteSettings',
            ProjectConfig::PATH_CATEGORY_GROUPS . '.{uid}.siteSettings'
        ];
    }

    /**
     * @inheritDoc
     */
    public static function getTemplate(): ?string
    {
        return 'activity/field-handlers/site-settings';
    }

    /**
     * @inheritDoc
     */
    public function getDirty(FieldHandler $handler): array
    {
        if ($this->_dirty === null) {
            $this->_dirty = $this->buildDirty($this->value, $handler->value);
        }
        return $this->_dirty;
    }

    /**
     * @inheritDoc
     */
    public function isDirty(FieldHandler $handler): bool
    {
        return !empty($this->getDirty($handler));
    }

    /**
     * @inheritDoc
     */
    public function getDbValue(string $valueKey): array
    {
        if ($valueKey == 'f') {
            return $this->buildDirty([], $this->value);
        }
        return $this->buildDirty($this->value, []);
    }

    /**
     * Build values
     * 
     * @param  array  $siteSettings
     * @return array
     */
    protected function buildValues(array $siteSettings): array
    {
        $siteSettings = ProjectConfigHelper::unpackAssociativeArrays($siteSettings);
        $values = [];
        foreach ($siteSettings as $siteUid => $settings) {
            try {
                $site = \Craft::$app->sites->getSiteByUid($siteUid);
                $values[$siteUid] = [
                    'template' => $settings['template'] ?? '',
                    'uriFormat' => $settings['uriFormat'] ?? '',
                    'name' => $site->name
                ];
            } catch (SiteNotFoundException $e) {}
        }
        return $values;
    }

    /**
     * Build dirty values
     * 
     * @param  array $newFields
     * @param  array $oldFields
     * @return array
     */
    protected function buildDirty(array $newFields, array $oldFields): array
    {
        $dirty = [];
        foreach ($newFields as $uid => $values) {
            if (array_key_exists($uid, $oldFields)) {
                $rowDirty = false;
                $rDirty = [
                    'name' => $values['name'],
                    'uid' => $uid,
                    'mode' => 'changed'
                ];
                foreach (['template', 'uriFormat'] as $field) {
                    if (($values[$field] ?? null) !== ($oldFields[$uid][$field] ?? null)) {
                        $rowDirty = true;
                        $rDirty[$field]['f'] = $oldFields[$uid][$field];
                        $rDirty[$field]['t'] = $values[$field];
                    }
                }
                if ($rowDirty) {
                    $dirty[] = $rDirty;
                }
            } else {
                $dirty[] = [
                    'name' => $values['name'],
                    'uid' => $uid,
                    'mode' => 'enabled',
                    'uriFormat' => ['t' => $values['uriFormat']],
                    'template' => ['t' => $values['template']]
                ];
            }
        }
        foreach (array_diff_key($oldFields, $newFields) as $uid => $values) {
            $dirty[] = [
                'name' => $values['name'],
                'uid' => $uid,
                'mode' => 'disabled',
                'uriFormat' => ['f' => $values['uriFormat']],
                'template' => ['f' => $values['template']]
            ];
        }
        return $dirty;
    }
}