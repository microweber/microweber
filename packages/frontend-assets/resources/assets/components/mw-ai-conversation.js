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
    font-size:14px; color:#182433;
}
html.dark .mw-ai-conv{ color:#e8eaed; }

.mw-ai-conv-head{
    display:flex; align-items:center; justify-content:space-between;
    padding:2px 2px 10px; gap:8px;
}
.mw-ai-conv-title{ font-weight:600; font-size:14px; display:flex; align-items:center; gap:8px; }
.mw-ai-conv-title svg{ width:18px; height:18px; }
.mw-ai-conv-head-actions{ display:flex; gap:6px; }
.mw-ai-conv-iconbtn{
    width:36px; height:36px; min-width:36px; border-radius:10px; border:none;
    display:inline-flex; align-items:center; justify-content:center; cursor:pointer;
    background:transparent; color:inherit;
}
.mw-ai-conv-iconbtn:hover{ background:#1824330f; }
html.dark .mw-ai-conv-iconbtn:hover{ background:#ffffff14; }
.mw-ai-conv-iconbtn svg{ width:20px; height:20px; }

.mw-ai-conv-thread{
    flex:1 1 auto; overflow-y:auto; padding:6px 2px; display:flex; flex-direction:column; gap:14px;
}
.mw-ai-conv-msg{ display:flex; flex-direction:column; gap:6px; max-width:100%; }
.mw-ai-conv-msg-bubble{
    padding:10px 13px; border-radius:14px; line-height:1.5; white-space:pre-wrap; word-wrap:break-word;
}
.mw-ai-conv-msg.user{ align-items:flex-end; }
.mw-ai-conv-msg.user .mw-ai-conv-msg-bubble{
    background:#182433; color:#fff; border-bottom-right-radius:4px; max-width:88%;
}
.mw-ai-conv-msg.assistant .mw-ai-conv-msg-bubble{
    background:#f2f3f5; color:#182433; border-bottom-left-radius:4px; max-width:96%;
}
html.dark .mw-ai-conv-msg.assistant .mw-ai-conv-msg-bubble{ background:#2a2d31; color:#e8eaed; }

.mw-ai-conv-edits{ display:flex; flex-direction:column; gap:5px; }
.mw-ai-conv-edit{
    display:inline-flex; align-items:center; gap:7px; align-self:flex-start;
    padding:5px 10px; border-radius:9px; font-size:12.5px;
    background:#18243310; color:#182433; border:1px solid #18243318;
}
html.dark .mw-ai-conv-edit{ background:#ffffff10; color:#dfe3e8; border-color:#ffffff1f; }
.mw-ai-conv-edit.err{ background:#e6394614; color:#c62030; border-color:#e6394633; }
.mw-ai-conv-edit svg{ width:15px; height:15px; }
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

.mw-ai-conv-empty{
    margin:auto; text-align:center; color:#8a94a3; padding:24px 16px; max-width:280px;
}
.mw-ai-conv-empty svg{ width:34px; height:34px; margin-bottom:10px; opacity:.7; }
.mw-ai-conv-suggest{ display:flex; flex-wrap:wrap; gap:6px; justify-content:center; margin-top:12px; }
.mw-ai-conv-suggest button{
    padding:6px 11px; border-radius:16px; border:1px solid #18243322; background:transparent;
    color:inherit; cursor:pointer; font-size:12.5px;
}
.mw-ai-conv-suggest button:hover{ background:#1824330f; }
html.dark .mw-ai-conv-suggest button{ border-color:#ffffff2a; }

.mw-ai-conv-typing{ display:inline-flex; gap:4px; padding:12px 14px; }
.mw-ai-conv-typing span{
    width:7px; height:7px; border-radius:50%; background:#9aa3af; animation:mwAiBlink 1.2s infinite both;
}
.mw-ai-conv-typing span:nth-child(2){ animation-delay:.2s; }
.mw-ai-conv-typing span:nth-child(3){ animation-delay:.4s; }
@keyframes mwAiBlink{ 0%,80%,100%{opacity:.25} 40%{opacity:1} }

.mw-ai-conv-form{
    display:flex; align-items:flex-end; gap:8px; padding:10px 4px 4px; border-top:1px solid #18243318;
}
html.dark .mw-ai-conv-form{ border-top-color:#ffffff1f; }
.mw-ai-conv-input{
    flex:1 1 auto; resize:none; border:1px solid #18243326; border-radius:12px;
    padding:11px 13px; font-size:14px; line-height:1.4; max-height:140px; min-height:44px;
    background:#fff; color:#182433; font-family:inherit;
}
html.dark .mw-ai-conv-input{ background:#1f2226; color:#e8eaed; border-color:#ffffff26; }
.mw-ai-conv-input:focus{ outline:none; border-color:#182433; }
.mw-ai-conv-send{
    width:44px; height:44px; min-width:44px; border-radius:12px; border:none; cursor:pointer;
    background:#182433; color:#fff; display:inline-flex; align-items:center; justify-content:center;
}
.mw-ai-conv-send:disabled{ opacity:.45; pointer-events:none; }
.mw-ai-conv-send svg{ width:20px; height:20px; }

.mw-ai-conv-history{
    position:absolute; inset:0; background:inherit; z-index:5; display:none;
    flex-direction:column; padding:2px; background:#fff;
}
html.dark .mw-ai-conv-history{ background:#1b1e22; }
.mw-ai-conv-history.open{ display:flex; }
.mw-ai-conv-history-item{
    padding:11px 12px; border-radius:10px; cursor:pointer; display:flex; flex-direction:column; gap:2px;
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
    width:40px; height:44px; min-width:40px; border:none; background:transparent; cursor:pointer;
    color:#8a94a3; display:inline-flex; align-items:center; justify-content:center; border-radius:10px;
}
.mw-ai-conv-attach-btn:hover{ background:#1824330f; color:inherit; }
.mw-ai-conv-attach-btn svg{ width:20px; height:20px; }
.mw-ai-conv-msg-images{ display:flex; flex-wrap:wrap; gap:6px; }
.mw-ai-conv-msg-images img{ max-width:180px; max-height:140px; border-radius:10px; border:1px solid #18243322; }
`;

export class MwAiConversation extends MicroweberBaseClass {
    constructor(options = {}) {
        super();
        this.settings = Object.assign({ contentId: 0 }, options);
        this.chatId = null;
        this.pending = false;
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
                <textarea class="mw-ai-conv-input" rows="1" placeholder="${mw.lang("Ask AI, or paste a design screenshot to recreate…")}" aria-label="${mw.lang("Message AI assistant")}"></textarea>
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
            this.sendBtn.disabled = (!this.input.value.trim() && !this.pendingImages.length) || this.pending;
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
            if ((v || this.pendingImages.length) && !this.pending) { this.send(v); }
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
        bubble.textContent = text || "";
        if (text || !images || !images.length) { msg.appendChild(bubble); }
        this.thread.appendChild(msg);
        this.scrollDown();
        return { msg, bubble };
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

    setPending(v) {
        this.pending = v;
        this.sendBtn.disabled = v || (!this.input.value.trim() && !this.pendingImages.length);
        this.input.disabled = v;
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
                        if (data && data.chat_id) { self.chatId = data.chat_id; }
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
                        anyEdit = true;
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
                        if (data && data.chat_id) { self.chatId = data.chat_id; }
                    },
                }
            );

            typing.remove();
            const replyText = (done && done.response) ? done.response : mw.lang("Done.");
            const bubble = document.createElement("div");
            bubble.className = "mw-ai-conv-msg-bubble";
            bubble.textContent = replyText;
            turn.appendChild(bubble);
            this.scrollDown();

            // Auto-save the model's edits so nothing is lost (unless a navigation
            // this turn already saved before leaving the page).
            if (anyEdit && !navigated) {
                try { MwAi().saveCanvas(); } catch (e) {}
            }

            // Self-check: after a visual/CSS edit, screenshot the RESULT and feed
            // it back to the agent so it can catch and fix its own bugs (invisible
            // text, hidden menus, broken layout) in one correction pass.
            if (visualEdit && !navigated && !this._verifying && this.settings.verify !== false) {
                await this.runVerification(editsWrap, turn);
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

        const verifyMsg = "SELF-CHECK your last change. The attached screenshot is exactly how the "
            + "page looks now. Inspect it carefully for VISUAL BUGS: (1) text that is invisible or "
            + "very low-contrast (nearly the same colour as its background); (2) navigation menus or "
            + "links that are hidden, missing or unreadable; (3) elements overlapping or a broken/"
            + "collapsed layout; (4) unstyled or default-looking areas that should match the design. "
            + "If you find ANY problem, FIX it now with apply_css (styles are global and must win — use "
            + "clear, high-contrast colours). If everything looks correct, reply exactly 'Looks good.' "
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
                    onStart(data) { if (data && data.chat_id) { self.chatId = data.chat_id; } },
                    onReference() { self.addEdit(editsWrap, { tool: "reference" }, { ok: true }); },
                    onTool(edit, result) { fixed = true; self.addEdit(editsWrap, edit, result); },
                    onError(msg) { self.addEdit(editsWrap, { tool: "error" }, { ok: false, message: msg }); },
                    onDone(data) { if (data && data.chat_id) { self.chatId = data.chat_id; } }
                }
            );
            typing.remove();
            const txt = (done && done.response) ? done.response : "";
            if (txt) {
                const bubble = document.createElement("div");
                bubble.className = "mw-ai-conv-msg-bubble";
                bubble.textContent = (fixed ? "🔧 " : "✓ ") + txt;
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

    loadChat(id) {
        const url = mw.settings.site_url + "api/ai/chat-history/" + id;
        $.get(url).then((res) => {
            const data = res && res.data;
            const messages = (data && data.messages && data.messages.data) ? data.messages.data : [];
            this.chatId = id;
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
            this.input.focus();
        }).catch(() => {
            mw.notification && mw.notification.error(mw.lang("Could not load chat"));
        });
    }
}
