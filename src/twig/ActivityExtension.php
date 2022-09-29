<?php

namespace Ryssbowh\Activity\twig;

use Ryssbowh\Activity\helpers\PrettyPrint;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class ActivityExtension extends AbstractExtension
{
    /**
     * @inheritDoc
     */
    public function getFilters()
    {
        return [
            new TwigFilter('prettyPrint', [$this, 'prettyPrint'], ['is_safe' => ['html']])
        ];
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('handlerTemplate', [$this, 'handlerTemplate']),
        ];
    }

    public function handlerTemplate(string $handlerClass): ?string
    {
        if (!$handlerClass) {
            return null;
        }
        return $handlerClass::getTemplate();
    }

    /**
     * Pretty print a value
     * 
     * @param  $value
     * @return string
     */
    public function prettyPrint($value)
    {
        return PrettyPrint::get($value);
    }
}