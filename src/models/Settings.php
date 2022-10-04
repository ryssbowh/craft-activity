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
    public bool $ignoreConsoleRequests = false;

    /**
     * @var boolean
     */
    public bool $ignoreCpRequests = false;
        
    /**
     * @var boolean
     */
    public bool $ignoreSiteRequests = false;

    /**
     * @var boolean
     */
    public bool $ignorePropagate = true;

    /**
     * @var boolean
     */
    public bool $ignoreApplyingYaml = false;

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
    public bool $ignoreNoConfigChanges = false;

    /**
     * @var boolean
     */
    public bool $ignoreNoElementChanges = false;

    /**
     * @var boolean
     */
    public bool $ignoreNoSettingsChanges = false;

    /**
     * @var string
     */
    public string $autoDeleteLogsThreshold = '';

    /**
     * @var array
     */
    public $ignoreTypes = ['routeSaved', 'routeDeleted'];

    /**
     * @inheritDoc
     */
    public function defineRules(): array
    {
        return [
            ['ignoreTypes', function () {
                if (!$this->ignoreTypes) {
                    $this->ignoreTypes = [];
                }
            }, 'skipOnEmpty' => false]
        ];
    }

    /**
     * Is a log type ignored
     * 
     * @param  string  $handle
     * @return boolean
     */
    public function isTypeIgnored(string $handle): bool
    {
        return (in_array($handle, $this->ignoreTypes) or ($this->ignoreApplyingYaml and \Craft::$app->projectConfig->isApplyingYamlChanges) or 
            ($this->ignoreConsoleRequests and \Craft::$app->request->isConsoleRequest) or
            ($this->ignoreCpRequests and \Craft::$app->request->isCpRequest) or
            ($this->ignoreSiteRequests and \Craft::$app->request->isSiteRequest));
    }
}