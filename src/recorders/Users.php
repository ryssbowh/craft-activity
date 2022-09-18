<?php

namespace Ryssbowh\Activity\recorders;

use Ryssbowh\Activity\Activity;
use Ryssbowh\Activity\base\ElementsRecorder;
use Ryssbowh\Activity\models\fieldHandlers\elements\Plain;
use craft\base\Element;
use craft\controllers\UsersController;
use craft\elements\User;
use craft\services\Users as CraftUsers;
use yii\base\Event;

class Users extends ElementsRecorder
{
    /**
     * @inheritDoc
     */
    public function init()
    {
        Event::on(User::class, User::EVENT_BEFORE_SAVE, function ($event) {
            Activity::getRecorder('users')->beforeSaved($event->sender);
        });
        Event::on(User::class, User::EVENT_AFTER_SAVE, function ($event) {
            Activity::getRecorder('users')->onSaved($event->sender);
        });
        Event::on(User::class, User::EVENT_AFTER_DELETE, function ($event) {
            Activity::getRecorder('users')->onDeleted($event->sender);
        });
        Event::on(User::class, User::EVENT_AFTER_RESTORE, function ($event) {
            Activity::getRecorder('users')->onRestored($event->sender);
        });
        Event::on(CraftUsers::class, CraftUsers::EVENT_AFTER_SUSPEND_USER, function ($event) {
            Activity::getRecorder('users')->onSuspend($event->user);
        });
        Event::on(CraftUsers::class, CraftUsers::EVENT_AFTER_UNSUSPEND_USER, function ($event) {
            Activity::getRecorder('users')->onUnsuspend($event->user);
        });
        Event::on(CraftUsers::class, CraftUsers::EVENT_AFTER_LOCK_USER, function ($event) {
            Activity::getRecorder('users')->onLocked($event->user);
        });
        Event::on(CraftUsers::class, CraftUsers::EVENT_AFTER_UNLOCK_USER, function ($event) {
            Activity::getRecorder('users')->onUnlocked($event->user);
        });
        Event::on(CraftUsers::class, CraftUsers::EVENT_AFTER_VERIFY_EMAIL, function ($event) {
            Activity::getRecorder('users')->onEmailVerified($event->user);
        });
        Event::on(CraftUsers::class, CraftUsers::EVENT_AFTER_ACTIVATE_USER, function ($event) {
            Activity::getRecorder('users')->onActivated($event->user);
        });
        Event::on(CraftUsers::class, CraftUsers::EVENT_AFTER_ASSIGN_USER_TO_GROUPS, function ($event) {
            Activity::getRecorder('users')->onAssignGroups($event->userId, $event->newGroupIds, $event->removedGroupIds);
        });
        Event::on(CraftUsers::class, CraftUsers::EVENT_AFTER_ASSIGN_USER_TO_DEFAULT_GROUP, function ($event) {
            Activity::getRecorder('users')->onAssignDefaultGroup($event->user);
        });
        Event::on(UsersController::class, UsersController::EVENT_LOGIN_FAILURE, function ($event) {
            if ($event->user) {
                Activity::getRecorder('users')->onLoginFailed($event->user);
            }
        });
        Event::on(UsersController::class, UsersController::EVENT_INVALID_USER_TOKEN, function ($event) {
            if ($event->user) {
                Activity::getRecorder('users')->onInvalidToken($event->user);
            }
        });
    }

    /**
     * @inheritDoc
     */
    public function onDeleted(Element $user)
    {
        if (Activity::$plugin->settings->deleteLogsWithUser) {
            Activity::$plugin->logs->deleteUserLogs($user);
        }
        parent::onDeleted($user);
    }

    /**
     * Save a log when a user is suspended
     * 
     * @param User $user
     */
    public function onSuspend(User $user)
    {
        if (!$this->shouldSaveLog('userSuspended')) {
            return;
        }
        $this->saveLog('userSuspended', [
            'element' => $user
        ]);
    }

    /**
     * Save a log when a user is unsuspended
     * 
     * @param User $user
     */
    public function onUnsuspend(User $user)
    {
        if (!$this->shouldSaveLog('userUnsuspended')) {
            return;
        }
        $this->saveLog('userUnsuspended', [
            'element' => $user
        ]);
    }

    /**
     * Save a log when a user is locked
     * 
     * @param User $user
     */
    public function onLocked(User $user)
    {
        if (!$this->shouldSaveLog('userLocked')) {
            return;
        }
        $this->saveLog('userLocked', [
            'element' => $user,
            'user' => $user,
            'attempts' => \Craft::$app->config->general->maxInvalidLogins
        ]);
    }

