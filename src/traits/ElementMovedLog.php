<?php

namespace Ryssbowh\Activity\traits;

trait ElementMovedLog
{
    public $lft;
    public $rgt;
    public $level;

    public function getDbData(): array
    {
        return array_merge(parent::getDbData(), [
            'lft' => $this->lft,
            'rgt' => $this->rgt,
            'level' => $this->level,
        ]);
    }
}