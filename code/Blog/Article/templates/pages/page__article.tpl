<!--=================================
=         Single Article Page       =
==================================-->
<section class="blog single-blog section">
    <div class="container">
        <div class="row">
            <div class="col-md-10 offset-md-1 col-lg-9 offset-lg-0">
                <!-- Single Page Content -->
                {$block__single_article}
                <!-- Recommended Articles -->
                {$block__recommended_articles}
            </div>
            <div class="col-md-10 offset-md-1 col-lg-3 offset-lg-0">
                <!-- Article Sidebar -->
                <div class="sidebar">
                    {* Category Search *}
                    {include file="{baseRegistry->getTemplateFile template='widgets/widget__articles_search'}"}
                    {* Category Menu *}
                    {$category_menu_widget}
                    {* Related Articles *}
                    {$block__related_articles}
                </div>
            </div>
        </div>
    </div>
</section>

<script type="text/javascript">
let articleCookieId = '{$get.id}';
let host = '{$host}';
{literal}
(function(articleCookieId, host) {
    let state = { active: false, clientProperties: null, userAgentData: null };
    function init() {
        state.active = true;
        const cookieName = 'article_visited';
        const cookieValues = getCookie(cookieName);
        const visitedArticles = cookieValues?.split(',') ?? [];
        const hasCookie = visitedArticles.includes(articleCookieId);

        if (!hasCookie && host.trim() !== '') {
            state.clientProperties = getClientProperties();
            if (navigator?.userAgentData) {
                navigator.userAgentData.getHighEntropyValues(["architecture", "model", "platformVersion"])
                    .then(ua => { state.userAgentData = ua; });
            }

            setTimeout(() => {
                const clientProperties = state.clientProperties ?? {};
                const userAgentData = state.userAgentData ?? {};
                const fingerprintsData = { ...clientProperties, ...userAgentData };
                const jsonString = JSON.stringify(fingerprintsData);
                getHash(jsonString).then(hash => updateViews(hash, cookieName, articleCookieId, visitedArticles));
            }, 50);
        }
    }

    function updateArticleViews() {
        const viewContainer = $('[data-article="views"]');
        const text = parseInt(viewContainer.text().trim(), 10);
        viewContainer.html('<i class="fa fa-eye"></i>' + (text + 1));
    }

    function getCookie(name) {
        const match = document.cookie.match(new RegExp('(^| )' + name + '=([^;]+)'));
        return match ? decodeURIComponent(match[2]) : null;
    }

    function updateViews(hash, cookieName, articleCookieId, visitedArticles) {
        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        $.ajax({
            url: host.trim() + '/update-article-views',
            method: 'POST',
            dataType: 'json',
            headers: {
                'X-CSRF-TOKEN': token
            },
            data: { hash: hash, id: articleCookieId },
        })
        .done(function(response) {
            visitedArticles.push(articleCookieId);
            document.cookie = cookieName + "=" + visitedArticles.join(',');
            updateArticleViews();
        });
    }

    async function getHash(message) {
        const msgUint8 = new TextEncoder().encode(message);
        const hashBuffer = await crypto.subtle.digest('SHA-256', msgUint8);
        const hashArray = Array.from(new Uint8Array(hashBuffer));
        const hashHex = hashArray.map(b => b.toString(16).padStart(2, '0')).join('');
        return hashHex;
    }

    function getClientProperties() {
        return {
            userAgent: navigator.userAgent,
            language: navigator.language,
            languages: navigator.languages?.join(','),
            colorDepth: screen.colorDepth,
            devicePixelRatio: window.devicePixelRatio,
            hardwareConcurrency: navigator.hardwareConcurrency,
            deviceMemory: navigator.deviceMemory,
            timezone: Intl.DateTimeFormat().resolvedOptions().timeZone,
            timezoneOffset: new Date().getTimezoneOffset(),
            screenResolution: `${screen.width}x${screen.height}`,
            availableResolution: `${screen.availWidth}x${screen.availHeight}`,
            platform: navigator.platform,
            cookieEnabled: navigator.cookieEnabled
        };
    }

    window.updateArticleViewsModule = {
        init: init,
        getState: function() { return state; }
    };
})(articleCookieId, host);

{/literal}
</script>
