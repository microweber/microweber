<template>
    <div class="mw-live-edit-right-sidebar-wrapper mx-2" v-if="isReady">
        <div class="dropdown btn-icon live-edit-toolbar-buttons ">
            <a role="button" id="dropdownLiveEditMenuLinkmultilanguageSwticherSettings" data-bs-toggle="dropdown"
               aria-expanded="false">
                <span :class="flagClass"></span>

                <!--                <svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24">
                                    <path
                                        d="M480-345 240-585l56-56 184 184 184-184 56 56-240 240Z"/>
                                </svg>-->
            </a>

            <ul class="dropdown-menu change-lang-dropdown-live-edit"
                aria-labelledby="dropdownLiveEditMenuLinkmultilanguageSwticherSettings"
                ref="multilanguageSwticherSettingsDropdown">


                <li class="dropdown-item" v-for="(language,locale) in languages" :key="locale">
                    <a @click="changeLang(locale)" :class="{active: currentLanguage == locale}">

                        <span :class="'flag-icon flag-icon-' + languagesIcons[locale]"></span>
                        {{ language }}
                    </a>
                </li>


                <li class="dropdown-item">
                    <a href="javascript:;" @click="showLangSettings">
                        <span class="mdi mdi-cog"></span>
                        Settings
                    </a>
                </li>


            </ul>
        </div>
    </div>
</template>
<style>
.change-lang-dropdown-live-edit .flag-icon {
    margin-right: 7px;
}


</style>
<script>


export default {
    data() {
        return {
            isReady: false,
            languages: {},
            languagesIcons: {},
            currentLanguage: false,
        }
    },
    computed: {
        flagClass() {
            return 'flag-icon flag-icon-' + this.languagesIcons[this.currentLanguage];
        }
    },
    components: {},
    methods: {
        hideLangDropdown() {
            if (this.$refs.multilanguageSwticherSettingsDropdown && this.$refs.multilanguageSwticherSettingsDropdown.classList) {
                this.$refs.multilanguageSwticherSettingsDropdown.classList.remove('show');
            }
        },
        showLangSettings() {
            if (this.$refs.multilanguageSwticherSettingsDropdown && this.$refs.multilanguageSwticherSettingsDropdown.classList) {
                this.$refs.multilanguageSwticherSettingsDropdown.classList.remove('show');
            }
            mw.tools.open_global_module_settings_modal('multilanguage/admin');
        },


        changeLang: function (name) {
            var from_url = mw.app.canvas.getDocument().location.href;
            $.post(mw.settings.api_url + "multilanguage/change_language", {
                locale: name,
                from_url: from_url,


            })
                .done(function (data) {
                    if (data.refresh) {
                        if (data.location) {
                            mw.app.canvas.getDocument().location.href = data.location;
                        } else {
                            mw.app.canvas.getDocument().location.reload();
                        }
                    }

                });

        }
    },

    mounted() {

        mw.app.canvas.on('canvasDocumentClick', event => {
            this.hideLangDropdown();

        });

        mw.app.canvas.on('liveEditCanvasBeforeUnload', event => {
            this.hideLangDropdown();
        });


        mw.app.on('populateSupportedLanguages', data => {


            if (!Array.isArray(data)) {
                return;
            }
            this.languages = {};
            data.forEach((item, index) => {
                this.languages[item.locale] = item.language;
                this.languagesIcons[item.locale] = item.icon;
            })

        });


        mw.app.canvas.on('liveEditCanvasLoaded', () => {


            var liveEditIframeData = mw.app.canvas.getLiveEditData();

            if (liveEditIframeData
                && liveEditIframeData.content
                && liveEditIframeData.content.id
                && liveEditIframeData.content.title
                && liveEditIframeData.multiLanguageIsEnabled
                && liveEditIframeData.multiLanguageCurrentLanguage
                && liveEditIframeData.multiLanguageEnabledLanguages
                && liveEditIframeData.multiLanguageEnabledLanguages.length > 0
                && liveEditIframeData.multiLanguageIsEnabled == 1
            ) {
                var cont_id = liveEditIframeData.content.id;


                liveEditIframeData.multiLanguageEnabledLanguages.forEach((item, index) => {
                    this.languages[item.locale] = item.language;
                    this.languagesIcons[item.locale] = item.icon;
                })
                this.currentLanguage = liveEditIframeData.multiLanguageCurrentLanguage;

                this.isReady = true;
            }


        })
    }
}
</script>
