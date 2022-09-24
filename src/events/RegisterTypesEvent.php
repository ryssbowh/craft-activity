<?php

namespace Ryssbowh\Activity\events;

use Ryssbowh\Activity\base\logs\ActivityLog;
use Ryssbowh\Activity\exceptions\ActivityTypeException;
use Ryssbowh\Activity\models\logs\CraftEditionChanged;
use Ryssbowh\Activity\models\logs\assets\AssetCreated;
use Ryssbowh\Activity\models\logs\assets\AssetDeleted;
use Ryssbowh\Activity\models\logs\assets\AssetSaved;
use Ryssbowh\Activity\models\logs\assets\AssetTransformCreated;
use Ryssbowh\Activity\models\logs\assets\AssetTransformDeleted;
use Ryssbowh\Activity\models\logs\assets\AssetTransformSaved;
use Ryssbowh\Activity\models\logs\assets\VolumeCreated;
use Ryssbowh\Activity\models\logs\assets\VolumeDeleted;
use Ryssbowh\Activity\models\logs\assets\VolumeSaved;
use Ryssbowh\Activity\models\logs\backups\BackupCreated;
use Ryssbowh\Activity\models\logs\backups\BackupRestored;
use Ryssbowh\Activity\models\logs\categories\CategoryCreated;
use Ryssbowh\Activity\models\logs\categories\CategoryDeleted;
use Ryssbowh\Activity\models\logs\categories\CategoryGroupCreated;
use Ryssbowh\Activity\models\logs\categories\CategoryGroupDeleted;
use Ryssbowh\Activity\models\logs\categories\CategoryGroupSaved;
use Ryssbowh\Activity\models\logs\categories\CategoryMoved;
use Ryssbowh\Activity\models\logs\categories\CategoryRestored;
use Ryssbowh\Activity\models\logs\categories\CategorySaved;
use Ryssbowh\Activity\models\logs\entries\EntryCreated;
use Ryssbowh\Activity\models\logs\entries\EntryDeleted;
use Ryssbowh\Activity\models\logs\entries\EntryMoved;
use Ryssbowh\Activity\models\logs\entries\EntryRestored;
use Ryssbowh\Activity\models\logs\entries\EntrySaved;
use Ryssbowh\Activity\models\logs\entries\EntryTypeCreated;
use Ryssbowh\Activity\models\logs\entries\EntryTypeDeleted;
use Ryssbowh\Activity\models\logs\entries\EntryTypeSaved;
use Ryssbowh\Activity\models\logs\entries\SectionCreated;
use Ryssbowh\Activity\models\logs\entries\SectionDeleted;
use Ryssbowh\Activity\models\logs\entries\SectionSaved;
use Ryssbowh\Activity\models\logs\fields\FieldCreated;
use Ryssbowh\Activity\models\logs\fields\FieldDeleted;
use Ryssbowh\Activity\models\logs\fields\FieldGroupCreated;
use Ryssbowh\Activity\models\logs\fields\FieldGroupDeleted;
use Ryssbowh\Activity\models\logs\fields\FieldGroupSaved;
use Ryssbowh\Activity\models\logs\fields\FieldSaved;
use Ryssbowh\Activity\models\logs\globals\GlobalDeleted;
use Ryssbowh\Activity\models\logs\globals\GlobalSaved;
use Ryssbowh\Activity\models\logs\globals\GlobalSetCreated;
use Ryssbowh\Activity\models\logs\globals\GlobalSetDeleted;
use Ryssbowh\Activity\models\logs\globals\GlobalSetSaved;
use Ryssbowh\Activity\models\logs\plugins\PluginDisabled;
use Ryssbowh\Activity\models\logs\plugins\PluginEnabled;
use Ryssbowh\Activity\models\logs\plugins\PluginInstalled;
use Ryssbowh\Activity\models\logs\plugins\PluginSettingsChanged;
use Ryssbowh\Activity\models\logs\plugins\PluginUninstalled;
use Ryssbowh\Activity\models\logs\routes\RouteDeleted;
use Ryssbowh\Activity\models\logs\routes\RouteSaved;
use Ryssbowh\Activity\models\logs\settings\AssetSettingsChanged;
use Ryssbowh\Activity\models\logs\settings\EmailSettingsChanged;
use Ryssbowh\Activity\models\logs\settings\GeneralSettingsChanged;
use Ryssbowh\Activity\models\logs\sites\SiteCreated;
use Ryssbowh\Activity\models\logs\sites\SiteDeleted;
use Ryssbowh\Activity\models\logs\sites\SiteGroupCreated;
use Ryssbowh\Activity\models\logs\sites\SiteGroupDeleted;
use Ryssbowh\Activity\models\logs\sites\SiteGroupSaved;
use Ryssbowh\Activity\models\logs\sites\SiteSaved;
use Ryssbowh\Activity\models\logs\tags\TagGroupCreated;
use Ryssbowh\Activity\models\logs\tags\TagGroupDeleted;
use Ryssbowh\Activity\models\logs\tags\TagGroupSaved;
use Ryssbowh\Activity\models\logs\users\UserActivated;
use Ryssbowh\Activity\models\logs\users\UserAssignedDefaultGroup;
use Ryssbowh\Activity\models\logs\users\UserAssignedGroups;
use Ryssbowh\Activity\models\logs\users\UserCreated;
use Ryssbowh\Activity\models\logs\users\UserDeleted;
use Ryssbowh\Activity\models\logs\users\UserGroupCreated;
use Ryssbowh\Activity\models\logs\users\UserGroupDeleted;
use Ryssbowh\Activity\models\logs\users\UserGroupSaved;
use Ryssbowh\Activity\models\logs\users\UserInvalidToken;
use Ryssbowh\Activity\models\logs\users\UserLayoutSaved;
use Ryssbowh\Activity\models\logs\users\UserLocked;
use Ryssbowh\Activity\models\logs\users\UserLoginFailed;
use Ryssbowh\Activity\models\logs\users\UserRegistered;
use Ryssbowh\Activity\models\logs\users\UserRestored;
use Ryssbowh\Activity\models\logs\users\UserSaved;
use Ryssbowh\Activity\models\logs\users\UserSelfActivated;
use Ryssbowh\Activity\models\logs\users\UserSettingsChanged;
use Ryssbowh\Activity\models\logs\users\UserSuspended;
use Ryssbowh\Activity\models\logs\users\UserUnlocked;
use Ryssbowh\Activity\models\logs\users\UserUnsuspended;
use Ryssbowh\Activity\models\logs\users\UserVerifiedEmail;
use Ryssbowh\Activity\models\logs\widgets\WidgetCreated;
use Ryssbowh\Activity\models\logs\widgets\WidgetDeleted;
use Ryssbowh\Activity\models\logs\widgets\WidgetSaved;
use yii\base\Event;

