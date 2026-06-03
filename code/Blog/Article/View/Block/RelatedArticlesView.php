<?php
namespace Blog\Article\View\Block;

use App\View\BlockView;
use Blog\Article\DataProvider\ArticleDataProviderFactory;
use Blog\Article\Registry;

class RelatedArticlesView extends BlockView
{
    protected string $_template = 'blocks/block__related_articles';

    public function render(bool $return = false): ?string
    {
        $articleId = $this->getInputData('article_id');
        $this->_template = (new Registry())->getTemplatePath($this->_template);
        if ($articleId && !$this->hasCache()) {
            $articleDataProvider = (new ArticleDataProviderFactory())->create();

            $relatedArticles = $articleDataProvider->getRelatedArticles($articleId);

            $relatedArticleCards = [];
            $relatedArticles = $this->_getPreparedRelatedArticles($relatedArticles);
            foreach ($relatedArticles as $relatedArticle) {
                $articleSmallCardView = new SingleArticleSmallCardView($this->_inputData, $this);
                $articleId = $relatedArticle->getData('article_id');
                $relatedArticleCard = $articleSmallCardView
                    ->setData('article_id', $articleId)
                    ->setData('image', $relatedArticle->getData('image'))
                    ->setData('title', $relatedArticle->getData('title'))
                    ->cacheOn()
                    ->setCacheId(\Blog\Article\DataProvider\ArticleDataProvider::CACHE_ID . '_' . $articleId)
                    ->render(true);

                $relatedArticleCards[] = $relatedArticleCard;
            }
            $this->addTemplateVariable('related_article_cards', $relatedArticleCards);
        }

        return parent::render($return);
    }

    protected function _getPreparedRelatedArticles(array $categories): array
    {
        $result = [];
        if ($categories) {
            $limit = min(count($categories), 3);
            $randomKeys = array_rand($categories, $limit);
            if ($limit < 2) {
                $randomKeys = [$randomKeys];
            }
            $result = array_intersect_key($categories, $randomKeys);
        }

        return $result;
    }
}
