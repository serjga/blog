<?php

namespace Blog\Article\DataProvider;

use App\DataProvider\DataProvider;
use Blog\Tag\Resource\TagResource;

class ArticleTagsDataProvider extends DataProvider
{
    protected static ?ArticleTagsDataProvider $_instance = null;

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

    public function getArticleTags(int $articleId): array
    {
        $articleTags = $this->getData('articleTags') ?? [];
        if (!isset($articleTags[$articleId])) {
            $loadedTags = $this->_loadArticleTags($articleId);
            $tagMap = $this->_getPrepareTagsData($loadedTags);
            $articleTags[$articleId] = $tagMap;
            $this->setData('articleTags', $articleTags);
        }
        return $articleTags[$articleId];
    }

    protected function _loadArticleTags(int $articleId): array
    {
        $tagResource = new TagResource();
        $tagResource->selectTags()->filterByArticle($articleId);
        return $tagResource->query()->all();
    }

    protected function _getPrepareTagsData(array $tags): array
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
