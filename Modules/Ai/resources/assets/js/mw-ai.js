function MwAi() {
    return {
        async init() {
            //todo

        },

        async generateImage(messages, options = {}) {
            try {
                const data = await $.post(mw.settings.site_url + 'api/ai/generateImage', {
                    messages: messages,
                    options: options
                });

                if (data.success) {
                    // First try to use the stored file URL if available
                    if (data.url) {
                        return data.url;
                    }
                    // Fall back to base64 data if URL is not available
                    else if (data.data) {
                        return 'data:image/png;base64,' + data.data;
                    }
                }
                throw new Error('Image generation failed');
            } catch (error) {
                console.error('AI Image Generation Error:', error);
                throw error;
            }
        },

        async sendToChat(messages, options = {}) {
            return new Promise((resolve, reject) => {
                let  data = {
                    messages: messages,
                    options: options
                };

                let  ajaxSettings = {
                    url: mw.settings.site_url + 'api/ai/chat',
                    type: 'POST',
                    data: data,
                    dataType: "json",
               };

               const csrf = $('meta[name="csrf-token"]')

                if ( csrf.length) {
                    ajaxSettings.headers = {
                        "X-CSRF-TOKEN": csrf.attr("content")
                    };
                }

                $.post(ajaxSettings)
                    .then(function(res) {
                        if (res.success && res.data) {
                            resolve(res);
                        } else {
                            reject(res);
                        }
                    })
                    .fail(function(jqXHR, textStatus, errorThrown) {
                        reject(errorThrown);
                    });
            })
        }
    }
}
