<?php 

namespace Ryssbowh\Activity\models;

use craft\base\Model;

class ChangedField extends Model
{
    /**
     * @var id
     */
    public $id;

    /**
     * @var string
     */
    public $name;

    /**
     * @var array
     */
    public $data;

    /**
     * @var string
     */
    public $handler;

    /**
     * Get the template used to render this changed field description
     * 
     * @return ?string
     */
    public function getTemplate(): ?string
    {
        if (class_exists($this->handler)) {
            return $this->handler::getTemplate();
        }
        return null;
    }
}