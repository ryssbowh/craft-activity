<?php

namespace Ryssbowh\Activity\models\fieldHandlers\projectConfig;

use Ryssbowh\Activity\base\fieldHandlers\FieldHandler;
use craft\services\UserGroups;

class Permissions extends DefaultHandler
{
    /**
     * @var array
     */
    protected $_dirty;

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
            UserGroups::CONFIG_USERPGROUPS_KEY . '.{uid}.permissions'
        ];
    }

    /**
     * @inheritDoc
     */
    public static function getTemplate(): ?string
    {
        return 'activity/field-handlers/permissions';
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
     * Build dirty values
     * 
     * @param  array $newPerms
     * @param  array $oldPerms
     * @return array
     */
    protected function buildDirty(array $newPerms, array $oldPerms): array
    {
        $changed = [];
        $added = array_diff($newPerms, $oldPerms);
        $removed = array_diff($oldPerms, $newPerms);
        if ($added) {
            $changed['added'] = $this->labelledPermissions($added);
        }
        if ($removed) {
            $changed['removed'] = $this->labelledPermissions($removed);
        }
        return $changed;
    }

    /**
     * Get permissions section + label for an array of permission handles
     * 
     * @param  array $perms
     * @return array
     */
    protected function labelledPermissions(array $perms): array
    {
        $labelled = [];
        foreach ($perms as $perm) {
            list($section, $label) = $this->findPermission($perm);
            $labelled[$perm] = $section . ': ' . $label;
        }
        return $labelled;
    }

    /**
     * Find a permission and its section
     * 
     * @param  string $perm
     * @return array
     */
    protected function findPermission(string $perm): array
    {
        $allPerms = \Craft::$app->userPermissions->getAllPermissions();
        foreach ($allPerms as $section => $array) {
            if ($label = $this->findPermissionLabel($perm, $array)) {
                return [$section, $label];
            }
        }
    }

    /**
     * Find a permission label
     * 
     * @param  string $perm
     * @param  array  $perms
     * @return ?string
     */
    protected function findPermissionLabel(string $perm, array $perms): ?string
    {
        foreach ($perms as $name => $array) {
            if (strtolower($name) == $perm) {
                return $array['label'];
            }
            if (isset($array['nested']) and $label = $this->findPermissionLabel($perm, $array['nested'])) {
                return $label;
            }
        }
        return null;
    }
}