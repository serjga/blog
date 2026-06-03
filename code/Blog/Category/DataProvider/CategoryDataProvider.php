<?php

namespace Blog\Category\DataProvider;

use App\DataProvider\DataProvider;
use Blog\Category\Resource\CategoryResource;

class CategoryDataProvider extends DataProvider
{
    const string CACHE_ID = 'CAT_CID';

    protected static ?CategoryDataProvider $_instance = null;

    public static function getInstance(): self
    {
        if (self::$_instance === null) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    private function __clone() {}

    public function __wakeup() {
        throw new \Exception("Cannot unserialize singleton");
    }

    public function getData(?string $name = null): mixed
    {
        if ($name === 'categoryList') {
            return $this->getCategoryList();
        } else {
            return parent::getData($name);
        }
    }

    public function getCategoryList(): array
    {
        if (!$this->isset('categoryList')) {
            $categories = $this->_loadCategories();
            $categoriesData = $this->_prepareCategoriesData($categories);
            $this->categoryList = $categoriesData['category_list'];
            $this->categoryMap = $categoriesData['category_map'];
        }
        return $this->categoryList;
    }

    public function getCategoryMap(): array
    {
        if (!$this->isset('categoryMap')) {
            $categories = $this->_loadCategories();
            $categoriesData = $this->_prepareCategoriesData($categories);
            $this->categoryList = $categoriesData['category_list'];
            $this->categoryMap = $categoriesData['category_map'];
        }
        return $this->categoryMap;
    }

    protected function _loadCategories(): array
    {
        $categoryResource = new CategoryResource();
        return $categoryResource->selectCategories()
            ->hasArticles()
            ->sort()
            ->query()
            ->all();
    }

    protected function _prepareCategoriesData(array $categories): array
    {
        $categoryList = [];
        $categoryMap = [];
        foreach ($categories as $category) {

            $details = !empty($category->details) ? json_decode($category->details, true) : [];
            $detailsData = new DataProvider($details);

            $categoryData = [
                'category_id' => $category->category_id,
                'name' => $category->name,
                'description' => $category->description,
                'icon' => $detailsData->getData('icon'),
                'main_color' => $detailsData->getData('main_color'),
                'secondary_color' => $detailsData->getData('secondary_color'),
            ];

            if (!is_null($category->article_count ?? null)) {
                $categoryData['articleCount'] = $category->article_count;
            }

            if (!is_null($category->article_ids ?? null)) {
                $categoryData['article_ids'] = $category->article_ids;
            }

            if (!is_null($category->total_views ?? null)) {
                $categoryData['total_views'] = $category->total_views;
            }

            $categoryDataProvider = new DataProvider($categoryData);
            $categoryList[] = $categoryDataProvider;
            $categoryMap[$category->category_id] = $categoryDataProvider;
        }
        return [
            'category_list' => $categoryList,
            'category_map' => $categoryMap
        ];
    }

    public function getCategoriesWithRelatedArticleData(): array
    {
        if (!$this->isset('categoriesWithRelatedArticleData')) {
            $categories = $this->_loadCategoriesWithArticleData();
            $categoriesData = $this->_prepareCategoriesData($categories);
            $this->categoriesWithRelatedArticleData = $categoriesData['category_list'];
            $this->categoryList = $categoriesData['category_list'];
            $this->categoryMap = $categoriesData['category_map'];
        }
        return $this->categoriesWithRelatedArticleData;
    }

    protected function _loadCategoriesWithArticleData(): array
    {
        $categoryResource = new CategoryResource();
        return $categoryResource->selectCategories()
            ->withArticleIds()
            ->hasArticles()
            ->sort()
            ->query()
            ->all();
    }

    public function getCategoriesWithChildArticlesViews(): array
    {
        if (!$this->isset('categoriesWithChildArticlesViews')) {
            $categories = $this->_loadCategoriesWithChildArticlesViews();
            $categoriesData = $this->_prepareCategoriesData($categories);
            $this->categoriesWithChildArticlesViews = $categoriesData['category_list'];
            $this->categoryList = $categoriesData['category_list'];
            $this->categoryMap = $categoriesData['category_map'];
        }
        return $this->categoriesWithChildArticlesViews;
    }

    protected function _loadCategoriesWithChildArticlesViews(): array
    {
        $categoryResource = new CategoryResource();
        return $categoryResource->selectCategories()
            ->withArticlesViewsCount()
            ->hasArticles()
            ->query()
            ->all();
    }
}
