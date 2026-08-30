<template>
    <div v-if="showModal" style="visibility: hidden; position: absolute; width: 1px; height: 1px;"></div>

    <!-- task-2026-06-13-letele2 — NO Teleport needed (position:fixed modal). The old
         teleport worked around a slide-panel transform:translateX(0) trap that is now
         fixed (control-box.scss active→none); #live-edit-app has no transform ancestor
         in any mode (canvas/control-boxes are siblings). See ListLayouts.vue for the
         full rationale. Don't add an ancestor transform on #live-edit-app's chain. -->
    <div v-if="false && showModal" v-on:click="hideModal" class="mw-le-overlay active"></div>

    <Transition
        enter-active-class="animate__animated animate__zoomIn"
        leave-active-class="animate__animated animate__zoomOut"
    >
        <div v-if="showModal"
             class=" mw-setup-wizard-modal active"
             style="animation-duration: .3s;"
             id="mw-setup-wizard-dialog"
        >            <!-- Close Button -->
            <button
                type="button"
                class="mw-le-dialog-close-btn"
                @click="hideModal"
                aria-label="Close"
                style="position:absolute;top:16px;right:16px;z-index:10;background:none;border:none;font-size:2rem;line-height:1;cursor:pointer;"
            >
                &times;
            </button>

            <!-- Wizard Header -->
            <div class="mw-setup-wizard-header">
                <h3 class="text-center mb-4">
                    <Lang>Setup Wizard</Lang>
                </h3>                <!-- Progress Bar -->
                <div class="mw-progress-container mb-4">
                    <!-- Progress Bar Track -->
                    <div class="mw-progress-track">
                        <div
                            class="mw-progress-fill"
                            :style="{ width: progressPercentage + '%' }"
                        ></div>
                    </div>

                    <!-- Step Indicators -->
                    <div class="mw-step-indicators">
                        <div
                            v-for="(step, index) in steps"
                            :key="index"
                            class="mw-step-indicator"
                            :class="{
                                'active': currentStep === index,
                                'completed': completedSteps.has(index) && currentStep !== index
                            }"
                            @click="navigateToStep(index)"
                        >
                            <div class="step-circle">
                                <span v-if="completedSteps.has(index) && currentStep !== index" class="step-check">✓</span>
                                <span v-else>{{ index + 1 }}</span>
                            </div>
                            <div class="step-label">{{ step.title }}</div>
                        </div>
                    </div>

                    <!-- Current Step Info (Mobile Only) -->
                    <div class="mw-current-step-info">
                        <span class="step-counter">{{ currentStep + 1 }} of {{ steps.length }}</span>
                        <span class="step-name">{{ steps[currentStep].title }}</span>
                    </div>
                </div>
            </div>            <!-- Wizard Content -->
            <div class="mw-setup-wizard-content p-4">                <!-- Website Info Step -->
                <div v-show="currentStep === 0" class="wizard-step-content">
                    <h4>Website Information</h4>
                    <div class="bg-light text-center">
                        <SetupWizardSiteInfo
                            @ai-request-start="handleAIRequestStart"
                            @ai-request-end="handleAIRequestEnd"
                            @form-submit-result="handleFormSubmitResult"
                            @exposeQuickEdit="getQuickEdit"
                        ></SetupWizardSiteInfo>



                    </div>
                </div>

                <!-- Full Styles Step -->
                <div v-show="currentStep === 1" class="wizard-step-content">
                    <h4>Styles</h4>
                    <div class="bg-light text-center">
                        <TemplateSettings setting="predefined-styles/full-styles"></TemplateSettings>
                    </div>
                </div>

                <!-- Colors Step -->
                <div v-show="currentStep === 2" class="wizard-step-content">
                    <h4>Colors</h4>
                    <div class="bg-light text-center">

                        <TemplateSettings setting="predefined-styles/website-colors"></TemplateSettings>

                    </div>
                </div>

                <!-- Buttons Step -->
                <div v-if="currentStep === 3" class="wizard-step-content">

                    <!-- This in on v-if because if colors change -->

                    <h4>Buttons</h4>
                    <div class="bg-light text-center">

                        <TemplateSettings setting="predefined-styles/button-styles"></TemplateSettings>

                    </div>
                </div>

                <!-- Fonts Step -->
                <div v-show="currentStep === 4" class="wizard-step-content">
                    <h4>Fonts</h4>
                    <div class="bg-light text-center">

                        <TemplateSettings setting="predefined-styles/text-styles"></TemplateSettings>

                    </div>
                </div>

            </div>

            <!-- Wizard Navigation -->
            <div class="wizard-navigation d-flex justify-content-between p-3 border-top">
                <button
                    @click="completeWizard"
                    :class="['btn', 'w-100', completedSteps.size > 0 ? 'btn-outline-success' : 'btn-outline-secondary']"
                >
                    {{ completedSteps.size > 0 ? 'Complete Setup' : 'Skip Setup' }}
                </button>
            </div>
            <div class="wizard-navigation d-flex justify-content-between p-3 border-top">






                <button
                    v-if="currentStep > 0"
                    @click="previousStep"
                    class="btn btn-outline-secondary"
                >
                    Previous
                </button>
                <div v-else></div>


                <button
                    v-if="currentStep < steps.length - 1"
                    @click="nextStep"
                    class="btn btn-primary"
                    :disabled="isAIProcessing"
                >
                    <span v-if="isAIProcessing">AI Processing...</span>
                    <span v-else> {{ getNextButtonText() }}</span>
                </button>

            </div>
        </div>
    </Transition>

    <div v-if="showModal" v-on:click="hideModal" class="mw-le-dialog-close active"></div>