    /**
     * Save a log when a user is unlocked
     * 
     * @param User $user
     */
    public function onUnlocked(User $user)
    {
        if (!$this->shouldSaveLog('userUnlocked')) {
            return;
        }
        $this->saveLog('userUnlocked', [
            'element' => $user
        ]);
    }

    /**
     * Save a log when a user fails to login
     * 
     * @param User $user
     */
    public function onLoginFailed(User $user)
    {
        if (!$this->shouldSaveLog('userLoginFailed')) {
            return;
        }
        $this->saveLog('userLoginFailed', [
            'element' => $user,
            'user' => $user
        ]);
    }

    /**
     * Save a log when a user uses an invalid token
     * 
     * @param User $user
     */
    public function onInvalidToken(User $user)
    {
        if (!$this->shouldSaveLog('userInvalidToken')) {
            return;
        }
        $this->saveLog('userInvalidToken', [
            'element' => $user,
            'user' => $user
        ]);
    }

    /**
     * Save a log when a user email is verified
     * 
     * @param User $user
     */
    public function onEmailVerified(User $user)
    {
        if (!$this->shouldSaveLog('userEmailVerified')) {
            return;
        }
        $this->saveLog('userEmailVerified', [
            'element' => $user,
            'user' => $user
        ]);
    }

    /**
     * Save a log when a user is assigned to groups
     * 
     * @param int    $userId
     * @param array $newGroupIds
     * @param array $removedGroupIds
     */
    public function onAssignGroups(int $userId, array $newGroupIds, array $removedGroupIds)
    {
        if (!$this->shouldSaveLog('userAssignGroups')) {
            return;
        }
        $this->saveLog('userAssignGroups', [
            'element' => User::find()->anyStatus()->id($userId),
            'changedFields' => [
                'removedGroups' => $this->getGroupNames($removedGroupIds),
                'newGroups' => $this->getGroupNames($newGroupIds)
            ]
        ]);
    }

    /**
     * Save a log when a user is activated
     * 
     * @param User $user
     */
    public function onActivated(User $user)
    {
        $params = [
            'element' => $user,
        ];
        $type = 'userActivated';
        if (!\Craft::$app->user->identity) {
            $type = 'userSelfActivated';
            $params['user'] = $user;
        }
        if (!$this->shouldSaveLog($type)) {
            return;
        }
        $this->saveLog($type, $params);
    }

    /**
     * Save a log when a user is assigned to the default group
     * 
     * @param User $user
     */
    public function onAssignDefaultGroup(User $user)
    {
        if (!$this->shouldSaveLog('userAssignDefaultGroup')) {
            return;
        }
        $uid = \Craft::$app->getProjectConfig()->get('users.defaultGroup');
        $group = \Craft::$app->getUserGroups()->getGroupByUid($uid);
        $this->saveLog('userAssignDefaultGroup', [
            'element' => $user,
            'user' => $user,
            'group' => $group->name
        ]);
    }

    /**
     * @inheritDoc
     */
    protected function getElementType(): string
    {
        return User::class;
    }

    /**
     * @inheritDoc
     */
    protected function getActivityHandle(): string
    {
        return 'user';
    }

    /**
     * Get the group names from an array of ids
     * 
     * @param  array  $ids
     * @return string
     */
    protected function getGroupNames(array $ids): string
    {
        $groups = [];
        foreach ($ids as $id) {
            $group = \Craft::$app->userGroups->getGroupById($id);
            $groups[] = $group->name;
        }
        return implode(', ', $groups);
    }

    /**
     * @inheritDoc
     */
    protected function getSavedActivityType(Element $user)
    {
        if ($user->firstSave) {
            return !\Craft::$app->user->identity ? 'userRegistered' : 'userCreated';
        } else {
            return 'userSaved';
        }
    }

    /**
     * @inheritDoc
     */
    protected function getFields(Element $user): array
    {
        return array_merge(
            [
                'username' => new Plain([
                    'name' => \Craft::t('app', 'Username'),
                    'value' => $user->username
                ]),
                'firstName' => new Plain([
                    'name' => \Craft::t('app', 'First Name'),
                    'value' => $user->firstName
                ]),
                'lastName' => new Plain([
                    'name' => \Craft::t('app', 'Last Name'),
                    'value' => $user->lastName
                ]),
                'email' => new Plain([
                    'name' => \Craft::t('app', 'Email'),
                    'value' => $user->email
                ]),
                'admin' => new Plain([
                    'name' => \Craft::t('app', 'Admin'),
                    'value' => $user->admin ? \Craft::t('app', 'Yes') : \Craft::t('app', 'No'),
                ])
            ],
            $this->getFieldValues($user)
        );
    }
}