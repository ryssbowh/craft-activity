<?php

namespace Ryssbowh\Activity\twig;

use Ryssbowh\Activity\helpers\PrettyPrint;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class ActivityExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('prettyPrint', [$this, 'prettyPrint'], ['is_safe' => ['html']])
        ];
    }

    public function prettyPrint($value)
    {
        return PrettyPrint::get($value);
    }
}