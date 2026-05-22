{if is_array($articles)}
    {foreach $articles as $article}
        <!-- article card -->
        <div class="article-item bg-light">
            <div class="card">
                {if !empty($article.image)}
                <a href="{url->getUrl path="/article" id="{$article.id}"}">
                    <div class="thumb-content" style="background-image: url({$article.image}); ">

{*                            <img class="card-img-top img-fluid" src="{$article.image}" alt="Article image">*}

                    </div>
                </a>
                    {/if}
                <div class="card-body">
                    <h4 class="card-title">
                        <a href="{url->getUrl path="/article" id="{$article.id}"}">{$article.title}</a>
                    </h4>
                    <ul class="list-inline article-categories">
                        {if is_array($article.categories)}
                            {foreach $article.categories as $categoryId => $categoryName}
                                <li class="list-inline-item">
                                    <a href="{url->getUrl path="/categories" category="{$categoryId}"}">
                                        {$categoryName}
                                    </a>
                                    {if !$categoryName@last}, {/if}
                                </li>
                            {/foreach}
                        {/if}
                    </ul>

                    <ul class="list-inline">
                        {if !empty($article.createdAt)}
                        <li class="list-inline-item">
                            <i class="fa fa-calendar"></i>
                            {$article.createdAt|date_format}
                        </li>
                        {/if}
                        {if !empty($article.views)}
                            <li class="list-inline-item">
                                <i class="fa fa-eye"></i>
                                {$article.views}
                            </li>
                        {/if}
                    </ul>

                    <p class="card-text">
                        {$article.description}
                    </p>

                </div>
            </div>
        </div>
    {/foreach}
{/if}
