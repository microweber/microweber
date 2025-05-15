function MwAi() {
    return {
        async init() {
            //todo

        },

        async generateImage(messages, options = {}) {
            try {
                const data = await $.post(mw.settings.site_url + 'api/ai/edit-image', {
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


            try {
                // Create data object
                let  data = {
                    messages: messages,
                    options: options
                };


                 // Set up AJAX settings
                let  ajaxSettings = {
                    url: mw.settings.site_url + 'api/ai/chat',
                    type: 'POST',
                    data: data,
                 //   data: JSON.stringify(data),
                    dataType: "json",

                 //   contentType: "application/json; charset=utf-8",
               };

                // Add CSRF token if jQuery is available
                if (typeof $ !== 'undefined' && $('meta[name="csrf-token"]').length) {
                    ajaxSettings.headers = {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
                    };
                }

                // Create a local variable for the response
                let responseData;

                // Wait for the promise to resolve
                responseData = await $.post(ajaxSettings)
                    .then(function(res) {
                        if (res.success && res.data) {
                            responseData = res;
                            return responseData;
                        } else {
                            throw new Error('Invalid response format or unsuccessful operation');
                        }
                    })
                    .fail(function(jqXHR, textStatus, errorThrown) {
                        console.error('AI Service Error:', textStatus, errorThrown);
                        throw new Error(`HTTP error! Status: ${jqXHR.status}`);
                    });


                // Check if the response is valid

                console.log( 'AI Response:', responseData);





                return responseData;

            } catch (error) {
                console.error('AI Service Error:', error);
                throw error;
            }
        }
    }
}
