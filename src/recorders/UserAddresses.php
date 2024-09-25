<?php

namespace Ryssbowh\Activity\recorders;

use Ryssbowh\Activity\Activity;
use Ryssbowh\Activity\base\recorders\ElementsRecorder;
use Ryssbowh\Activity\models\fieldHandlers\elements\Plain;
use craft\base\Element;
use craft\elements\Address;
use craft\elements\User;
use craft\fieldlayoutelements\FullNameField;
use craft\fieldlayoutelements\addresses\AddressField;
use craft\fieldlayoutelements\addresses\CountryCodeField;
use craft\fieldlayoutelements\addresses\LabelField;
use craft\fieldlayoutelements\addresses\LatLongField;
use craft\fieldlayoutelements\addresses\OrganizationField;
use craft\fieldlayoutelements\addresses\OrganizationTaxIdField;
use yii\base\Event;

/**
 * @since 3.0.0
 */
class UserAddresses extends ElementsRecorder
{
    /**
     * @inheritDoc
     */
    protected ?string $deleteTypesCategory = 'userAddresses';

    /**
     * @inheritDoc
     */
    protected array $deleteTypes = ['userAddressDeleted', 'userAddressCreated', 'userAddressSaved'];

    /**
     * @inheritDoc
     */
    public function init(): void
    {
        Event::on(Address::class, Address::EVENT_BEFORE_SAVE, function ($event) {
            if ($event->sender->owner instanceof User) {
                Activity::getRecorder('userAddresses')->beforeSaved($event->sender);
            }
        });
        Event::on(Address::class, Address::EVENT_AFTER_SAVE, function ($event) {
            if ($event->sender->owner instanceof User) {
                Activity::getRecorder('userAddresses')->onSaved($event->sender);
            }
        });
        Event::on(Address::class, Address::EVENT_AFTER_DELETE, function ($event) {
            if ($event->sender->owner instanceof User) {
                Activity::getRecorder('userAddresses')->onDeleted($event->sender);
            }
        });
    }

    /**
     * @inheritDoc
     */
    protected function getElementType(): string
    {
        return Address::class;
    }

    /**
     * @inheritDoc
     */
    protected function getActivityHandle(): string
    {
        return 'userAddress';
    }

    /**
     * @inheritDoc
     */
    protected function getFieldsValues(Element $address): array
    {
        $mappings = $fields = [];
        $addressesService = \Craft::$app->addresses;
        foreach (\Craft::$app->addresses->getFieldLayout()->getTabs() as $tab) {
            foreach ($tab->elements as $element) {
                if ($element instanceof LabelField) {
                    $mappings[] = [
                        'class' => Plain::class,
                        'attribute' => $element->attribute,
                        'label' => \Craft::t('app', 'Label')
                    ];
                }
                if ($element instanceof CountryCodeField) {
                    $mappings[] = [
                        'class' => Plain::class,
                        'attribute' => $element->attribute,
                        'label' => \Craft::t('app', 'Country')
                    ];
                }
                if ($element instanceof OrganizationField) {
                    $mappings[] = [
                        'class' => Plain::class,
                        'attribute' => $element->attribute,
                        'label' => \Craft::t('app', 'Organization')
                    ];
                }
                if ($element instanceof OrganizationTaxIdField) {
                    $mappings[] = [
                        'class' => Plain::class,
                        'attribute' => $element->attribute,
                        'label' => \Craft::t('app', 'Organization Tax Id')
                    ];
                }
                if ($element instanceof FullNameField) {
                    $mappings[] = [
                        'class' => Plain::class,
                        'attribute' => $element->attribute,
                        'label' => \Craft::t('app', 'Full Name')
                    ];
                }
                if ($element instanceof LatLongField) {
                    $mappings[] = [
                        'class' => Plain::class,
                        'attribute' => 'latitude',
                        'label' => \Craft::t('app', 'Latitude')
                    ];
                    $mappings[] = [
                        'class' => Plain::class,
                        'attribute' => 'longitude',
                        'label' => \Craft::t('app', 'Longitude')
                    ];
                }
                if ($element instanceof AddressField) {
                    $visibleFields = array_merge(
                        $addressesService->getUsedFields($address->countryCode),
                        $addressesService->getUsedSubdivisionFields($address->countryCode),
                    );
                    foreach (['addressLine1', 'addressLine2', 'addressLine3', 'administrativeArea', 'locality', 'dependentLocality', 'postalCode', 'sortingCode'] as $attribute) {
                        if (in_array($attribute, $visibleFields)) {
                            $mappings[] = [
                                'class' => Plain::class,
                                'attribute' => $attribute,
                                'label' => $address->getAttributeLabel($attribute)
                            ];
                        }
                    }
                }
            }
        }
        foreach ($mappings as $mapping) {
            $fields[$mapping['attribute']] = new $mapping['class']([
                'name' => $mapping['label'],
                'value' => $address->{$mapping['attribute']}
            ]);
        }
        $fields = array_merge(
            $fields,
            $this->getCustomFieldValues($address)
        );
        return $fields;
    }

    /**
     * @inheritDoc
     */
    protected function getRootElement(Element $element): Element
    {
        return $element;
    }
}
