<?php
namespace Blog\Base\View\Widget;

use App\View\BlockView;
use Blog\Base\Registry;

class CategoryMenuItemWidgetView extends BlockView
{
    protected string $_template = 'widgets/widget__category_menu_item';

    public function render(bool $return = false): ?string
    {
        $this->_template = (new Registry())->getTemplatePath($this->_template);
        if (!$this->hasCache()) {
            $this->addTemplateVariable('article_count', (int) $this->getData('article_count'));
            $this->addTemplateVariable('category_name', (string) $this->getData('category_name'));
            $this->addTemplateVariable('category_id', (int) $this->getData('category_id'));
        }

        return parent::render($return);
    }
}
