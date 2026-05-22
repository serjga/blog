{extends file="../layout.tpl"}
{block name=body}

    {include file='./hero_section.tpl' categories=$popularCategories}

    {include file='./popular_articles_section.tpl' articles=$popularArticles}

    {include file='./all_category_section.tpl' categories=$categories}

{/block}
