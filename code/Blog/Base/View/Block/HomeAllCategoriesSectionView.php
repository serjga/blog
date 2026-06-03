<?php
namespace Blog\Base\View\Block;

use App\Request\Url;
use App\View\BlockView;
use App\DataProvider\DataProviderFactory;
use Blog\Base\Registry;
use Blog\Article\Resource\ArticleResource;
use Blog\Category\DataProvider\CategoryDataProviderFactory;

class HomeAllCategoriesSectionView extends BlockView
{
    protected string $_template = 'blocks/block__home_all_categories_section';
    protected Url $_url;
    protected DataProviderFactory $_dataProviderFactory;

    function __construct (
        \App\DataProvider\DataProviderInterface $inputDataProvider,
        ?\App\View\ViewInterface $context
    ) {
        $this->_url = new Url();
        $this->_dataProviderFactory = new DataProviderFactory();
        $this->_template = (new Registry())->getTemplatePath($this->_template);
        parent::__construct($inputDataProvider, $context);
    }

    public function render(bool $return = false): ?string
    {
        if (!$this->hasCache()) {
            $templateCategories = [];
            $categoriesData = (new CategoryDataProviderFactory())->create();

            $categoriesList = $categoriesData->getCategoriesWithRelatedArticleData();
            $articleToCategoryMap = [];
            $categories = $this->_getPreparedCategoriesData($categoriesList, $articleToCategoryMap);

            $categories = $this->_prepareCategoryArticles($categories, $articleToCategoryMap);

            foreach ($categories as $category) {
                $categorySectionView = new HomeCategorySectionView($this->_inputData, $this);
                $categorySection = $categorySectionView->setCategory($category)
                    ->cacheOn()
                    ->setCacheId(\Blog\Category\DataProvider\CategoryDataProvider::CACHE_ID . '_' . $category->getData('category_id'))
                    ->render(true);
                $templateCategories[] = $categorySection;
            }

            $this->addTemplateVariable('categories', $templateCategories);
        }

        return parent::render($return);
    }

    protected function _getPreparedCategoriesData($categoriesList, & $articleToCategoryMap): array
    {
        $categoriesData = [];
        foreach ($categoriesList as $category) {
            $categoryId = $category->category_id;
            $categoryArticleIds = explode(',', (string) $category->article_ids);
            $categoryData = [
                'category_id' => $categoryId,
                'name' => $category->name,
                'icon' => $category->icon,
                'main_color' => $category->main_color,
                'secondary_color' => $category->secondary_color,
                'category_article_ids' => $categoryArticleIds,
                'category_url' => $this->_url->getUrl(['path' => '/category', 'id' => $categoryId]),
                'article_ids' => [],
                'articles' => []
            ];

            foreach ($categoryArticleIds as $articleId) {
                // display not repeated articles
                if (!isset($articleToCategoryMap[$articleId])) {
                    $articleToCategoryMap[$articleId] = $categoryId;
                    $categoryData['article_ids'][] = $articleId;
                }

                if (count($categoryData['article_ids'] ?? []) === 3) {
                    break;
                }
            }
            $categoriesData[] = $this->_dataProviderFactory->create($categoryData);;
        }
        return $categoriesData;
    }

    protected function _prepareCategoryArticles(array $categories, array $articleToCategoryMap): array
    {
        $articleIds = array_keys($articleToCategoryMap);

        if (!$articleIds) {
            return $categories;
        }

        $articleResource = new ArticleResource();
        $articles = $articleResource->selectArticles()
            ->sort('created_at', 'DESC')
            ->filterArticlesByIds($articleIds)
            ->withMainImage()
            ->filterByMainImage()
            ->query()
            ->all();

        $categoryArticlesMap = [];
        foreach ($articles as $article) {
            $articleId = $article->article_id;
            if (isset($articleToCategoryMap[$articleId])) {

                $articleData = [
                    'id' => $articleId,
                    'title' => $article->title,
                    'description' => $article->description,
                    'views' => $article->views,
                    'created_at' => $article->created_at,
                    'article_url' => $this->_url->getUrl(['path' => '/article', 'id' => $articleId]),
                    'image' => $article->main_image_path ? $this->_url->getImageUrl(['path' => $article->main_image_path]) : null,
                    'categories' => []
                ];

                $categoryArticlesMap[$articleToCategoryMap[$articleId]][] = $this->_dataProviderFactory->create($articleData);
            }
        }

        foreach ($categories as $category) {
            // article correct order
            $categoryId = $category->getData('category_id');
            if (isset($categoryArticlesMap[$categoryId])) {
                $category->setData('articles', $categoryArticlesMap[$categoryId]);
            }
        }
        return $categories;
    }
}
