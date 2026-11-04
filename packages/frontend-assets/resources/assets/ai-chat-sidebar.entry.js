/**
 * Standalone bundle that registers the global AI chat sidebar service
 * (window.mw.aiChat + the mw:ai-chat:* event listeners). Loaded as a plain
 * <script> on any surface that is not the Live-Edit Vue app — primarily the
 * Filament admin panel — so the chat can be opened from there via an event.
 * (Requires window.MwAi, provided by modules/ai/js/mw-ai.js, for streaming.)
 */
import "./services/mw-ai-chat-sidebar.js";
