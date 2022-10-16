<?php

use craft\models\ImageTransform;

class TransformsTest extends BaseTest
{
    public function testImageTransforms()
    {
        $this->resetActivity();
        $transform = new ImageTransform([
            'name' => 'test',
            'handle' => 'test',
            'width' => 500
        ]);
        $this->assertTrue(\Craft::$app->imageTransforms->saveTransform($transform));
        $this->assertLogCount(1);
        $this->assertLatestLog('imageTransformCreated');
        $transform->width = 600;
        $this->assertTrue(\Craft::$app->imageTransforms->saveTransform($transform));
        $this->assertLogCount(2);
        $this->assertLatestLog('imageTransformSaved');
        $this->assertTrue(\Craft::$app->imageTransforms->deleteTransform($transform));
        $this->assertLogCount(3);
        $this->assertLatestLog('imageTransformDeleted');
    }
}
