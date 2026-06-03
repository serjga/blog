<?php
namespace Blog\Base\View\Block;

use App\View\BlockView;
use App\DataProvider\DataProviderInterface;
use Blog\Article\View\Block\SingleArticleCardView;
use Blog\Base\Registry;

class HomeCategorySectionView extends BlockView
{
    protected string $_template = 'blocks/block__home_category_section';

    public function render(bool $return = false): ?string
    {
        $this->_template = (new Registry())->getTemplatePath($this->_template);

        $category = $this->getCategory();
        if ($category) {
            $this->addTemplateVariable('id', $category->getData('category_id'))
                ->addTemplateVariable('name', $category->getData('name'))
                ->addTemplateVariable('icon', $category->getData('icon'))
                ->addTemplateVariable('main_color', $category->getData('main_color'))
                ->addTemplateVariable('secondary_color', $category->getData('secondary_color'))
                ->addTemplateVariable('category_url', $category->getData('category_url'));

            $articles = $category->getData('articles');
            $categoryArticleCards = [];
            if (is_array($articles)) {
                foreach ($articles as $article) {
                    $singleArticleCardView = new SingleArticleCardView($this->_inputData, $this);
                    $singleArticleCardView->setArticle($article)
                        ->cacheOn()
                        ->setCacheId(\Blog\Article\DataProvider\ArticleDataProvider::CACHE_ID . '_' . $article->getData('id'));
                    $categoryArticleCards[] = $singleArticleCardView->render(true);
                }
            }

            $this->addTemplateVariable('category_article_cards', $categoryArticleCards);
        }

        return parent::render($return);
    }

    public function setCategory(DataProviderInterface $category): self
    {
        $this->setData('category', $category);
        return $this;
    }

    public function getCategory(): ?DataProviderInterface
    {
        return $this->getData('category');
    }
}
