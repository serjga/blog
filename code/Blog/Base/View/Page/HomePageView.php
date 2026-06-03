<?php

namespace Blog\Base\View\Page;

use App\Request\RequestFactory;
use App\Request\Url;
use App\View\BlockView;
use Blog\Base\Registry;
use Blog\Base\Registry as BaseRegistry;
use Blog\Base\View\Block\HomeAllCategoriesSectionView;
use Blog\Base\View\Block\HomeHeroSectionView;
use Blog\Base\View\Block\HomePopularArticlesSectionView;

class HomePageView extends BlockView
{
    protected string $_template = 'pages/page__home';

    public function render(bool $return = false): ?string
    {
        $this->_template = (new Registry())->getTemplatePath($this->_template);

        $request = (new RequestFactory())->create();
        $this->addTemplateVariable('get', $request->get());
        $this->registerObject('url', new Url());
        $this->registerObject('baseRegistry', new BaseRegistry());

        $layoutView = new LayoutView($this->_inputData, $this);
        $this->extends($layoutView, 'block_page_body_content');

        $heroSectionView = new HomeHeroSectionView($this->_inputData, $this);
        $heroSection = $heroSectionView->render(true);
        $this->addTemplateVariable('block__hero_section', $heroSection);

        $popularArticlesSectionView = new HomePopularArticlesSectionView($this->_inputData, $this);
        $popularArticlesSection = $popularArticlesSectionView->render(true);
        $this->addTemplateVariable('block__popular_articles_section', $popularArticlesSection);

        $allCategoriesSectionView = new HomeAllCategoriesSectionView($this->_inputData, $this);
        $allCategoriesSection = $allCategoriesSectionView
            ->cacheOn()
            ->setCacheId(\Blog\Category\DataProvider\CategoryDataProvider::CACHE_ID)->render(true);
        $this->addTemplateVariable('block__home_all_categories_section', $allCategoriesSection);

        return parent::render($return);
    }
}
