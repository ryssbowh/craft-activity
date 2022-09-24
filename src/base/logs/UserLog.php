<?php

namespace Ryssbowh\Activity\base\logs;

use craft\base\Element;
use craft\elements\User;

abstract class UserLog extends ElementLog
{
    /**
     * @inheritDoc
     */
    protected function getTitleField(): string
    {
        return 'friendlyName';
    }

    /**
     * @inheritDoc
     */
    protected function getElementType(): string
    {
        return User::class;
    }

    /**
     * @inheritDoc
     */
    protected function loadElement(): ?Element
    {
        return $this->getElementType()::find()->anyStatus()->id($this->target_id)->one();
    }

    /**
     * @inheritDoc
     */
    protected function getIncludeSiteNameInTitle(): bool
    {
        return false;
    }
}