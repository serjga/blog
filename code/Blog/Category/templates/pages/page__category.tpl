<!-- BODY CONTENT -->
{if !empty($current_category_name) }
    <section class="page-title">
        <div class="container">
            <div class="row">
                <div class="col-md-8 offset-md-2 text-center">
                    <!-- Category Title -->
                    <h3>{$current_category_name}</h3>
                </div>
            </div>
        </div>
    </section>
{/if}

<!--==================================
=            Blog Section            =
===================================-->
<section class="blog section {if empty($current_category_name) }pt-50{/if}">
    <div class="container">
        {if !empty({$current_category_description}) }
            <div class="row">
                <div class="col">
                    <div class="category-description" >
                        <p>{$current_category_description}</p>
                    </div>
                </div>
            </div>
        {/if}
        <div class="row">
            <div class="col-md-10 offset-md-1 col-lg-9 offset-lg-0">
                {$block__category_articles_listing}
            </div>

            <div class="col-md-10 offset-md-1 col-lg-3 offset-lg-0">
                <div class="sidebar">
                    {* Category Search *}
                    {$widget__articles_search}
                    {* Category Menu *}
                    {$widget__category_menu}
                    {* Articles Sort *}
                    {$widget__articles_listing_sort}
                    {* Tag Filter *}
                    {$widget__tags_filter}
                    {* Archive Filter *}
                    {$widget__archive_filter}
                </div>
            </div>
        </div>
    </div>
</section>
