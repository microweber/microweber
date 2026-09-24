import MicroweberBaseClass from "../api-core/services/containers/base-class.js";
import html2canvas from "html2canvas";

/**
 * Live-Edit AI conversation panel — a Claude-style chat that edits the live site.
 *
 * The user talks to the Live-Edit agent (NeuronAI + local Ollama/Kimi) like a
 * design collaborator ("make the headings blue", "rewrite this title"). Each turn
 * streams over Server-Sent Events (MwAi().agentChatStream): the agent's tool calls
 * arrive as they happen and are applied to the real canvas in real time (apply_css,
 * set_text, set_image), so the change is visible immediately and persists through
 * the normal Live-Edit SAVE button. Conversations are backed by history — the
 * drawer lists past chats and reloads them (/api/ai/user-chats + chat-history/{id}).
 */

const CONV_CSS = `
.mw-ai-conv{
    display:flex; flex-direction:column; height:100%; min-height:420px;
    font-family:-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,Helvetica,Arial,sans-serif;
    font-size:14px; line-height:1.5; color:#182433; letter-spacing:-.006em;
}
html.dark .mw-ai-conv{ color:#e8eaed; }

.mw-ai-conv-head{
    display:flex; align-items:center; justify-content:space-between;
    padding:0 0 12px; gap:8px;
}
.mw-ai-conv-title{ font-weight:600; font-size:13px; display:flex; align-items:center; gap:8px; letter-spacing:-.01em; }
.mw-ai-conv-title svg{ width:17px; height:17px; opacity:.85; }
.mw-ai-conv-head-actions{ display:flex; gap:2px; }
.mw-ai-conv-iconbtn{
    width:36px; height:36px; min-width:36px; border-radius:9px; border:none;
    display:inline-flex; align-items:center; justify-content:center; cursor:pointer;
    background:transparent; color:inherit;
    transition: background-color .15s ease, color .15s ease;
}
.mw-ai-conv-iconbtn:hover{ background:#18243310; }
html.dark .mw-ai-conv-iconbtn:hover{ background:#ffffff14; }
.mw-ai-conv-iconbtn svg{ width:18px; height:18px; opacity:.72; }

.mw-ai-conv-thread{
    flex:1 1 auto; overflow-y:auto; padding:4px 2px; display:flex; flex-direction:column; gap:18px;
}
.mw-ai-conv-msg{ display:flex; flex-direction:column; gap:6px; max-width:100%; }
.mw-ai-conv-msg-bubble{
    line-height:1.55; white-space:pre-wrap; word-wrap:break-word;
}
/* User turns: a soft ink pill, right-aligned. */
.mw-ai-conv-msg.user{ align-items:flex-end; }
.mw-ai-conv-msg.user .mw-ai-conv-msg-bubble{
    background:#182433; color:#fff; border-radius:16px 16px 5px 16px;
    padding:9px 13px; max-width:86%;
}
html.dark .mw-ai-conv-msg.user .mw-ai-conv-msg-bubble{ background:#39424f; color:#fff; }
/* Assistant turns: plain text, no bubble (modern agent-UI style). */
.mw-ai-conv-msg.assistant .mw-ai-conv-msg-bubble{
    background:transparent; color:inherit; padding:0; max-width:100%;
}

/* Messages queued while the AI is thinking — muted, right-aligned pills. */
.mw-ai-conv-queue{ display:flex; flex-direction:column; gap:6px; align-items:flex-end; }
.mw-ai-conv-queued{
    align-self:flex-end; max-width:86%; padding:8px 12px; border-radius:14px;
    background:#18243310; color:#182433; opacity:.7; font-size:13px; line-height:1.4;
    border:1px dashed #18243333; white-space:pre-wrap; word-wrap:break-word;
}
html.dark .mw-ai-conv-queued{ background:#ffffff12; color:#e8eaed; border-color:#ffffff2e; }

.mw-ai-conv-edits{ display:flex; flex-direction:column; gap:5px; }
.mw-ai-conv-edit{
    display:inline-flex; align-items:center; gap:7px; align-self:flex-start;
    padding:5px 11px; border-radius:999px; font-size:12px; font-weight:500;
    background:#18243309; color:#182433; border:1px solid #18243314;
}
html.dark .mw-ai-conv-edit{ background:#ffffff0d; color:#dfe3e8; border-color:#ffffff1f; }
.mw-ai-conv-edit.err{ background:#e6394612; color:#c62030; border-color:#e6394630; }
.mw-ai-conv-edit svg{ width:14px; height:14px; }
.mw-ai-conv-edit code{ font-size:12px; opacity:.85; }
.mw-ai-conv-editwrap{ display:flex; flex-direction:column; gap:4px; align-self:flex-start; max-width:100%; }
.mw-ai-conv-edit{ cursor:pointer; font-family:inherit; text-align:left; }
.mw-ai-conv-caret{ margin-left:4px; font-size:10px; opacity:.6; }
.mw-ai-conv-edit-details{
    margin:0; padding:9px 11px; border-radius:9px; background:#0d1526; color:#cbd5e1;
    font-size:11.5px; line-height:1.45; max-height:220px; overflow:auto; white-space:pre-wrap;
    word-break:break-word; border:1px solid #ffffff14;
}
html:not(.dark) .mw-ai-conv-edit-details{ background:#111827; color:#e5e7eb; }

/* offer_choices — the AI presents options as clickable pills. */
.mw-ai-conv-choices{ display:flex; flex-direction:column; gap:8px; align-self:stretch; }
.mw-ai-conv-choices-prompt{ font-size:13px; line-height:1.45; color:#182433; }
html.dark .mw-ai-conv-choices-prompt{ color:#e8eaed; }
.mw-ai-conv-choices-pills{ display:flex; flex-wrap:wrap; gap:7px; }
.mw-ai-conv-choice{
    padding:8px 13px; border-radius:999px; border:1px solid #18243322; background:#18243308;
    color:inherit; cursor:pointer; font-size:12.5px; font-weight:500; text-align:left; font-family:inherit; line-height:1.25;
    transition: background-color .15s ease, border-color .15s ease, color .15s ease, transform .1s ease;
}
.mw-ai-conv-choice:hover{ background:#0d6efd; border-color:#0d6efd; color:#fff; }
.mw-ai-conv-choice:active{ transform:scale(.97); }
.mw-ai-conv-choice.picked{ background:#0d6efd; border-color:#0d6efd; color:#fff; }
.mw-ai-conv-choice:disabled{ opacity:.5; pointer-events:none; }
html.dark .mw-ai-conv-choice{ background:#ffffff0d; border-color:#ffffff26; }
html.dark .mw-ai-conv-choice:hover{ background:#0d6efd; border-color:#0d6efd; color:#fff; }

.mw-ai-conv-empty{
    margin:auto; text-align:center; color:#8a94a3; padding:20px 12px; max-width:280px;
}
.mw-ai-conv-empty svg{
    width:26px; height:26px; margin-bottom:14px; opacity:.9; padding:9px;
    box-sizing:content-box; border-radius:50%; background:#18243308; color:#182433;
}
html.dark .mw-ai-conv-empty svg{ background:#ffffff12; color:#e8eaed; }
.mw-ai-conv-empty-title{ font-size:14px; font-weight:600; color:#182433; margin-bottom:4px; }
html.dark .mw-ai-conv-empty-title{ color:#e8eaed; }
/* Suggestions read as a tidy stacked list of clickable prompts. */
.mw-ai-conv-suggest{ display:flex; flex-direction:column; gap:7px; margin-top:18px; text-align:left; }
.mw-ai-conv-suggest button{
    padding:9px 13px; border-radius:11px; border:1px solid #18243314; background:#18243305;
    color:inherit; cursor:pointer; font-size:12.5px; text-align:left; line-height:1.3;
    transition: background-color .15s ease, border-color .15s ease;
}
.mw-ai-conv-suggest button:hover{ background:#1824330d; border-color:#18243226; }
html.dark .mw-ai-conv-suggest button{ background:#ffffff08; border-color:#ffffff1a; }
html.dark .mw-ai-conv-suggest button:hover{ background:#ffffff14; border-color:#ffffff2e; }

.mw-ai-conv-typing{ display:inline-flex; gap:4px; padding:12px 14px; }
.mw-ai-conv-typing span{
    width:7px; height:7px; border-radius:50%; background:#9aa3af; animation:mwAiBlink 1.2s infinite both;
}
.mw-ai-conv-typing span:nth-child(2){ animation-delay:.2s; }
.mw-ai-conv-typing span:nth-child(3){ animation-delay:.4s; }
@keyframes mwAiBlink{ 0%,80%,100%{opacity:.25} 40%{opacity:1} }

/* Unified composer: one rounded field that holds the attach button, the
   auto-growing textarea and the circular send button — focus lights the whole
   box (modern chat-composer pattern). */
.mw-ai-conv-form{
    display:flex; align-items:flex-end; gap:4px; margin-top:10px;
    padding:5px 5px 5px 8px;
    border:1px solid #1824331f; border-radius:16px; background:#fff;
    box-shadow:0 1px 2px rgba(24,36,51,.05);
    transition: border-color .15s ease, box-shadow .15s ease;
}
html.dark .mw-ai-conv-form{ background:#22262c; border-color:#ffffff1f; box-shadow:none; }
.mw-ai-conv-form:focus-within{ border-color:#182433; box-shadow:0 0 0 3px #18243310; }
html.dark .mw-ai-conv-form:focus-within{ border-color:#8a94a3; box-shadow:0 0 0 3px #ffffff14; }
.mw-ai-conv-input{
    flex:1 1 auto; resize:none; border:none; background:transparent;
    padding:8px 2px; font-size:14px; line-height:1.45; max-height:140px; min-height:26px;
    color:#182433; font-family:inherit; outline:none;
    overflow-y:auto; scrollbar-width:thin; scrollbar-color:#18243330 transparent;
}
html.dark .mw-ai-conv-input{ color:#e8eaed; }
.mw-ai-conv-input:focus{ outline:none; box-shadow:none; }
.mw-ai-conv-input::placeholder{ color:#9aa3af; }
/* Thin scrollbar with NO up/down arrow buttons (those made the input look
   unfinished on webkit when the placeholder/text overflowed). */
.mw-ai-conv-input::-webkit-scrollbar,
.mw-ai-conv-thread::-webkit-scrollbar{ width:8px; height:8px; }
.mw-ai-conv-input::-webkit-scrollbar-button,
.mw-ai-conv-thread::-webkit-scrollbar-button{ display:none; width:0; height:0; }
.mw-ai-conv-input::-webkit-scrollbar-track,
.mw-ai-conv-thread::-webkit-scrollbar-track{ background:transparent; }
.mw-ai-conv-input::-webkit-scrollbar-thumb,
.mw-ai-conv-thread::-webkit-scrollbar-thumb{ background:#18243326; border-radius:8px; }
html.dark .mw-ai-conv-input::-webkit-scrollbar-thumb,
html.dark .mw-ai-conv-thread::-webkit-scrollbar-thumb{ background:#ffffff2a; }
.mw-ai-conv-send{
    width:34px; height:34px; min-width:34px; border-radius:50%; border:none; cursor:pointer;
    background:#182433; color:#fff; display:inline-flex; align-items:center; justify-content:center;
    transition: background-color .15s ease, transform .1s ease, opacity .15s ease;
}
.mw-ai-conv-send:hover{ background:#26364a; }
.mw-ai-conv-send:active{ transform:scale(.94); }
.mw-ai-conv-send:disabled{ opacity:.4; pointer-events:none; }
.mw-ai-conv-send svg{ width:17px; height:17px; }
html.dark .mw-ai-conv-send{ background:#e8eaed; color:#182433; }
html.dark .mw-ai-conv-send:hover{ background:#fff; }

.mw-ai-conv-history{
    position:absolute; inset:0; background:inherit; z-index:5; display:none;
    flex-direction:column; padding:2px; background:#fff;
}
html.dark .mw-ai-conv-history{ background:#1b1e22; }
.mw-ai-conv-history.open{ display:flex; }
.mw-ai-conv-history-item{
    padding:11px 12px; border-radius:10px; cursor:pointer; display:flex; flex-direction:column; gap:2px;
    transition: background-color .15s ease, border-color .15s ease, box-shadow .15s ease, color .15s ease;
}
.mw-ai-conv-history-item:hover{ background:#1824330d; }
html.dark .mw-ai-conv-history-item:hover{ background:#ffffff12; }
.mw-ai-conv-history-item.active{ background:#0a63c814; box-shadow:inset 3px 0 0 #0a63c8; }
.mw-ai-conv-history-item .t{ font-weight:500; display:flex; align-items:center; gap:6px; }
.mw-ai-conv-history-item .t .badge{ font-style:normal; font-size:10px; font-weight:600; text-transform:uppercase; letter-spacing:.04em; color:#0a63c8; background:#0a63c81f; border-radius:5px; padding:1px 6px; }
.mw-ai-conv-history-item .s{ font-size:12px; color:#8a94a3; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; }
.mw-ai-conv-history-list{ overflow-y:auto; flex:1; }

.mw-ai-conv-attachments{ display:flex; flex-wrap:wrap; gap:8px; padding:6px 4px 0; }
.mw-ai-conv-attachments:empty{ display:none; }
.mw-ai-conv-thumb{ position:relative; width:60px; height:60px; border-radius:8px; overflow:hidden; border:1px solid #18243326; }
.mw-ai-conv-thumb img{ width:100%; height:100%; object-fit:cover; }
.mw-ai-conv-thumb button{
    position:absolute; top:-6px; right:-6px; width:20px; height:20px; border-radius:50%;
    border:none; background:#182433; color:#fff; cursor:pointer; font-size:12px; line-height:1;
    display:flex; align-items:center; justify-content:center;
}
.mw-ai-conv-attach-btn{
    width:34px; height:34px; min-width:34px; border:none; background:transparent; cursor:pointer;
    color:#9aa3af; display:inline-flex; align-items:center; justify-content:center; border-radius:9px;
    transition: background-color .15s ease, color .15s ease;
}
.mw-ai-conv-attach-btn:hover{ background:#18243310; color:inherit; }
.mw-ai-conv-attach-btn svg{ width:19px; height:19px; }
.mw-ai-conv-msg-images{ display:flex; flex-wrap:wrap; gap:6px; }
.mw-ai-conv-msg-images img{ max-width:180px; max-height:140px; border-radius:10px; border:1px solid #18243322; }

/* Rendered markdown inside assistant bubbles */
.mw-ai-md > *:first-child{ margin-top:0; }
.mw-ai-md > *:last-child{ margin-bottom:0; }
.mw-ai-md p{ margin:0 0 8px; }
.mw-ai-md h1,.mw-ai-md h2,.mw-ai-md h3,.mw-ai-md h4{ margin:10px 0 6px; line-height:1.25; font-weight:700; }
.mw-ai-md h1{ font-size:1.25em; } .mw-ai-md h2{ font-size:1.15em; } .mw-ai-md h3{ font-size:1.05em; } .mw-ai-md h4{ font-size:1em; }
.mw-ai-md ul,.mw-ai-md ol{ margin:4px 0 8px; padding-left:20px; }
.mw-ai-md li{ margin:2px 0; }
.mw-ai-md a{ color:#0a63c8; text-decoration:underline; }
html.dark .mw-ai-md a{ color:#6aa9ff; }
.mw-ai-md code{ background:#18243312; border-radius:4px; padding:1px 5px; font-size:.9em; font-family:ui-monospace,SFMono-Regular,Menlo,monospace; }
html.dark .mw-ai-md code{ background:#ffffff1f; }
.mw-ai-md pre{ background:#0d1526; color:#e5e7eb; border-radius:9px; padding:10px 12px; overflow:auto; margin:6px 0 8px; }
.mw-ai-md pre code{ background:none; padding:0; color:inherit; font-size:.88em; }
.mw-ai-md-table{ border-collapse:collapse; width:100%; margin:6px 0 8px; font-size:.92em; display:block; overflow-x:auto; }
.mw-ai-md-table th,.mw-ai-md-table td{ border:1px solid #18243326; padding:5px 9px; text-align:left; }
.mw-ai-md-table th{ background:#18243310; font-weight:600; }
html.dark .mw-ai-md-table th,html.dark .mw-ai-md-table td{ border-color:#ffffff26; }
html.dark .mw-ai-md-table th{ background:#ffffff14; }
.mw-ai-md strong{ font-weight:700; }

/* task-2026-09-06-darkaudit — the edit/plus icon SVGs ship with NO fill
   attribute (default #000) and icon() is called without {fill:'currentColor'},
   so the header pencil, the empty-state pencil and the "+" (new chat) rendered
   black — invisible on the dark #1F2937 panel. Force the panel's monochrome
   icons to inherit currentColor (which .mw-ai-conv already flips: #182433 light
   / #e8eaed dark). currentColor icons (send/image) are unaffected. */
.mw-ai-conv-title svg, .mw-ai-conv-iconbtn svg, .mw-ai-conv-empty svg,
.mw-ai-conv-attach-btn svg, .mw-ai-conv-send svg, .mw-ai-conv-edit svg{ fill: currentColor; }

/* task-2026-09-06-darkaudit — the error tool-chip had no dark override (only the
   success chip did), so dark red on the dark panel was ~2:1. */
html.dark .mw-ai-conv-edit.err{ background:#e6394622; color:#ff8a93; border-color:#e6394655; }
`;

