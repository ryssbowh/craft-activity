<?php

namespace Ryssbowh\Activity\models;

use Ryssbowh\Activity\events\RegisterDeleteTypesOptions;
use craft\base\Model;

class Settings extends Model
{
    public const EVENT_REGISTER_DELETE_TYPES_OPTIONS = 'event-register-delete-types';

    /**
     * @var boolean
     */
    public bool $ignoreResave = true;

    /**
     * @var boolean
     */
    public bool $ignoreUpdateSlugs = true;

    /**
     * @var boolean
     */
    public bool $deleteLogsWithUser = false;

    /**
     * @var boolean
     * @since 2.4.0
     */
    public bool $deleteLogsWithSite = false;

    /**
     * @var boolean
     */
    public bool $ignorePropagate = true;

    /**
     * @var boolean
     */
    public bool $trackElementFieldsChanges = true;

    /**
     * @var boolean
     */
    public bool $trackConfigFieldsChanges = true;

    /**
     * @var boolean
     */
    public bool $ignoreNoElementChanges = false;

    /**
     * @var string
     */
    public string $autoDeleteLogsThreshold = '';

    /**
     * @var boolean
     */
    public bool $showUsersFullName = false;

    /**
     * @var boolean
     * @since 2.3.4
     */
    public bool $showUserIP = false;

    /**
     * @var array
     * @since 2.4.0
     */
    public $deleteTypes;

    /**
     * @var bool
     * @since 3.1.0
     */
    public bool $rulesAreWhitelist = false;

    /**
     * @var array
     * @since 3.1.0
     */
    public $ignoreUserGroups = '';

    /**
     * @var bool
     * @since 3.1.0
     */
    public bool $ignoreAdmins = false;

    /**
     * @var array
     */
    public $ignoreRules = [
        ['type' => 'entryMoved', 'active' => 1, 'request' => ''],
        ['type' => 'categoryMoved', 'active' => 1, 'request' => ''],
        ['type' => 'userLoggedOut', 'active' => 1, 'request' => '']
    ];

    protected $_deleteTypesOptions;

    /**
     * Is a log type ignored by the set of rules
     *
     * @param  string  $type
     * @return boolean
     */
    public function isTypeIgnored(string $type): bool
    {
        if (!$this->userMatches()) {
            return true;
        }
        if (!$this->ignoreRules) {
            return $this->rulesAreWhitelist;
        }
        foreach ($this->ignoreRules as $rule) {
            if ($this->ruleMatches($rule, $type)) {
                return !$this->rulesAreWhitelist;
            }
        }
        return $this->rulesAreWhitelist;
    }

    /**
     * Get the options for the activity types that can be deleted
     *
     * @return array
     */
    public function getDeleteTypesOptions(): array
    {
        if ($this->_deleteTypesOptions === null) {
            $event = new RegisterDeleteTypesOptions();
            $this->trigger(self::EVENT_REGISTER_DELETE_TYPES_OPTIONS, $event);
            $this->_deleteTypesOptions = $event->options;
        }
        return $this->_deleteTypesOptions;
    }

    /**
     * Should a type of activity be deleted
     *
     * @param  string $type
     * @return bool
     */
    public function shouldDeleteActivity(string $type): bool
    {
        if ($this->deleteTypes === '*') {
            return true;
        }
        return in_array($type, is_array($this->deleteTypes) ? $this->deleteTypes : []);
    }

    /**
     * Does a rule match
     * 
     * @param array $rule
     * @param string $type
     * @return bool
     * @since 3.1.0
     */
    protected function ruleMatches(array $rule, string $type): bool
    {
        if ($rule['active'] and ($rule['type'] == $type or !$rule['type'])) {
            return (
                !$rule['request'] or
                ($rule['request'] == 'yaml' and \Craft::$app->projectConfig->isApplyingExternalChanges) or
                ($rule['request'] == 'console' and \Craft::$app->request->isConsoleRequest) or
                ($rule['request'] == 'cp' and \Craft::$app->request->isCpRequest) or
                ($rule['request'] == 'site' and \Craft::$app->request->isSiteRequest)
            );
        }
        return false;
    }

    /**
     * Does the user match
     * 
     * @return bool
     * @since 3.1.0
     */
    protected function userMatches(): bool
    {
        $user = \Craft::$app->getUser()->getIdentity();
        if ($user) {
            if ($this->ignoreAdmins and $user->admin) {
                return false;
            }
            if (!$this->ignoreUserGroups) {
                return true;
            }
            foreach ($this->ignoreUserGroups as $group) {
                if ($user->isInGroup($group)) {
                    return false;
                }
            }
        }
        return true;
    }
}
