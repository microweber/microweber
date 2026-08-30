<script setup>
import {ref, defineEmits, onMounted, onBeforeUnmount} from 'vue'
import axios from 'axios'
import {QuickEditComponent} from '../../../components/quick-ai-edit'

// Component props and emits
const emit = defineEmits(['update:siteTitle', 'update:siteDescription', 'update:siteKeywords', 'ai-request-start', 'ai-request-end', 'form-submit-result'])

// Reactive data
const siteTitle = ref('')
const siteDescription = ref('')
const siteKeywords = ref('')
const isSaving = ref(false)
const isLoading = ref(false)

// AI Integration
const isAIAvailable = ref(false)
const activeTab = ref('manual') // 'manual', 'ai'
const aiPrompt = ref('')
const aiLoading = ref(false)
const aiError = ref('')
let wizardAiChat = ref(null)


// Debounce timers for auto-save
let titleSaveTimeout = null
let descriptionSaveTimeout = null
let keywordsSaveTimeout = null
let showTabs = ref(true)

// Load website info from API
const loadWebsiteInfo = async () => {
    try {
        isLoading.value = true

        const response = await axios.get(mw.settings.api_url + 'live-edit/get-website-info', {
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || mw.cookie.get("XSRF-TOKEN")
            }
        })

        if (response.data) {
            // Set the values from API response
            if (response.data.title) {
                siteTitle.value = response.data.title
                emit('update:siteTitle', response.data.title)
            }

            if (response.data.description) {
                siteDescription.value = response.data.description
                emit('update:siteDescription', response.data.description)
            }

            if (response.data.keywords) {
                siteKeywords.value = response.data.keywords
                emit('update:siteKeywords', response.data.keywords)
            }
        }
    } catch (error) {
        console.error('Error loading website info:', error)
    } finally {
        isLoading.value = false
    }
}




onMounted(async () => {
    // Check AI availability
    checkAIAvailability()

    // Load website info
    await loadWebsiteInfo()

    const quickEdit = new QuickEditComponent({
        target: mw.top().doc.body,
        submitOnEnter: true,
        disableSync: true,
        generateSiteInfo: true,
        chatOptions: () => {

        }
    });









    quickEdit.on('aiRequestStart', () => {

        emit('ai-request-start')
    });
    quickEdit.on('aiRequestEnd', () => {
        aiLoading.value = false
        aiError.value = ''



        //must enable  the parent wizard next  button
        // advance the setup wizard on the next setep
        emit('ai-request-end')
    });    // Listen for wizard events to trigger form submission
    const handleWizardFormSubmit = async (event) => {
        // If AI is available and we're on the AI tab, don't allow manual advancement
        if (isAIAvailable.value && activeTab.value === 'ai') {


            if (quickEdit) {

                let val = quickEdit.aiChatForm.area.value;

                if (val) {

                    // quickEdit.aiChatForm.dispatch('formSubmit');
                    // quickEdit.dispatch('formSubmit');

                    await quickEdit.ai(val);

                    // await generateSiteInfoWithAI(val)

                    emit('form-submit-result', false);


                    return false;

                } else {
                    emit('form-submit-result', true);
                    return true;
                }
                // Prevent advancement when using AI - let AI completion handle it
                // return false;
            }
        }


    };

    // Fallback to window event listener
    window.addEventListener('setupWizard.triggerFormSubmit', handleWizardFormSubmit);

    // Store reference for cleanup


    setTimeout(() => {
    if(wizardAiChat.value) {
        wizardAiChat.value.appendChild(quickEdit.editor());
        wizardAiChat.value._wizardEventHandler = handleWizardFormSubmit;
        emit('exposeQuickEdit', quickEdit);
    }



    }, 300);


})

// AI Integration Methods
const checkAIAvailability = () => {
    try {
        if (typeof mw.top().win.MwAi === 'function') {
            isAIAvailable.value = true
            activeTab.value = 'ai'
            console.log('AI is available')
        } else {
            isAIAvailable.value = false
            console.log('AI is not available')
        }
    } catch (error) {
        isAIAvailable.value = false
        console.log('Error checking AI availability:', error)
    }
}

const switchTab = (tab) => {
    activeTab.value = tab
    aiError.value = ''
}



