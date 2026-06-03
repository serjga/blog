<?php

namespace Blog\Base\View\Block;

use App\View\BlockView;
use Blog\Base\Registry;
use Blog\Category\DataProvider\CategoryDataProviderFactory;

class FooterView extends BlockView
{
    protected string $_template = 'blocks/block__footer_section';

    public function render(bool $return = false, bool $cache = true): ?string
    {
        $this->_template = (new Registry())->getTemplatePath($this->_template);
        $this->cacheOn()->setCacheId(\Blog\Category\DataProvider\CategoryDataProvider::CACHE_ID);

        if (!$this->hasCache()) {
            $categoryData = (new CategoryDataProviderFactory())->create();
            $categoryList = $categoryData->getData('categoryList');
            $categories = [];
            foreach ($categoryList as $category) {
                $categories[] = [
                    'id' => $category->getData('category_id'),
                    'name' => $category->getData('name'),
                ];
            }

            $this->addTemplateVariable('category_list', $categories);
        }

        return parent::render($return);
    }
}
