<?php
namespace Blog\Base\View\Widget;

use App\View\BlockView;
use Blog\Base\Registry;

class ArticleListingSortWidgetView extends BlockView
{
    protected string $_template = 'widgets/widget__articles_listing_sort';

    public function render(bool $return = false): ?string
    {
        $this->_template = (new Registry())->getTemplatePath($this->_template);

        $sort = $this->getInputData('sort');
        $order = $this->getInputData('order');

        $sort = $sort ?? 'date';
        $order = $order ?? 'desc';
        $selected_sort = $sort ? $sort . '_' . $order : null;

        $options = $this->_getSortOptions();
        $this->addTemplateVariable('options', $options)
            ->addTemplateVariable('selected_sort', $selected_sort);

        return parent::render($return);
    }

    protected function _getSortOptions(): array
    {
        $options = [
            'date' => ['desc' => 'From Most Recent', 'asc' => 'To Most Recent'],
            'popular' => ['desc' => 'From Most Popular', 'asc' => 'To Most Popular'],
        ];

        $widgetOptions = [];
        foreach ($options as $sortKey => $dirOption) {
            foreach ($dirOption as $orderKey => $option) {
                $widgetOptions[$sortKey . "_" . $orderKey] = $option;
            }
        }

        return $widgetOptions;
    }
}
