<template>
    <div>


        <div v-if="canShowAiChat">
            <div class="d-flex mt-4">
                <!-- Heroicon outline cpu-chip — matches the other section header icons
                     (outline, viewBox 0 0 24 24) and signals AI, instead of a filled robot. -->
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="24" height="24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 3v1.5M4.5 8.25H3m18 0h-1.5M4.5 12H3m18 0h-1.5m-15 3.75H3m18 0h-1.5M8.25 19.5V21M12 3v1.5m0 15V21m3.75-18v1.5m0 15V21m-9-1.5h10.5a2.25 2.25 0 0 0 2.25-2.25V6.75a2.25 2.25 0 0 0-2.25-2.25H6.75A2.25 2.25 0 0 0 4.5 6.75v10.5a2.25 2.25 0 0 0 2.25 2.25Zm.75-12h9v9h-9v-9Z" />
                </svg>

                <span class="mw-admin-action-links mw-adm-liveedit-tabs ms-3" :class="{'active': showAiChat }"
                      v-on:click="toggleAiChat">
                    AI Style Editor
              </span>
            </div>

            <!-- task-2026-05-22-48070d / AI-720: show empty state when the selected
                 element is not text-based (div, section, img, etc. don't have
                 text styles for AI to suggest). -->
            <div v-show="showAiChat">

                <template v-if="elementSupportsAiStyles">
                    <!--
                      task-2026-05-05-854d66 (QW9) — removed duplicate span + dead input block.
                    -->
                    <div id="ai-gui-editor" ref="wrapper"></div>

                    <div v-if="loading" class="text-center">AI is thinking...</div>
                    <div v-else-if="error" class="text-danger">{{ error }}</div>
                </template>

                <!-- AI-720 empty state: non-text element selected -->
                <div v-else class="mw-ese-panel-empty-state" aria-live="polite">
                    <h3 class="mw-admin-empty-state__heading">Select text</h3>
                    <p class="mw-admin-empty-state__body">Select text to apply AI styles.</p>
                </div>
            </div>
        </div>
    </div>

</template>

<script>

import {AIChatForm} from '../../../components/ai-chat';


export default {

    mounted() {
        this._initAiChatForm();
    },
    watch: {
        elementSupportsAiStyles(val) {
            if (val) {
                this.$nextTick(() => this._initAiChatForm());
            }
        }
    },
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
                'font-style',
                'font-variant',

                'font-weight',
                'text-align',
                'text-shadow',
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
                'filter',
                'opacity',


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

    computed: {
        // AI-720: empty state shows when the selected element is not a text-content
        // element. Text elements (headings, paragraphs, spans, links, etc.) have
        // meaningful text styles for the AI to suggest; non-text elements (div,
        // section, img, video, etc.) render the empty state instead.
        elementSupportsAiStyles() {
            const el = this.$root && this.$root.selectedElement;
            if (!el || el.nodeType !== 1) return false;
            var textTags = ['H1','H2','H3','H4','H5','H6','P','SPAN','A','STRONG',
                'EM','B','I','LABEL','LI','TD','TH','BLOCKQUOTE','FIGCAPTION',
                'CITE','ABBR','MARK','CODE','PRE','ADDRESS','DT','DD','CAPTION'];
            return textTags.indexOf((el.tagName || '').toUpperCase()) !== -1;
        }
    },

    methods: {
        _initAiChatForm() {
            const target = this.$refs.wrapper;
            if (!target || this._aiChatFormAttached) return;
            const aiChatForm = new AIChatForm({
                multiLine: true,
                submitOnEnter: true,
                placeholder: mw.lang('Make text bigger')
            });
            target.appendChild(aiChatForm.form);
            this._aiChatFormAttached = true;
            aiChatForm.on('submit', val => {
                this.aiMessage = val;
                this.submitAiRequest();
            });
            aiChatForm.on('areaValue', val => {
                this.aiMessage = val;
            });
        },
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

"""



Note: The JSON schema must be valid and must not contain any additional comments or explanations.
Note: the font color must aways be in hex format, like #ffffff, and the font size must be in px, like 16px.
Note: the font color must aways be visible in the design, so you must use a color that is visible on the background.
Note: Make sure the background color is also visible and does not conflict with the font color.
Note: Use background clip to make sure the background is visible and does not conflict with the font color.
Note: If color is not specified, use the default color for the element.
Note: If color is applied to a specific element, use the color of that element.
Note: If color is requested by user, make sure to use the color that is visible on the background.
Note: If the user specifies more than one color, use the first color as the primary color and the second color as the secondary color.
Note: If the user specifies more than one color, you must use the first color as the primary color and the second color as the secondary color and use clip background to make sure the background is visible and does not conflict with the font color.
Critical: the font color must never conflict with the background color, so you must use a color that is visible on the background.
Critical: you must never apply color transparent or rgba(0,0,0,0) to the font color or background color.
Critical: If you apply background linear-gradient make sure also to appy background-clip: text; to make sure the background is visible and does not conflict with the font color and font color is visible on the background.
Critical: If never apply color transparent to the font color.
Critical: If the user does not specify font color, but only background, you must use a font color that is visible on the background. If needed, use a contrasting color to ensure visibility.
Critical: If the user specifies new colors for font and background, you must use the first color as the primary color and the second color as the secondary color and use clip background to make sure the background is visible and does not conflict with the font color.




`


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
