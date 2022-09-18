<?php

namespace Ryssbowh\Activity\models\fieldHandlers\projectConfig;

use craft\helpers\MailerHelper;

class TransportType extends DefaultHandler
{
    public function init()
    {
        $this->fancyValue = $this->getTypes()[$this->value];
    }

    public function hasFancyValue(): bool
    {
        return true;
    }

    public static function getTargets(): array
    {
        return [
            'email.transportType'
        ];
    }

    protected function getTypes(): array
    {
        $types = [];
        foreach (MailerHelper::allMailerTransportTypes() as $type) {
            $types[$type] = $type::displayName();
        }
        return $types;
    }
}