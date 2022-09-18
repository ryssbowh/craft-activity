<?php

namespace Ryssbowh\Activity\models\logs\entries;

use Ryssbowh\Activity\base\EntryLog;
use Ryssbowh\Activity\traits\ElementMovedLog;

class EntryMoved extends EntryLog
{
    use ElementMovedLog;
}