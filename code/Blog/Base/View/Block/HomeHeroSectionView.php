<?php
namespace Blog\Base\View\Block;

use App\View\BlockView;
use Blog\Base\Registry;
use Blog\Category\DataProvider\CategoryDataProviderFactory;

class HomeHeroSectionView extends BlockView
{
    protected string $_template = 'blocks/block__hero_section';

    public function render(bool $return = false): ?string
    {
        $this->_template = (new Registry())->getTemplatePath($this->_template);

        $categories = $this->_getTopPopularCategories();
        $this->addTemplateVariable('categories', $categories);

        return parent::render($return);
    }

    protected function _getTopPopularCategories(): array
    {
        $categoryData = (new CategoryDataProviderFactory())->create();
        $categories = $categoryData->getCategoriesWithChildArticlesViews();
        $popularCategories = [];
        $limit = 4;
        $topCategories = array_slice($categories, 0, $limit, true);

        foreach ($topCategories as $category) {
            $categoryData = [
                'id' => $category->getData('category_id'),
                'name' => $category->getData('name'),
                'icon' => $category->getData('icon'),
            ];

            $popularCategories[] = $categoryData;
        }

        return $popularCategories;
    }
}
