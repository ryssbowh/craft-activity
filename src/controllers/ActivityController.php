<?php

namespace Ryssbowh\Activity\controllers;

use Ryssbowh\Activity\Activity;
use Ryssbowh\Activity\assets\ActivityAssets;
use craft\elements\User;
use craft\web\Controller;

class ActivityController extends Controller
{
    /**
     * Index action
     */
    public function actionIndex()
    {
        $this->requirePermission('viewActivityLogs');
        \Craft::$app->view->registerAssetBundle(ActivityAssets::class);
        $filters = $this->request->getParam('filters', []);
        $perPage = $this->request->getParam('perPage', 20);
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

    /**
     * Delete all logs action
     */
    public function actionDeleteAllLogs()
    {
        $this->requirePermission('deleteActivityLogs');
        Activity::$plugin->logs->deleteAllLogs();
        return $this->asJson(['success' => true]);
    }

    /**
     * Delete a log action
     */
    public function actionDeleteLog()
    {
        $this->requirePermission('deleteActivityLogs');
        $id = $this->request->getRequiredBodyParam('id');
        Activity::$plugin->logs->deleteLog($id);
        return $this->asJson(['success' => true]);
    }

    /**
     * Load logs action
     */
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

    /**
     * Return the value of a changed field, requires 'data' to be set in the request as 't' or 'f' for example
     */
    public function actionFieldValue()
    {
        $this->requirePermission('viewActivityLogs');
        $id = $this->request->getRequiredBodyParam('id');
        $data = $this->request->getRequiredBodyParam('data');
        $field = Activity::$plugin->logs->getChangedFieldById($id);
        return $this->asJson([
            'success' => true,
            'data' => array_key_exists($data, $field->data) ? $field->data[$data] : ''
        ]);
    }
}