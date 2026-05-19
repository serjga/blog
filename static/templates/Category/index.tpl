{extends file="../layout.tpl"}
{block name=body}

<!--================================
=            Page Title            =
=================================-->
{if is_array($currentCategory) }
	<section class="page-title">
		<!-- Container Start -->
		<div class="container">
			<div class="row">
				<div class="col-md-8 offset-md-2 text-center">
					<!-- Title text -->
					<h3>{$currentCategory.name}</h3>
				</div>
			</div>
		</div>
		<!-- Container End -->
	</section>
{/if}
<!--==================================
=            Blog Section            =
===================================-->

<section class="blog section">
	<div class="container">
		{if is_array($currentCategory) }
			<div class="row">
				<div class="col-md-12">
					<div class="widget" >
						<p>{$currentCategory.description}</p>
					</div>
				</div>
			</div>
		{/if}
		<div class="row">
			<div class="col-md-10 offset-md-1 col-lg-9 offset-lg-0">
				{if is_array($searchResult) }
					<div class="row">
						<div class="col-md-12">
							<div class="search-result bg-gray">
								<h2>Results For "{$search}"</h2>
								<p>{$searchResult.count} Of {$searchResult.totalRecords} Results</p>
							</div>
						</div>
					</div>
				{/if}

				{if is_array($articles)}
					<!-- Article 01 -->
					{foreach $articles as $article}
						<article>
							<!-- Post Image -->
							{if $article.image}
								<div class="image">
									<img src="{$article.image}" alt="{$article.title}">
								</div>
							{/if}
							<!-- Post Title -->
							<h3>{$article.title}</h3>
							<ul class="list-inline">
								<li class="list-inline-item">Views {$article.views}</li>
								<li class="list-inline-item">{$article.createdAt|date_format}</li>
							</ul>

							<div>
								<ul class="list-inline">
									<i class="fa fa-folder-open-o"></i>
									{foreach $article.categories as $categoryId => $categoryName}
										<li class="list-inline-item">
											<a href="{url->getUrl path="/categories" category="{$categoryId}"}">
												{$categoryName}
											</a>
											{if !$categoryName@last}, {/if}
										</li>
									{/foreach}
								</ul>
							</div>
							<!-- Post Description -->
							<p class="">{$article.description}</p>
							<!-- Read more button -->
							<a href="{url->getUrl path="/article" id="{$article.id}"}" class="btn btn-transparent">Read More</a>
						</article>
					{/foreach}

					{else}
						<div class="category-search-filter">
							<div class="row">
								<div class="col">
									<strong>No records found.</strong>
								</div>
							</div>
						</div>
					{/if}
				{include file='../pagination.tpl'}
			</div>

			<div class="col-md-10 offset-md-1 col-lg-3 offset-lg-0">
				{include file='../blog_sidebar.tpl'}
			</div>
		</div>
	</div>
</section>

{/block}
