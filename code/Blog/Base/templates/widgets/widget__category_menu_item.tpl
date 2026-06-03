{if is_array($category_data) }
    <li data-id="{$category_data.category_id}">
        <a href="{url->getUrl path="/category" id="{$category_data.category_id}"}">{$category_data.category_name}
            <span>{$category_data.article_count}</span>
        </a>
    </li>
{/if}