</template>

<style>


/* Setup Wizard Modal */
.mw-setup-wizard-modal {
    --dark-bg:  rgb(var(--gray-900));
    max-width: 30vw !important;
    height: 100vh;
    min-height: 100vh;
    width: 400px;
    min-width: 310px;
    position: fixed !important;
    top: 0 !important;
    right: 0 !important;
    transform: none !important;
    margin: 0 !important;
    display: flex;
    flex-direction: column;
    z-index: 1050;
    background-color: white;
    box-shadow: -10px 0 20px #00000040;
}

html.dark .mw-setup-wizard-modal {
    background: var(--dark-bg)
}

/* Wizard Header */
.mw-setup-wizard-header {
    padding: 20px 20px 10px;
    border-bottom: 1px solid #e9ecef;
    background: #f8f9fa;
}
html.dark .mw-setup-wizard-header {
    border-bottom: 1px solid #e9ecef44;
    background: var(--dark-bg)
}

/* Progress Bar Styles */
.mw-progress-container {
    width: 100%;
    max-width: 600px;
    margin: 0 auto;
}


.mw-progress-track {
    width: 100%;
    height: 6px;
    background-color: #e9ecef;
    border-radius: 3px;
    overflow: hidden;
    margin-bottom: 20px;
    position: relative;
}

html.dark .mw-progress-track {
    background: var(--dark-bg)
}

.mw-progress-fill {
    height: 100%;
    background: linear-gradient(90deg, #0d6efd 0%, #0a58ca 100%);
    border-radius: 3px;
    transition: width 0.4s ease;
    position: relative;
}

.mw-progress-fill::after {
    content: '';
    position: absolute;
    top: 0;
    right: 0;
    width: 10px;
    height: 100%;
    background: linear-gradient(90deg, transparent 0%, rgba(255, 255, 255, 0.3) 100%);
}

/* Step Indicators */
.mw-step-indicators {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
    padding: 0 4px;
    position: relative;
    overflow: visible;
}

.mw-step-indicator {
    display: flex;
    flex-direction: column;
    align-items: center;
    cursor: pointer;
    transition: all 0.3s ease;
    z-index: 2;
}

.step-circle {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: #e9ecef;
    color: #6c757d;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 13px;
    transition: all 0.3s ease;
    margin-bottom: 8px;
    border: 2px solid transparent;
}


.step-label {
    font-size: 12px;
    font-weight: 500;
    color: #6c757d;
    text-align: center;
    transition: all 0.3s ease;
    white-space: nowrap;
    max-width: 80px;
    overflow: hidden;
    text-overflow: ellipsis;
}

/* Active and Completed States */
.mw-step-indicator.active .step-circle {
    background: #0d6efd;
    color: white;
    border-color: #0d6efd;
    box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.2);
}

.mw-step-indicator.active .step-label {
    color: #0d6efd;
    font-weight: 600;
}

.mw-step-indicator.completed .step-circle {
    background: #28a745;
    color: white;
    border-color: #28a745;
}

.mw-step-indicator.completed .step-label {
    color: #28a745;
}

.mw-step-indicator:hover:not(.active) .step-circle {
    background: #dee2e6;
    transform: scale(1.05);
}

/* Current Step Info (Hidden on Desktop) */
.mw-current-step-info {
    display: none;
    text-align: center;
    padding: 12px 16px;
    background: #f8f9fa;
    border-radius: 8px;
    margin-top: 15px;
}

