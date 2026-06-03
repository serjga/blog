<?php
namespace Blog\Base\View\Page;

use App\Token\Token;
use App\View\BlockView;
use Blog\Base\Registry;
use Blog\Base\View\Block\AlertsView;
use Blog\Base\View\Block\FooterView;
use Blog\Base\View\Block\HeaderView;

class LayoutView extends BlockView
{
    protected string $_template = 'pages/layout';

    public function render(bool $return = false, bool $cache = true): ?string
    {
        $this->_template = (new Registry())->getTemplatePath($this->_template);
        $this->registerObject('token', new Token());

        $headerView = (new HeaderView($this->_inputData, $this));
        $headerViewContent = $headerView->render(true);
        $this->addTemplateVariable('block__header', $headerViewContent);

        $footerView = (new FooterView($this->_inputData, $this));
        $footerViewContent = $footerView->render(true);
        $this->addTemplateVariable('block__footer_section', $footerViewContent);

        $alertsView = (new AlertsView($this->_inputData, $this));
        $alerts = $alertsView->render(true);
        $this->addTemplateVariable('block__alerts', $alerts);

        return parent::render($return);
    }
}
