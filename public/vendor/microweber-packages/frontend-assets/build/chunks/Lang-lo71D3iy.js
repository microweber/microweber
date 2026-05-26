var k=e=>{throw TypeError(e)};var y=(e,t,i)=>t.has(e)||k("Cannot "+i);var h=(e,t,i)=>(y(e,t,"read from private field"),i?i.call(e):t.get(e)),b=(e,t,i)=>t.has(e)?k("Cannot add the same private member more than once"):t instanceof WeakSet?t.add(e):t.set(e,i),g=(e,t,i,s)=>(y(e,t,"write to private field"),s?s.call(e,i):t.set(e,i),i),_=(e,t,i)=>(y(e,t,"access private method"),i);import{M as S}from"./base-class-C01B1n9o.js";import{a6 as r,o,V as m,ab as c,P as F,a3 as L,a8 as v,n as D,a5 as H,aj as N,F as $,a7 as C,M as V,af as P}from"./vue-runtime-BMAnCFvb.js";const f=(e,t)=>{const i=e.__vccOpts||e;for(const[s,a]of t)i[s]=a;return i},q=`
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

`,R=(e,t,i,s,a)=>{let n="";return Array.isArray(i)&&(n=`
            <div class="mw-ai-chat-box-options">

            </div>
        `),`
    <div class="mw-ai-chat-box" style="display:${a?"":"none"}">
        <div class="mw-ai-chat-box-area">
            <${e?"textarea":"input"} class="mw-ai-chat-box-area-field" placeholder="${t||mw.lang("Enter topic")}">${e?"</textarea>":""}
            <div class="mw-ai-chat-box-footer">
                <div class="mw-ai-chat-box-options">
                ${n}
                </div>
                <div class="mw-ai-chat-box-actions d-flex align-items-center gap-1">
                    <button type="button" class="mw-ai-chat-box-action-voice" aria-label="${mw.lang("Voice input")}" title="${mw.lang("Voice input")}" style="display: ${s?"":"none"}">${mw.top().app.iconService.icon("mic")}</button>


                    </div>
            </div>

            <button type="button" class="mw-ai-chat-box-action-send "> ${mw.lang("Submit")} ${mw.top().app.iconService.icon("send")} </button>
        </div>
     </div>

     <style>${q}</style>
`};var p,u;class A extends S{constructor(){super();b(this,p,!1);b(this,u,window.SpeechRecognition||window.webkitSpeechRecognition||window.mozSpeechRecognition);this.init()}isSupported(){return!!h(this,u)}init(){h(this,u)&&(this.recognition=new(h(this,u)),this.events())}events(){this.recognition.onstart=()=>{this.dispatch("start")},this.recognition.onend=()=>{this.dispatch("end"),g(this,p,!1)},this.recognition.onerror=i=>{this.dispatch("error",i)},this.recognition.onresult=i=>{const s=i.results[0][0].transcript;this.dispatch("result",s)}}start(){h(this,u)&&(this.recognition.start(),g(this,p,!0))}stop(){h(this,u)&&(this.recognition.stop(),g(this,p,!1))}toggle(){this[h(this,p)?"stop":"start"]()}}p=new WeakMap,u=new WeakMap;var w,B;class _e extends S{constructor(i=[]){super();b(this,w);const s={multiLine:!0,submitOnEnter:!1};this.settings=Object.assign({},s,i),this.init()}rend(){const i=document.createElement("div"),s=!!mw.top().win.MwAi;i.innerHTML=R(this.settings.multiLine,this.settings.placeholder,this.settings.chatOptions,this.speechRecognition.isSupported(),s);const a=i.querySelector("button.mw-ai-chat-box-action-send");if(a.disabled=!0,i.querySelector(".mw-ai-chat-box-area-field").addEventListener("input",n=>{a.disabled=!n.target.value.trim()}),i.className="mw-ai-chat-form",this.form=i,this.settings.chatOptions&&this.settings.chatOptions.length){const n=this.form.querySelector(".mw-ai-chat-box-options");mw.select({element:n,data:this.settings.chatOptions}).on("change",d=>{const M=d[0]&&d[0].id;this.dispatch("chatOptionChange",M)})}return this.area=i.querySelector(".mw-ai-chat-box-area-field"),this.micButton=i.querySelector(".mw-ai-chat-box-action-voice"),this.sendButton=i.querySelector(".mw-ai-chat-box-action-send"),i}areaSize(){this.area.style.height="auto",this.area.style.height=this.area.scrollHeight+"px"}handleArea(){this.area.addEventListener("keypress",i=>{(i.key==="Enter"||i.keyCode===13)&&this.settings.submitOnEnter&&!i.shiftKey&&(this.dispatch("submit",this.area.value),i.preventDefault())}),this.area.addEventListener("input",()=>{this.areaSize(),this.dispatch("areaValue",this.area.value)})}handleMic(){this.micButton.addEventListener("click",()=>{this.speechRecognition.toggle()})}handleSubmit(){this.sendButton.addEventListener("click",()=>{this.dispatch("submit",this.area.value)})}disable(){this.disabled=!0,this.enabled=!1,this.area.disabled=!0,this.micButton.disabled=!0,this.sendButton.disabled=!0}enable(){this.disabled=!1,this.enabled=!0,this.area.disabled=!1,this.micButton.disabled=!1,this.sendButton.disabled=!1}init(){_(this,w,B).call(this),this.rend(),this.handleArea(),this.handleMic(),this.handleSubmit(),this.enable()}}w=new WeakSet,B=function(){this.speechRecognition=new A,this.speechRecognition.on("start",()=>{this.micButton.classList.add("speaking")}),this.speechRecognition.on("end",()=>{this.micButton.classList.remove("speaking")}),this.speechRecognition.on("result",i=>{this.area.value=i,this.areaSize(),this.dispatch("areaValue",this.area.value)})};const j={emits:["change"],props:{label:{type:String,default:"Color"},color:{type:String,default:"#ffffff"}},data(){var e=this.color||"#ffffff",t=this.getHexColorDisplayValueText(e);return{selectedColor:e,selectedColorHex:t}},watch:{color(e){this.selectedColor=e,this.setHexColorDisplay(e)}},mounted(){this.$nextTick(()=>{this.setHexColorDisplay(this.color)})},methods:{getHexColorDisplayValueText(e){if(!e)return"transparent";if(e=="revert-layer"||e=="none"||e=="currentColor")return"";if(e=="rgb(0 0 0 / 0%)")return"transparent";if(e.includes("rgb")||e.includes("rgba")){var t=mw.color.rgbOrRgbaToHex(e);return t=="#00000000"?"":t}return e},setHexColorDisplay(e){this.$refs.colorPickerButton&&(!e||e==="transparent"||e===""?this.$refs.colorPickerButton.style.backgroundColor="transparent":this.$refs.colorPickerButton.style.backgroundColor=e),this.selectedColorHex=this.getHexColorDisplayValueText(e)},handleColorChange(e){const t=e.target.value;this.setNewColor(t)},setNewColor(e){this.selectedColor=e,this.setHexColorDisplay(e),this.$emit("change",e)},resetColor(){this.selectedColor="",this.setHexColorDisplay(""),this.$emit("change",this.selectedColor)},togglePicker(){let e=this.$refs.colorPickerButton;mw.app.colorPicker.openColorPicker(this.selectedColor,t=>{this.setNewColor(t)},e)}}},I={class:"form-control-live-edit-label-wrapper my-4"},z={class:"d-flex justify-content-between align-items-center"},U={class:"live-edit-label"};function Z(e,t,i,s,a,n){return c(),r("div",I,[o("div",z,[o("div",null,[o("label",U,m(i.label),1)]),o("div",null,[o("button",{onClick:t[0]||(t[0]=(...l)=>n.togglePicker&&n.togglePicker(...l)),ref:"colorPickerButton",class:"picker-button",type:"button"},"Pick color",512)])])])}const Ve=f(j,[["render",Z],["__scopeId","data-v-bb29582f"]]),K={props:{showLabel:{type:Boolean,default:!0},label:String,modelValue:Number,min:Number,max:Number,step:Number,unit:String},data(){return{selectedValue:Number.isFinite(this.modelValue)?this.modelValue:null}},methods:{resetValue(){this.selectedValue=null},validateValue(){if(typeof this.selectedValue=="number"&&!Number.isFinite(this.selectedValue)){this.selectedValue=null;return}this.selectedValue!==null&&this.selectedValue!==void 0&&(this.min!==void 0&&this.selectedValue<this.min&&(this.selectedValue=this.min),this.max!==void 0&&this.selectedValue>this.max&&(this.selectedValue=this.max))}},watch:{selectedValue(e){e!==this.modelValue&&this.$emit("update:modelValue",e)},modelValue(e){this.selectedValue=Number.isFinite(e)?e:null}}},Q={class:"mw-live-edit-slider-small form-control-live-edit-label-wrapper"},W={key:0,class:"d-flex justify-content-between align-items-center gap-2 slider-small-header"},G={class:"live-edit-label mb-0"},J={class:"d-flex align-items-center gap-1 slider-small-controls"},X=["min","max","step","aria-label"],Y={key:0,class:"slider-small-unit"},ee={"data-size":"medium",class:"slider-small-track-row"};function te(e,t,i,s,a,n){const l=H("v-slider");return c(),r("div",Q,[i.showLabel?(c(),r("div",W,[o("label",G,m(i.label),1),o("div",J,[F(o("input",{type:"number",class:"form-control-input-range-slider","onUpdate:modelValue":t[0]||(t[0]=d=>a.selectedValue=d),min:i.min,max:i.max,step:i.step,onBlur:t[1]||(t[1]=(...d)=>n.validateValue&&n.validateValue(...d)),"aria-label":i.label},null,40,X),[[L,a.selectedValue,void 0,{number:!0}]]),i.unit?(c(),r("span",Y,m(i.unit),1)):v("",!0),o("button",{onClick:t[2]||(t[2]=(...d)=>n.resetValue&&n.resetValue(...d)),type:"button",class:"reset-field-btn",title:"Restore default value","aria-label":"Restore default value"},[...t[4]||(t[4]=[o("svg",{xmlns:"http://www.w3.org/2000/svg",fill:"currentColor",height:"14",viewBox:"0 -960 960 960",width:"14","aria-hidden":"true"},[o("path",{d:"M440-122q-121-15-200.5-105.5T160-440q0-66 26-126.5T260-672l57 57q-38 34-57.5 79T240-440q0 88 56 155.5T440-202v80Zm80 0v-80q87-16 143.5-83T720-440q0-100-70-170t-170-70h-3l44 44-56 56-140-140 140-140 56 56-44 44h3q134 0 227 93t93 227q0 121-79.5 211.5T520-122Z"})],-1)])])])])):v("",!0),o("div",ee,[D(l,{min:i.min,max:i.max,step:i.step,modelValue:a.selectedValue,"onUpdate:modelValue":t[3]||(t[3]=d=>a.selectedValue=d)},null,8,["min","max","step","modelValue"])])])}const Se=f(K,[["render",te],["__scopeId","data-v-8276f0e8"]]),ie={class:"form-control-live-edit-label-wrapper my-4"},se={class:"d-flex justify-content-between align-items-center gap-2"},ae=["innerHTML"],ne=["selected","value"],oe={props:{modelValue:String,label:String,options:Array},data(){return{selectedOption:this.modelValue}},watch:{modelValue(e){this.selectedOption!==e&&(this.selectedOption=e)}},methods:{handleInput(){this.selectedOption!==this.modelValue&&this.$emit("update:modelValue",this.selectedOption)}}},le=Object.assign(oe,{__name:"DropdownSmall",emits:["update:modelValue"],setup(e){return(t,i)=>(c(),r("div",ie,[o("div",se,[e.label?(c(),r("label",{key:0,class:"live-edit-label mb-0",innerHTML:e.label},null,8,ae)):v("",!0),F(o("select",{"onUpdate:modelValue":i[0]||(i[0]=s=>t.selectedOption=s),class:"form-control-live-edit-input form-select dropdown-small-select",onInput:i[1]||(i[1]=s=>t.$emit("update:modelValue",s.target.value))},[(c(!0),r($,null,C(e.options,s=>(c(),r("option",{selected:t.selectedOption===s.key,value:s.key},m(s.value),9,ne))),256))],544),[[N,t.selectedOption]])])]))}}),Fe=f(le,[["__scopeId","data-v-437b7bc8"]]),re={inheritAttrs:!1,props:{value:String},watch:{value(e){typeof e=="string"&&(this.fontFamily=e,this.updateSelectElement())}},methods:{loadMoreFonts(){mw.top().app.fontManager.manageFonts()},isInSupportedFonts(e){return this.supportedFonts.includes(e)},updateSelectElement(){this.$nextTick(()=>{this.$refs.fontSelect&&(this.fontFamily&&typeof this.fontFamily=="string"?this.$refs.fontSelect.value=this.fontFamily:this.$refs.fontSelect.value="")})},handleFontChange(e){e.preventDefault();const t=e.target.value;typeof t=="string"&&(this.fontFamily=t,this.$emit("change",t),this.$emit("input",t),this.$forceUpdate())}},created(){typeof this.value=="string"?this.fontFamily=this.value:this.fontFamily=""},mounted(){setTimeout(()=>{this.supportedFonts=mw.top().app.fontManager.getFonts(),this.updateSelectElement(),mw.top().app.fontManager.subscribe(e=>{e&&(this.supportedFonts=e,this.updateSelectElement())})},1e3)},updated(){this.updateSelectElement()},data(){return{supportedFonts:[],fontFamily:""}}},ce={class:"form-control-live-edit-label-wrapper my-4",modelvalue:null},de={class:"d-flex justify-content-between align-items-center gap-2"},ue=["value"],he=["value"],pe=["value","selected"];function me(e,t,i,s,a,n){return c(),r("div",ce,[o("div",de,[t[4]||(t[4]=o("label",{class:"live-edit-label mb-0"},"Font",-1)),o("select",{class:"form-control-live-edit-input form-select dropdown-small-select font-picker-select",onChange:t[0]||(t[0]=(...l)=>n.handleFontChange&&n.handleFontChange(...l)),value:a.fontFamily,ref:"fontSelect"},[t[2]||(t[2]=o("option",{value:""},"Default",-1)),t[3]||(t[3]=o("option",{value:"inherit"},"Inherit",-1)),a.fontFamily&&!n.isInSupportedFonts(a.fontFamily)&&a.fontFamily!==""&&a.fontFamily!=="inherit"?(c(),r("option",{key:0,value:a.fontFamily,style:V({fontFamily:`${a.fontFamily}`})},m(a.fontFamily),13,he)):v("",!0),(c(!0),r($,null,C(a.supportedFonts,(l,d)=>(c(),r("option",{key:d,value:l,selected:a.fontFamily===l,style:V({fontFamily:`${l}`})},m(l),13,pe))),128))],40,ue)]),o("small",{class:"cursor-pointer d-flex ms-auto justify-content-end pt-2 pb-1 font-picker-add-more",onClick:t[1]||(t[1]=l=>n.loadMoreFonts())},"Add more fonts")])}const $e=f(re,[["render",me],["__scopeId","data-v-27d4f2bd"]]),T=function(){return document.ontouchstart!==null?"click":"touchstart"},x="__vue_click_away__",O=function(e,t,i){E(e);let s=i.context,a=t.value,n=!1;setTimeout(function(){n=!0},0),e[x]=function(l){if((!e||!e.contains(l.target))&&a&&n&&typeof a=="function")return a.call(s,l)},document.addEventListener(T(),e[x],!1)},E=function(e){document.removeEventListener(T(),e[x],!1),delete e[x]},fe=function(e,t,i){t.value!==t.oldValue&&O(e,t,i)},Ce={install:function(e){e.directive("click-away",be)}},be={mounted:O,updated:fe,unmounted:E};function Be(e){return{all:e=e||new Map,on:function(t,i){var s=e.get(t);s?s.push(i):e.set(t,[i])},off:function(t,i){var s=e.get(t);s&&(i?s.splice(s.indexOf(i)>>>0,1):e.set(t,[]))},emit:function(t,i){var s=e.get(t);s&&s.slice().map(function(a){a(i)}),(s=e.get("*"))&&s.slice().map(function(a){a(t,i)})}}}const Te={install:(e,t)=>{e.config.globalProperties.$lang=i=>mw.lang(i)}},ge={mounted(){const e=this.$refs.lang;e&&(e.textContent=mw.lang(this.$slots.default()[0].children))}},ve={ref:"lang"};function xe(e,t,i,s,a,n){return c(),r("span",ve,[P(e.$slots,"default")],512)}const Oe=f(ge,[["render",xe]]);export{_e as A,Ve as C,Fe as D,$e as F,Oe as L,Se as S,f as _,Te as i,Be as m,Ce as p};
