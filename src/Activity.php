<?php

namespace Ryssbowh\Activity;

use Ryssbowh\Activity\base\Recorder;
use Ryssbowh\Activity\models\Settings;
use Ryssbowh\Activity\services\FieldHandlers;
use Ryssbowh\Activity\services\Logs;
use Ryssbowh\Activity\services\Recorders;
use Ryssbowh\Activity\services\Types;
use Ryssbowh\Activity\twig\ActivityExtension;
use craft\base\Model;
use craft\base\Plugin;
use craft\services\Elements;
use craft\services\Gc;
use craft\services\UserPermissions;
use craft\web\UrlManager;
use yii\base\Event;

class Activity extends Plugin
{
    /**
     * @var Themes
     */
    public static $plugin;
    
    /**
     * @inheritdoc
     */
    public $hasCpSettings = true;

    /**
     * @inheritDoc
     */
    public $hasCpSection = true;

    /**
     * inheritDoc
     */
    public function init(): void
    {
        parent::init();

        self::$plugin = $this;

        $this->registerServices();
        $this->registerPermissions();
        $this->registerCpRoutes();
        $this->registerGarbageCollection();

        if (!($this->settings->ignoreApplyingYaml and !\Craft::$app->projectConfig->isApplyingYamlChanges) and 
            !($this->settings->ignoreConsoleRequests and \Craft::$app->request->isConsoleRequest) and
            !($this->settings->ignoreCpRequests and \Craft::$app->request->isCpRequest) and
            !($this->settings->ignoreSiteRequests and \Craft::$app->request->isSiteRequest)) {
            $this->recorders->register();
            $this->registerElementEvents();
        }
        if (\Craft::$app->request->isCpRequest) {
            \Craft::$app->view->registerTwigExtension(new ActivityExtension);
        }
    }

    /**
     * Get a recorder by its name
     * 
     * @param  string $name
     * @return Recorder
     */
    public static function getRecorder(string $name): ?Recorder
    {
        return static::$plugin->recorders->$name;
    }

    /**
     * @inheritdoc
     */
    protected function createSettingsModel(): ?Model
    {
        return new Settings();
    }

    /**
     * Register garbace collection
     */
    protected function registerGarbageCollection()
    {
        Event::on(Gc::class, Gc::EVENT_RUN, function () {
            Activity::$plugin->logs->runGc();
        });
    }

    /**
     * @inheritDoc
     */
    protected function settingsHtml(): string
    {
        $types = [];
        foreach ($this->types->getTypes() as $handle => $class) {
            $class = new $class;
            $types[$handle] = $class->name;
        }
        ksort($types);
        return \Craft::$app->view->renderTemplate(
            'activity/settings',
            [
                'settings' => $this->getSettings(),
                'types' => $types
            ]
        );
    }

    /**
     * Register elements events
     */
    protected function registerElementEvents()
    {
        if ($this->settings->ignoreResaveElements) {
            // Event::on(Elements::class, Elements::EVENT_BEFORE_RESAVE_ELEMENT, function (Event $event) {
            //     \Craft::debug('disabling all recorders 1');
            //     Activity::$plugin->recorders->stopRecording();
            // });
            // Event::on(Elements::class, Elements::EVENT_AFTER_RESAVE_ELEMENT, function (Event $event) {
            //     Activity::$plugin->recorders->startRecording();
            // });
            // Event::on(Elements::class, Elements::EVENT_BEFORE_RESAVE_ELEMENTS, function (Event $event) {
            //     \Craft::debug('disabling all recorders 2');
            //     Activity::$plugin->recorders->stopRecording();
            // });
            // Event::on(Elements::class, Elements::EVENT_AFTER_RESAVE_ELEMENTS, function (Event $event) {
            //     Activity::$plugin->recorders->startRecording();
            // });
        }
        if ($this->settings->ignoreUpdateSlugs) {
            Event::on(Elements::class, Elements::EVENT_BEFORE_UPDATE_SLUG_AND_URI, function (Event $event) {
                Activity::$plugin->recorders->stopRecording();
            });
            Event::on(Elements::class, Elements::EVENT_AFTER_UPDATE_SLUG_AND_URI, function (Event $event) {
                Activity::$plugin->recorders->startRecording();
            });
        }
        if ($this->settings->ignorePropagate) {
            Event::on(Elements::class, Elements::EVENT_BEFORE_PROPAGATE_ELEMENTS, function (Event $event) {
                Activity::$plugin->recorders->stopRecording();
            });
            Event::on(Elements::class, Elements::EVENT_AFTER_PROPAGATE_ELEMENTS, function (Event $event) {
                Activity::$plugin->recorders->startRecording();
            });
        }
    }

    /**
     * Register cp routes
     */
    protected function registerCpRoutes()
    {
        Event::on(UrlManager::class, UrlManager::EVENT_REGISTER_CP_URL_RULES, function(Event $event) {
            $event->rules = array_merge($event->rules, [
                'activity' => 'activity/activity'
            ]);
        });
    }

    /**
     * Register permissions
     */
    protected function registerPermissions()
    {
        Event::on(UserPermissions::class, UserPermissions::EVENT_REGISTER_PERMISSIONS, function (Event $event) {
            $event->permissions[\Craft::t('activity', 'Activity')] = [
                'viewActivityLogs' => [
                    'label' => \Craft::t('activity', 'View activity records')
                ],
                'deleteActivityLogs' => [
                    'label' => \Craft::t('activity', 'Delete activity records')
                ]
            ];
        });
    }

    /**
     * Register all services
     */
    protected function registerServices()
    {
        $this->setComponents([
            'recorders' => Recorders::class,
            'logs' => Logs::class,
            'types' => Types::class,
            'fieldHandlers' => FieldHandlers::class,
        ]);
    }
}