const generateSiteInfoWithAI = async (prompt) => {
    prompt = (prompt || '').trim();
    if (!prompt) {
        aiError.value = 'Please enter a description for your website'
        return
    }

    if (!isAIAvailable.value) {
        aiError.value = 'AI functionality is not available'
        return
    }
    try {
        aiLoading.value = true
        aiError.value = ''

        // Emit AI request start event


        const message = `Based on this website description: "${prompt}"

Please generate appropriate website information in JSON format with the following structure:
{
    "title": "website title (max 100 characters)",
    "description": "SEO description (max 160 characters)",
    "keywords": "relevant keywords separated by commas (max 200 characters)"
}

Make sure the content is relevant, professional, and optimized for SEO.`

        const messages = [{role: 'user', content: message}]

        const response = await window.mw.top().win.MwAi().sendToChat(messages, {
            schema: JSON.stringify({
                title: "",
                description: "",
                keywords: ""
            })
        })

        if (response.success && response.data) {
            // Populate the fields with AI-generated content
            if (response.data.title) {
                siteTitle.value = response.data.title
                emit('update:siteTitle', response.data.title)
                saveSiteTitle(response.data.title)
            }

            if (response.data.description) {
                siteDescription.value = response.data.description
                emit('update:siteDescription', response.data.description)
                saveSiteDescription(response.data.description)
            }

            if (response.data.keywords) {
                siteKeywords.value = response.data.keywords
                emit('update:siteKeywords', response.data.keywords)
                saveSiteKeywords(response.data.keywords)
            }


        } else {
            throw new Error('Invalid response from AI')
        }
    } catch (error) {
        console.error('AI generation error:', error)
        aiError.value = 'Failed to generate content with AI: ' + (error.message || 'Unknown error')
    } finally {
        aiLoading.value = false
    }
}

// Methods
const updateTitle = (value) => {
    siteTitle.value = value
    emit('update:siteTitle', value)

    // Clear previous timeout and set new one (debounce)
    if (titleSaveTimeout) {
        clearTimeout(titleSaveTimeout)
    }
    titleSaveTimeout = setTimeout(() => {
        saveSiteTitle(value)
    }, 1000) // Save after 1 second of no typing
}

const updateDescription = (value) => {
    siteDescription.value = value
    emit('update:siteDescription', value)

    // Clear previous timeout and set new one (debounce)
    if (descriptionSaveTimeout) {
        clearTimeout(descriptionSaveTimeout)
    }
    descriptionSaveTimeout = setTimeout(() => {
        saveSiteDescription(value)
    }, 1000) // Save after 1 second of no typing
}

const updateKeywords = (value) => {
    siteKeywords.value = value
    emit('update:siteKeywords', value)

    // Clear previous timeout and set new one (debounce)
    if (keywordsSaveTimeout) {
        clearTimeout(keywordsSaveTimeout)
    }
    keywordsSaveTimeout = setTimeout(() => {
        saveSiteKeywords(value)
    }, 1000) // Save after 1 second of no typing
}

// AJAX save functions
const saveSiteTitle = async (title) => {
    try {
        const response = await axios.post(mw.settings.api_url + 'save_option', {
            option_key: 'website_title',
            option_group: 'website',
            option_value: title
        }, {
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || mw.cookie.get("XSRF-TOKEN")
            }
        })

        if (response.data.is_saved) {
            console.log('Site title saved successfully')
        }
    } catch (error) {
        console.error('Error saving site title:', error)
    }
}

const saveSiteDescription = async (description) => {
    try {
        const response = await axios.post(mw.settings.api_url + 'save_option', {
            option_key: 'website_description',
            option_group: 'website',
            option_value: description
        }, {
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || mw.cookie.get("XSRF-TOKEN")
            }
        })

        if (response.data.is_saved) {
            console.log('Site description saved successfully')
        }
    } catch (error) {
        console.error('Error saving site description:', error)
    }
}

const saveSiteKeywords = async (keywords) => {
    try {
        const response = await axios.post(mw.settings.api_url + 'save_option', {
            option_key: 'website_keywords',
            option_group: 'website',
            option_value: keywords
        }, {
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || mw.cookie.get("XSRF-TOKEN")
            }
        })

        if (response.data.is_saved) {
            console.log('Site keywords saved successfully')
        }
    } catch (error) {
        console.error('Error saving site keywords:', error)
    }
}


