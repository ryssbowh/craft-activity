<?php

namespace Ryssbowh\Activity\services;

use Ryssbowh\Activity\Activity;
use Ryssbowh\Activity\base\logs\ActivityLog as BaseActivityLog;
use Ryssbowh\Activity\exceptions\ActivityChangedFieldException;
use Ryssbowh\Activity\exceptions\ActivityLogException;
use Ryssbowh\Activity\models\ChangedField;
use Ryssbowh\Activity\records\ActivityChangedField;
use Ryssbowh\Activity\records\ActivityLog;
use craft\base\Component;
use craft\db\ActiveQuery;
use craft\db\Paginator;
use craft\db\Query;
use craft\elements\User;
use craft\web\Request;
use craft\web\twig\variables\Paginate;

class Logs extends Component
{
    public const REQUEST_CP = 'cp';
    public const REQUEST_SITE = 'site';
    public const REQUEST_YAML = 'yaml';
    public const REQUEST_CONSOLE = 'console';

    /**
     * Saves a log
     *
     * @param  BaseActivityLog $log
     * @throws ActivityLogException
     * @return bool
     */
    public function saveLog(BaseActivityLog $log): bool
    {
        if (!$user = $log->user) {
            $user = \Craft::$app->user->identity;
        }
        if (!$site = $log->site) {
            $site = \Craft::$app->sites->currentSite;
        }
        $request = $log->request;
        if (!$request) {
            $request = $this->getCurrentRequest();
        }
        $userName = $user ? (Activity::$plugin->settings->showUsersFullName ? $user->fullName : $user->friendlyName) : '';
        $record = new ActivityLog([
            'user_id' => $user ? $user->id : 0,
            'user_name' => $user ? $userName : '',
            'type' => $log->handle,
            'target_class' => $log->target_class,
            'target_uid' => $log->target_uid,
            'target_name' => $log->target_name,
            'site_name' => $site ? $site->name : '',
            'site_id' => $site ? $site->id : null,
            'request' => $request,
            'data' => $log->data
        ]);
        $info = \Craft::$app->plugins->getStoredPluginInfo('activity');
        if ($info and version_compare($info['schemaVersion'], '2.3.4', '>=')) {
            $record->ip = \Craft::$app->request instanceof Request ? \Craft::$app->request->getUserIP() : null;
        }
        if ($record->save(false)) {
            foreach ($log->changedFields as $name => $array) {
                $field = new ActivityChangedField([
                    'log_id' => $record->id,
                    'name' => $name,
                    'handler' => $array['handler'] ?? '',
                    'data' => $array['data'] ?? []
                ]);
                $field->save(false);
            }
            return true;
        }
        \Craft::warning('Unable to save activity record ' . $log->handle, __METHOD__);
        return false;
    }

    /**
     * Get current request descriptor
     *
     * @return string
     */
    public function getCurrentRequest(): string
    {
        if (\Craft::$app->projectConfig->isApplyingYamlChanges) {
            return self::REQUEST_YAML;
        }
        return \Craft::$app->request->isConsoleRequest ? self::REQUEST_CONSOLE : (\Craft::$app->request->isCpRequest ? self::REQUEST_CP : self::REQUEST_SITE);
    }

    /**
     * Get all user used in database in all records
     *
     * @return array
     */
    public function getUsedUsers(): array
    {
        $query = (new Query())
            ->select(['user_id', 'user_name'])
            ->distinct()
            ->from('{{%activity_logs}}')
            ->all();
        $users = [];
        $userIds = [];
        foreach ($query as $res) {
            if (in_array($res['user_id'], $userIds)) {
                continue;
            }
            $userIds[] = $res['user_id'];
            $user = null;
            if ($res['user_id'] == 0) {
                $res['user_name'] = \Craft::t('app', 'System');
            } else {
                $user = User::find()->id($res['user_id'])->anyStatus()->one();
            }
            $data = [
                'id' => $res['user_id'],
                'name' => $res['user_name'],
                'exists' => false
            ];
            if ($user) {
                $name = Activity::$plugin->settings->showUsersFullName ? $user->fullName : $user->friendlyName;
                $data['name'] = $name;
                $data['exists'] = true;
                $data['status'] = $user->status;
            }
            $users[] = $data;
        }
        return $users;
    }

