
function MwAi() {
    return {
        async init() {
            //todo

        },





        async sendToChat(messages, options = {}) {






            try {
                // Create data object
                const data = {
                    messages: messages,
                    options: options
                };

                // Set up AJAX settings
                const ajaxSettings = {
                    url: mw.settings.site_url + 'api/ai/chat',
                    type: 'POST',
                    data: JSON.stringify(data),
                    contentType: 'application/json',
                    dataType: 'json'
                };

                // Add CSRF token if jQuery is available
                if (typeof $ !== 'undefined' && $('meta[name="csrf-token"]').length) {
                    ajaxSettings.headers = {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
                    };
                }

                // Return a promise
                return $.ajax(ajaxSettings)
                    .fail(function(jqXHR, textStatus, errorThrown) {
                        console.error('AI Service Error:', textStatus, errorThrown);
                        throw new Error(`HTTP error! Status: ${jqXHR.status}`);
                    });
            } catch (error) {
                console.error('AI Service Error:', error);
                throw error;
            }
        }
    }
}
