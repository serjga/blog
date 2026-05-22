{if is_array($list) && count($list) gt 0}
    <!-- Category List Widget -->
    <div class="widget sidebar-category">
        <h4 class="widget-header">
            <a href="{url->getUrl path="/categories"}">
                All Category
            </a>
        </h4>
        <ul class="category-list">
            {foreach $list as $category}
                <li><a href="{url->getUrl path="/categories" category="{$category.id}"}">{$category.name} <span>{$category.articleCount}</span></a></li>
            {/foreach}
        </ul>
    </div>
{/if}
