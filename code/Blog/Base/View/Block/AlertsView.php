<?php
namespace Blog\Base\View\Block;

use App\Alert\AlertTrait;
use App\View\BlockView;
use Blog\Base\Registry;

class AlertsView extends BlockView
{
    use AlertTrait;

    protected string $_template = 'blocks/block__alerts';

    public function render(bool $return = false): ?string
    {
        $this->_template = (new Registry())->getTemplatePath($this->_template);
        $messages = json_encode($this->getAlertMessages());
        $this->addTemplateVariable('alert_messages', $messages);
        return parent::render($return);
    }
}
