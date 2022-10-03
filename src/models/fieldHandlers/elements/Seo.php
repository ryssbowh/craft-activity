<?php

namespace Ryssbowh\Activity\models\fieldHandlers\elements;

use Ryssbowh\Activity\Activity;
use Ryssbowh\Activity\base\fieldHandlers\ElementFieldHandler;
use Ryssbowh\Activity\base\fieldHandlers\FieldHandler;
use craft\elements\Asset;

class Seo extends ElementFieldHandler
{
    /**
     * @var array
     */
    protected $_dirty;

    /**
     * @inheritDoc
     */
    public function init()
    {
        parent::init();
        $this->value = $this->buildValues();
    }

    /**
     * @inheritDoc
     */
    public function getDirty(FieldHandler $handler): array
    {
        if ($this->_dirty === null) {
            $this->_dirty = $this->buildDirty($this->value, $handler->value);
        }
        return $this->_dirty;
    }

    /**
     * @inheritDoc
     */
    public function isDirty(FieldHandler $handler): bool
    {
        return !empty($this->getDirty($handler));
    }

    /**
     * @inheritDoc
     */
    public static function getTargets(): array
    {
        return [
            'ether\seo\fields\SeoField'
        ];
    }

    /**
     * @inheritDoc
     */
    public static function getTemplate(): ?string
    {
        return 'activity/field-handlers/seo-field';
    }

    /**
     * @inheritDoc
     */
    public function getDbValue(string $valueKey): array
    {
        if ($valueKey == 'f') {
            return $this->buildDirty([], $this->value);
        }
        return $this->buildDirty($this->value, []);
    }

    /**
     * Build dirty values
     * 
     * @param  array  $newValue
     * @param  array  $oldFields
     * @return array
     */
    protected function buildDirty(array $newValue, array $oldValue): array
    {
        $dirty = [];
        foreach (['title', 'description', 'keywords'] as $key) {
            if ($oldValue[$key] !== $newValue[$key]) {
                $dirty[$key] = [
                    'f' => $oldValue[$key],
                    't' => $newValue[$key]
                ];
            }
        }
        foreach (['facebook', 'twitter'] as $key) {
            foreach (['title', 'description'] as $key2) {
                if ($oldValue[$key][$key2] !== $newValue[$key][$key2]) {
                    $dirty[$key][$key2] = [
                        'f' => $oldValue[$key][$key2],
                        't' => $newValue[$key][$key2]
                    ];
                }
            }
            if ($oldValue[$key]['image']['id'] !== $newValue[$key]['image']['id']) {
                $iDirty = [];
                if ($oldValue[$key]['image']['id']) {
                    $iDirty['f'] = $oldValue[$key]['image']['id'];
                    $iDirty['ff'] = $oldValue[$key]['image']['id'];
                }
                if ($newValue[$key]['image']['id']) {
                    $iDirty['t'] = $newValue[$key]['image']['id'];
                    $iDirty['tf'] = $newValue[$key]['image']['id'];
                }
                $dirty[$key]['image'] = $iDirty;
            }
        }
        foreach (['canonical', 'noindex', 'nofollow', 'noarchive', 'nosnippet', 'notranslate', 'noimageindex'] as $key) {
            if ($oldValue['advanced'][$key] !== $newValue['advanced'][$key]) {
                $dirty['advanced'][$key] = [
                    'f' => $oldValue['advanced'][$key],
                    't' => $newValue['advanced'][$key]
                ];
            }
        }
        if ($dirty) {
            $dirty['name'] = $this->name;
        }
        return $dirty;
    }

    /**
     * Build the value
     * 
     * @return array
     */
    protected function buildValues(): array
    {
        $advanced = [
            'canonical' => $this->rawValue->advanced['canonical'],
            'noindex' => in_array('noindex', $this->rawValue->advanced['robots']),
            'nofollow' => in_array('nofollow', $this->rawValue->advanced['robots']),
            'noarchive' => in_array('noarchive', $this->rawValue->advanced['robots']),
            'nosnippet' => in_array('nosnippet', $this->rawValue->advanced['robots']),
            'notranslate' => in_array('notranslate', $this->rawValue->advanced['robots']),
            'noimageindex' => in_array('noimageindex', $this->rawValue->advanced['robots']),
        ];
        $twitterImage = null;
        if ($this->rawValue->social['twitter']->imageId) {
            $twitterImage = Asset::find()->anyStatus()->id($this->rawValue->social['twitter']->imageId)->one();
        }
        $facebookImage = null;
        if ($this->rawValue->social['facebook']->imageId) {
            $facebookImage = Asset::find()->anyStatus()->id($this->rawValue->social['facebook']->imageId)->one();
        }
        return [
            'name' => $this->name,
            'title' => (string)$this->rawValue->getTitle(),
            'description' => $this->rawValue->descriptionRaw,
            'keywords' => array_map(function ($k) {
                return $k['keyword'];
            }, $this->rawValue->keywords),
            'advanced' => $advanced,
            'twitter' => [
                'image' => [
                    'id' => (int)$this->rawValue->social['twitter']->imageId ?: null,
                    'title' => $twitterImage ? $twitterImage->title : '',
                ],
                'title' => (string)$this->rawValue->social['twitter']->title,
                'description' => (string)$this->rawValue->social['twitter']->description
            ],
            'facebook' => [
                'image' => [
                    'id' => (int)$this->rawValue->social['facebook']->imageId ?: null,
                    'title' => $facebookImage ? $facebookImage->title : '',
                ],
                'title' => (string)$this->rawValue->social['facebook']->title,
                'description' => (string)$this->rawValue->social['facebook']->description
            ]
        ];
    }
}