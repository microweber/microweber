import MicroweberBaseClass from "../containers/base-class.js";

const AIChatFormCSS = `
     .mw-ai-chat-box-footer{

        width: 100%;
        bottom: 15px;
        padding: 10px;
        display: flex;
        align-items: center;
        justify-content: space-between;

        border-bottom:1px solid  #45454524;
        border-top: 1px solid #45454524;

     }
        html.dark .mw-ai-chat-box-footer{



            border-bottom: 1px solid #eeeeee24;
            border-top: 1px solid #eeeeee21;
        }
     .mw-ai-chat-box-footer svg{
        width: 22px;
        margin: 0 5px;
     }
    .mw-ai-chat-box-actions button.speaking{

        background: linear-gradient(-45deg,rgba(0, 68, 194, 0.32),rgba(0, 60, 255, 0.3),rgba(35, 165, 213, 0.27),rgba(35, 213, 171, 0.29));
        animation: speaking 2s ease infinite;
    }
    @keyframes speaking {

        0% {
            box-shadow: 0 0 0 0px rgba(0, 0, 0, 0.2);
        }
        100% {
            box-shadow: 0 0 0 20px rgba(0, 0, 0, 0);
        }
    }
    .mw-ai-chat-box-actions button:not(.speaking):hover{
    background: #eeeeee3d;
    }
    /* task-2026-05-27-df6d28 / AI-1175: 30px → 44px for WCAG 2.5.5 */
    .mw-ai-chat-box-actions button{
        width: 44px;
        height: 44px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 44px !important;
        color: #111;

    }

     .mw-ai-chat-box-area{
        position:relative;
        display: block;
        background: rgb(205 205 205);
        border: none;
        border-radius: 20px;
        padding-bottom: 1px;
     }

    html.dark .mw-ai-chat-box-area textareat::placeholder {
        color: white;
        opacity: 0.5;
    }
    html.dark .mw-ai-chat-box-area{

        background: rgba(var(--gray-700), var(--tw-bg-opacity, 1));


     }

     .mw-ai-chat-box-area:has(.mw-ai-chat-box-area-field:focus){
        box-shadow: var(--tw-ring-offset-shadow), var(--tw-ring-shadow), var(--tw-shadow);
     }


      html.dark .mw-ai-chat-box .mw-ai-chat-box-area-field{

        color: white

      }

      .mw-ai-chat-box-options select selectedcontent{
        white-space: nowrap;
      }
      .mw-ai-chat-box-options button.active{
        background: white;
        color: #111;
      }
      .mw-ai-chat-box-options button{

    width: 35px;
    height: 35px;
    text-align: center;
   align-items:center;
    display: inline-flex;
    vertical-align: middle;
    margin-inline-end: 10px;
        svg{
            width: 25px;
        }
      }

      .mw-ai-chat-box-options select{
        width: 140px;
        height:31px;
        font-size:12px;


      }

      .mw-ai-chat-box-action-send[disabled]{
        opacity: .5;
        pointer-events: none
      }
      .mw-ai-chat-box-action-send{
            display: flex;
            width: 92%;
            height: 40px;
            overflow: hidden;
            background: #003da4;
            margin: 14px 4%;

            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: bold;
            justify-content: center;
            align-items: center;
            gap: 8px;
            color: white;
            svg{
                width: 24px;
            }
      }

`;

const AIChatFormTpl = (multiLine, placeholder, options, speech, hasChat) => {
    let optionsTpl = "";

    if (Array.isArray(options)) {
        optionsTpl = `
            <div class="mw-ai-chat-box-options">

            </div>
        `;
    }

    const tpl = `
    <div class="mw-ai-chat-box" style="display:${hasChat ? "" : "none"}">
        <div class="mw-ai-chat-box-area">
            <${
                multiLine ? "textarea" : "input"
            } class="mw-ai-chat-box-area-field" placeholder="${
        placeholder || mw.lang("Enter topic")
    }">${multiLine ? "</textarea>" : ""}
            <div class="mw-ai-chat-box-footer">
                <div class="mw-ai-chat-box-options">
                ${optionsTpl}
                </div>
                <div class="mw-ai-chat-box-actions d-flex align-items-center gap-1">
                    <button type="button" class="mw-ai-chat-box-action-voice" aria-label="${mw.lang(
                        "Voice input"
                    )}" title="${mw.lang(
                        "Voice input"
                    )}" style="display: ${
                        speech ? "" : "none"
                    }">${mw.top().app.iconService.icon("mic")}</button>


                    </div>
            </div>

            <button type="button" class="mw-ai-chat-box-action-send "> ${mw.lang(
                "Submit"
            )} ${mw.top().app.iconService.icon("send")} </button>
        </div>
     </div>

     <style>${AIChatFormCSS}</style>
`;
    return tpl;
};

