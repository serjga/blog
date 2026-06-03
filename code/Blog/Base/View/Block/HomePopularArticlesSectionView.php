<?php
namespace Blog\Base\View\Block;

use App\Request\Url;
use App\View\BlockView;
use App\DataProvider\DataProviderFactory;
use Blog\Base\Registry;
use Blog\Article\Resource\ArticleResource;
use Blog\Article\View\Block\SingleArticleCardView;
use Blog\Category\DataProvider\CategoryDataProviderFactory;

class HomePopularArticlesSectionView extends BlockView
{
    protected string $_template = 'blocks/block__popular_articles_section';
    protected Url $_url;
    private CategoryDataProviderFactory $_categoryDataProviderFactory;
    private DataProviderFactory $_dataProviderFactory;

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
        $this->_template = (new Registry())->getTemplatePath($this->_template);

        $topPopularArticle = [];
        $articles = $this->_getTopPopularArticlesData();
        foreach ($articles as $article) {
            $singleArticleCardView = new SingleArticleCardView($this->_inputData, $this);
            $singleArticleCardView->setArticle($article);
            $topPopularArticle[] = $singleArticleCardView->render(true);
        }

        $this->addTemplateVariable('top_popular_article_cards', $topPopularArticle);

        return parent::render($return);
    }

    protected function _getTopPopularArticlesData(): array
    {
        $articleResource = new ArticleResource();
        $articles = $articleResource->selectArticles()
            ->sort('article.views', 'DESC')
            ->withMainImage()
            ->withCategories()
            ->page(1, 3)
            ->query()
            ->all();

        if (!$articles) {
            return [];
        }

        $articleList = [];
        $categoryDataProvider = $this->_categoryDataProviderFactory->create();
        $categoryMap = $categoryDataProvider->getCategoryMap();
        foreach ($articles as $article) {
            $articleData = [
                'id' => $article->article_id,
                'title' => $article->title,
                'description' => $article->description,
                'created_at' => $article->created_at,
                'article_url' => $this->_url->getUrl(['path' => '/article', 'id' => $article->article_id]),
                'image' => $article->main_image_path ? $this->_url->getImageUrl(['path' => $article->main_image_path]) : null
            ];

            $categoryIds = explode(',', $article->category_ids);

            foreach ($categoryIds as $categoryId) {
                if (isset($categoryMap[$categoryId])) {
                    $articleData['categories'][$categoryId] = $categoryMap[$categoryId]->getData('name');
                }
            }
            $articleList[] = $this->_dataProviderFactory->create($articleData);
        }

        return $articleList;
    }
}
