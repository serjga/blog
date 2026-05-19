{if is_array($articles)}
    {foreach $articles as $article}
        <div class="col-sm-12 col-lg-4 col-md-6">
            <!-- article card -->
            <div class="product-item bg-light">
                <div class="card">
                    <div class="thumb-content">
                        {if !empty($article.image)}
                            <a href="{url->getUrl path="/article" id="{$article.id}"}">
                                <img class="card-img-top img-fluid" src="{$article.image}" alt="Article image">
                            </a>
                        {/if}
                    </div>
                    <div class="card-body">
                        <h4 class="card-title">
                            <a href="{url->getUrl path="/article" id="{$article.id}"}">{$article.title}</a>
                        </h4>
                        <ul class="list-inline product-meta">
                            {if is_array($article.categories)}
                                {foreach $article.categories as $categoryId => $categoryName}
                                    <li class="list-inline-item">
                                        <a href="{url->getUrl path="/categories" category="{$categoryId}"}">
                                            <i class="fa fa-folder-open-o"></i>{$categoryName}
                                        </a>
                                        {if !$categoryName@last}, {/if}
                                    </li>
                                {/foreach}
                            {/if}
                            <li class="list-inline-item">
                                <i class="fa fa-calendar"></i>{$article.createdAt|date_format}
                            </li>
                        </ul>
                        <p class="card-text">
                            {$article.description}
                        </p>

                        {if !empty($article.views)}
                            <div class="product-ratings">
                                <ul class="list-inline">
                                    <li class="list-inline-item">
                                        <i class="fa fa-folder-open-o"></i>{$article.views}
                                    </li>
                                </ul>
                            </div>
                        {/if}
                    </div>
                </div>
            </div>
        </div>
    {/foreach}
{/if}