class RegisterTypesEvent extends Event
{
    /**
     * @var array
     */
    protected $_types = [];

    /**
     * @inheritDoc
     */
    public function init()
    {
        parent::init();
        $this->addMany([
            new AssetCreated,
            new AssetSaved,
            new AssetDeleted,
            new AssetTransformCreated,
            new AssetTransformSaved,
            new AssetTransformDeleted,
            new AssetSettingsChanged,
            new BackupCreated,
            new BackupRestored,
            new CategoryCreated,
            new CategorySaved,
            new CategoryDeleted,
            new CategoryRestored,
            new CategoryMoved,
            new CategoryGroupCreated,
            new CategoryGroupSaved,
            new CategoryGroupDeleted,
            new CraftEditionChanged,
            new EmailSettingsChanged,
            new EntryCreated,
            new EntrySaved,
            new EntryDeleted,
            new EntryMoved,
            new EntryRestored,
            new EntryTypeCreated,
            new EntryTypeSaved,
            new EntryTypeDeleted,
            new FieldCreated,
            new FieldSaved,
            new FieldDeleted,
            new FieldGroupCreated,
            new FieldGroupSaved,
            new FieldGroupDeleted,
            new GeneralSettingsChanged,
            new GlobalSetCreated,
            new GlobalSetSaved,
            new GlobalSetDeleted,
            new GlobalSaved,
            new GlobalDeleted,
            new PluginDisabled,
            new PluginEnabled,
            new PluginInstalled,
            new PluginUninstalled,
            new PluginSettingsChanged,
            new RouteSaved,
            new RouteDeleted,
            new SectionDeleted,
            new SectionCreated,
            new SectionSaved,
            new SiteCreated,
            new SiteSaved,
            new SiteDeleted,
            new SiteGroupCreated,
            new SiteGroupSaved,
            new SiteGroupDeleted,
            new TagGroupCreated,
            new TagGroupSaved,
            new TagGroupDeleted,
            new UserSettingsChanged,
            new UserCreated,
            new UserSaved,
            new UserSuspended,
            new UserUnsuspended,
            new UserLocked,
            new UserUnlocked,
            new UserVerifiedEmail,
            new UserActivated,
            new UserSelfActivated,
            new UserRestored,
            new UserDeleted,
            new UserAssignedGroups,
            new UserAssignedDefaultGroup,
            new UserRegistered,
            new UserLoginFailed,
            new UserInvalidToken,
            new UserGroupCreated,
            new UserGroupSaved,
            new UserGroupDeleted,
            new UserLayoutSaved,
            new VolumeCreated,
            new VolumeSaved,
            new VolumeDeleted,
            new WidgetCreated,
            new WidgetSaved,
            new WidgetDeleted,
        ]);
    }

    /**
     * Get registered types
     * 
     * @return array
     */
    public function getTypes(): array
    {
        return $this->_types;
    }

    /**
     * Add a type to register
     * 
     * @param ActivityLog $type
     * @param boolean     $replace
     */
    public function add(ActivityLog $type, bool $replace = false)
    {
        if (isset($this->_types[$type->handle]) and !$replace) {
            throw ActivityTypeException::registered($type->handle, get_class($type));
        }
        $this->_types[$type->handle] = get_class($type);
    }

    /**
     * Add many types to register
     * 
     * @param array   $types
     * @param boolean $replace
     */
    public function addMany(array $types, bool $replace = false)
    {
        foreach ($types as $type) {
            $this->add($type, $replace);
        }
    }
}