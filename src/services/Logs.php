<?php

namespace Ryssbowh\Activity\services;

use Ryssbowh\Activity\Activity;
use Ryssbowh\Activity\exceptions\ActivityLogException;
use Ryssbowh\Activity\records\ActivityChangedField;
use Ryssbowh\Activity\records\ActivityLog;
use craft\base\Component;
use craft\db\Paginator;
use craft\db\Query;
use craft\elements\User;
use craft\web\twig\variables\Paginate;

class Logs extends Component
{
    public function saveLog(array $data, array $changedFields = [])
    {
        if (!isset($data['type'])) {
            throw ActivityLogException::noType();
        }
        $user = \Craft::$app->user->identity;
        $currentSite = \Craft::$app->sites->currentSite;
        $request = \Craft::$app->request->isConsoleRequest ? 'console' : (\Craft::$app->request->isCpRequest ? 'cp' : 'site');
        if (\Craft::$app->projectConfig->isApplyingYamlChanges) {
            $request = 'yaml';
        }
        $record = new ActivityLog([
            'user_id' => ($data['user_id'] ?? null) ? $data['user_id'] : ($user ? $user->id : 0),
            'user_name' => ($data['user_name'] ?? null) ? $data['user_name'] : ($user ? $user->friendlyName : ''),
            'type' => $data['type'],
            'target_class' => $data['target_class'] ?? null,
            'target_id' => $data['target_id'] ?? null,
            'target_name' => $data['target_name'] ?? null,
            'site_name' => $currentSite->name,
            'site_id' => $currentSite->id,
            'request' => $request,
            'data' => $data['data'] ?? null
        ]);
        $record->save(false);
        foreach ($changedFields as $name => $field) {
            $field = new ActivityChangedField([
                'log_id' => $record->id,
                'name' => $name,
                'data' => $field
            ]);
            $field->save(false);
        }
    }

    /**
     * Get all user used in database
     * 
     * @return array
     */
    public function getUsedUsers(): array
    {
        $query = (new Query)
            ->select(['user_id', 'user_name'])
            ->distinct()
            ->from('{{%activity_logs}}')
            ->all();
        $users = [];
        foreach ($query as $res) {
            $user = User::find()->id($res['user_id'])->anyStatus()->one();
            $data = [
                'id' => $res['user_id'],
                'name' => $res['user_name'],
                'exists' => false
            ];
            if ($user) {
                $data['name'] = $user->friendlyName;
                $data['exists'] = true;
                $data['status'] = $user->status;
            }
            $users[] = $data;
        }
        return $users;
    }

    public function getPaginatedLogs(array $filters, int $perPage = 5, string $orderBy = 'dateCreated desc'): array
    {
        $query = ActivityLog::find()->with('changedFields')->orderBy($orderBy);
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

    public function deleteUserLogs(User $user)
    {
        ActivityLog::deleteAll(['user_id' => $user->id]);
    }

    public function deleteAllLogs()
    {
        ActivityLog::deleteAll();
    }

    public function deleteLog(int $id)
    {
        ActivityLog::deleteAll(['id' => $id]);
    }

    public function runGc()
    {
        $threshold = Activity::$plugin->settings->autoDeleteLogsThreshold;
        if (!$threshold) {
            return;
        }
        $date = (new \DateTime)->sub(new \DateInterval('P' . $threshold . 'D'));
        ActivityLog::deleteAll(['<', 'dateCreated', $date->format('Y-m-d H:i:s')]);
    }
}