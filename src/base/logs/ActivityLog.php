<?php

namespace Ryssbowh\Activity\base\logs;

use Ryssbowh\Activity\Activity;
use craft\base\Model;
use craft\elements\User;
use craft\helpers\StringHelper;
use craft\models\Site;

abstract class ActivityLog extends Model
{
    /**
     * @var int
     */
    public $id;

    /**
     * @var int
     */
    public $user_id;

    /**
     * @var string
     */
    public $user_name;

    /**
     * @var string
     */
    public $target_uid;

    /**
     * @var string
     */
    public $target_name;

    /**
     * @var string
     */
    public $target_class;

    /**
     * @var array
     */
    public $changedFields = [];

    /**
     * @var string
     */
    public $site_name;

    /**
     * @var int
     */
    public $site_id;

    /**
     * @var string
     */
    public $request;

    /**
     * @var array
     */
    public $data;

    /**
     * @var \DateTime
     */
    public $dateCreated;

    /**
     * @var User
     */
    protected $_user;

    /**
     * @var Site
     */
    protected $_site;

    /**
     * Handle getter
     * 
     * @return string
     */
    public function getHandle(): string
    {
        $arr = preg_split('/\\\/', get_class($this));
        return lcfirst(end($arr));
    }

    /**
     * Name getter
     * 
     * @return string
     */
    public function getName(): string
    {
        $elems = preg_split('/\\\/', get_class($this));
        $elems = preg_split('/(?=[A-Z])/', lcfirst(end($elems)));
        $name = ucfirst(implode(' ', array_map(function ($elem) {
            return strtolower($elem);
        }, $elems)));
        return \Craft::t('activity', $name);
    }

    /**
     * Title getter
     * 
     * @return string
     */
    public function getTitle(): string
    {
        return \Craft::t('activity', $this->_getTitle());
    }

    /**
     * Get data to be saved in database
     * 
     * @return array
     */
    public function getDbData(): array
    {
        return [
            'user_id' => $this->user ? $this->user->id : null,
            'user_name' => $this->user ? $this->user->friendlyName : null,
            'type' => $this->handle,
            'target_name' => $this->target_name,
            'target_uid' => $this->target_uid,
            'target_name' => $this->target_name,
            'site_id' => $this->site ? $this->site->id : null,
            'site_name' => $this->site ? $this->site->name : null,
        ];
    }

    /**
     * User getter
     * 
     * @return ?User
     */
    public function getUser(): ?User
    {
        if ($this->_user === null and $this->user_id) {
            $this->_user = User::find()->anyStatus()->trashed(null)->id($this->user_id)->one();
        }
        return $this->_user;
    }

    /**
     * User setter
     * 
     * @param User $user
     */
    public function setUser(User $user)
    {
        $this->_user = $user;
    }

    /**
     * User name getter
     * 
     * @return string
     */
    public function getUserName(): string
    {
        if ($this->user_id === 0) {
            return \Craft::t('app', 'System');
        }
        if ($this->user) {
            $status = '<span class="status ' . $this->user->status . '"></span>';
            if ($this->user->trashed) {
                $status = '<span class="status trashed"></span>';
            }
            return '<a href="' . $this->user->cpEditUrl . ' " target="_blank">' . $status . $this->user->friendlyName . '</a>';
        }
        return $this->user . ' ' . \Craft::t('activity', '(deleted)');
    }

    /**
     * Description getter
     * 
     * @return string
     */
    public function getDescription(): string
    {
        return '';
    }

    /**
     * Site getter
     * 
     * @return ?Site
     */
    public function getSite(): ?Site
    {
        if ($this->_site === null and $this->site_id) {
            $this->_site = \Craft::$app->sites->getSiteById($this->site_id);
        }
        return $this->_site;
    }

    /**
     * Site name getter
     * 
     * @return string
     */
    public function getSiteName(): string
    {
        if ($this->site) {
            return $this->site->name;
        }
        return $this->site_name . ' ' . \Craft::t('activity', '(deleted)');
    }

    /**
     * Save this log in database
     */
    public function save()
    {
        Activity::$plugin->logs->saveLog($this->getDbData(), $this->changedFields);
    }

    /**
     * Get the templates to output dirty fields
     * 
     * @return array
     */
    public function dirtyFieldTemplates(): array
    {
        return [
            'previewTargets' => 'activity/includes/dirty-preview-targets',
            'siteSettings' => 'activity/includes/dirty-site-settings',
            'fieldLayouts' => 'activity/includes/dirty-field-layout',
            'permissions' => 'activity/includes/dirty-permissions'
        ];
    }

    /**
     * Build the title from class name
     * 
     * @return string
     */
    protected function _getTitle(): string
    {
        $elems = preg_split('/\\\/', get_class($this));
        $elems = preg_split('/(?=[A-Z])/', lcfirst(end($elems)));
        $elems[] = $elems[0];
        unset($elems[0]);
        $name = ucfirst(implode(' ', array_map(function ($elem) {
            return strtolower($elem);
        }, $elems)));
        return $name;
    }
}