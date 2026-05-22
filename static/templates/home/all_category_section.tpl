<!--==========================================
=            All Category Section            =
===========================================-->

<section class=" section">
    <!-- Container Start -->
    <div class="container">
        <div class="row">
            <div class="col-12">
                <!-- Section title -->
                <div class="section-title">
                    <h2>All Categories</h2>
                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Perferendis, provident!</p>
                </div>
                <div class="row">
                    <!-- Category list -->
                    {if is_array($categories)}
                        {foreach $categories as $category}
{*                            <div class="col">*}
                                <div class="article-block">
                                    <div class="header">
                                        <div class="category-icon">
                                            <i class="fa fa-laptop icon-bg-1"></i>
                                        </div>
                                        <h4>{$category.name}</h4>
                                        <div class="visit-category">
                                            <a href="{url->getUrl path="/categories" category="{$category.id}"}" class="btn-sm btn-visit-category">
                                                Visit
                                                <span aria-hidden="true"><i class="fa fa-angle-right"></i></span>
                                            </a>
                                        </div>
                                    </div>

                                    <div class="article-block-row">
                                        {include file='../article_cards.tpl' articles=$category.articles}
                                    </div>
                                </div>
{*                            </div>*}
                        {/foreach}
                    {/if}
                    <!-- /Category List -->

                </div>
            </div>
        </div>
    </div>
    <!-- Container End -->
</section>
