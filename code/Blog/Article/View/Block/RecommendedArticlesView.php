<?php
namespace Blog\Article\View\Block;

use App\Request\Url;
use App\View\BlockView;
use App\DataProvider\DataProviderFactory;
use Blog\Article\Registry;
use Blog\Article\DataProvider\ArticleDataProviderFactory;
use Blog\Category\DataProvider\CategoryDataProviderFactory;

class RecommendedArticlesView extends BlockView
{
    protected string $_template = 'blocks/block__recommended_articles';
    protected Url $_url;
    protected DataProviderFactory $_dataProviderFactory;
    protected CategoryDataProviderFactory $_categoryDataProviderFactory;

    function __construct (
        \App\DataProvider\DataProviderInterface $inputDataProvider,
        ?\App\View\ViewInterface $context
    ) {
        $this->_url = new Url();
        $this->_dataProviderFactory = new DataProviderFactory();
        $this->_categoryDataProviderFactory = new CategoryDataProviderFactory();
        parent::__construct($inputDataProvider, $context);
    }

    public function render(bool $return = false): ?string
    {
        $articleId = $this->getInputData('article_id');
        $this->_template = (new Registry())->getTemplatePath($this->_template);

        if (!$this->hasCache()) {
            $articleDataProvider = (new ArticleDataProviderFactory())->create();
            $recommendedArticles = $articleDataProvider->getRecommendedArticles($articleId);
            if ($recommendedArticles) {
                $categoryData = $this->_categoryDataProviderFactory->create();
                $categoryMap = $categoryData->getCategoryMap();
                $recommendedArticleCards = [];
                foreach ($recommendedArticles as $recommendedArticle) {
                    $articleCategories = [];
                    foreach ($recommendedArticle->getData('category_ids') as $categoryId) {
                        $articleCategories[$categoryId] = $categoryMap[$categoryId]->getData('name');
                    }

                    $articleId = $recommendedArticle->getData('article_id');
                    $articleData = [
                        'id' => $articleId,
                        'title' => $recommendedArticle->getData('title'),
                        'views' => $recommendedArticle->getData('views'),
                        'description' => $recommendedArticle->getData('description'),
                        'created_at' => $recommendedArticle->getData('created_at'),
                        'categories' => $articleCategories,
                        'article_url' => $this->_url->getUrl(['path' => '/article', 'id' => $articleId]),
                        'image' => $recommendedArticle->getData('image') ? $this->_url->getImageUrl(['path' => $recommendedArticle->getData('image')]) : null
                    ];

                    $article = $this->_dataProviderFactory->create($articleData);

                    $articleCardView = new SingleArticleCardView($this->_inputData, $this);

                    $articleCard = $articleCardView
                        ->setArticle($article)
                        ->cacheOn()
                        ->setCacheId(\Blog\Article\DataProvider\ArticleDataProvider::CACHE_ID . '_' . $articleId)
                        ->render(true);

                    $recommendedArticleCards[] = $articleCard;
                }
                $this->addTemplateVariable('recommended_article_cards', $recommendedArticleCards);                
            }
        }
        return parent::render($return);
    }
}
