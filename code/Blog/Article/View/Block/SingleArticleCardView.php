<?php

namespace Blog\Article\View\Block;

use App\View\BlockView;
use App\DataProvider\DataProviderInterface;
use Blog\Article\Registry;

class SingleArticleCardView extends BlockView
{
    protected string $_template = 'blocks/block__single_article_card';

    public function render(bool $return = false): ?string
    {
        $this->_template = (new Registry())->getTemplatePath($this->_template);
        $article = $this->getArticle();
        if (!$this->hasCache() && $article) {
            $this->addTemplateVariable('id', $article->getData('id'))
                ->addTemplateVariable('image', $article->getData('image'))
                ->addTemplateVariable('title', $article->getData('title'))
                ->addTemplateVariable('article_url', $article->getData('article_url'))
                ->addTemplateVariable('published_date', $article->getData('created_at'))
                ->addTemplateVariable('description', $article->getData('description'))
                ->addTemplateVariable('views', $article->getData('views'))
                ->addTemplateVariable('categories', $article->getData('categories'));
        }

        return parent::render($return);
    }

    public function setArticle(DataProviderInterface $article): self
    {
        $this->setData('article', $article);
        return $this;
    }

    public function getArticle(): ?DataProviderInterface
    {
        return $this->getData('article');
    }
}
