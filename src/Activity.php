<?php

namespace Ryssbowh\Activity;

use Ryssbowh\Activity\base\recorders\Recorder;
use Ryssbowh\Activity\models\Settings;
use Ryssbowh\Activity\services\Exporters;
use Ryssbowh\Activity\services\FieldHandlers;
use Ryssbowh\Activity\services\Fields;
use Ryssbowh\Activity\services\Logs;
use Ryssbowh\Activity\services\Recorders;
use Ryssbowh\Activity\services\Types;
use Ryssbowh\Activity\traits\CkeditorField;
use Ryssbowh\Activity\traits\NeoField;
use Ryssbowh\Activity\traits\RedactorField;
use Ryssbowh\Activity\traits\SeoField;
use Ryssbowh\Activity\traits\SuperTableField;
use Ryssbowh\Activity\traits\TinyMceField;
use Ryssbowh\Activity\traits\TypedLinkField;
use Ryssbowh\Activity\traits\VizyField;
use Ryssbowh\Activity\twig\ActivityExtension;
use Ryssbowh\Activity\twig\TwigActivity;
use craft\base\Model;
use craft\base\Plugin;
use craft\services\Elements;
use craft\services\Gc;
use craft\services\Plugins;
use craft\services\UserPermissions;
use craft\web\UrlManager;
use craft\web\twig\variables\CraftVariable;
use yii\base\Application;
use yii\base\Event;

class Activity extends Plugin
{
    use RedactorField;
    use SeoField;
    use TinyMceField;
    use TypedLinkField;
    use SuperTableField;
    use NeoField;
    use VizyField;
    use CkeditorField;

    /**
     * @var Themes
     */
    public static $plugin;

    /**
     * @inheritDoc
     */
    public string $schemaVersion = '2.4.1';

    /**
     * @inheritdoc
     */
    public bool $hasCpSettings = true;

    /**
     * @inheritDoc
     */
    public bool $hasCpSection = true;

    /**
     * The context used to render logs, can be 'web' or 'export'
     * @since 2.3.5
     * @var   string
     */
    public string $twigContext = 'web';

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
        $this->registerUserHook();
        $this->registerTwig();
        $this->registerRecorders();

        $this->initSeoField();
        $this->initRedactorField();
        $this->initTinyMceField();
        $this->initTypedLinkField();
        $this->initSuperTableField();
        $this->initNeoField();
        $this->initVizyField();
        $this->initCkeditorField();

        Event::on(Application::class, Application::EVENT_AFTER_REQUEST, function (Event $event) {
            $this->recorders->saveLogs();
        });
    }

    /**
     * Get a recorder by its name
     *
     * @param  string $name
     * @return Recorder
     */
    public static function getRecorder(string $name): Recorder
    {
        return static::$plugin->recorders->$name;
    }

    /**
     * Register all recorders once all plugins are loaded
     */
    protected function registerRecorders()
    {
        Event::on(Plugins::class, Plugins::EVENT_AFTER_LOAD_PLUGINS, function (Event $event) {
            $this->recorders->register();
        });
    }

    /**
     * @inheritdoc
     */
    protected function createSettingsModel(): ?Model
    {
        return new Settings();
    }

    /**
     * Register twig variables and extensions
     */
    protected function registerTwig()
    {
        if (\Craft::$app->request->isCpRequest or \Craft::$app->request->isConsoleRequest) {
            \Craft::$app->view->registerTwigExtension(new ActivityExtension());
        }
        Event::on(CraftVariable::class, CraftVariable::EVENT_INIT, function (Event $event) {
            $event->sender->set('activity', TwigActivity::class);
        });
    }

    /**
     * Register garbage collection
     */
    protected function registerGarbageCollection()
    {
        Event::on(Gc::class, Gc::EVENT_RUN, function () {
            Activity::$plugin->logs->runGc();
        });
    }

    /**
     * @inheritdoc
     */
    public function getSettingsResponse(): mixed
    {
        $types = [];
        foreach ($this->types->getTypes() as $handle => $class) {
            $class = new $class();
            $types[$handle] = $class->name;
        }
        ksort($types);
        $controller = \Craft::$app->controller;

        $userGroups = [];
        foreach (\Craft::$app->userGroups->getAllGroups() as $group) {
            $userGroups[$group->handle] = $group->name;
        }

        return $controller->renderTemplate('activity/settings', [
            'settings' => $this->settings,
            'userGroups' => $userGroups,
            'types' => $types,
            'plugin' => $this,
            'isPro' => \Craft::$app->getEdition() == \Craft::Pro
        ]);
    }

    /**
     * Register user details hook to show user's latest activity
     */
    protected function registerUserHook()
    {
        \Craft::$app->view->hook('cp.users.edit.details', function (array &$context) {
            if (\Craft::$app->user->identity->can('viewActivityLogs')) {
                return \Craft::$app->view->renderTemplate('activity/user-latest-activity', [
                    'user' => $context['user']
                ]);
            }
        });
    }

    /**
     * Register cp routes
     */
    protected function registerCpRoutes()
    {
        Event::on(UrlManager::class, UrlManager::EVENT_REGISTER_CP_URL_RULES, function (Event $event) {
            $event->rules = array_merge($event->rules, [
                'activity' => 'activity/activity',
                'export-activity-logs' => 'activity/export'
            ]);
        });
    }

    /**
     * Register permissions
     */
    protected function registerPermissions()
    {
        Event::on(UserPermissions::class, UserPermissions::EVENT_REGISTER_PERMISSIONS, function (Event $event) {
            $event->permissions[] = [
                'heading' => \Craft::t('activity', 'Activity'),
                'permissions' => [
                    'viewActivityLogs' => [
                        'label' => \Craft::t('activity', 'View activity records')
                    ],
                    'deleteActivityLogs' => [
                        'label' => \Craft::t('activity', 'Delete activity records')
                    ]
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
            'fields' => Fields::class,
            'exporters' => Exporters::class,
        ]);
    }
}