export class MwAiConversation extends MicroweberBaseClass {
    constructor(options = {}) {
        super();
        this.settings = Object.assign({ contentId: 0 }, options);
        this.chatId = null;
        this.pending = false;
        this.queue = [];        // messages typed while a turn is streaming, sent in order after it
        this.root = null;
        this.build();
    }

    icon(name) {
        try { return mw.top().app.iconService.icon(name); } catch (e) { return ""; }
    }

    build() {
        const el = document.createElement("div");
        el.className = "mw-ai-conv";
        el.innerHTML = `
            <div class="mw-ai-conv-head">
                <div class="mw-ai-conv-title">${this.icon("magic") || this.icon("edit") || ""}<span>${mw.lang("AI Assistant")}</span></div>
                <div class="mw-ai-conv-head-actions">
                    <button type="button" class="mw-ai-conv-iconbtn mw-ai-conv-history-btn" title="${mw.lang("History")}" aria-label="${mw.lang("History")}">${this.icon("history") || this.icon("list") || "☰"}</button>
                    <button type="button" class="mw-ai-conv-iconbtn mw-ai-conv-new-btn" title="${mw.lang("New chat")}" aria-label="${mw.lang("New chat")}">${this.icon("plus") || "+"}</button>
                </div>
            </div>
            <div class="mw-ai-conv-thread" role="log" aria-live="polite"></div>
            <div class="mw-ai-conv-attachments" aria-label="${mw.lang("Attached reference images")}"></div>
            <form class="mw-ai-conv-form">
                <button type="button" class="mw-ai-conv-attach-btn" title="${mw.lang("Attach a reference image")}" aria-label="${mw.lang("Attach a reference image")}">${this.icon("image") || this.icon("image-change") || "🖼"}</button>
                <textarea class="mw-ai-conv-input" rows="1" placeholder="${mw.lang("Ask AI, or paste a design…")}" aria-label="${mw.lang("Message AI assistant")}"></textarea>
                <button type="submit" class="mw-ai-conv-send" disabled aria-label="${mw.lang("Send")}">${this.icon("send") || "→"}</button>
            </form>
            <input type="file" class="mw-ai-conv-file" accept="image/*" multiple style="display:none">
            <div class="mw-ai-conv-history">
                <div class="mw-ai-conv-head">
                    <div class="mw-ai-conv-title">${mw.lang("Chat sessions")}</div>
                    <div class="mw-ai-conv-head-actions">
                        <button type="button" class="mw-ai-conv-iconbtn mw-ai-conv-history-new" title="${mw.lang("New chat")}" aria-label="${mw.lang("New chat")}">${this.icon("plus") || "+"}</button>
                        <button type="button" class="mw-ai-conv-iconbtn mw-ai-conv-history-close" aria-label="${mw.lang("Close")}">${this.icon("close") || "✕"}</button>
                    </div>
                </div>
                <div class="mw-ai-conv-history-list"></div>
            </div>
            <style>${CONV_CSS}</style>
        `;
        el.style.position = "relative";

        this.root = el;
        this.thread = el.querySelector(".mw-ai-conv-thread");
        this.form = el.querySelector(".mw-ai-conv-form");
        this.input = el.querySelector(".mw-ai-conv-input");
        this.sendBtn = el.querySelector(".mw-ai-conv-send");
        this.historyPanel = el.querySelector(".mw-ai-conv-history");
        this.historyList = el.querySelector(".mw-ai-conv-history-list");
        this.attachments = el.querySelector(".mw-ai-conv-attachments");
        this.attachBtn = el.querySelector(".mw-ai-conv-attach-btn");
        this.fileInput = el.querySelector(".mw-ai-conv-file");
        this.pendingImages = [];

        this.wire();
        this.renderEmpty();
        return el;
    }

