<?php

namespace Ryssbowh\Activity\models\logs\entries;

use Ryssbowh\Activity\base\logs\EntryLog;
use Ryssbowh\Activity\traits\ElementMovedLog;

class EntryMoved extends EntryLog
{
    use ElementMovedLog;
}