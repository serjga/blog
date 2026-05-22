{if $totalPages > 1}
    <div class="pagination justify-content-center">
        <nav aria-label="Page navigation example">
            <ul class="pagination">
                {if $currentPage > 1}
                    <li class="page-item">
                        <a class="page-link" href="{url->getCurrentUrl page="{$currentPage - 1}"}" aria-label="Previous">
                            <span aria-hidden="true"><i class="fa fa-angle-left"></i></span>
                            <span class="sr-only">Previous</span>
                        </a>
                    </li>
                {/if}

                {if $currentPage-3 > 1}
                    <li class="page-item page-item-points">
                        <span class="points">
                            <i class="fa-solid fa-ellipsis"></i>
                        </span>
                    </li>
                {/if}

                {section name=page start={math equation="max(a, b)" a={$currentPage-3} b=1} loop={math equation="min(a, b)" a={$currentPage+4} b={$totalPages+1}}}
                    <li class="page-item {if $currentPage == $smarty.section.page.index}active{/if} page-item-{$currentPage - $smarty.section.page.index}">
                        <a class="page-link" href="{url->getCurrentUrl page="{$smarty.section.page.index}"}">
                            {$smarty.section.page.index}
                        </a>
                    </li>
                {/section}

                {if $currentPage+3 < $totalPages}
                    <li class="page-item page-item-points">
                        <span class="points">
                            <i class="fa-solid fa-ellipsis"></i>
                        </span>
                    </li>
                {/if}

                {if $currentPage < $totalPages}
                    <li class="page-item">
                        <a class="page-link" href="{url->getCurrentUrl page="{$currentPage + 1}"}" aria-label="Next">
                            <span aria-hidden="true"><i class="fa fa-angle-right"></i></span>
                            <span class="sr-only">Next</span>
                        </a>
                    </li>
                {/if}
            </ul>
        </nav>
    </div>
{/if}