    wire() {
        const autosize = () => {
            this.input.style.height = "auto";
            this.input.style.height = Math.min(this.input.scrollHeight, 140) + "px";
        };
        this.input.addEventListener("input", () => {
            // Enabled whenever there's something to send — even mid-turn, where
            // pressing send QUEUES the message rather than being blocked.
            this.sendBtn.disabled = (!this.input.value.trim() && !this.pendingImages.length);
            autosize();
        });
        this.input.addEventListener("keydown", (e) => {
            if (e.key === "Enter" && !e.shiftKey) {
                e.preventDefault();
                this.form.requestSubmit();
            }
        });
        this.form.addEventListener("submit", (e) => {
            e.preventDefault();
            const v = this.input.value.trim();
            if (v || this.pendingImages.length) {
                if (this.pending) { this.queueMessage(v); } else { this.send(v); }
            }
        });

        this.root.querySelector(".mw-ai-conv-new-btn").addEventListener("click", () => this.newChat());
        this.root.querySelector(".mw-ai-conv-history-btn").addEventListener("click", () => this.openHistory());
        this.root.querySelector(".mw-ai-conv-history-close").addEventListener("click", () => this.closeHistory());
        this.root.querySelector(".mw-ai-conv-history-new").addEventListener("click", () => this.newChat());

        // Paste a design screenshot straight into the box to recreate it.
        this.input.addEventListener("paste", (e) => this.handlePaste(e));
        this.attachBtn.addEventListener("click", () => this.fileInput.click());
        this.fileInput.addEventListener("change", () => {
            Array.from(this.fileInput.files || []).forEach((f) => this.addPendingImage(f));
            this.fileInput.value = "";
        });

        // Save before leaving the page: if the AI made edits this session that are
        // not yet persisted, flush a save when the page is about to unload (the
        // model doesn't always call save_page, and edits would otherwise be lost).
        this._beforeUnload = () => {
            if (this._dirty) {
                try { MwAi().saveCanvas(); } catch (e) {}
                this._dirty = false;
            }
        };
        window.addEventListener("beforeunload", this._beforeUnload);

        // Restore the last-used chat so closing/reopening the panel keeps the
        // conversation + its history instead of dropping to an empty new chat.
        this.restoreSession();
    }

