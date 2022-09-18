<?php 

namespace Ryssbowh\Activity\models;

use craft\base\Model;

class Settings extends Model
{
    public bool $ignoreResaveElements = true;

    public bool $ignoreUpdateSlugs = true;

    public bool $deleteLogsWithUser = false;

    public bool $ignoreConsoleRequests = false;

    public bool $ignoreCpRequests = false;
    
    public bool $ignoreSiteRequests = false;

    public bool $ignorePropagate = true;

    public bool $ignoreApplyingYaml = false;

    public bool $trackElementFieldsChanges = true;

    public bool $trackConfigFieldsChanges = true;

    public string $autoDeleteLogsThreshold = '';

    public $ignoreTypes = ['routeSaved', 'routeDeleted'];

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

    public function isTypeIgnored(string $type): bool
    {
        return in_array($type, $this->ignoreTypes);
    }
}