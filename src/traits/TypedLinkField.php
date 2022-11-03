<?php

namespace Ryssbowh\Activity\traits;

use Ryssbowh\Activity\models\fieldHandlers\elements\LinkField;
use Ryssbowh\Activity\models\fieldHandlers\projectConfig\LinkFieldTypes;
use Ryssbowh\Activity\services\FieldHandlers;
use Ryssbowh\Activity\services\Fields;
use yii\base\Event;

/**
 * @since 1.2.0
 */
trait TypedLinkField
{
    /**
     * Register everything needed for Typed link fields tracking
     */
    protected function initTypedLinkField()
    {
        Event::on(Fields::class, Fields::EVENT_REGISTER_FIELD_LABELS, function (Event $event) {
            $categories = array_keys(\Craft::$app->i18n->translations);
            $category = in_array('typedlinkfield', $categories) ? 'typedlinkfield' : 'activity';
            $event->labels['lenz\\linkfield\\fields\\LinkField'] = [
                'settings.allowCustomText' => \Craft::t($category, 'Allow custom link text'),
                'settings.allowTarget' => \Craft::t($category, 'Allow links to open in new window'),
                'settings.autoNoReferrer' => \Craft::t($category, 'Set link relation to "noopener noreferrer" when opening links in a new window'),
                'settings.customTextMaxLength' => \Craft::t($category, 'Maximum length'),
                'settings.customTextRequired' => \Craft::t($category, 'Custom link text is required'),
                'settings.defaultLinkName' => \Craft::t($category, 'Default link type'),
                'settings.defaultText' => \Craft::t($category, 'Default link text'),
                'settings.enableAllLinkTypes' => \Craft::t($category, 'Enable all'),
                'settings.enableAriaLabel' => \Craft::t($category, 'Enable element url and title cache'),
                'settings.enableElementCache' => \Craft::t($category, 'Enable element url and title cache'),
                'settings.enableTitle' => \Craft::t($category, 'Enable title support'),
                'settings.typeSettings.custom.allowAliases' => \Craft::t($category, 'Allow aliases and environment variables'),
                'settings.typeSettings.custom.disableValidation' => \Craft::t($category, 'Disable Custom input validation'),
                'settings.typeSettings.email.disableValidation' => \Craft::t($category, 'Disable Mail input validation'),
                'settings.typeSettings.email.allowAliases' => \Craft::t($category, 'Allow aliases and environment variables'),
                'settings.typeSettings.tel.disableValidation' => \Craft::t($category, 'Disable Telephone input validation'),
                'settings.typeSettings.tel.allowAliases' => \Craft::t($category, 'Allow aliases and environment variables'),
                'settings.typeSettings.url.disableValidation' => \Craft::t($category, 'Disable URL input validation'),
                'settings.typeSettings.url.allowAliases' => \Craft::t($category, 'Allow aliases and environment variables')
            ];
            $field = null;
            foreach (\Craft::$app->fields->getAllFields() as $cfield) {
                if (get_class($cfield) == 'lenz\\linkfield\\fields\\LinkField') {
                    $field = $cfield;
                    break;
                }
            }
            if ($field) {
                foreach ($field->getAvailableLinkTypes() as $name => $type) {
                    $event->labels['lenz\\linkfield\\fields\\LinkField'] = array_merge($event->labels['lenz\\linkfield\\fields\\LinkField'], [
                        "settings.typeSettings.$name.enabled" => \Craft::t('app', 'Enabled'),
                        "settings.typeSettings.$name.sources" => \Craft::t('app', 'Sources'),
                        "settings.typeSettings.$name.sites" => \Craft::t('app', 'Sites'),
                        "settings.typeSettings.$name.allowCustomQuery" => \Craft::t($category, 'Allow users to enter custom queries that will be appended to the url'),
                        "settings.typeSettings.$name.allowCrossSiteLink" => \Craft::t($category, 'Allow links to reference a different site'),
                    ]);
                }
            }
        });
        Event::on(Fields::class, Fields::EVENT_REGISTER_TRACKED_FIELDS, function (Event $event) {
            $event->tracked['lenz\\linkfield\\fields\\LinkField'] = ['settings.allowCustomText', 'settings.allowTarget', 'settings.autoNoReferrer', 'settings.customTextMaxLength', 'settings.customTextRequired', 'settings.defaultLinkName', 'settings.defaultText', 'settings.enableAllLinkTypes', 'settings.enableAriaLabel', 'settings.enableElementCache', 'settings.enableTitle', 'settings.typeSettings'];
        });
        Event::on(FieldHandlers::class, FieldHandlers::EVENT_REGISTER_ELEMENT_HANDLERS, function (Event $event) {
            $event->add(LinkField::class);
        });
        Event::on(FieldHandlers::class, FieldHandlers::EVENT_REGISTER_PROJECTCONFIG_HANDLERS, function (Event $event) {
            $event->add(LinkFieldTypes::class);
        });
    }
}