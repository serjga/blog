{if is_array($options) && count($options) gt 0}
    <div id="blog-articles-tag-filter" class="widget article-checkbox-filter">
        <h4 class="widget-header">Tags</h4>
        <select name="select">
            {foreach $options as $tagCode => $tagValue}
                <option value="{$tagCode}" {if $tagCode === ''} disabled selected {/if}>{$tagValue}</option>
            {/foreach}
        </select>

        <div class="form-check"></div>
    </div>

    <script type="text/javascript">
        {literal}
            (function(elementId) {
                let state = {active: false};

                function init() {
                    state.active = true;
                    const url = new URL(window.location.href);
                    let tagList = getQueryTagList(url);

                    const widgetContainer = $('#' + elementId);
                    const checkedTags = [];

                    widgetContainer.find('select option').each(function(index, element) {
                        if (tagList.includes(element.value)) {
                            checkedTags.push({value: element.value, label: element.innerText});
                            $(this).remove();
                        }
                    });

                    $(`#${elementId} select`).on('change', function() {
                        let selectedValue = $(this).val();
                        handleSelect(selectedValue);
                    }).niceSelect('update');

                    checkedTags.forEach(({value, label}) => {
                        $(widgetContainer).find('.form-check').append(`<label class="container"><input type="checkbox" value="${value}" checked><span class="checkmark"></span>${label}</label>`);
                    });

                    widgetContainer.find('input[type="checkbox"]').each(function(index, checkbox) {
                        const value = String(checkbox.value).trim();
                        $(checkbox).on('change', function() {
                            if (value !== '') {
                                handleCheck(value);
                            }
                        });
                    });
                }

                function handleSelect(tag) {
                    const url = new URL(window.location.href);
                    let tagList = getQueryTagList(url);
                    tagList.push(tag);
                    url.searchParams.set('tags', tagList.join(','));
                    url.searchParams.delete('page');
                    let cleanSearch = url.search.replace(/%2C/g, ',');
                    window.location.href = url.origin + url.pathname + cleanSearch;
                }

                function handleCheck(tag) {
                    const url = new URL(window.location.href);
                    let tagList = getQueryTagList(url);
                    tagList = tagList.filter(item => item !== tag);

                    if (!tagList.length) {
                        url.searchParams.delete('tags')
                    } else {
                        url.searchParams.set('tags', tagList.join(','));
                    }

                    url.searchParams.delete('page');
                    url.searchParams.delete('search');
                    let cleanSearch = url.search.replace(/%2C/g, ',');
                    window.location.href = url.origin + url.pathname + cleanSearch;
                }

                function getQueryTagList (url) {
                    const tags = url.searchParams.has('tags') ? String(url.searchParams.get('tags')) : '';
                    return tags !== '' ? tags.split(',') : [];
                }

                window.tagFilterComponentModule = {
                    init: init,
                    getState: function() {return state;}
                };
            })('blog-articles-tag-filter');

        {/literal}
    </script>
{/if}
