<a class="nav-link dropdown-toggle" href="{url->getUrl path="/categories"}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    Categories <span><i class="fa fa-angle-down"></i></span>
</a>
<!-- Dropdown list -->
<div class="dropdown-menu dropdown-menu-right">
    {foreach $category_list as $category}
        <a class="dropdown-item" href="{url->getUrl path="/category" id="{$category.id}"}">
            {$category.name}
        </a>
    {foreachelse}
        No categories found.
    {/foreach}
</div>
