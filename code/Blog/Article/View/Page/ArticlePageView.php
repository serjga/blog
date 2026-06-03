<?php
namespace Blog\Article\View\Page;

use App\Request\RequestFactory;
use App\Request\Url;
use App\View\BlockView;
use Blog\Article\Registry;
use Blog\Article\View\Block\RecommendedArticlesView;
use Blog\Article\View\Block\RelatedArticlesView;
use Blog\Article\View\Block\SingleArticleView;
use Blog\Base\Registry as BaseRegistry;
use Blog\Base\View\Page\LayoutView;
use Blog\Base\View\Widget\CategoryMenuWidgetView;
use Blog\Category\DataProvider\CategoryDataProviderFactory;

class ArticlePageView extends BlockView
{
    protected string $_template = 'pages/page__article';

    public function render(bool $return = false): ?string
    {
        $articleRegistry = new Registry();
        $this->_template = ($articleRegistry)->getTemplatePath($this->_template);
        $articleId = $this->getInputData('article_id');
        $request = (new RequestFactory())->create();
        $url = new Url();
        $this->addTemplateVariable('get', $request->get());
        $this->registerObject('url', $url);
        $this->registerObject('baseRegistry', new BaseRegistry());
        $this->registerObject('articleRegistry', $articleRegistry);

        $categoryData = (new CategoryDataProviderFactory())->create();
        $categoryMenuWidgetView = new CategoryMenuWidgetView($categoryData, null);
        $categoryMenuWidgetView->cacheOn()->setCacheId(\Blog\Category\DataProvider\CategoryDataProvider::CACHE_ID);
        $categoryMenuWidget = $categoryMenuWidgetView->render(true);
        $this->addTemplateVariable('category_menu_widget', $categoryMenuWidget);

        $recommendedArticlesView = new RecommendedArticlesView($this->_inputData, $this);
        $blockRecommendedArticles = $recommendedArticlesView
            ->cacheOn()
            ->setCacheId(\Blog\Article\DataProvider\ArticleDataProvider::RECOMMENDED_ARTICLES_CACHE_ID . '_' . $articleId)
            ->render(true);
        $this->addTemplateVariable('block__recommended_articles', $blockRecommendedArticles);

        $relatedArticlesView = new RelatedArticlesView($this->_inputData, $this);
        $blockRelatedArticles = $relatedArticlesView
            ->cacheOn()
            ->setCacheId(\Blog\Article\DataProvider\ArticleDataProvider::RELATED_ARTICLES_CACHE_ID . '_' . $articleId)
            ->render(true);
        $this->addTemplateVariable('block__related_articles', $blockRelatedArticles);

        // Single Article Block
        $singleArticleView = new SingleArticleView($this->_inputData, $this);
        $singleArticleView->cacheOn()->setCacheId(\Blog\Article\DataProvider\ArticleDataProvider::CACHE_ID . '_' . $articleId);
        $singleArticle = $singleArticleView->render(true);
        $this->addTemplateVariable('host', $url->getServerUrl());
        $this->addTemplateVariable('block__single_article', $singleArticle);

        $layoutView = new LayoutView($this->_inputData, $this);
        $layoutView->registerObject('url', new Url());
        $this->extends($layoutView, 'block_page_body_content');

        return parent::render($return);
    }
}
