<script type="text/javascript">
let alertMessages = {$alert_messages|json_encode nofilter};

{literal}
    (function(alertMessages) {
        let state = { active: false };

        function init() {
            state.active = true;
            state.alert = alert;
            state.alerts = alerts;

            try {
                alerts(alertMessages);
            } catch (e) {
                // do nothing
            }
        }

        function alerts(messagesJson) {
            if (state.active && messagesJson !== '') {
                const messages = JSON.parse(messagesJson);
                if (Array.isArray(messages)) {
                    messages.forEach((item) => {
                        let message = (item?.message ?? '').trim();
                        let type = (item?.type ?? '').trim();
                        if (message) {
                            alert (message, type);
                        }
                    });
                }
            }
        }

        function alert (message, type) {
            if (state.active && typeof message === 'string' && message.trim() !== '') {
                const alertId = Math.random() * 1000;
                showMessage(alertId, message, type);

                setTimeout(() => {
                    hideMessage(alertId);
                }, 5000);
            }
        }

        function showMessage (id, message, type) {
            let alertTypeStyle;
            switch (type) {
                case 'info':
                    alertTypeStyle = 'info'
                    break;
                case 'error':
                    alertTypeStyle = 'error'
                    break;
                default:
                    alertTypeStyle = 'secondary'
            }

            const target = document.querySelector('body');
            if (target) {
                target.insertAdjacentHTML(
                    'beforeend',
                    '<div id="' + id + '" class="alert-message slide-bottom-emerge"><div class="' + alertTypeStyle + '">'+ message + '</div></div>'
                );
            }
        }

        function hideMessage(id) {
            const target = document.getElementById(id);
            if (target) {
                target.classList.add('slide-hide-left');
            }
        }

        window.alertModule = {
            init: init,
            getState: function() { return state; }
        };
    })(alertMessages);
{/literal}
</script>
