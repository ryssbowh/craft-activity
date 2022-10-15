<?php

namespace Ryssbowh\Activity\models\fieldHandlers\projectConfig;

use craft\helpers\MailerHelper;

class TransportType extends DefaultHandler
{
    /**
     * @inheritDoc
     */
    public function init(): void
    {
        $this->fancyValue = $this->getTypes()[$this->value];
    }

    /**
     * @inheritDoc
     */
    public function hasFancyValue(): bool
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    public static function getTargets(): array
    {
        return [
            'email.transportType'
        ];
    }

    /**
     * Get types fancy labels
     * 
     * @return array
     */
    protected function getTypes(): array
    {
        $types = [];
        foreach (MailerHelper::allMailerTransportTypes() as $type) {
            $types[$type] = $type::displayName();
        }
        return $types;
    }
}