function MwAi() {
    return {
        async init() {
            //todo

        },

        generateImage(messages, options = {}) {
            return new Promise((resolve, reject) => {
                let data = {
                    messages: messages,
                    options: options
                };

                let ajaxSettings = {
                    url: mw.settings.site_url + 'api/ai/generateImage',
                    type: 'POST',
                    data: data,
                    dataType: "json",
                };

                const csrf = $('meta[name="csrf-token"]')

                if (csrf.length) {
                    ajaxSettings.headers = {
                        "X-CSRF-TOKEN": csrf.attr("content")
                    };
                }

                $.post(ajaxSettings)
                    .then(function(res) {
                        if (res.success) {
                            resolve(res);
                        } else {
                            reject('Image generation failed');
                        }
                    })
                    .fail(function(jqXHR, textStatus, errorThrown) {
                        reject(errorThrown || 'AI Image Generation Error');
                    });
            });
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
