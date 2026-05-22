{if is_array($options) && count($options) gt 0}
    <!-- Years Filter Widget -->
    <div class="widget archive">
        <h5 class="widget-header">Archives</h5>
        {foreach $options as $item}
            <ul class="archive-list">
                <li>
                    <a href="{url->getUrl path="/categories" year="{$item}"}">{$item}</a>
                </li>
            </ul>
        {/foreach}
    </div>
{/if}
