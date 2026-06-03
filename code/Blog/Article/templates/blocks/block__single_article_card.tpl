{if !empty($id)}
    <div class="article-item bg-light">
        <div class="card">
            {if !empty($image)}
                <a href="{$article_url}">
                    <div class="thumb-content" style="background-image: url({$image}); ">
                    </div>
                </a>
            {/if}

            <div class="card-body">
                <h4 class="card-title">
                    <a href="{$article_url}">
                        {$title}
                    </a>
                </h4>

                {if is_array($categories)}
                    <ul class="list-inline article-categories">
                        {foreach $categories as $categoryId => $categoryName}
                            <li class="list-inline-item">
                                <a href="{$article_url}">
                                    {$categoryName}
                                </a>
                                {if !$categoryName@last}, {/if}
                            </li>
                        {/foreach}
                    </ul>
                {/if}

                <ul class="list-inline">
                    {if !empty($published_date)}
                        <li class="list-inline-item">
                            <i class="fa fa-calendar"></i>
                            {$published_date|date_format}
                        </li>
                    {/if}

                    {if !empty($views)}
                        <li class="list-inline-item">
                            <i class="fa fa-eye"></i>
                            {$views}
                        </li>
                    {/if}
                </ul>

                {if !empty($description)}
                    <p class="card-text">
                        {$description}
                    </p>
                {/if}

            </div>
        </div>
    </div>
{/if}