// Cleanup
const cleanup = () => {
    // Clear timeouts
    if (titleSaveTimeout) clearTimeout(titleSaveTimeout)
    if (descriptionSaveTimeout) clearTimeout(descriptionSaveTimeout)
    if (keywordsSaveTimeout) clearTimeout(keywordsSaveTimeout)
    // Remove event listener
    if (wizardAiChat.value && wizardAiChat.value._wizardEventHandler) {

        // Remove from window events
        window.removeEventListener('setupWizard.triggerFormSubmit', wizardAiChat.value._wizardEventHandler);
    }
}

// Vue lifecycle hook for cleanup
onBeforeUnmount(() => {
    cleanup()
})
</script>

<template>
    <div class="site-info-component">
        <!-- Loading state -->
        <div v-if="isLoading" class="text-center py-4">
            <div class="spinner-border spinner-border-sm me-2" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <small class="text-muted">Loading website information...</small>
        </div>

        <!-- Main content -->
        <div v-else>

            <!-- Tabs Navigation (only show if AI is available) -->
            <div v-if="isAIAvailable && showTabs" class="tab-navigation mb-4">
                <div class="nav nav-tabs" role="tablist">
                    <button
                        class="nav-link"
                        :class="{ active: activeTab === 'manual' }"
                        @click="switchTab('manual')"
                        type="button"
                    >
                        Manual Setup
                    </button>
                    <button
                        class="nav-link"
                        :class="{ active: activeTab === 'ai' }"
                        @click="switchTab('ai')"
                        type="button"
                    >
                        AI Generator
                    </button>
                </div>
            </div>


            <!-- AI Tab Content -->
            <div v-show="isAIAvailable && activeTab === 'ai'" class="ai-tab-content">
                <div class="ai-generator-section">
                    <h5 class="mb-3">Generate with AI</h5>
                    <p class="text-muted mb-3">
                        Describe your website and let AI generate the title, description, keywords, and text for you.
                    </p>

                    <div id="wizard-ai-chat" ref="wizardAiChat"></div>

                    <!-- Simple AI Prompt Input -->
                    <div class="ai-prompt-container mb-3">


                        <div class="mt-2">

                            <small class="text-muted ms-2">Press Enter to submit</small>
                        </div>
                    </div>

                    <!-- Shared UI elements -->
                    <div v-if="aiLoading" class="text-center mt-2">AI is thinking...</div>
                    <div v-if="aiError" class="text-danger mt-2">{{ aiError }}</div>
                </div>
            </div>

            <!-- Manual Tab Content -->
            <div v-if="!isAIAvailable || activeTab === 'manual'"
                 class="manual-content">

                <!-- Site Title Section -->
                <div class="mb-4">
                    <label for="siteTitle" class="form-label">Site title</label>
                    <p class="text-muted small mb-2">This is the name of your site. You can change it later.</p>
                    <div class="position-relative">
                        <input
                            id="siteTitle"
                            v-model="siteTitle"
                            @input="updateTitle($event.target.value)"
                            type="text"
                            class="form-control"
                            placeholder="Enter your site title"
                            maxlength="100"
                        />
                        <span class="character-count">{{ siteTitle.length }}/100</span>
                    </div>
                </div>

                <!-- Site Description Section -->
                <div class="mb-4">
                    <label for="siteDescription" class="form-label">Site description</label>
                    <p class="text-muted small mb-2">A brief description of your site that appears in search engines and
                        social
                        media.</p>
                    <div class="position-relative">
            <textarea
                id="siteDescription"
                v-model="siteDescription"
                @input="updateDescription($event.target.value)"
                class="form-control"
                placeholder="Enter a brief description of your site"
                rows="3"
                maxlength="160"
            ></textarea>
                        <span class="character-count">{{ siteDescription.length }}/160</span>
                    </div>
                </div>

                <!-- Site Keywords Section -->
                <div class="mb-4">
                    <label for="siteKeywords" class="form-label">Keywords</label>
                    <p class="text-muted small mb-2">Important keywords that describe your site content. Separate
                        multiple
                        keywords with commas.</p>
                    <div class="position-relative">
                        <input
                            id="siteKeywords"
                            v-model="siteKeywords"
                            @input="updateKeywords($event.target.value)"
                            type="text"
                            class="form-control"
                            placeholder="e.g. business, consulting, web design"
                            maxlength="200"
                        />
                        <span class="character-count">{{ siteKeywords.length }}/200</span>
                    </div>
                </div>
            </div> <!-- End manual content -->
        </div>
    </div>
