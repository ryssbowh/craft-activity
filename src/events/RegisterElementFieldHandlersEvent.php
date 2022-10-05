<?php

namespace Ryssbowh\Activity\events;

use Ryssbowh\Activity\exceptions\FieldHandlerException;
use Ryssbowh\Activity\models\fieldHandlers\elements\Assets;
use Ryssbowh\Activity\models\fieldHandlers\elements\Categories;
use Ryssbowh\Activity\models\fieldHandlers\elements\Date;
use Ryssbowh\Activity\models\fieldHandlers\elements\Entries;
use Ryssbowh\Activity\models\fieldHandlers\elements\Lightswitch;
use Ryssbowh\Activity\models\fieldHandlers\elements\LinkField;
use Ryssbowh\Activity\models\fieldHandlers\elements\ListField;
use Ryssbowh\Activity\models\fieldHandlers\elements\ListsField;
use Ryssbowh\Activity\models\fieldHandlers\elements\Matrix;
use Ryssbowh\Activity\models\fieldHandlers\elements\Plain;
use Ryssbowh\Activity\models\fieldHandlers\elements\Redactor;
use Ryssbowh\Activity\models\fieldHandlers\elements\Seo;
use Ryssbowh\Activity\models\fieldHandlers\elements\SuperTable;
use Ryssbowh\Activity\models\fieldHandlers\elements\Tags;
use Ryssbowh\Activity\models\fieldHandlers\elements\Users;
use yii\base\Event;

class RegisterElementFieldHandlersEvent extends Event
{
    /**
     * @var array
     */
    protected $_handlers = [];

    /**
     * @inheritDoc
     */
    public function init()
    {
        parent::init();
        $this->addMany([
            Assets::class,
            Categories::class,
            Date::class,
            Entries::class,
            Lightswitch::class,
            LinkField::class,
            ListField::class,
            ListsField::class,
            Matrix::class,
            Plain::class,
            Redactor::class,
            Seo::class,
            SuperTable::class,
            Tags::class,
            Users::class
        ]);
    }

    /**
     * Get registered field handlers
     * @return array
     */
    public function getHandlers(): array
    {
        return $this->_handlers;
    }

    /**
     * Add a field handler to register
     * 
     * @param string  $handler
     * @param boolean $replace
     */
    public function add(string $handler, bool $replace = false)
    {
        foreach ($handler::getTargets() as $target) {
            if (isset($this->_handlers[$target]) and !$replace) {
                throw FieldHandlerException::elementRegistered($target, $this->_handlers[$target]);
            }
            $this->_handlers[$target] = $handler;
        }
    }

    /**
     * Add many field handlers to register
     * 
     * @param array   $handlers
     * @param boolean $replace
     */
    public function addMany(array $handlers, bool $replace = false)
    {
        foreach ($handlers as $handler) {
            $this->add($handler, $replace);
        }
    }
}