    handlePaste(e) {
        const items = (e.clipboardData && e.clipboardData.items) || [];
        let handled = false;
        for (let i = 0; i < items.length; i++) {
            const it = items[i];
            if (it.type && it.type.indexOf("image") === 0) {
                const blob = it.getAsFile();
                if (blob) { this.addPendingImage(blob); handled = true; }
            }
        }
        if (handled) { e.preventDefault(); }
    }

    addPendingImage(blob) {
        if (!blob || this.pendingImages.length >= 4) { return; }
        const reader = new FileReader();
        reader.onload = () => {
            this.pendingImages.push(String(reader.result));
            this.renderAttachments();
            this.sendBtn.disabled = this.pending;
        };
        reader.readAsDataURL(blob);
    }

    renderAttachments() {
        this.attachments.innerHTML = "";
        this.pendingImages.forEach((src, i) => {
            const thumb = document.createElement("div");
            thumb.className = "mw-ai-conv-thumb";
            const img = document.createElement("img");
            img.src = src;
            const rm = document.createElement("button");
            rm.type = "button";
            rm.textContent = "×";
            rm.setAttribute("aria-label", mw.lang("Remove image"));
            rm.addEventListener("click", () => {
                this.pendingImages.splice(i, 1);
                this.renderAttachments();
            });
            thumb.appendChild(img);
            thumb.appendChild(rm);
            this.attachments.appendChild(thumb);
        });
    }

    renderEmpty() {
        const suggestions = [
            mw.lang("Make the headings blue"),
            mw.lang("Use a modern rounded button style"),
            mw.lang("Rewrite the main title"),
        ];
        this.thread.innerHTML = `
            <div class="mw-ai-conv-empty">
                ${this.icon("magic") || this.icon("edit") || ""}
                <div>${mw.lang("Tell the AI how to change your site. Edits apply live — press Save to keep them.")}</div>
                <div class="mw-ai-conv-suggest">
                    ${suggestions.map((s) => `<button type="button">${s}</button>`).join("")}
                </div>
            </div>
        `;
        this.thread.querySelectorAll(".mw-ai-conv-suggest button").forEach((b) => {
            b.addEventListener("click", () => {
                this.input.value = b.textContent;
                this.sendBtn.disabled = false;
                this.input.focus();
            });
        });
    }

    newChat() {
        this.chatId = null;
        this._forgetChat();     // don't snap back to the old thread on the next open
        this.closeHistory();
        this.renderEmpty();
        this.input.value = "";
        this.sendBtn.disabled = true;
        this.input.focus();
    }

    scrollDown() {
        this.thread.scrollTop = this.thread.scrollHeight;
    }

    // Rasterise the live canvas so the AI can SEE the current design. The
    // backend runs this through a vision model and feeds the description to the
    // editing model. Best-effort: bounded height, downscaled, JPEG — and never
    // blocks the turn (returns null on any failure/timeout).
    async captureScreenshot() {
        try {
            const doc = mw.top().app.canvas.getDocument();
            if (!doc || !doc.body) { return null; }
            const maxH = 2200;
            const height = Math.min(doc.body.scrollHeight || maxH, maxH);
            const shot = html2canvas(doc.body, {
                backgroundColor: "#ffffff",
                scale: 0.4,
                useCORS: true,
                allowTaint: true,
                logging: false,
                width: doc.documentElement.clientWidth || 1280,
                height: height,
                windowHeight: height,
            });
            const timeout = new Promise((resolve) => setTimeout(() => resolve(null), 6000));
            const canvas = await Promise.race([shot, timeout]);
            if (!canvas || !canvas.toDataURL) { return null; }
            return canvas.toDataURL("image/jpeg", 0.6);
        } catch (e) {
            return null;
        }
    }

    addMessage(role, text, images) {
        // Clear the empty state on first real message.
        const empty = this.thread.querySelector(".mw-ai-conv-empty");
        if (empty) { empty.remove(); }

        const msg = document.createElement("div");
        msg.className = "mw-ai-conv-msg " + role;
        if (images && images.length) {
            const wrap = document.createElement("div");
            wrap.className = "mw-ai-conv-msg-images";
            images.forEach((src) => {
                const im = document.createElement("img");
                im.src = src;
                wrap.appendChild(im);
            });
            msg.appendChild(wrap);
        }
        const bubble = document.createElement("div");
        bubble.className = "mw-ai-conv-msg-bubble";
        // Render the assistant's markdown (bold, lists, tables, code…); keep the
        // user's own message as plain text.
        if (role === "assistant" && text) {
            bubble.classList.add("mw-ai-md");
            bubble.innerHTML = this.renderMarkdown(text);
        } else {
            bubble.textContent = text || "";
        }
        if (text || !images || !images.length) { msg.appendChild(bubble); }
        this.thread.appendChild(msg);
        this.scrollDown();
        return { msg, bubble };
    }

