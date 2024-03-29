<?php

namespace Ryssbowh\Activity\records;

use Ryssbowh\Activity\Activity;
use Ryssbowh\Activity\base\logs\ActivityLog as ActivityLogModel;
use Ryssbowh\Activity\exceptions\ActivityTypeException;
use Ryssbowh\Activity\models\logs\MissingType;
use craft\db\ActiveRecord;
use craft\helpers\Json;

class ActivityLog extends ActiveRecord
{
    /**
     * @inheritDoc
     */
    public static function tableName()
    {
        return '{{%activity_logs}}';
    }

    /**
     * Changed fields relation
     */
    public function getChangedFields()
    {
        return $this->hasMany(ActivityChangedField::class, ['log_id' => 'id']);
    }

    /**
     * Turn this record into a model
     *
     * @return ActivityLogModel
     */
    public function toModel(): ActivityLogModel
    {
        try {
            $class = Activity::$plugin->types->getTypeClassByHandle($this->type);
        } catch (ActivityTypeException $e) {
            $class = MissingType::class;
        }
        $params = $this->toArray(['id', 'user_id', 'user_name', 'site_id', 'site_name', 'target_class', 'target_uid', 'target_name', 'request', 'data', 'ip', 'dateCreated']);
        $params['changedFields'] = array_map(function ($record) {
            return $record->toModel();
        }, $this->changedFields);
        $params['data'] = Json::decode($params['data']);
        return new $class($params);
    }
}
