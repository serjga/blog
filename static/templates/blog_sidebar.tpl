<div class="sidebar">

    {include file='components/blog_articles_search.tpl' search=$search}

    {include file='components/blog_category_list.tpl' list=$categoryList}

    {include file='components/blog_articles_sort.tpl' options=$sortOptions selected=$selectedSort}

    {include file='components/blog_related_article_cards.tpl' articles=$relatedArticles}

    {include file='components/blog_article_tags_filter.tpl' tags=$tagList}

    {include file='components/blog_article_years_filter.tpl' options=$yearOptions}

</div>