    // Compact, safe Markdown -> HTML renderer for assistant replies. Escapes all
    // HTML first, then handles fenced code, GFM tables, headings, bold/italic,
    // inline code, links, ordered/unordered lists and paragraphs. No external
    // dependency; output is built from escaped text so it is XSS-safe.
    renderMarkdown(src) {
        const esc = (s) => String(s).replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;");
        let s = String(src == null ? "" : src);
        // Strip Kimi's stray <think> tags.
        s = s.replace(/<\/?think>/gi, "").replace(/\r\n/g, "\n");

        // Pull out fenced code blocks first so their contents aren't parsed.
        const codeBlocks = [];
        s = s.replace(/```[a-z]*\n?([\s\S]*?)```/gi, (_m, code) => {
            codeBlocks.push(code.replace(/\n$/, ""));
            return " CB" + (codeBlocks.length - 1) + " ";
        });

        s = esc(s);

        // GFM tables: a header row, a |---|---| separator, then body rows.
        s = s.replace(/(?:^\|.*\|[ \t]*\n)(?:^\|[ \t:|-]+\|[ \t]*\n)(?:^\|.*\|[ \t]*\n?)*/gm, (block) => {
            const rows = block.trim().split("\n").map((r) => r.trim());
            const cells = (r) => r.replace(/^\||\|$/g, "").split("|").map((c) => c.trim());
            const head = cells(rows[0]);
            const body = rows.slice(2).map(cells);
            let h = "<table class='mw-ai-md-table'><thead><tr>" + head.map((c) => "<th>" + c + "</th>").join("") + "</tr></thead><tbody>";
            body.forEach((r) => { h += "<tr>" + r.map((c) => "<td>" + c + "</td>").join("") + "</tr>"; });
            return h + "</tbody></table>\n";
        });

        // Headings.
        s = s.replace(/^###### (.*)$/gm, "<h6>$1</h6>")
             .replace(/^##### (.*)$/gm, "<h5>$1</h5>")
             .replace(/^#### (.*)$/gm, "<h4>$1</h4>")
             .replace(/^### (.*)$/gm, "<h3>$1</h3>")
             .replace(/^## (.*)$/gm, "<h2>$1</h2>")
             .replace(/^# (.*)$/gm, "<h1>$1</h1>");

        // Inline: bold, italic, inline code, links.
        s = s.replace(/`([^`]+)`/g, "<code>$1</code>");
        s = s.replace(/\*\*([^*]+)\*\*/g, "<strong>$1</strong>");
        s = s.replace(/__([^_]+)__/g, "<strong>$1</strong>");
        s = s.replace(/(^|[^*])\*([^*\n]+)\*/g, "$1<em>$2</em>");
        s = s.replace(/\[([^\]]+)\]\((https?:\/\/[^)\s]+)\)/g, "<a href=\"$2\" target=\"_blank\" rel=\"noopener\">$1</a>");

        // Lists: group runs of - / * / 1. lines.
        s = s.replace(/(?:^[ \t]*(?:[-*]|\d+\.)[ \t]+.*(?:\n|$))+/gm, (block) => {
            const ordered = /^[ \t]*\d+\./.test(block);
            const items = block.trim().split("\n").map((l) => l.replace(/^[ \t]*(?:[-*]|\d+\.)[ \t]+/, "").trim());
            return "<" + (ordered ? "ol" : "ul") + ">" + items.map((i) => "<li>" + i + "</li>").join("") + "</" + (ordered ? "ol" : "ul") + ">\n";
        });

        // Paragraphs / line breaks for the remaining plain lines.
        s = s.split(/\n{2,}/).map((chunk) => {
            const t = chunk.trim();
            if (!t) { return ""; }
            if (/^<(h\d|ul|ol|table|pre|blockquote)/.test(t)) { return t; }
            return "<p>" + t.replace(/\n/g, "<br>") + "</p>";
        }).join("");

        // Restore code blocks.
        s = s.replace(/ CB(\d+) /g, (_m, i) => "<pre><code>" + esc(codeBlocks[i]) + "</code></pre>");
        return s;
    }

    addTyping() {
        const t = document.createElement("div");
        t.className = "mw-ai-conv-msg assistant mw-ai-conv-typing-wrap";
        t.innerHTML = `<div class="mw-ai-conv-typing"><span></span><span></span><span></span></div>`;
        this.thread.appendChild(t);
        this.scrollDown();
        return t;
    }

    editLabel(edit) {
        const t = edit && edit.tool;
        const a = (edit && edit.args) || {};
        if (t === "reference") { return mw.lang("Read the reference design"); }
        if (t === "vision") { return mw.lang("Looked at the page"); }
        if (t === "verify") { return mw.lang("Checking the result for bugs"); }
        if (t === "add_section") { return mw.lang("Added a section"); }
        if (t === "insert_module") { return mw.lang("Inserted module") + (a.type ? " · " + a.type : ""); }
        if (t === "set_module_option") { return mw.lang("Configured module") + (a.key ? " · " + a.key : ""); }
        if (t === "set_custom_field") { return mw.lang("Set custom field") + (a.field ? " · " + a.field : ""); }
        if (t === "create_content") { return mw.lang("Created page") + (a.title ? " · " + a.title : ""); }
        if (t === "create_post") { return mw.lang("Created post") + (a.title ? " · " + a.title : ""); }
        if (t === "add_menu_item") { return mw.lang("Added menu link") + (a.title ? " · " + a.title : ""); }
        if (t === "get_menu") { return mw.lang("Read the menu"); }
        if (t === "edit_menu_item") { return mw.lang("Edited menu item"); }
        if (t === "navigate_to_page") { return mw.lang("Opened page") + (a.url ? " · " + a.url : ""); }
        if (t === "save_page") { return mw.lang("Saved the page"); }
        if (t === "apply_css") { return mw.lang("Applied styles"); }
        if (t === "set_text") { return mw.lang("Updated text") + (a.selector ? " · " + a.selector : ""); }
        if (t === "set_image") { return mw.lang("Replaced image") + (a.selector ? " · " + a.selector : ""); }
        if (t === "generate_image") { return mw.lang("Generated image"); }
        if (t === "get_page_context") { return mw.lang("Read the page"); }
        return t || mw.lang("Edit");
    }

    // A short, human string of the exact tool-call arguments, shown in the
    // collapsible details box so you can verify what actually happened.
    editDetails(edit, result) {
        const a = (edit && edit.args) || {};
        const t = edit && edit.tool;
        const parts = [];
        if (t === "apply_css") { if (a.css) { parts.push(String(a.css)); } }
        else if (t === "add_section") {
            if (a.html) { parts.push(String(a.html)); }
            if (a.css) { parts.push("/* css */\n" + String(a.css)); }
        } else if (t === "set_text") { parts.push((a.selector || "?") + "  →  " + (a.text || "")); }
        else if (t === "set_image") { parts.push((a.selector || "?") + "  →  " + (a.url || "")); }
        else if (t === "insert_module") { parts.push("type: " + (a.type || "?")); }
        else if (t === "set_module_option") { parts.push((a.key || "?") + " = " + (a.value || "")); }
        else if (a && Object.keys(a).length) { try { parts.push(JSON.stringify(a, null, 2)); } catch (e) {} }
        if (result && result.ok === false && result.message) { parts.push("⚠ " + String(result.message)); }
        return parts.join("\n\n").trim();
    }

    addEdit(container, edit, result) {
        const ok = !result || result.ok !== false;
        const wrap = document.createElement("div");
        wrap.className = "mw-ai-conv-editwrap";

        const chip = document.createElement("button");
        chip.type = "button";
        chip.className = "mw-ai-conv-edit" + (ok ? "" : " err");
        const ic = ok ? (this.icon("check") || "✓") : (this.icon("close") || "✕");
        const detailText = this.editDetails(edit, result);
        chip.innerHTML = `${ic}<span>${this.editLabel(edit)}</span>`
            + (detailText ? `<span class="mw-ai-conv-caret">▸</span>` : "");
        wrap.appendChild(chip);

        if (detailText) {
            const details = document.createElement("pre");
            details.className = "mw-ai-conv-edit-details";
            details.textContent = detailText;
            details.style.display = "none";
            wrap.appendChild(details);
            chip.addEventListener("click", () => {
                const open = details.style.display !== "none";
                details.style.display = open ? "none" : "block";
                const caret = chip.querySelector(".mw-ai-conv-caret");
                if (caret) { caret.textContent = open ? "▸" : "▾"; }
                if (!open) { this.scrollDown(); }
            });
        } else if (!ok && result && result.message) {
            chip.innerHTML += ` <code>${String(result.message)}</code>`;
        }

        container.appendChild(wrap);
        this.scrollDown();
    }

    // Render an offer_choices tool call as a prompt + clickable pills. Clicking a
    // pill sends that choice as the user's next message so the agent applies it.
    addChoices(container, edit) {
        const a = (edit && edit.args) || {};
        const prompt = String(a.prompt || "").trim();
        const choices = String(a.choices || "")
            .split(/\r\n|\r|\n|\|/).map((s) => s.trim()).filter(Boolean).slice(0, 8);
        if (!choices.length) { return; }

        const wrap = document.createElement("div");
        wrap.className = "mw-ai-conv-choices";
        if (prompt) {
            const p = document.createElement("div");
            p.className = "mw-ai-conv-choices-prompt";
            p.textContent = prompt;
            wrap.appendChild(p);
        }
        const pills = document.createElement("div");
        pills.className = "mw-ai-conv-choices-pills";
        choices.forEach((c) => {
            const b = document.createElement("button");
            b.type = "button";
            b.className = "mw-ai-conv-choice";
            b.textContent = c;
            b.addEventListener("click", () => {
                if (this.pending) { return; }
                wrap.querySelectorAll(".mw-ai-conv-choice").forEach((x) => { x.disabled = true; });
                b.classList.add("picked");
                this.send(c);
            });
            pills.appendChild(b);
        });
        wrap.appendChild(pills);
        container.appendChild(wrap);
        this.scrollDown();
    }

    setPending(v) {
        this.pending = v;
        // Keep the textarea usable while the AI is thinking so the user can compose
        // (and queue) the next message; the send button just needs some content.
        this.input.disabled = false;
        this.sendBtn.disabled = (!this.input.value.trim() && !this.pendingImages.length);
    }

    // Typed a message mid-turn — hold it and send when the current turn finishes.
    queueMessage(text) {
        const t = String(text || '').trim();
        if (!t && !this.pendingImages.length) { return; }
        this.queue.push({ text: t, images: this.pendingImages.slice() });
        this.pendingImages = [];
        this.renderAttachments();
        this.input.value = '';
        this.input.style.height = 'auto';
        this.sendBtn.disabled = true;
        this.renderQueue();
    }

    // Show queued messages as muted "pending" pills so the user sees what's lined up.
    renderQueue() {
        if (this._queueEl) { this._queueEl.remove(); this._queueEl = null; }
        if (!this.queue.length) { return; }
        const el = document.createElement('div');
        el.className = 'mw-ai-conv-queue';
        this.queue.forEach((q) => {
            const pill = document.createElement('div');
            pill.className = 'mw-ai-conv-queued';
            pill.textContent = '⏳ ' + (q.text || (q.images.length ? mw.lang('Queued image') : ''));
            el.appendChild(pill);
        });
        this.thread.appendChild(el);
        this._queueEl = el;
        this.scrollDown();
    }

    // After a turn ends, send the next queued message (which re-enters send() and
    // chains to the following one on its own completion).
    drainQueue() {
        if (this.pending || !this.queue.length) { return false; }
        const next = this.queue.shift();
        if (this._queueEl) { this._queueEl.remove(); this._queueEl = null; }
        this.renderQueue();
        this.pendingImages = (next.images || []).slice();
        this.renderAttachments();
        this.send(next.text || '');
        return true;
    }

    async send(text) {
        // Consume any pasted/attached reference images for this turn.
        const refImages = this.pendingImages.slice();
        this.pendingImages = [];
        this.renderAttachments();

        this.setPending(true);
        this.addMessage("user", (text || (refImages.length ? mw.lang("Recreate this design") : "")), refImages);
        this.input.value = "";
        this.input.style.height = "auto";

        // The assistant turn: an edits container (filled as tool frames stream in)
        // plus a typing indicator that becomes the reply bubble on done.
        const turn = document.createElement("div");
        turn.className = "mw-ai-conv-msg assistant";
        const editsWrap = document.createElement("div");
        editsWrap.className = "mw-ai-conv-edits";
        turn.appendChild(editsWrap);
        this.thread.appendChild(turn);
        const typing = this.addTyping();

        let anyEdit = false;
        let navigated = false;
        let visualEdit = false;
        const self = this;

        // Capture what the page looks like now so the AI can see the design.
        const screenshot = await this.captureScreenshot();

        try {
            const done = await MwAi().agentChatStream(
                (text || (refImages.length ? "Recreate this design as closely as possible on the page." : "")),
                { chat_id: this.chatId || undefined, content_id: this.settings.contentId || undefined, screenshot: screenshot, reference_images: refImages },
                {
                    onStart(data) {
                        if (data && data.chat_id) { self._rememberChat(data.chat_id); }
                    },
                    onReference(data) {
                        // The AI read the pasted reference design — show a chip.
                        self.addEdit(editsWrap, { tool: "reference" }, { ok: true });
                    },
                    onVision(data) {
                        // The AI looked at a screenshot of the page — show a chip.
                        self.addEdit(editsWrap, { tool: "vision" }, { ok: true });
                    },
                    onTool(edit, result) {
                        // offer_choices isn't a canvas edit — render the options
                        // as clickable pills and wait for the user to pick one.
                        if (edit && edit.tool === "offer_choices") {
                            self.addChoices(turn, edit);
                            return;
                        }
                        anyEdit = true;
                        self._dirty = true;
                        if (edit && edit.tool === "navigate_to_page") { navigated = true; }
                        if (edit && ["apply_css", "add_section", "set_text", "set_image", "insert_module"].indexOf(edit.tool) !== -1) {
                            visualEdit = true;
                        }
                        self.addEdit(editsWrap, edit, result);
                    },
                    onError(msg) {
                        self.addEdit(editsWrap, { tool: "error" }, { ok: false, message: msg });
                    },
                    onDone(data) {
                        if (data && data.chat_id) { self._rememberChat(data.chat_id); }
                    },
                }
            );

            typing.remove();
            const replyText = (done && done.response) ? done.response : mw.lang("Done.");
            const bubble = document.createElement("div");
            bubble.className = "mw-ai-conv-msg-bubble mw-ai-md";
            bubble.innerHTML = this.renderMarkdown(replyText);
            turn.appendChild(bubble);
            this.scrollDown();

            // Auto-save the model's edits so nothing is lost (unless a navigation
            // this turn already saved before leaving the page).
            if (anyEdit && !navigated) {
                try { MwAi().saveCanvas(); self._dirty = false; } catch (e) {}
            } else if (navigated) {
                self._dirty = false;
            }

            // Verify by screenshot. When the user pasted a design to recreate,
            // run the autonomous MATCH LOOP: screenshot the result, compare it to
            // the target, fix the differences, and repeat until it matches (or a
            // round cap). Otherwise do the one-round self-check for visual bugs.
            if (!navigated && !this._verifying && this.settings.verify !== false) {
                if (refImages.length) {
                    await this.runRecreationLoop(editsWrap, turn);
                } else if (visualEdit) {
                    await this.runVerification(editsWrap, turn);
                }
            }
        } catch (e) {
            typing.remove();
            this.addEdit(editsWrap, { tool: "error" }, { ok: false, message: String(e && e.message || e) });
            const bubble = document.createElement("div");
            bubble.className = "mw-ai-conv-msg-bubble";
            bubble.textContent = mw.lang("Sorry, something went wrong. Please try again.");
            turn.appendChild(bubble);
        } finally {
            this.setPending(false);
            this.input.focus();
            // Send anything the user typed while this turn was streaming.
            this.drainQueue();
        }
    }

    // Post-edit self-check: screenshot the result and feed it back to the agent
    // (as a reference image the vision model reads) so it can spot and fix its own
    // visual bugs — invisible/low-contrast text, hidden or missing navigation
    // menus, overlapping/broken layout, unstyled areas — in a single pass. One
    // round only (guarded by _verifying) so it never loops.
    async runVerification(editsWrap, turn) {
        let shot = null;
        try { shot = await this.captureScreenshot(); } catch (e) {}
        if (!shot) { return; }

        this._verifying = true;
        this.addEdit(editsWrap, { tool: "verify" }, { ok: true });
        const typing = this.addTyping();
        const self = this;

        const verifyMsg = "CRITIQUE your last change like a HARSH design reviewer who assumes it is broken. "
            + "The attached screenshot is EXACTLY how the page looks now — judge only what you SEE, and be "
            + "honest. Check, in order: (1) IS THE PAGE EMPTY OR BROKEN? Did content disappear — is the page "
            + "now mostly blank/coloured bands with no visible hero, text or sections? If the content is gone "
            + "or collapsed, your last change broke it — you MUST fix or REVERT it. (2) READABILITY: is any "
            + "text invisible or low-contrast (nearly the same colour as its background)? Every text colour "
            + "must clearly contrast its background. Use get_computed_styles to confirm suspect elements. "
            + "(3) NAVIGATION: menus/links hidden, missing or unreadable? (4) LAYOUT: anything overlapping, "
            + "clipped, squashed or pushed off-screen? (5) UNSTYLED default-looking areas that clash. "
            + "List every problem you find, then FIX each with apply_css (global, high-contrast colours) — if "
            + "a rule hid or broke content, remove/override it. NEVER approve a page that is empty, broken or "
            + "unreadable. ONLY if the page is genuinely complete and readable, reply exactly 'Looks good.' "
            + "and make no tool calls.";

        let fixed = false;
        try {
            const done = await MwAi().agentChatStream(
                verifyMsg,
                {
                    chat_id: this.chatId || undefined,
                    content_id: this.settings.contentId || undefined,
                    reference_images: [shot]
                },
                {
                    onStart(data) { if (data && data.chat_id) { self._rememberChat(data.chat_id); } },
                    onReference() { self.addEdit(editsWrap, { tool: "reference" }, { ok: true }); },
                    onTool(edit, result) { fixed = true; self.addEdit(editsWrap, edit, result); },
                    onError(msg) { self.addEdit(editsWrap, { tool: "error" }, { ok: false, message: msg }); },
                    onDone(data) { if (data && data.chat_id) { self._rememberChat(data.chat_id); } }
                }
            );
            typing.remove();
            const txt = (done && done.response) ? done.response : "";
            if (txt) {
                const bubble = document.createElement("div");
                bubble.className = "mw-ai-conv-msg-bubble mw-ai-md";
                bubble.innerHTML = (fixed ? "🔧 " : "✓ ") + this.renderMarkdown(txt);
                turn.appendChild(bubble);
                this.scrollDown();
            }
            if (fixed) { try { MwAi().saveCanvas(); } catch (e) {} }
        } catch (e) {
            typing.remove();
        } finally {
            this._verifying = false;
        }
    }

    // Autonomous recreation MATCH LOOP. Used when the user pasted a design to
    // recreate: after the first build, screenshot the current page, ask the agent
    // to compare it to the target design (which it read from the pasted reference
    // earlier in this conversation) and fix the differences, then repeat. Stops
    // when the agent makes no edits / replies DONE, or after maxRounds. Each round
    // sends the current screenshot via the `screenshot` param so the vision model
    // describes "what the page looks like now" for the text-only editing model.
    async runRecreationLoop(editsWrap, turn, maxRounds = 5) {
        this._verifying = true;
        const self = this;
        const compareMsg = "You are recreating the target design the user pasted earlier in this "
            + "conversation. The attached screenshot is the CURRENT state of the page. Compare CURRENT "
            + "to that TARGET design and make them match: if a section is MISSING add it (add_section "
            + "with matching css); if colours/spacing/typography/backgrounds differ fix them (apply_css, "
            + "styles are global and must win); if text differs fix it (set_text). Change only what does "
            + "not match yet — do NOT re-add sections that are already present. When the page already "
            + "closely matches the target, reply exactly 'DONE' and make no tool calls.";

        try {
            for (let round = 0; round < maxRounds; round++) {
                let shot = null;
                try { shot = await this.captureScreenshot(); } catch (e) {}
                if (!shot) { break; }

                this.addEdit(editsWrap, { tool: "verify" }, { ok: true });
                const typing = this.addTyping();
                let fixed = false;
                let reply = "";

                const done = await MwAi().agentChatStream(
                    compareMsg,
                    {
                        chat_id: this.chatId || undefined,
                        content_id: this.settings.contentId || undefined,
                        screenshot: shot
                    },
                    {
                        onStart(data) { if (data && data.chat_id) { self._rememberChat(data.chat_id); } },
                        onVision() { self.addEdit(editsWrap, { tool: "vision" }, { ok: true }); },
                        onTool(edit, result) { fixed = true; self.addEdit(editsWrap, edit, result); },
                        onError(msg) { self.addEdit(editsWrap, { tool: "error" }, { ok: false, message: msg }); },
                        onDone(data) { if (data && data.chat_id) { self._rememberChat(data.chat_id); } }
                    }
                );
                typing.remove();
                reply = (done && done.response) ? done.response : "";
                if (reply) {
                    const bubble = document.createElement("div");
                    bubble.className = "mw-ai-conv-msg-bubble mw-ai-md";
                    bubble.innerHTML = (fixed ? "🔧 " : "✓ ") + this.renderMarkdown(reply);
                    turn.appendChild(bubble);
                    this.scrollDown();
                }
                if (fixed) { try { MwAi().saveCanvas(); } catch (e) {} }

                // Stop when the agent reports a match or makes no further edits.
                if (!fixed || /\bDONE\b/i.test(reply) || /looks good|matches/i.test(reply)) {
                    break;
                }
            }
        } catch (e) {
            // best-effort loop
        } finally {
            this._verifying = false;
        }
    }

    openHistory() {
        this.historyPanel.classList.add("open");
        this.loadChats();
    }

    closeHistory() {
        this.historyPanel.classList.remove("open");
    }

    // Short relative-time label from an ISO timestamp ("5m", "3h", "2d").
    relativeTime(iso) {
        if (!iso) { return ""; }
        const t = Date.parse(iso);
        if (isNaN(t)) { return ""; }
        const s = Math.max(0, Math.floor((Date.now() - t) / 1000));
        if (s < 60) { return mw.lang("just now"); }
        const m = Math.floor(s / 60);
        if (m < 60) { return m + "m"; }
        const h = Math.floor(m / 60);
        if (h < 24) { return h + "h"; }
        return Math.floor(h / 24) + "d";
    }

    loadChats() {
        this.historyList.innerHTML = `<div class="mw-ai-conv-history-item"><span class="s">${mw.lang("Loading…")}</span></div>`;
        const url = mw.settings.site_url + "api/ai/user-chats";
        $.get(url).then((res) => {
            const list = (res && res.data && res.data.data) ? res.data.data : [];
            if (!list.length) {
                this.historyList.innerHTML = `<div class="mw-ai-conv-history-item"><span class="s">${mw.lang("No previous chats — start a new one.")}</span></div>`;
                return;
            }
            this.historyList.innerHTML = "";
            list.forEach((chat) => {
                const item = document.createElement("div");
                item.className = "mw-ai-conv-history-item" + (chat.id === this.chatId ? " active" : "");
                const last = (chat.messages && chat.messages[0]) ? chat.messages[0].content : "";
                const when = this.relativeTime(chat.updated_at || chat.created_at);
                const count = (typeof chat.messages_count !== "undefined") ? chat.messages_count : "";
                const title = (chat.title && chat.title.trim()) ? chat.title : mw.lang("Untitled chat");
                item.innerHTML =
                    `<span class="t">${title}${chat.id === this.chatId ? ' <em class="badge">' + mw.lang("current") + '</em>' : ''}</span>`
                    + `<span class="s">${when}${count !== "" ? " · " + count + " " + mw.lang("msgs") : ""}${last ? " · " + String(last).replace(/\s+/g, " ").slice(0, 48) : ""}</span>`;
                item.addEventListener("click", () => this.loadChat(chat.id));
                this.historyList.appendChild(item);
            });
        }).catch(() => {
            this.historyList.innerHTML = `<div class="mw-ai-conv-history-item"><span class="s">${mw.lang("Could not load chats")}</span></div>`;
        });
    }

    loadChat(id, opts) {
        opts = opts || {};
        const url = mw.settings.site_url + "api/ai/chat-history/" + id;
        $.get(url).then((res) => {
            const data = res && res.data;
            const messages = (data && data.messages && data.messages.data) ? data.messages.data : [];
            this.chatId = id;
            this._rememberChat();               // persist so a close/reopen keeps this session
            this.pendingImages = [];
            this.renderAttachments();
            this.thread.innerHTML = "";
            messages.forEach((m) => {
                if (m.role === "user" || m.role === "assistant") {
                    this.addMessage(m.role, m.content);
                }
            });
            if (!messages.length) { this.renderEmpty(); }
            this.closeHistory();
            if (!opts.silent) { this.input.focus(); }
        }).catch(() => {
            // A remembered chat may have been deleted — fall back rather than error.
            if (typeof opts.fallback === "function") { opts.fallback(); return; }
            if (!opts.silent) { mw.notification && mw.notification.error(mw.lang("Could not load chat")); }
        });
    }

    // --- Session persistence (task-2026-09-24) -----------------------------
    // Closing the AI panel used to lose the current chat: a fresh MwAiConversation
    // starts with chatId=null and shows an empty thread. Remember the last-used
    // chat id in localStorage and, on (re)open, reload it so the history is still
    // there. If the user hasn't been in a chat yet, fall back to their most recent
    // one; New chat clears the memory so it doesn't snap back to the old thread.
    _chatKey() {
        let uid = "";
        try { uid = (mw.settings && (mw.settings.user_id || mw.settings.logged_user_id)) || ""; } catch (e) {}
        return "mw_ai_last_chat" + (uid ? "_" + uid : "");
    }

    _rememberChat(id) {
        if (id) { this.chatId = id; }
        try { if (this.chatId) { localStorage.setItem(this._chatKey(), String(this.chatId)); } } catch (e) {}
    }

    _forgetChat() {
        try { localStorage.removeItem(this._chatKey()); } catch (e) {}
    }

    restoreSession() {
        let saved = null;
        try { saved = localStorage.getItem(this._chatKey()); } catch (e) {}
        const id = saved ? parseInt(saved, 10) : 0;
        if (id) {
            this.loadChat(id, { silent: true, fallback: () => this.loadLatestChat() });
            return;
        }
        this.loadLatestChat();
    }

    loadLatestChat() {
        const url = mw.settings.site_url + "api/ai/user-chats";
        $.get(url).then((res) => {
            const list = (res && res.data && res.data.data) ? res.data.data : [];
            if (list.length && list[0] && list[0].id) {
                this.loadChat(list[0].id, { silent: true });
            }
        }).catch(() => {});
    }
}