    /**
     * Get paginated logs according to some filters
     *
     * @param  array       $filters
     * @param  int|integer $perPage
     * @param  string      $orderBy
     * @return array
     */
    public function getPaginatedLogs(array $filters, int $perPage = 5, string $orderBy = 'dateCreated desc'): array
    {
        $query = $this->getLogsQuery($filters, $orderBy);
        $paginator = new Paginator($query->limit(null), [
            'currentPage' => \Craft::$app->getRequest()->getPageNum(),
            'pageSize' => $perPage,
        ]);
        $results = array_map(function ($record) {
            return $record->toModel();
        }, $paginator->getPageResults());

        return [
            Paginate::create($paginator),
            $results,
        ];
    }


    /**
     * Get a filtered logs query
     *
     * @param  array  $filters
     * @param  string $orderBy
     * @return ActiveQuery
     * @since  1.3.0
     */
    public function getLogsQuery(array $filters, string $orderBy = 'dateCreated desc'): ActiveQuery
    {
        $query = ActivityLog::find()->with('changedFields')->orderBy($orderBy . ', id desc');
        if ($filters['users'] ?? false) {
            $query->andWhere(['in', 'user_id', $filters['users']]);
        }
        if ($filters['types'] ?? false) {
            $query->andWhere(['in', 'type', $filters['types']]);
        }
        if ($filters['dateFrom'] ?? false) {
            $date = \DateTime::createFromFormat('d/m/Y', $filters['dateFrom']);
            $query->andWhere(['>=', 'dateCreated', $date->format('Y-m-d') . ' 00:00:00']);
        }
        if ($filters['dateTo'] ?? false) {
            $date = \DateTime::createFromFormat('d/m/Y', $filters['dateTo']);
            $query->andWhere(['<=', 'dateCreated', $date->format('Y-m-d') . ' 23:59:59']);
        }
        return $query;
    }

    /**
     * Get the latest logs for a user
     *
     * @param  User $user
     * @param  int  $limit
     * @return array
     */
    public function getUserLogs(User $user, int $limit = 10)
    {
        $query = ActivityLog::find()->where([
            'user_id' => $user->id
        ])->with('changedFields')->orderBy('dateCreated desc')->limit($limit);
        return array_map(function ($record) {
            return $record->toModel();
        }, $query->all());
    }

    /**
     * Deletes all logs created by a user
     *
     * @param User $user
     */
    public function deleteUserLogs(User $user)
    {
        ActivityLog::deleteAll(['user_id' => $user->id]);
    }

    /**
     * Delete all logs
     */
    public function deleteAllLogs()
    {
        ActivityLog::deleteAll();
    }

    /**
     * Delete a log by id
     *
     * @param int $id
     */
    public function deleteLog(int $id)
    {
        ActivityLog::deleteAll(['id' => $id]);
    }

    /**
     * Get a changed field by id
     *
     * @param  int    $id
     * @return ChangedField
     * @throws ActivityChangedFieldException
     */
    public function getChangedFieldById(int $id): ChangedField
    {
        $record = ActivityChangedField::find()->where(['id' => $id])->one();
        if ($record) {
            return $record->toModel();
        }
        throw ActivityChangedFieldException::noId($id);
    }

    /**
     * Run garbage collection, delete all logs older than threshold
     */
    public function runGc()
    {
        $threshold = Activity::$plugin->settings->autoDeleteLogsThreshold;
        if (!$threshold) {
            return;
        }
        $date = (new \DateTime())->sub(new \DateInterval('P' . $threshold . 'D'));
        ActivityLog::deleteAll(['<', 'dateCreated', $date->format('Y-m-d H:i:s')]);
    }
}
