{if is_array($tags) && count($tags) gt 0}
    <!-- Tag Filter Widget -->
    {if count($tagList) gt 0}
        <div id="blog-articles-tag-filter" class="widget article-checkbox-filter">
            <h4 class="widget-header">Tags</h4>

            {foreach $tags as $tag}
                <div class="form-check">
                    <label class="container">
                        <input type="checkbox" value="{$tag.id}">
                        <span class="checkmark"></span>
                        {$tag.label}
                    </label>

{*                    <label class="form-check-label">*}
{*                        <input class="form-check-input" type="checkbox" value="{$tag.id}">*}
{*                        {$tag.label}*}
{*                    </label>*}
                </div>
            {/foreach}
        </div>
    {/if}

    <script type="text/javascript">
        {literal}
            (function(elementId) {
                let state = { active: false };

                function init() {

                    state.active = true;
                }

                window.tagFilterComponentModule = {
                    init: init,
                    getState: function() { return state; }
                };
            })('blog-articles-tag-filter');

            window.tagFilterComponentModule.init();
        {/literal}
    </script>
{/if}
