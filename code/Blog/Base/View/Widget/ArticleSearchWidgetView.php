<?php
namespace Blog\Base\View\Widget;

use App\View\BlockView;
use Blog\Base\Registry;

class ArticleSearchWidgetView extends BlockView
{
    protected string $_template = 'widgets/widget__articles_search';

    public function render(bool $return = false): ?string
    {
        $this->_template = (new Registry())->getTemplatePath($this->_template);
        return parent::render($return);
    }
}
