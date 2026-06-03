<?php
namespace Blog\Base\View\Widget;

use App\View\BlockView;
use Blog\Base\Registry;
use Blog\Tag\Resource\TagResource;

class TagFilterWidgetView extends BlockView
{
    protected string $_template = 'widgets/widget__tags_filter';

    public function render(bool $return = false): ?string
    {
        $this->_template = (new Registry())->getTemplatePath($this->_template);
        $queryParams = $this->getInputData('queryParams');
        $categoryId = $queryParams ? $queryParams->getData('categoryId') : null;

        if (!$this->hasCache()) {
            $tagOptions = $this->_getTagOptions($categoryId);
            $this->addTemplateVariable('options', $tagOptions);
        }

        return parent::render($return);
    }

    protected function _getTagOptions(?int $categoryId = null): array
    {
        $tagResource = new TagResource();
        $tagResource->selectTags();

        if ($categoryId) {
            $tagResource->filterByCategory($categoryId);
        }

        $tags = $tagResource->page(1, 100)->query()->all();

        $tagList = ['' => 'Select Tag'];
        foreach ($tags as $tag) {
            $tagList[$tag->code] = $tag->label;
        }

        return $tagList;
    }
}