.step-counter {
    font-size: 14px;
    color: #6c757d;
    margin-right: 10px;
}

.step-name {
    font-size: 16px;
    font-weight: 600;
    color: #495057;
}

/* Wizard Content */
.mw-setup-wizard-content {
    flex: 1;
    overflow-y: auto;
    min-height: 400px;
    max-height: calc(100vh - 190px); /* dvh is not supported in firefox (142) */
    max-height: calc(100dvh - 190px);
}

.wizard-step-content {
    padding: 0;
}

/* Color Preview */
.color-preview {
    width: 40px;
    height: 40px;
    border-radius: 4px;
    cursor: pointer;
    transition: transform 0.2s ease;
}

.color-preview:hover {
    transform: scale(1.1);
}

/* Wizard Navigation */
.wizard-navigation {
    margin-top: auto;
    background: #f8f9fa;
    border-top: 1px solid #e9ecef5b;
}

html.dark .wizard-navigation{
    background-color: var(--dark-bg);
}

/* Font Section (adapted from original) */
.mw-setup-wizard-content .col-md-4 {
    border-right: 1px solid #eee;
    height: 400px;
    overflow-y: auto;
    padding: 15px;
}

.mw-setup-wizard-content .col-md-8 {
    height: 400px;
    overflow-y: auto;
    padding: 15px;
}

/* Form styling improvements */
.form-label {
    font-weight: 500;
    margin-bottom: 0.5rem;

}

.form-control:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .mw-setup-wizard-modal {
        width: 95% !important;
        max-height: 85vh !important;
    }

    /* Mobile Progress Bar Adjustments */
    .mw-progress-container {
        max-width: 100%;
        padding: 0 10px;
    }

    .mw-step-indicators {
        margin-bottom: 10px;
    }

    .mw-step-indicator {
        flex: 1;
        max-width: none;
    }

    .step-circle {
        width: 28px;
        height: 28px;
        font-size: 12px;
        margin-bottom: 6px;
    }

    .step-label {
        font-size: 10px;
        max-width: 60px;
        line-height: 1.2;
    }

    /* Show current step info on mobile */
    .mw-current-step-info {
        display: block;
    }
}

@media (max-width: 480px) {
    /* Extra small screens - minimize step labels */
    .step-label {
        display: none;
    }

    .mw-current-step-info {
        margin-top: 20px;
        padding: 15px;
    }

    .step-counter {
        display: block;
        margin-bottom: 5px;
        margin-right: 0;
    }

    .step-name {
        font-size: 18px;
    }

    .mw-progress-track {
        height: 8px;
        margin-bottom: 25px;
    }

    .step-circle {
        width: 24px;
        height: 24px;
        font-size: 11px;
    }
}

/* Legacy font modal support */
.mw-font-picker-modal-wrapper {
    height: 100%;
    overflow: hidden;
}

/* Compact pagination styles */
.pagination-sm .page-link {
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
    min-width: 32px;
    text-align: center;
}

html.mw-setup-wizard-document #live-editor-frame {
    transform: scale(.75);
    width: 133.333%;
    height: 133.333%;
    transform-origin: center top;
    transition: .4s;
}

#wizard-ai-chat .edit-fields-container {
    display: none !important;
}

/* AI Trigger Button Styling */
.wizard-step-content .btn-outline-primary {
    border-color: #0d6efd;
    color: #0d6efd;
    transition: all 0.3s ease;
}

.wizard-step-content .btn-outline-primary:hover {
    background-color: #0d6efd;
    color: white;
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(0, 123, 255, 0.3);
}


</style>

<script>
import TemplateSettings from "../RightSidebar/TemplateSettings/TemplateSettings.vue";
import SetupWizardSiteInfo from "./SetupWizardSiteInfo.vue";
import * as api from "../../../api-core/services/services/preview.service.js";

