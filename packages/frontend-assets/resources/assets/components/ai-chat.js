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
        color: #111;

    }

     .mw-ai-chat-box-area{
        position:relative;
        display: block;
        background: rgb(205 205 205);
        border: none;
        border-radius: 20px;

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

     .mw-ai-chat-box .mw-ai-chat-box-area-field{
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

      .mw-ai-chat-box-options select selectedcontent{
        white-space: nowrap;
      }
      .mw-ai-chat-box-options select{
        width: 140px;
        height:31px;
        font-size:12px;


      }

`;




const AIChatFormTpl = (multiLine, placeholder, options) => `
    <div class="mw-ai-chat-box">
        <div class="mw-ai-chat-box-area">
            <${multiLine ? 'textarea' : 'input' } class="mw-ai-chat-box-area-field" placeholder="${placeholder || mw.lang('Enter topic')}">${multiLine ? '</textarea>' : ''}
            <div class="mw-ai-chat-box-footer">
                <div class="mw-ai-chat-box-options">
                ${options ? `
                    <select class="mw-native-select" name="chatOptions">
                        <button>
                            <selectedcontent></selectedcontent>
                        </button>
                        <option value="" selected disabled>${mw.lang('Choose action')}</option>
                        ${options.map(o => `<option value="${o.id}" ${o.selected ? 'selected' : ''}>${o.content}</option>`).join('')}
                    </select>`: ''}
                </div>
                <div class="mw-ai-chat-box-actions">
                    <button type="button" class="mw-ai-chat-box-action-voice">${mw.top().app.iconService.icon('mic')}</button>
                    <button type="button" class="mw-ai-chat-box-action-send">${mw.top().app.iconService.icon('send')}</button>
                </div>
            </div>
        </div>
     </div>

     <style>${AIChatFormCSS}</style>
`;




export class MWSpeechRecognition extends MicroweberBaseClass {
    constructor() {
        super();
        this.init();

    }

    #status = false;

    init() {
        this.recognition = new (window.SpeechRecognition ||
        window.webkitSpeechRecognition ||
        window.mozSpeechRecognition)();
        this.events();
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
    constructor(options = []) {
        super();
        const defaults = {
            multiLine: true,
            submitOnEnter: false
        }
        this.settings = Object.assign({}, defaults, options);
        this.init();


    }

    rend() {
        const frag = document.createElement('div');
        frag.innerHTML = AIChatFormTpl(this.settings.multiLine, this.settings.placeholder, this.settings.chatOptions);
        frag.className = 'mw-ai-chat-form';

        this.form = frag;
        this.chatOptionsSelect = this.form.querySelector('[name="chatOptions"]');
        if(this.chatOptionsSelect) {
            this.chatOptionsSelect.addEventListener('input', e => {
                this.dispatch('chatOptionChange', this.chatOptionsSelect.value);
            })
        }
        this.area = frag.querySelector('.mw-ai-chat-box-area-field');
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
        this.area.addEventListener('keypress', (e) => {
            if (e.key === 'Enter' || e.keyCode === 13) {
                if(this.settings.submitOnEnter && !e.shiftKey) {
                    this.dispatch('submit',  this.area.value);
                    e.preventDefault();
                }
            }
        })

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
        if(this.chatOptionsSelect ) {
            this.chatOptionsSelect.disabled = true;
        }
    }

    enable() {
        this.disabled = false;
        this.enabled = true;
        this.area.disabled = false;
        this.micButton.disabled = false;
        this.sendButton.disabled = false;
        if(this.chatOptionsSelect ) {
            this.chatOptionsSelect.disabled = false;
        }
    }

    init() {
        this.rend()
        this.#speech()
        this.handleArea()
        this.handleMic()
        this.handleSubmit()
        this.enable()
    }
}