export class MWSpeechRecognition extends MicroweberBaseClass {
    constructor() {
        super();
        this.init();
    }

    #status = false;
    #recognition =
        window.SpeechRecognition ||
        window.webkitSpeechRecognition ||
        window.mozSpeechRecognition;

    isSupported() {
        return !!this.#recognition;
    }

    init() {
        if (this.#recognition) {
            this.recognition = new this.#recognition();
            this.events();
        }
    }

    events() {
        this.recognition.onstart = () => {
            this.dispatch("start");
        };
        this.recognition.onend = () => {
            this.dispatch("end");
            this.#status = false;
        };
        this.recognition.onerror = (err) => {
            this.dispatch("error", err);
        };

        this.recognition.onresult = (event) => {
            const transcript = event.results[0][0].transcript;
            this.dispatch("result", transcript);
        };
    }

    start() {
        if (this.#recognition) {
            this.recognition.start();
            this.#status = true;
        }
    }

    stop() {
        if (this.#recognition) {
            this.recognition.stop();
            this.#status = false;
        }
    }

    toggle() {
        this[this.#status ? "stop" : "start"]();
    }
}
export class AIChatForm extends MicroweberBaseClass {
    constructor(options = []) {
        super();
        const defaults = {
            multiLine: true,
            submitOnEnter: false,
        };
        this.settings = Object.assign({}, defaults, options);
        this.init();
    }

    rend() {
        const frag = document.createElement("div");
        const hasChat = !!mw.top().win.MwAi;
        frag.innerHTML = AIChatFormTpl(
            this.settings.multiLine,
            this.settings.placeholder,
            this.settings.chatOptions,
            this.speechRecognition.isSupported(),
            hasChat
        );

        const btn = frag.querySelector("button.mw-ai-chat-box-action-send");
        btn.disabled = true;

        frag.querySelector(".mw-ai-chat-box-area-field").addEventListener(
            "input",
            (event) => {
                btn.disabled = !event.target.value.trim();
            }
        );

        frag.className = "mw-ai-chat-form";

        this.form = frag;

        if (this.settings.chatOptions && this.settings.chatOptions.length) {
            const btnOptionsBlock = this.form.querySelector(
                ".mw-ai-chat-box-options"
            );

            const dropdown = mw.select({
                element: btnOptionsBlock,
                data: this.settings.chatOptions,
            });

            dropdown.on("change", (val) => {
                const action = val[0] && val[0].id;

                this.dispatch("chatOptionChange", action);
            });
        }

        this.area = frag.querySelector(".mw-ai-chat-box-area-field");
        this.micButton = frag.querySelector(".mw-ai-chat-box-action-voice");
        this.sendButton = frag.querySelector(".mw-ai-chat-box-action-send");

        return frag;
    }

    #speech() {
        this.speechRecognition = new MWSpeechRecognition();
        this.speechRecognition.on("start", () => {
            this.micButton.classList.add("speaking");
        });
        this.speechRecognition.on("end", () => {
            this.micButton.classList.remove("speaking");
        });
        this.speechRecognition.on("result", (result) => {
            this.area.value = result;
            this.areaSize();
            this.dispatch("areaValue", this.area.value);
        });
    }

    areaSize() {
        this.area.style.height = "auto";
        this.area.style.height = this.area.scrollHeight + "px";
    }
    handleArea() {
        this.area.addEventListener("keypress", (e) => {
            if (e.key === "Enter" || e.keyCode === 13) {
                if (this.settings.submitOnEnter && !e.shiftKey) {
                    this.dispatch("submit", this.area.value);
                    e.preventDefault();
                }
            }
        });

        this.area.addEventListener("input", () => {
            this.areaSize();
            this.dispatch("areaValue", this.area.value);
        });
    }
    handleMic() {
        this.micButton.addEventListener("click", () => {
            this.speechRecognition.toggle();
        });
    }

    handleSubmit() {
        this.sendButton.addEventListener("click", () => {
            this.dispatch("submit", this.area.value);
        });
    }

    disable() {
        this.disabled = true;
        this.enabled = false;
        this.area.disabled = true;
        this.micButton.disabled = true;
        this.sendButton.disabled = true;
    }

    enable() {
        this.disabled = false;
        this.enabled = true;
        this.area.disabled = false;
        this.micButton.disabled = false;
        this.sendButton.disabled = false;
    }

    init() {
        this.#speech();
        this.rend();

        this.handleArea();
        this.handleMic();
        this.handleSubmit();
        this.enable();
    }
}
