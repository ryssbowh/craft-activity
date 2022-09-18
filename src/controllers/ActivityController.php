<?php

namespace Ryssbowh\Activity\controllers;

use Ryssbowh\Activity\Activity;
use Ryssbowh\Activity\assets\ActivityAssets;
use craft\elements\User;
use craft\web\Controller;

class ActivityController extends Controller
{
    public function actionIndex()
    {
        $this->requirePermission('viewActivityLogs');
        \Craft::$app->view->registerAssetBundle(ActivityAssets::class);
        $filters = $this->request->getParam('filters', []);
        $perPage = $this->request->getParam('perPage', 10);
        list($paginator, $logs) = Activity::$plugin->logs->getPaginatedLogs($filters, $perPage);
        $types = [];
        foreach (Activity::$plugin->types->usedTypes as $handle => $class) {
            $type = new $class;
            $types[$handle] = $type->name;
        }
        asort($types);
        $users = Activity::$plugin->logs->usedUsers;
        usort($users, function ($user1, $user2) {
            return ($user1['name'] > $user2['name']) ? 1 : -1;
        });
        return $this->renderTemplate('activity/activity', [
            'logs' => $logs,
            'pageInfo' => $paginator,
            'types' => $types,
            'users' => $users,
            'filters' => $filters,
            'perPage' => $perPage
        ]);
    }

    public function actionDeleteAllLogs()
    {
        $this->requirePermission('deleteActivityLogs');
        Activity::$plugin->logs->deleteAllLogs();
        return $this->asJson(['success' => true]);
    }

    public function actionDeleteLog()
    {
        $this->requirePermission('deleteActivityLogs');
        $id = $this->request->getRequiredBodyParam('id');
        Activity::$plugin->logs->deleteLog($id);
        return $this->asJson(['success' => true]);
    }

    public function actionLoadLogs()
    {
        $this->requirePermission('viewActivityLogs');
        $filters = $this->request->getBodyParam('filters', []);
        $perPage = $this->request->getParam('perPage', 10);
        list($paginator, $logs) = Activity::$plugin->logs->getPaginatedLogs($filters, $perPage);
        return $this->asJson([
            'success' => true,
            'logs' => \Craft::$app->view->renderTemplate('activity/activity-logs', [
                'logs' => $logs
            ]),
            'pagination' => \Craft::$app->view->renderTemplate('activity/activity-pager', [
                'pageInfo' => $paginator,
                'logs' => $logs
            ])
        ]);
    }
}