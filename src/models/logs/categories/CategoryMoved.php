<?php

namespace Ryssbowh\Activity\models\logs\categories;

use Ryssbowh\Activity\base\logs\CategoryLog;
use Ryssbowh\Activity\traits\ElementMovedLog;

class CategoryMoved extends CategoryLog
{
    use ElementMovedLog;
}