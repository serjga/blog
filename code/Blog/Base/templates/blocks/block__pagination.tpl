{if $total_pages > 1}
    <div class="pagination justify-content-center">
        <nav aria-label="Page navigation example">
            <ul class="pagination">
                {if $current_page > 1}
                    <li class="page-item">
                        <a class="page-link icon-page" href="{url->getCurrentUrl page="{$current_page - 1}"}" aria-label="Previous">
                            <span aria-hidden="true"><i class="fa fa-angle-left"></i></span>
                            <span class="sr-only">Previous</span>
                        </a>
                    </li>
                {/if}

                {if $current_page-3 > 1}
                    <li class="page-item page-item-points">
                        <span class="points">
                            <i class="fa-solid fa-ellipsis"></i>
                        </span>
                    </li>
                {/if}

                {section name=page start={math equation="max(a, b)" a={$current_page-3} b=1} loop={math equation="min(a, b)" a={$current_page+4} b={$total_pages+1}}}
                    <li class="page-item {if $current_page == $smarty.section.page.index}active{/if} page-item-{$current_page - $smarty.section.page.index}">
                        <a class="page-link" href="{url->getCurrentUrl page="{$smarty.section.page.index}"}">
                            {$smarty.section.page.index}
                        </a>
                    </li>
                {/section}

                {if $current_page+3 < $total_pages}
                    <li class="page-item page-item-points">
                        <span class="points">
                            <i class="fa-solid fa-ellipsis"></i>
                        </span>
                    </li>
                {/if}

                {if $current_page < $total_pages}
                    <li class="page-item">
                        <a class="page-link icon-page" href="{url->getCurrentUrl page="{$current_page + 1}"}" aria-label="Next">
                            <span aria-hidden="true"><i class="fa fa-angle-right"></i></span>
                            <span class="sr-only">Next</span>
                        </a>
                    </li>
                {/if}
            </ul>
        </nav>
    </div>
{/if}
