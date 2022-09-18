<?php

namespace Ryssbowh\Activity\records;

use Ryssbowh\Activity\models\ChangedField;
use craft\db\ActiveRecord;
use craft\helpers\Json;

class ActivityChangedField extends ActiveRecord
{
    /**
     * @inheritDoc
     */
    public static function tableName()
    {
        return '{{%activity_changed_fields}}';
    }

    public function toModel(): ChangedField
    {
        $params = $this->toArray(['name', 'data']);
        $params['data'] = Json::decode($params['data']);
        return new ChangedField($params);
    }
}