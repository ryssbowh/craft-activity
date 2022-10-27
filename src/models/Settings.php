<?php 

namespace Ryssbowh\Activity\models;

use craft\base\Model;

class Settings extends Model
{
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
     * @var array
     */
    public $ignoreRules = [
        ['type' => 'entryMoved', 'active' => 1, 'request' => ''],
        ['type' => 'categoryMoved', 'active' => 1, 'request' => ''],
        ['type' => 'userLoggedOut', 'active' => 1, 'request' => '']
    ];

    /**
     * Is a log type ignored by the set of rules
     * 
     * @param  string  $handle
     * @return boolean
     */
    public function isTypeIgnored(string $handle): bool
    {
        if (!$this->ignoreRules) {
            return false;
        }
        foreach ($this->ignoreRules as $rule) {
            if ($rule['active'] and ($rule['type'] == $handle or !$rule['type'])) {
                if (!$rule['request'] or 
                    ($rule['request'] == 'yaml' and \Craft::$app->projectConfig->isApplyingExternalChanges) or 
                    ($rule['request'] == 'console' and \Craft::$app->request->isConsoleRequest) or
                    ($rule['request'] == 'cp' and \Craft::$app->request->isCpRequest) or
                    ($rule['request'] == 'site' and \Craft::$app->request->isSiteRequest)) {
                    return true;
                }
            }
        }
        return false;
    }
}