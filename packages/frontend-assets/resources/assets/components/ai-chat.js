import MicroweberBaseClass from "../containers/base-class.js";

const AIChatFormCSS= `
     .mw-ai-chat-box-footer{
        position: absolute;
        width: 100%;
        bottom: 0;
        padding: 10px;
        display: flex;
        align-items: center;
        justify-content: space-between;
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
    .mw-ai-chat-box-actions button{
        width: 30px;
        height: 30px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 30px !important;

    }

     .mw-ai-chat-box-area{
        position:relative;
        display: block;
        background: rgb(153 153 153 / 34%);
        border: none;
        border-radius: 20px;

     }

     .mw-ai-chat-box-area:has(textarea:focus){
        box-shadow: var(--tw-ring-offset-shadow), var(--tw-ring-shadow), var(--tw-shadow);
     }

     .mw-ai-chat-box textarea{
        width: 100%;
        resize: none;
        background: transparent;
        border-color: transparent;
        min-height: 65px;
        display: block;
        outline: none !important;
        padding-bottom: 60px;
        border-radius: 20px !important;
      }

`;
const AIChatFormTpl = `
    <div class="mw-ai-chat-box">
        <div class="mw-ai-chat-box-area">
            <textarea placeholder="${mw.lang('Enter topic')}"></textarea>
            <div class="mw-ai-chat-box-footer">
                <div class="mw-ai-chat-box-options">

                </div>
                <div class="mw-ai-chat-box-actions">
                    <button type="button" class="mw-ai-chat-box-action-voice">${mw.top().app.iconService.icon('mic')}</button>
                    <button type="button" class="mw-ai-chat-box-action-send">${mw.top().app.iconService.icon('send')}</button>
                </div>
            </div>
        </div>
     </div>

     <style>

        ${AIChatFormCSS}

     </style>
`;




export class MWSpeechRecognition extends MicroweberBaseClass {
    constructor() {
        super();
        this.init();
        this.events();
    }

    #status = false;

    init() {
        this.recognition = new (window.SpeechRecognition ||
        window.webkitSpeechRecognition ||
        window.mozSpeechRecognition)();
    }

    events () {
        this.recognition.onstart = () => {
            this.dispatch('start');


        };
        this.recognition.onend = () => {
            this.dispatch('end')
            this.#status = false;

        };
        this.recognition.onerror = (err) => {

            this.dispatch('error', err)
        };

        this.recognition.onresult = (event) => {
            const transcript = event.results[0][0].transcript;
            this.dispatch('result', transcript);

        };
    }

    start() {
        this.recognition.start();
        this.#status = true;
    }

    stop() {
        this.recognition.stop();
        this.#status = false;
    }

    toggle() {
        this[this.#status ? 'stop' : 'start']();
    }

}
export class AIChatForm extends MicroweberBaseClass {
    constructor() {
        super();
        this.init();


    }

    rend() {
        const frag = document.createElement('div');
        frag.innerHTML = AIChatFormTpl;
        frag.className = 'mw-ai-chat-form';

        this.form = frag;
        this.area = frag.querySelector('textarea');
        this.micButton = frag.querySelector('.mw-ai-chat-box-action-voice');
        this.sendButton = frag.querySelector('.mw-ai-chat-box-action-send');

        return frag
    }

    #speech() {
        this.speechRecognition = new MWSpeechRecognition();
        this.speechRecognition.on('start', () => {
            this.micButton.classList.add('speaking')
        });
        this.speechRecognition.on('end', () => {
            this.micButton.classList.remove('speaking')
        });
        this.speechRecognition.on('result', result => {
            this.area.value = result;
            this.areaSize();
            this.dispatch('areaValue',  this.area.value);
        });
    }

    areaSize() {
        this.area.style.height = 'auto';
        this.area.style.height = this.area.scrollHeight+'px';
    }
    handleArea() {
        this.area.addEventListener('input', () => {
            this.areaSize();
            this.dispatch('areaValue',  this.area.value);
        });
    }
    handleMic() {
        this.micButton.addEventListener('click', () => {
            this.speechRecognition.toggle();
        });
    }

    handleSubmit() {
        this.sendButton.addEventListener('click', () => {
            this.dispatch('submit',  this.area.value);
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
        this.rend()
        this.#speech()
        this.handleMic()
        this.handleSubmit()
    }
}
