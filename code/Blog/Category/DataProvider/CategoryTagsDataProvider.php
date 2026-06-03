<?php

namespace Blog\Article\DataProvider;

use App\DataProvider\DataProvider;
use Blog\Tag\Resource\TagResource;

class CategoryTagsDataProvider extends DataProvider
{
    protected static ?CategoryTagsDataProvider $_instance = null;

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

    public function getCategoryTags(int $categoryId): array
    {
        $categoryTags = $this->getData('categoryTags') ?? [];
        if (!isset($articleTags[$categoryId])) {
            $loadedTags = $this->_loadCategoryTags($categoryId);
            $tagMap = $this->_getPreparedTagsData($loadedTags);
            $categoryTags[$categoryId] = $tagMap;
            $this->setData('categoryTags', $categoryTags);
        }
        return $categoryTags[$categoryId];
    }

    protected function _loadCategoryTags(int $categoryId): array
    {
        $tagResource = new TagResource();
        $tagResource->selectTags()->filterByCategory($categoryId);
        return $tagResource->query()->all();
    }

    protected function _getPreparedTagsData(array $tags): array
    {
        $tagMap = [];
        foreach ($tags as $tag) {
            $tagData = [
                'id' => $tag->tag_id,
                'code' => $tag->code,
                'label' => $tag->label,
            ];
            $tagMap[$tag->tag_id] = $tagData;
        }

        return $tagMap;
    }
}
