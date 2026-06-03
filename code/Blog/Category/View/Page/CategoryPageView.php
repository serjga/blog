<?php
namespace Blog\Category\View\Page;

use App\Request\Url;
use App\View\BlockView;
use App\Request\RequestFactory;
use Blog\Category\Registry;
use Blog\Base\View\Page\LayoutView;
use Blog\Base\Registry as BaseRegistry;
use Blog\Base\View\Widget\ArchiveWidgetView;
use Blog\Base\View\Widget\TagFilterWidgetView;
use Blog\Base\View\Widget\CategoryMenuWidgetView;
use Blog\Base\View\Widget\ArticleSearchWidgetView;
use Blog\Base\View\Widget\ArticleListingSortWidgetView;
use Blog\Category\View\Block\CategoryArticlesListingView;

class CategoryPageView extends BlockView
{
    protected string $_template = 'pages/page__category';

    public function render(bool $return = false): ?string
    {
        $this->_template = (new Registry())->getTemplatePath($this->_template);

        $request = (new RequestFactory())->create();
        $this->addTemplateVariable('get', $request->get());
        $this->registerObject('url', new Url());
        $this->registerObject('baseRegistry', new BaseRegistry());

        // Article Listing
        $categoryListingView = new CategoryArticlesListingView($this->_inputData, $this);
        $categoryListing = $categoryListingView->render(true);
        $this->addTemplateVariable('block__category_articles_listing', $categoryListing);

        $currentCategory = $this->getInputData('currentCategory');
        $categoryId = null;
        if ($currentCategory) {
            $categoryId = $currentCategory->category_id;
            $this->addTemplateVariable('current_category_name', $currentCategory->name)
                ->addTemplateVariable('current_category_description', $currentCategory->description);
        }

        // Sidebar Search
        $articleSearchWidgetView = new ArticleSearchWidgetView($this->_inputData, $this);
        $articleSearchWidget = $articleSearchWidgetView->render(true);
        $this->addTemplateVariable('widget__articles_search', $articleSearchWidget);

        // Sidebar Category Menu
        $categoryMenuWidgetView = new CategoryMenuWidgetView($this->_inputData, $this);
        $categoryMenuWidget = $categoryMenuWidgetView
            ->cacheOn()
            ->setCacheId(\Blog\Category\DataProvider\CategoryDataProvider::CACHE_ID)
            ->render(true);
        $this->addTemplateVariable('widget__category_menu', $categoryMenuWidget);

        // Sidebar Listing Sort
        $articleListingSortWidgetView = new ArticleListingSortWidgetView($this->_inputData, $this);
        $articleListingSortWidget = $articleListingSortWidgetView->render(true);
        $this->addTemplateVariable('widget__articles_listing_sort', $articleListingSortWidget);

        // Sidebar Tag Filter
        $tagFilterWidgetView = new TagFilterWidgetView($this->_inputData, $this);
        $tagFilterWidget = $tagFilterWidgetView
            ->cacheOn()
            ->setCacheId(\Blog\Category\DataProvider\CategoryDataProvider::CACHE_ID . '_' . $categoryId)
            ->render(true);
        $this->addTemplateVariable('widget__tags_filter', $tagFilterWidget);

        // Sidebar Archive Filter
        $archiveWidgetView = new ArchiveWidgetView($this->_inputData, $this);
        $archiveWidget = $archiveWidgetView
            ->cacheOn()
            ->setCacheId(\Blog\Category\DataProvider\CategoryDataProvider::CACHE_ID . '_' . $categoryId)
            ->render(true);
        $this->addTemplateVariable('widget__archive_filter', $archiveWidget);

        $layoutView = new LayoutView($this->_inputData, $this);
        $this->extends($layoutView, 'block_page_body_content');

        return parent::render($return);
    }
}