export default {
    name: 'SetupWizard',
    components: {
        TemplateSettings,
        SetupWizardSiteInfo,
    },

    mounted() {
        const instance = this;        // Initialize setup wizard listener
        mw.app.on('showSetupWizard', (params) => {
            const prompt = (params?.prompt || '').trim();
            this.prompt = prompt;
            this.params = params;
            this.openModal();

        });

        mw.app.on('hideSetupWizard', () => {
            this.hideModal();
        });



         const isDark = mw.top().app.theme.isDark();
        document.querySelectorAll('iframe.preview-iframe[srcdoc]')
            .forEach(frame => frame.contentDocument.documentElement.classList[isDark ? 'add' : 'remove']('dark'))

        mw.top().app.theme.on('change', () => {
            const isDark = mw.top().app.theme.isDark();

            document.querySelectorAll('iframe.preview-iframe[srcdoc]')
            .forEach(frame => frame.contentDocument.documentElement.classList[isDark ? 'add' : 'remove']('dark'))

        })



    },

    data() {
        return {
            showModal: false,
            currentStep: 0,
            steps: [
                {title: 'Website Info', key: 'websiteInfo'},
                {title: 'Styles', key: 'styles'},
                {title: 'Colors', key: 'colors'},
                {title: 'Buttons', key: 'buttons'},
                {title: 'Fonts', key: 'fonts'}
            ],
            params: null,
            prompt: '',
            isAIProcessing: false,
            completedSteps: new Set(),

        }
    },

    computed: {
        progressPercentage() {
            return ((this.currentStep + 1) / this.steps.length) * 100;
        }
    },

    methods: {
        getNextButtonText() {
            if (this.currentStep < this.steps.length - 1) {
                const nextStep = this.steps[this.currentStep + 1];
                switch (nextStep.key) {
                    case 'styles':
                        return 'Select Styles';
                    case 'colors':
                        return 'Select Colors';
                    case 'buttons':
                        return 'Select Buttons';
                    case 'fonts':
                        return 'Select Fonts';
                    default:
                        return 'Next';
                }
            }
            return 'Next';
        },


        getQuickEdit(quickEditInstance) {
            console.log(quickEditInstance)

            if(this.prompt) {
                quickEditInstance.aiChatForm.area.value = this.prompt;
                quickEditInstance.ai(this.prompt);
            }
        },
        pagePreviewToggle: () => {
            //toggle  class to the body 'wizard-preview'

            document.body.classList.toggle('wizard-preview');

            api.pagePreviewToggle()
        },        // Wizard navigation methods
        nextStep() {


            /*
            if (this.currentStep === 0) {


                if(!this.isAIProcessing) {
                     this.triggerSiteInfoFormSubmit();
                }


                return;
            }*/


            if (this.currentStep < this.steps.length - 1) {
                this.completedSteps.add(this.currentStep);
                this.currentStep++;
            }
        },

        previousStep() {
            if (this.currentStep > 0) {
                this.currentStep--;
            }
        },

        navigateToStep(stepIndex) {
            // Allow navigation to any step
            if (stepIndex >= 0 && stepIndex < this.steps.length) {
                this.currentStep = stepIndex;
            }
        },

        // Handle form submit result from SiteInfo component
        handleFormSubmitResult(canAdvance) {


            // If form submission allows advancement and we're on step 0, advance to next step
            if (!canAdvance && this.currentStep === 0) {

                if (this.currentStep < this.steps.length - 1) {

                    this.currentStep++;
                }
            }
        },// AI request handlers
        handleAIRequestStart() {
            this.isAIProcessing = true;
        },

        handleAIRequestEnd() {
            this.isAIProcessing = false;
            this.completedSteps.add(0);
            // Auto-advance to next step after AI completes
            setTimeout(() => {
                this.nextStep();
            }, 1000); // Small delay to show completion
        },        // Trigger form submit in SiteInfo component
        triggerSiteInfoFormSubmit() {
            // Fallback to custom window event
            const event = new CustomEvent('setupWizard.triggerFormSubmit', {
                detail: {step: this.currentStep}
            });
            window.dispatchEvent(event);
        },

        completeWizard() {

            const event = new CustomEvent('setupWizard.complete', {
                detail: {step: this.currentStep, completed: true}

            });

            var canvasWindow = mw.app.canvas.getWindow();
            if(canvasWindow.mw && typeof canvasWindow.mw.drag === 'undefined'){
                btn.classList.remove('btn-loading');
                btn.disabled = false;
                mw.notification.error('Something went wrong with saving the page.',7500);
                return;
            }
            var saved = canvasWindow.mw.drag.save()


            this.hideModal();
        },

        openModal() {
            this.showModal = true;
            this.currentStep = 0;
            this.completedSteps = new Set();
            this.pagePreviewToggle();
            mw.top().doc.documentElement.classList.add('mw-setup-wizard-document');
            mw.top().doc.documentElement.classList.add('live-edit-gui-editor-opened');
            mw.top().controlBox.hideAll()

        },

        hideModal() {
            this.showModal = false;
            this.params = null;
            this.pagePreviewToggle();
            mw.top().doc.documentElement.classList.remove('mw-setup-wizard-document');
            if (!mw.top().controlBox.hasOpened('right')) {
                mw.top().doc.documentElement.classList.remove('live-edit-gui-editor-opened');
            }

        }
    }
}
</script>
