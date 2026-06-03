<?php
namespace Blog\Base\View\Page;

use App\Request\RequestFactory;
use App\Request\Url;
use App\View\BlockView;
use Blog\Base\Registry;
use Blog\Base\Registry as BaseRegistry;
use Blog\Base\View\Block\BodyNotFoundPageView;

class NotFoundPageView extends BlockView
{
    protected string $_template = 'pages/page__not_found';

    public function render(bool $return = false): ?string
    {
        $this->_template = (new Registry())->getTemplatePath($this->_template);

        $request = (new RequestFactory())->create();
        $this->addTemplateVariable('get', $request->get());
        $this->registerObject('url', new Url());
        $this->registerObject('baseRegistry', new BaseRegistry());

        $layoutView = new LayoutView($this->_inputData, $this);
        $this->extends($layoutView, 'block_page_body_content');

        $notFoundBodyView = new BodyNotFoundPageView($this->_inputData, $this);
        $notFoundBodyViewViewContent = $notFoundBodyView->render(true);
        $this->addTemplateVariable('block__404', $notFoundBodyViewViewContent);

        return parent::render($return);
    }
}
