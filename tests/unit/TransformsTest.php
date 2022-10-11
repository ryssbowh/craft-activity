<?php

use craft\models\AssetTransform;

class TransformsTest extends BaseTest
{
    public function testAssetTransforms()
    {
        $this->resetActivity();
        $transform = new AssetTransform([
            'name' => 'test',
            'handle' => 'test',
            'width' => 500
        ]);
        $this->assertTrue(\Craft::$app->assetTransforms->saveTransform($transform));
        $this->assertLogCount(1);
        $this->assertLatestLog('assetTransformCreated');
        $transform->width = 600;
        $this->assertTrue(\Craft::$app->assetTransforms->saveTransform($transform));
        $this->assertLogCount(2);
        $this->assertLatestLog('assetTransformSaved');
        $this->assertTrue(\Craft::$app->assetTransforms->deleteTransform($transform));
        $this->assertLogCount(3);
        $this->assertLatestLog('assetTransformDeleted');
    }
}
