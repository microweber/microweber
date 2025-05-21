<template>
    <div v-if="canShowAiChat">
        <div class="d-flex">


            <svg fill="currentColor" width='24' height='24' viewBox='0 0 24 24' xmlns='http://www.w3.org/2000/svg'
                 xmlns:xlink='http://www.w3.org/1999/xlink'>
                <rect width='24' height='24' stroke='none' fill='currentColor' opacity='0'/>


                <g transform="matrix(0.71 0 0 0.71 12 12)">
                    <path
                        style="stroke: none; stroke-width: 1; stroke-dasharray: none; stroke-linecap: butt; stroke-dashoffset: 0; stroke-linejoin: miter; stroke-miterlimit: 4; fill: rgb(0,0,0); fill-rule: nonzero; opacity: 1;"
                        transform=" translate(-16, -16)"
                        d="M 16 3 C 14.895 3 14 3.895 14 5 C 14 5.7389464 14.404366 6.3763868 15 6.7226562 L 15 9 L 10 9 C 7.2504839 9 5 11.250484 5 14 L 5 15 L 2 15 L 2 22 L 5 22 L 5 27 L 9 27 L 10 27 L 13 27 L 13 25 L 11 25 L 11 23 L 21 23 L 21 24 L 23 24 L 23 21 L 9 21 L 9 25 L 7 25 L 7 14 C 7 12.331516 8.3315161 11 10 11 L 22 11 C 23.668484 11 25 12.331516 25 14 L 25 23 C 25 24.668484 23.668484 26 22 26 L 18.722656 26 C 18.376387 25.404366 17.738946 25 17 25 C 15.895 25 15 25.895 15 27 C 15 28.105 15.895 29 17 29 C 17.738946 29 18.376387 28.595634 18.722656 28 L 22 28 C 24.749516 28 27 25.749516 27 23 L 27 22 L 30 22 L 30 15 L 27 15 L 27 14 C 27 11.250484 24.749516 9 22 9 L 17 9 L 17 6.7226562 C 17.595634 6.3763868 18 5.7389464 18 5 C 18 3.895 17.105 3 16 3 z M 12 14 C 10.895 14 10 14.895 10 16 C 10 17.105 10.895 18 12 18 C 13.105 18 14 17.105 14 16 C 14 14.895 13.105 14 12 14 z M 20 14 C 18.895 14 18 14.895 18 16 C 18 17.105 18.895 18 20 18 C 21.105 18 22 17.105 22 16 C 22 14.895 21.105 14 20 14 z M 4 17 L 5 17 L 5 20 L 4 20 L 4 17 z M 27 17 L 28 17 L 28 20 L 27 20 L 27 17 z"
                        stroke-linecap="round"/>
                </g>
            </svg>

            <span class="mw-admin-action-links mw-adm-liveedit-tabs ms-3" :class="{'active': showAiChat }"
                  v-on:click="toggleAiChat">
        AiChat
      </span>
        </div>

        <div v-if="showAiChat">
            <div class="mb-4">
                <input type="text" v-model="aiMessage" placeholder="Type your message..."
                       @keyup.enter="submitAiRequest"/>
                <button @click="submitAiRequest">Send</button>
                <div v-if="loading" class="text-center">AI is thinking...</div>
                <div v-else-if="error" class="text-danger">{{ error }}</div>

            </div>
        </div>
    </div>
</template>

<script>


