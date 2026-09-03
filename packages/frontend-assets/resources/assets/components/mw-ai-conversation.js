import MicroweberBaseClass from "../api-core/services/containers/base-class.js";

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
.mw-ai-conv-history-item .t{ font-weight:500; }
.mw-ai-conv-history-item .s{ font-size:12px; color:#8a94a3; }
.mw-ai-conv-history-list{ overflow-y:auto; flex:1; }
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
            <form class="mw-ai-conv-form">
                <textarea class="mw-ai-conv-input" rows="1" placeholder="${mw.lang("Ask AI to change your site…")}" aria-label="${mw.lang("Message AI assistant")}"></textarea>
                <button type="submit" class="mw-ai-conv-send" disabled aria-label="${mw.lang("Send")}">${this.icon("send") || "→"}</button>
            </form>
            <div class="mw-ai-conv-history">
                <div class="mw-ai-conv-head">
                    <div class="mw-ai-conv-title">${mw.lang("Chat history")}</div>
                    <button type="button" class="mw-ai-conv-iconbtn mw-ai-conv-history-close" aria-label="${mw.lang("Close")}">${this.icon("close") || "✕"}</button>
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
            this.sendBtn.disabled = !this.input.value.trim() || this.pending;
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
            if (v && !this.pending) { this.send(v); }
        });

        this.root.querySelector(".mw-ai-conv-new-btn").addEventListener("click", () => this.newChat());
        this.root.querySelector(".mw-ai-conv-history-btn").addEventListener("click", () => this.openHistory());
        this.root.querySelector(".mw-ai-conv-history-close").addEventListener("click", () => this.closeHistory());
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

    addMessage(role, text) {
        // Clear the empty state on first real message.
        const empty = this.thread.querySelector(".mw-ai-conv-empty");
        if (empty) { empty.remove(); }

        const msg = document.createElement("div");
        msg.className = "mw-ai-conv-msg " + role;
        const bubble = document.createElement("div");
        bubble.className = "mw-ai-conv-msg-bubble";
        bubble.textContent = text || "";
        msg.appendChild(bubble);
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
        if (t === "add_section") { return mw.lang("Added a section"); }
        if (t === "insert_module") { return mw.lang("Inserted module") + (a.type ? " · " + a.type : ""); }
        if (t === "set_module_option") { return mw.lang("Configured module") + (a.key ? " · " + a.key : ""); }
        if (t === "apply_css") { return mw.lang("Applied styles"); }
        if (t === "set_text") { return mw.lang("Updated text") + (a.selector ? " · " + a.selector : ""); }
        if (t === "set_image") { return mw.lang("Replaced image") + (a.selector ? " · " + a.selector : ""); }
        if (t === "generate_image") { return mw.lang("Generated image"); }
        if (t === "get_page_context") { return mw.lang("Read the page"); }
        return t || mw.lang("Edit");
    }

    addEdit(container, edit, result) {
        const ok = !result || result.ok !== false;
        const chip = document.createElement("div");
        chip.className = "mw-ai-conv-edit" + (ok ? "" : " err");
        const ic = ok ? (this.icon("check") || "✓") : (this.icon("close") || "✕");
        chip.innerHTML = `${ic}<span>${this.editLabel(edit)}</span>`;
        if (!ok && result && result.message) {
            chip.innerHTML += ` <code>${String(result.message)}</code>`;
        }
        container.appendChild(chip);
        this.scrollDown();
    }

    setPending(v) {
        this.pending = v;
        this.sendBtn.disabled = v || !this.input.value.trim();
        this.input.disabled = v;
    }

    async send(text) {
        this.setPending(true);
        this.addMessage("user", text);
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
        const self = this;

        try {
            const done = await MwAi().agentChatStream(
                text,
                { chat_id: this.chatId || undefined, content_id: this.settings.contentId || undefined },
                {
                    onStart(data) {
                        if (data && data.chat_id) { self.chatId = data.chat_id; }
                    },
                    onTool(edit, result) {
                        anyEdit = true;
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

            // Nudge Live Edit that there are unsaved changes.
            if (anyEdit) {
                try { mw.top().app.registerAskUserToStay(true); } catch (e) {}
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

    openHistory() {
        this.historyPanel.classList.add("open");
        this.loadChats();
    }

    closeHistory() {
        this.historyPanel.classList.remove("open");
    }

    loadChats() {
        this.historyList.innerHTML = `<div class="mw-ai-conv-history-item"><span class="s">${mw.lang("Loading…")}</span></div>`;
        const url = mw.settings.site_url + "api/ai/user-chats";
        $.get(url).then((res) => {
            const list = (res && res.data && res.data.data) ? res.data.data : [];
            if (!list.length) {
                this.historyList.innerHTML = `<div class="mw-ai-conv-history-item"><span class="s">${mw.lang("No previous chats")}</span></div>`;
                return;
            }
            this.historyList.innerHTML = "";
            list.forEach((chat) => {
                const item = document.createElement("div");
                item.className = "mw-ai-conv-history-item";
                const last = (chat.messages && chat.messages[0]) ? chat.messages[0].content : "";
                item.innerHTML = `<span class="t">${chat.title || mw.lang("Chat")}</span><span class="s">${(last || "").slice(0, 60)}</span>`;
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
            this.thread.innerHTML = "";
            messages.forEach((m) => {
                if (m.role === "user" || m.role === "assistant") {
                    this.addMessage(m.role, m.content);
                }
            });
            if (!messages.length) { this.renderEmpty(); }
            this.closeHistory();
        }).catch(() => {
            mw.notification && mw.notification.error(mw.lang("Could not load chat"));
        });
    }
}
