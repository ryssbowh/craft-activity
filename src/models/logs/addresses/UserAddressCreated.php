<?php

namespace Ryssbowh\Activity\models\logs\addresses;

use Ryssbowh\Activity\base\logs\AddressLog;

/**
 * @since 3.0.0
 */
class UserAddressCreated extends AddressLog
{
    /**
     * @inheritDoc
     */
    public function getTitle(): string
    {
        return \Craft::t('activity', 'Created address {name}', ['name' => $this->elementTitle]);
    }
}
