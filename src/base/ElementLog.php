<?php

namespace Ryssbowh\Activity\base;

use craft\base\Element;
use craft\helpers\Html;

abstract class ElementLog extends ActivityLog
{
    protected $_element;

    /**
     * @inheritDoc
     */
    public function getDbData(): array
    {
        return array_merge(parent::getDbData(), [
            'target_id' => $this->element->id,
            'target_class' => $this->element->id,
            'target_name' => $this->element->{$this->titleField},
            'data' => [
                'site_id' => $this->element->site->id,
                'site_name' => $this->element->site->name
            ]
        ]);
    }

    /**
     * @inheritDoc
     */
    public function getTitle(): string
    {
        $title = $this->_getTitle() . ' {title}';
        $params = ['title' => $this->elementTitle];
        if ($this->includeSiteNameInTitle and \Craft::$app->isMultiSite) {
            $title .= ' in site {site}';
            $params['site'] = $this->elementSiteName;
        }
        return \Craft::t('activity', $title, $params);
    }

    /**
     * @inheritDoc
     */
    public function getDescription(): string
    {
        return \Craft::$app->view->renderTemplate('activity/descriptions/element', [
            'log' => $this
        ]);
    }

    /**
     * Element setter
     * 
     * @param Element $element
     */
    public function setElement(Element $element)
    {
        $this->_element = $element;
    }

    /**
     * @inheritDoc
     */
    public function getElement(): ?Element
    {
        if ($this->_element === null and $this->target_id) {
            $this->_element = $this->loadElement();
        }
        return $this->_element;
    }

    /**
     * Get element title
     * 
     * @return string
     */
    public function getElementTitle(): string
    {
        $title = $this->target_name;
        if ($this->element) {
            $status = '<span class="status ' . $this->element->status . '"></span>';
            $title = Html::a($status . $this->element->{$this->titleField} . '</span>', $this->element->cpEditUrl, ['target' => '_blank']);
        }
        return $title;
    }

    /**
     * Get the site name the element was modified in
     * 
     * @return string
     */
    public function getElementSiteName(): string
    {
        $name = $this->data['site_name'];
        if ($site = \Craft::$app->sites->getSiteById($this->data['site_id'])) {
            $name = $site->name;
        }
        return $name;
    }

    /**
     * Load the element
     * 
     * @return ?Element
     */
    protected function loadElement(): ?Element
    {
        $site = \Craft::$app->sites->getSiteById($this->data['site_id']);
        return $this->getElementType()::find()->site($site)->id($this->target_id)->anyStatus()->one();
    }

    /**
     * Include the site name in the title
     * 
     * @return bool
     */
    protected function getIncludeSiteNameInTitle(): bool
    {
        return true;
    }

    /**
     * Get element title field name
     * 
     * @return string
     */
    protected function getTitleField(): string
    {
        return 'title';
    }

    /**
     * Get the element type (Asset, Entry etc)
     * 
     * @return string
     */
    abstract protected function getElementType(): string;
}