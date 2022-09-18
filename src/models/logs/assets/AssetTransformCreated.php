<?php

namespace Ryssbowh\Activity\models\logs\assets;

use Ryssbowh\Activity\base\ConfigModelLog;
use craft\base\Model;
use craft\helpers\UrlHelper;
use craft\models\AssetTransform;

class AssetTransformCreated extends ConfigModelLog
{
    /**
     * @inheritDoc
     */
    public function getTitle(): string
    {
        return \Craft::t('activity', 'Created asset transform {name}', ['name' => $this->modelName]);
    }

    /**
     * @inheritDoc
     */
    protected function getModelLink(): string
    {
        return UrlHelper::cpUrl('settings/assets/transforms/' . $this->model->handle);
    }

    /**
     * @inheritDoc
     */
    protected function loadModel(): ?Model
    {
        return \Craft::$app->assetTransforms->getTransformById($this->target_id);
    }

    /**
     * @inheritDoc
     */
    protected function getFieldLabels(): array
    {
        return array_merge((new AssetTransform)->attributeLabels(), [
            'interlace' => \Craft::t('app', 'Interlacing'),
            'format' => \Craft::t('app', 'Image Format')
        ]);
    }
}