export default {
    components: {},
    data() {

        let canShowAiChat = false;
        if (typeof mw.top().win.MwAi !== 'undefined' && typeof mw.top().win.MwAi().sendToChat === 'function') {
            canShowAiChat = true;
        } else {
            canShowAiChat = false;
        }


        return {
            cssPropertiesToSelect: [
                'background-color',
                'background-clip',
                'color',
                'font-size',
                'font-weight',
                'text-align',
                'font-family',
                'text-decoration',
                'text-transform',
                'line-height',
                'letter-spacing',
                'text-shadow',

                'margin',
                'margin-top',
                'margin-bottom',
                'margin-left',
                'margin-right',



                'padding',
                'padding-top',
                'padding-bottom',
                'padding-left',
                'padding-right',


                'border-radius',
                'border-radius-top-left',
                'border-radius-top-right',
                'border-radius-bottom-left',
                'border-radius-bottom-right',


                'border',
                'border-top',
                'border-bottom',
                'border-left',
                'border-right',


                'border-color',
                'border-top-color',
                'border-bottom-color',
                'border-left-color',
                'border-right-color',


                'border-width',
                'border-top-width',
                'border-bottom-width',
                'border-left-width',
                'border-right-width',


                'border-style',
                'border-top-style',
                'border-bottom-style',
                'border-left-style',
                'border-right-style',








                'box-shadow',

            ],
            canShowAiChat: canShowAiChat,
            showAiChat: false,
            aiMessage: '',
            chatHistory: [],
            loading: false,
            error: null,
            activeNode: null
        };
    },

    methods: {
        toggleAiChat() {
            this.showAiChat = !this.showAiChat;
        },


        async submitAiRequest() {
            if (!this.aiMessage.trim()) return;

            this.loading = true;
            this.error = null;
            this.chatHistory.push(`You: ${this.aiMessage}`);


            if (this.$root.selectedElement) {

                console.log('selectedElement', this.$root.selectedElement);

                let selectedElementStyle = getComputedStyle(this.$root.selectedElement)
                console.log(selectedElementStyle)
                if (selectedElementStyle) {

                    //filter emplty styles

                    // filter only in cssPropertiesToSelect

                    selectedElementStyle = Object.fromEntries(
                        Object.entries(selectedElementStyle).filter(([key, value]) => this.cssPropertiesToSelect.includes(key))
                    );

                    selectedElementStyle = Object.fromEntries(
                        Object.entries(selectedElementStyle).filter(([key, value]) => value !== '')
                    );
                    selectedElementStyle = Object.fromEntries(
                        Object.entries(selectedElementStyle).filter(([key, value]) => value !== 'none')
                    );

                    selectedElementStyle = Object.fromEntries(
                        Object.entries(selectedElementStyle).filter(([key, value]) => value !== 'normal')
                    );

                    selectedElementStyle = Object.fromEntries(
                        Object.entries(selectedElementStyle).filter(([key, value]) => value !== '0px')
                    );

                    //remove css properties that are not needed
                    selectedElementStyle = Object.fromEntries(
                        Object.entries(selectedElementStyle).filter(([key, value]) =>
                            !key.startsWith('webkit') &&
                            !key.startsWith('-moz') &&
                            !key.startsWith('Moz')
                        )
                    );

                    var selectedElementId = this.$root.selectedElement.id;
                    if (!selectedElementId) {
                        this.$root.selectedElement.id = mw.id();
                    }

                    var selectedElementId = this.$root.selectedElement.id;


                    var valuesForEditArr = {};
                    for (const [key, value] of Object.entries(selectedElementStyle)) {
                        if (key && value) {
                            valuesForEditArr[key] = value;
                        }
                    }
                    let valuesForEdit = {
                        [selectedElementId]: valuesForEditArr
                    }


                    let editSchema = JSON.stringify(valuesForEdit);
                    let about = this.aiMessage;


                    let messageOptions = {};
                    //  messageOptions.schema = this.schema();
                    messageOptions.schema = editSchema;
                    mw.top().spinner(({element: mw.top().doc.body, size: 60, decorate: true})).show();


                    const message = `Using the existing object CSS properties,
        By using this schema: \n ${editSchema} \n
        You must write CSS values to the goven object,
        You are CSS values editor, you must edit the values of the css to complete the user design task,


You are a CSS value editor.

Your job is to modify and output only the CSS values needed to complete the user's design task.

When the user requests a **gradient on text**, you must:

* Set \`color: transparent\`
* Set \`background-clip: text\`
* Set \`-webkit-background-clip: text\`
* Set the gradient value in the \`background\` property

When the user requests a **gradient on the element background**, you must:

* Remove any \`background-color\` value
* Set the gradient value in the \`background\` property
* Remove \`background-clip\`
* Remove \`-webkit-background-clip\`
* Remove \`color: transparent\`



If the user asks to style a box (e.g., div, button, card):
Add padding if mentioned or implied (e.g., "space inside", "room around text")
Set background-color if a solid color is requested
Set color for text color
Optionally add border-radius, box-shadow, or border if mentioned


If the user asks to style the element, do it


Only return the CSS key-value pairs that apply to the current task. Do not return full selectors or surrounding styles. Keep the output minimal and task-focused.




        The css design task is : ${about}

        You must write css values to the given object,



You must respond ONLY with the JSON schema with the following structure. Do not add any additional comments""" + \\
"""[
  JSON
{
   { Populated Schema Definition with the items filled with text ... populate the schema with the existing object IDs and the text  }

"""`


                    let messages = [{role: 'user', content: message}];


                    try {
                        const response = await mw.top().win.MwAi().sendToChat(messages, messageOptions);


                        //parse response

                        let parsedResponse = response;

                        //check if success
                        if (!parsedResponse.success) {
                            this.error = 'Error: ' + parsedResponse.message;
                            mw.top().spinner(({element: mw.top().doc.body, size: 60, decorate: true})).hide();
                            return;
                        }

                        console.log('parsedResponse', parsedResponse)

                        //check if data not emplty and apply to selelcted node
                        var targetDocument = mw.top().app.canvas.getDocument();
                        console.log('parsedResponse.data', parsedResponse.data)
                        for (const [key, value] of Object.entries(parsedResponse.data)) {
                            if (key && value) {


                                //get Elemnet by key
                                let element = targetDocument.getElementById(key);
                                if (element) {
                                    for (const [prop, val] of Object.entries(value)) {
                                        console.log('apply', prop, val)
                                        this.$root.applyPropertyToActiveNode(element, prop, val);
                                    }
                                }

                            }
                        }


                        this.chatHistory.push(`AI: ${response}`);
                    } catch (err) {
                        this.error = err.message;
                        mw.top().spinner(({element: mw.top().doc.body, size: 60, decorate: true})).hide();
                    } finally {
                        this.loading = false;
                        this.aiMessage = '';
                        mw.top().spinner(({element: mw.top().doc.body, size: 60, decorate: true})).hide();

                    }
                }


            }

        }
    }
};
</script>
