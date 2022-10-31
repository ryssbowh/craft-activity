<?php

namespace Ryssbowh\Activity\models\logs\assets;

use Ryssbowh\Activity\base\logs\ConfigModelLog;
use craft\base\Model;
use craft\helpers\UrlHelper;
use craft\models\ImageTransform;

class ImageTransformCreated extends ConfigModelLog
{
    /**
     * @inheritDoc
     */
    public function getTitle(): string
    {
        return \Craft::t('activity', 'Created image transform {name}', ['name' => $this->modelName]);
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
        return \Craft::$app->imageTransforms->getTransformByUid($this->target_uid);
    }

    /**
     * @inheritDoc
     */
    protected function _getFieldLabels(): array
    {
        return array_merge((new ImageTransform)->attributeLabels(), [
            'interlace' => \Craft::t('app', 'Interlacing'),
            'format' => \Craft::t('app', 'Image Format')
        ]);
    }
}