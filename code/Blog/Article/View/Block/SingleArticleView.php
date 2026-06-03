<?php
namespace Blog\Article\View\Block;

use App\View\BlockView;
use Blog\Article\Registry;
use Blog\Article\DataProvider\ArticleDataProviderFactory;
use Blog\Base\View\Block\BodyNotFoundPageView;
use Blog\Category\DataProvider\CategoryDataProviderFactory;
use Blog\Article\DataProvider\ArticleTagsDataProviderFactory;

class SingleArticleView extends BlockView
{
    protected string $_template = 'blocks/block__single_article';

    public function render(bool $return = false): ?string
    {
        $this->_template = (new Registry())->getTemplatePath($this->_template);

        $articleId = $this->getInputData('article_id');

        if (!$this->hasCache()) {
            $articleDataProvider = (new ArticleDataProviderFactory())->create();
            $articleData = $articleDataProvider->getArticle($articleId);
            if (!$articleData) {
                // Page Not Found
                $block404 = new BodyNotFoundPageView($this->_inputData, $this);
                return $block404->render(true);
            } else {
                if ($articleData->getData('category_ids')) {
                    $this->_prepareArticleCategories($articleData);
                }

                if ($articleData->getData('tags')) {
                    $this->_prepareArticleTags($articleId);
                }

                $this->addTemplateVariable('id', $articleId)
                    ->addTemplateVariable('image', $articleData->getData('image'))
                    ->addTemplateVariable('published_date', $articleData->getData('created_at'))
                    ->addTemplateVariable('title', $articleData->getData('title'))
                    ->addTemplateVariable('content', $articleData->getData('content'))
                    ->addTemplateVariable('views', $articleData->getData('views'));
            }
        }

        return parent::render($return);
    }

    protected function _prepareArticleCategories($articleData): void
    {
        $categoryDataProvider = (new CategoryDataProviderFactory())->create();
        $categoryMap = $categoryDataProvider->getCategoryMap();

        $categories = [];

        foreach ($articleData->category_ids ?? [] as $categoryId) {
            if (isset($categoryMap[$categoryId])) {
                $categories[$categoryId] = $categoryMap[$categoryId]->getData('name');
            }
        }
        $this->addTemplateVariable('article_categories', $categories);
    }

    protected function _prepareArticleTags(int $articleId): void
    {
        $tagData = (new ArticleTagsDataProviderFactory())->create();
        $tagMap = $tagData->getArticleTags($articleId);
        $articleTags = [];
        foreach ($tagMap as $tagData) {
            $articleTags[$tagData['code']] = [
                'label' => $tagData['label'],
                'code' => $tagData['code']
            ];
        }
        $this->addTemplateVariable('tags', $articleTags);
    }
}
