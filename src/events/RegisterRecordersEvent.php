<?php

namespace Ryssbowh\Activity\events;

use Ryssbowh\Activity\base\recorders\Recorder;
use Ryssbowh\Activity\exceptions\ActivityRecorderException;
use Ryssbowh\Activity\recorders\AddressLayout;
use Ryssbowh\Activity\recorders\Application;
use Ryssbowh\Activity\recorders\AssetSettings;
use Ryssbowh\Activity\recorders\Assets;
use Ryssbowh\Activity\recorders\Backup;
use Ryssbowh\Activity\recorders\Categories;
use Ryssbowh\Activity\recorders\CategoryGroups;
use Ryssbowh\Activity\recorders\Dashboard;
use Ryssbowh\Activity\recorders\EmailSettings;
use Ryssbowh\Activity\recorders\Entries;
use Ryssbowh\Activity\recorders\EntryTypes;
use Ryssbowh\Activity\recorders\FieldGroups;
use Ryssbowh\Activity\recorders\Fields;
use Ryssbowh\Activity\recorders\FileSystems;
use Ryssbowh\Activity\recorders\GeneralSettings;
use Ryssbowh\Activity\recorders\GlobalSets;
use Ryssbowh\Activity\recorders\Globals;
use Ryssbowh\Activity\recorders\ImageTransforms;
use Ryssbowh\Activity\recorders\Mailer;
use Ryssbowh\Activity\recorders\Plugins;
use Ryssbowh\Activity\recorders\Routes;
use Ryssbowh\Activity\recorders\Sections;
use Ryssbowh\Activity\recorders\SiteGroups;
use Ryssbowh\Activity\recorders\Sites;
use Ryssbowh\Activity\recorders\Tags;
use Ryssbowh\Activity\recorders\UserGroups;
use Ryssbowh\Activity\recorders\UserGroupsPermissions;
use Ryssbowh\Activity\recorders\UserLayout;
use Ryssbowh\Activity\recorders\UserSettings;
use Ryssbowh\Activity\recorders\Users;
use Ryssbowh\Activity\recorders\Volumes;
use yii\base\Event;

class RegisterRecordersEvent extends Event
{
    /**
     * @var array
     */
    protected $_recorders = [];

    /**
     * @inheritDoc
     */
    public function init()
    {
        parent::init();
        $this->addMany([
            'addressLayout' => new AddressLayout,
            'sites' => new Sites,
            'siteGroups' => new SiteGroups,
            'application' => new Application,
            'assets' => new Assets,
            'assetSettings' => new AssetSettings,
            'backup' => new Backup,
            'categories' => new Categories,
            'categoryGroups' => new CategoryGroups,
            'dashboard' => new Dashboard,
            'entries' => new Entries,
            'entryTypes' => new EntryTypes,
            'emailSettings' => new EmailSettings,
            'fieldGroups' => new FieldGroups,
            'fields' => new Fields,
            'fileSystems' => new FileSystems,
            'globals' => new Globals,
            'globalSets' => new GlobalSets,
            'imageTransforms' => new ImageTransforms,
            'mailer' => new Mailer,
            'plugins' => new Plugins,
            'routes' => new Routes,
            'sections' => new Sections,
            'generalSettings' => new GeneralSettings,
            'tags' => new Tags,
            'users' => new Users,
            'userLayout' => new UserLayout,
            'userGroups' => new UserGroups,
            'userGroupsPermissions' => new UserGroupsPermissions,
            'userSettings' => new UserSettings,
            'volumes' => new Volumes
        ]);
    }

    /**
     * Get registered recorders
     * 
     * @return array
     */
    public function getRecorders(): array
    {
        return $this->_recorders;
    }

    /**
     * Add a recorder to register
     * 
     * @param string   $name
     * @param Recorder $recorder
     * @param boolean  $replace
     */
    public function add(string $name, Recorder $recorder, bool $replace = false)
    {
        if (isset($this->_recorders[$name]) and !$replace) {
            throw ActivityRecorderException::registered($name);
        }
        $this->_recorders[$name] = $recorder;
    }

    /**
     * Add many recorders to register
     * 
     * @param array   $recorders
     * @param boolean $replace
     */
    public function addMany(array $recorders, bool $replace = false)
    {
        foreach ($recorders as $name => $recorder) {
            $this->add($name, $recorder, $replace);
        }
    }
}