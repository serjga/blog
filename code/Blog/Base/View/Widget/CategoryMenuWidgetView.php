<?php
namespace Blog\Base\View\Widget;

use App\View\BlockView;
use Blog\Base\Registry;
use Blog\Category\DataProvider\CategoryDataProvider;

class CategoryMenuWidgetView extends BlockView
{
    protected string $_template = 'widgets/widget__category_menu';

    public function render(bool $return = false): ?string
    {
        $this->_template = (new Registry())->getTemplatePath($this->_template);

        if (!$this->hasCache()) {
            $categoryList = (new CategoryDataProvider())->getCategoryList();
            $menuItems = [];
            foreach ($categoryList as $category) {
                $itemData = [
                    'category_id' => $category->category_id,
                    'category_name' => $category->name,
                    'article_count' => $category->articleCount
                ];

                $menuItemView = new CategoryMenuItemWidgetView($this->_inputData, $this);
                $menuItemView->addTemplateVariable('category_data', $itemData)
                    ->cacheOn()
                    ->setCacheId(\Blog\Category\DataProvider\CategoryDataProvider::CACHE_ID . '_' . $category->category_id);
                $menuItems[] = $menuItemView->render(true);;
            }

            $this->addTemplateVariable('sidebar_category_menu_widget', $menuItems);
        }

        return parent::render($return);
    }
}
