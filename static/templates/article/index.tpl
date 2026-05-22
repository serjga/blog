{extends file="../layout.tpl"}
{block name=body}

    <!--=================================
    =            Single Blog            =
    ==================================-->

    <section class="blog single-blog section">
        <div class="container">
            <div class="row">
                <div class="col-md-10 offset-md-1 col-lg-9 offset-lg-0">
                    {if is_array($article)}
                        <article class="single-post">
                            <h3>{$article.title}</h3>
                            <ul class="list-inline">
                                <li class="list-inline-item">
                                    <i class="fa fa-calendar"></i>
                                    {$article.date|date_format}
                                </li>
                                <li class="list-inline-item">
                                    <i class="fa fa-eye"></i>
                                    {$article.views}
                                </li>
                            </ul>

                            {if is_array($article.categories)}
                                <ul class="list-inline">
                                    {foreach $article.categories as $categoryId => $categoryName}
                                        <li class="list-inline-item">
                                            <a href="{url->getUrl path="/categories" category="{$categoryId}"}">
                                                {$categoryName}
                                            </a>
                                            {if !$categoryName@last}, {/if}
                                        </li>
                                    {/foreach}
                                </ul>
                            {/if}

                            {if $article.image}
                                <ul class="list-inline">
                                    <img src="{$article.image}" alt="article-{$article.id}">
                                </ul>
                            {/if}
                            <div class="content">
                                {$article.content}
                            </div>
                        </article>

                        {if count($recommendedArticles) gt 0}
                            <div class="block comment">
                                <h4>You Might Be Interested</h4>
                                <div class="article-block-row">
                                    {include file='../article_cards.tpl'  articles=$recommendedArticles}
                                </div>
                            </div>
                        {/if}
                    {/if}
                </div>
                <div class="col-md-10 offset-md-1 col-lg-3 offset-lg-0">
                    {include file='../blog_sidebar.tpl'}
                </div>
            </div>
        </div>
    </section>

{/block}
