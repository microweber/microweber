<template>
    <div v-if="showButton" class="mb-3 mw-live-edit-settings-back-button">
        <button @click="handleGoBack"
                class="d-flex gap-2 btn btn-link mw-live-edit-toolbar-link mw-live-edit-toolbar-link--arrowed text-start">
            <svg class="mw-live-edit-toolbar-arrow-icon" xmlns="http://www.w3.org/2000/svg" width="32"
                 height="32" viewBox="0 0 32 32">
                <g fill="none" stroke-width="1.5" stroke-linejoin="round" stroke-miterlimit="10">
                    <circle class="arrow-icon--circle" cx="16" cy="16" r="15.12"></circle>
                    <path class="arrow-icon--arrow" d="M16.14 9.93L22.21 16l-6.07 6.07M8.23 16h13.98"></path>
                </g>
            </svg>
            <div class="ms-1 font-weight-bold">{{ buttonText }}</div>
        </button>
    </div>
</template>

<script>
export default {
    name: 'FieldBackButton',
    props: {
        currentPath: {
            type: String,
            default: '/'
        },
        currentSetting: {
            type: Object,
            default: null
        },
        buttonText: {
            type: String,
            default: 'Back'
        },
        showButton: {
            type: Boolean,
            default: true
        }
    },
    emits: ['go-back'],
    methods: {
        handleGoBack() {
            let backPath = '/';

            if (this.currentSetting && this.currentSetting.backUrl) {
                backPath = this.currentSetting.backUrl;
            }

            this.$emit('go-back', backPath);
        }
    }
};
</script>
