<?php

namespace Blog\Base\View\Block;

use App\View\BlockView;
use Blog\Base\Registry;

class HeaderView extends BlockView
{
    protected string $_template = 'blocks/block__header';

    public function render(bool $return = false): ?string
    {
        $this->_template = (new Registry())->getTemplatePath($this->_template);
        $categoryDropdownMenuView = new CategoryDropdownMenuView($this->_inputData, $this);
        $categoryDropdownMenu = $categoryDropdownMenuView->render(true);
        $this->addTemplateVariable('block__category_dropdown_menu', $categoryDropdownMenu);

        return parent::render($return);
    }
}
