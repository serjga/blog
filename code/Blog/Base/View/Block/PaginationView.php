<?php
namespace Blog\Base\View\Block;

use App\View\BlockView;
use Blog\Base\Registry;

class PaginationView extends BlockView
{
    protected string $_template = 'blocks/block__pagination';

    public function render(bool $return = false): ?string
    {
        $this->_template = (new Registry())->getTemplatePath($this->_template);

        $totalPages = $this->getData('totalPages');
        $currentPage = $this->getData('currentPage');

        $this->addTemplateVariable('total_pages', $totalPages)
            ->addTemplateVariable('current_page', $currentPage);

        return parent::render($return);
    }
}
