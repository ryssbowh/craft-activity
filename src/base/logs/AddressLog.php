<?php

namespace Ryssbowh\Activity\base\logs;

use craft\elements\Address;
use craft\helpers\Html;

/**
 * @since 3.0.0
 */
abstract class AddressLog extends ElementLog
{
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
    public function getElementTitle(): string
    {
        $title = $this->target_name;
        if ($this->element) {
            $status = '<span class="status ' . $this->element->status . '"></span>';
            if ($this->element->owner) {
                $elems = explode('?', $this->element->owner->cpEditUrl);
                $url = $elems[0] . '/addresses' . (isset($elems[1]) ? '?' . $elems[1] : '');
                $title = Html::a($status . $this->element->{$this->titleField}, $url, ['target' => '_blank']);
            } else {
                $title = $status . $this->element->{$this->titleField};
            }
        }
        return $title;
    }
}
