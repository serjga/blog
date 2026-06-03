{if !empty($id)}
    <article class="single-post">
        <h3>{$title}</h3>
        <ul class="list-inline">
            <li class="list-inline-item">
                <i class="fa fa-calendar"></i>
                {$published_date|date_format}
            </li>
            <li class="list-inline-item" data-article="views">
                <i class="fa fa-eye"></i>
                {$views}
            </li>
        </ul>

        {if is_array($article_categories)}
            <ul class="list-inline">
                {foreach $article_categories as $category_id => $category_name}
                    <li class="list-inline-item">
                        <a href="{url->getUrl path="/category" id="{$category_id}"}">
                            {$category_name}
                        </a>
                        {if !$category_name@last}, {/if}
                    </li>
                {/foreach}
            </ul>
        {/if}

        {if $image}
            <ul class="list-inline">
                <img src="{url->getImageUrl path="$image"}" alt="{$title}">
            </ul>
        {/if}
        <div class="content">
            {$content}
        </div>
        {if is_array($tags) && count($tags)}
            <ul class="list-inline article-tags">
                <span class="list-inline-item"><i class="fa-solid fa-hashtag"></i></span>
                {foreach $tags as $tag}
                    <li class="list-inline-item">
                        <a href="{url->getUrl path="/categories" tags=$tag.code}">{$tag.label}</a>
                    </li>
                {/foreach}
            </ul>
        {/if}
    </article>
{/if}
