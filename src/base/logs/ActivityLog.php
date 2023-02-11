<?php

namespace Ryssbowh\Activity\base\logs;

use Ryssbowh\Activity\Activity;
use craft\base\Model;
use craft\elements\User;
use craft\helpers\StringHelper;
use craft\helpers\UrlHelper;
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
     * @var string
     * @since 2.3.4
     */
    public $ip;

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
     * @param ?User $user
     */
    public function setUser(?User $user)
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
            $name = Activity::$plugin->settings->showUsersFullName ? $this->user->fullName : $this->user->friendlyName;
            return '<a href="' . UrlHelper::cpUrl('users/' . $this->user_id) . ' " target="_blank">' . $status . $name . '</a>';
        }
        return \Craft::t('activity', '{user} (deleted)', [
            'user' => $this->user_name
        ]);
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
     * Site setter
     *
     * @param Site $site
     */
    public function setSite(Site $site)
    {
        $this->_site = $site;
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
        return \Craft::t('activity', '{site} (deleted)', [
            'site' => $this->site_name
        ]);
    }

    /**
     * Request name getter
     *
     * @return string
     * @since  2.3.0
     */
    public function getRequestName(): string
    {
        if ($this->request == 'cp') {
            return \Craft::t('activity', 'Control Panel');
        }
        if ($this->request == 'site') {
            return \Craft::t('activity', 'Site');
        }
        if ($this->request == 'yaml') {
            return \Craft::t('activity', 'Yaml config');
        }
        return \Craft::t('activity', 'Console');
    }

    /**
     * Save this log in database
     */
    public function save(): bool
    {
        return Activity::$plugin->logs->saveLog($this);
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