</template>

<style scoped>
.site-info-component {
    padding: 0;
}

.form-label {
    font-weight: 600;
    color: #333;
    margin-bottom: 0.5rem;
}

.form-control {
    border: 1px solid #ddd;
    border-radius: 4px;
    padding: 12px;
    font-size: 14px;
    transition: border-color 0.2s ease;
}

.form-control:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    outline: none;
}

.position-relative {
    position: relative;
}

.character-count {
    position: absolute;
    bottom: 8px;
    right: 12px;
    font-size: 12px;
    color: #666;
    background: white;
    padding: 2px 4px;
}

textarea.form-control {
    resize: vertical;
    min-height: 80px;
}

.personality-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
    gap: 12px;
    margin-top: 16px;
}

.personality-btn {
    padding: 12px 16px;
    border: 2px solid #e9ecef;
    background: white;
    border-radius: 4px;
    font-size: 14px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s ease;
    text-align: center;
}

.personality-btn:hover {
    border-color: #0d6efd;
    background: #f8f9fa;
}

.personality-btn.active {
    border-color: #0d6efd;
    background: #0d6efd;
    color: white;
}

.personality-btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

.text-muted {
    color: #6c757d !important;
}

.small {
    font-size: 0.875rem;
}

/* Tab Navigation */
.tab-navigation {
    border-bottom: 1px solid #e9ecef;
}

.nav-tabs {
    display: flex;
    border-bottom: none;
    margin-bottom: 0;
}

.nav-link {
    padding: 12px 20px;
    border: none;
    border-bottom: 2px solid transparent;
    background: none;
    color: #6c757d;
    cursor: pointer;
    transition: all 0.3s ease;
    font-weight: 500;
    margin-right: 8px;
}

.nav-link:hover {
    color: #0d6efd;
    border-bottom-color: #0d6efd;
}

.nav-link.active {
    color: #0d6efd;
    border-bottom-color: #0d6efd;
    background: none;
}

/* AI Tab Content */
.ai-tab-content {
    padding: 20px 0;
}

.ai-generator-section h5 {
    color: #333;
    font-weight: 600;
}

.ai-prompt-container textarea {
    resize: vertical;
    min-height: 100px;
}

.btn {
    padding: 8px 16px;
    border-radius: 4px;
    font-weight: 500;
    border: none;
    cursor: pointer;
    transition: all 0.2s ease;
}

.btn-primary {
    background-color: #0d6efd;
    color: white;
}

.btn-primary:hover:not(:disabled) {
    background-color: #0a58ca;
}

.btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

/* Alert styles */
.alert {
    padding: 12px 16px;
    border-radius: 4px;
    margin-bottom: 16px;
}

.alert-info {
    color: #0c5460;
    background-color: #d1ecf1;
    border: 1px solid #bee5eb;
}

.alert-danger {
    color: #721c24;
    background-color: #f8d7da;
    border: 1px solid #f5c6cb;
}

/* Manual/Advanced Content */
.manual-content {
    transition: all 0.3s ease;
}

/* Loading Spinner */
.spinner-border {
    display: inline-block;
    width: 1rem;
    height: 1rem;
    vertical-align: text-bottom;
    border: 0.125em solid currentColor;
    border-right-color: transparent;
    border-radius: 50%;
    animation: spinner-border 0.75s linear infinite;
}

.spinner-border-sm {
    width: 0.75rem;
    height: 0.75rem;
    border-width: 0.1em;
}

.visually-hidden {
    position: absolute !important;
    width: 1px !important;
    height: 1px !important;
    padding: 0 !important;
    margin: -1px !important;
    overflow: hidden !important;
    clip: rect(0, 0, 0, 0) !important;
    white-space: nowrap !important;
    border: 0 !important;
}

@keyframes spinner-border {
    to {
        transform: rotate(360deg);
    }
}

@media (max-width: 768px) {
    .personality-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}


</style>
