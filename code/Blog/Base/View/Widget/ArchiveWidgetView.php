<?php
namespace Blog\Base\View\Widget;

use App\View\BlockView;
use Blog\Article\Resource\ArticleResource;
use Blog\Base\Registry;

class ArchiveWidgetView extends BlockView
{
    protected string $_template = 'widgets/widget__archive_filter';

    public function render(bool $return = false): ?string
    {
        $queryParams = $this->getInputData('queryParams');
        $categoryId = $queryParams ? $queryParams->getData('categoryId') : null;

        $this->_template = (new Registry())->getTemplatePath($this->_template);

        if (!$this->hasCache()) {
            $widgetOptions = $this->_archiveWidgetOptions($categoryId);
            $this->addTemplateVariable('options', $widgetOptions);
        }

        return parent::render($return);
    }

    protected function _archiveWidgetOptions(?int $categoryId = null): array
    {
        $articleResource = new ArticleResource;
        $articleResource->selectArticlePublishYears();

        if ($categoryId) {
            $articleResource->filterByCategory($categoryId);
        }

        $years = $articleResource
            ->query()
            ->columnValues();

        $options = [];
        foreach ($years as $year) {
            $options[$year] = $year;
        }

        return $options;
    }
}
