<?php
namespace Blog\Base\View\Block;

use App\View\BlockView;
use Blog\Base\Registry;

class BodyNotFoundPageView extends BlockView
{
    protected string $_template = 'blocks/block__404';

    public function render(bool $return = false): ?string
    {
        $this->_template = (new Registry())->getTemplatePath($this->_template);
        return parent::render($return);
    }
}
