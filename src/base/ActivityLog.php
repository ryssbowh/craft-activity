<?php

namespace Ryssbowh\Activity\base;

use Ryssbowh\Activity\Activity;
use craft\base\Model;
use craft\elements\User;
use craft\helpers\StringHelper;
use craft\models\Site;

abstract class ActivityLog extends Model
{
    public $id;
    public $user_id;
    public $user_name;
    public $target_id;
    public $target_name;
    public $target_class;
    public $changedFields = [];
    public $site_name;
    public $site_id;
    public $request;
    public $data;
    public $dateCreated;

    protected $_user;
    protected $_site;

    /**
     * @inheritDoc
     */
    public function getHandle(): string
    {
        $arr = preg_split('/\\\/', get_class($this));
        return lcfirst(end($arr));
    }

    /**
     * @inheritDoc
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
     * @inheritDoc
     */
    public function getTitle(): string
    {
        return \Craft::t('activity', $this->_getTitle());
    }

    /**
     * @inheritDoc
     */
    public function getDbData(): array
    {
        return [
            'user_id' => $this->user ? $this->user->id : null,
            'user_name' => $this->user ? $this->user->friendlyName : null,
            'type' => $this->handle,
            'target_name' => $this->target_name,
            'target_id' => $this->target_id,
            'target_class' => $this->target_class,
            'target_name' => $this->target_name,
            'site_id' => $this->site ? $this->site->id : null,
            'site_name' => $this->site ? $this->site->name : null,
        ];
    }

    /**
     * @inheritDoc
     */
    public function getUser(): ?User
    {
        if ($this->_user === null and $this->user_id) {
            $this->_user = User::find()->anyStatus()->trashed(null)->id($this->user_id)->one();
        }
        return $this->_user;
    }

    public function setUser(User $user)
    {
        $this->_user = $user;
    }

    /**
     * @inheritDoc
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
     * @inheritDoc
     */
    public function getDescription(): string
    {
        return '';
    }

    /**
     * @inheritDoc
     */
    public function getSite(): ?Site
    {
        if ($this->_site === null and $this->site_id) {
            $this->_site = \Craft::$app->sites->getSiteById($this->site_id);
        }
        return $this->_site;
    }

    /**
     * @inheritDoc
     */
    public function getSiteName(): string
    {
        if ($this->site) {
            return $this->site->name;
        }
        return $this->site_name . ' ' . \Craft::t('activity', '(deleted)');
    }

    public function save()
    {
        Activity::$plugin->logs->saveLog($this->getDbData(), $this->changedFields);
    }

    /**
     * @inheritDoc
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