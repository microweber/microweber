<template>
    <div class="mx-2" v-if="isReady">
        <div class="custom-dropdown">
            <a role="button" class="dropdown-trigger"
               :aria-expanded="dropdownOpen.toString()" @click.prevent="toggleDropdown">
                <span :class="flagClass"></span>
                {{ languages[currentLanguage] }}
            </a>

            <ul class="dropdown-content" :class="{ 'show': dropdownOpen }"
                ref="multilanguageSwticherSettingsDropdown">
                <li v-for="(language,locale) in languages" :key="locale">
                    <a @click="changeLang(locale)" :class="{ active: currentLanguage == locale }">
                        <span :class="'mw-flag-icon mw-flag-icon-' + languagesIcons[locale]"></span>
                        {{ language }}
                    </a>
                </li>

                <li class="settings-item">
                    <a @click="showLangSettings">
                        <span class="mdi mdi-cog"></span>
                        Settings
                    </a>
                </li>
            </ul>
        </div>
    </div>
</template>

<style>
.custom-dropdown {
    position: relative;
    display: inline-block;
}

.dropdown-trigger {
    cursor: pointer;
    padding: 8px 14px 8px 10px;
    display: flex;
    align-items: center;
    gap: 8px;
    background: #fff;
    border-radius: 7px;
    border: 1px solid #e0e0e0;
    box-shadow: none;
    transition: border 0.2s, box-shadow 0.2s;
    min-width: 120px;
    font-size: 14px;
    font-weight: 400;
    line-height: 20px;
    color: #222;
    outline: none;
    position: relative;
    height: 35px;
    max-height: 35px;
}

.dropdown-trigger:focus,
.dropdown-trigger:hover {
    border: 1px solid #c0c0c0;
    box-shadow: none;
    background: #f5f5f5;
}

.dropdown-content {
    display: none;
    position: absolute;
    top: 110%;
    right: 0;
    background-color: #fff;
    min-width: 180px;
    box-shadow: 0 8px 24px rgba(0,0,0,0.13);
    border-radius: 8px;
    padding: 8px 0;
    z-index: 1000;
    border: 1px solid #e0e0e0;
    transition: opacity 0.18s;
}

.dropdown-content.show {
    display: block;
}

.dropdown-content li {
    list-style: none;
}

.dropdown-content li a {
    display: flex;
    align-items: center;
    padding: 10px 20px;
    text-decoration: none;
    color: #222;
    cursor: pointer;
    font-size: 15px;
    border-radius: 5px;
    transition: background 0.15s, color 0.15s;
    gap: 10px;
}

.dropdown-content li a:hover,
.dropdown-content li a:focus {
    background-color: #f0f4fa;
    color: #1976d2;
}

.dropdown-content li a.active {
    background-color: #e3f2fd;
    color: #1976d2;
    font-weight: 600;
}

.flag-icon {
    margin-right: 0;
    width: 22px;
    height: 16px;
    border-radius: 3px;
    box-shadow: 0 1px 2px rgba(0,0,0,0.07);
    object-fit: cover;
    background: #eee;
}

.settings-item {
    border-top: 1px solid #f0f0f0;
    margin-top: 6px;
    padding-top: 6px;
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
            dropdownOpen: false
        }
    },
    computed: {
        flagClass() {
            return 'mw-flag-icon mw-flag-icon-' + this.languagesIcons[this.currentLanguage];
        }
    },
    components: {},
    methods: {
        toggleDropdown() {
            if (this.$refs.multilanguageSwticherSettingsDropdown && this.$refs.multilanguageSwticherSettingsDropdown.classList) {
                this.$refs.multilanguageSwticherSettingsDropdown.classList.toggle('show');
                this.dropdownOpen = !this.dropdownOpen;
            }
        },
        hideLangDropdown() {
            if (this.$refs.multilanguageSwticherSettingsDropdown && this.$refs.multilanguageSwticherSettingsDropdown.classList) {
                this.$refs.multilanguageSwticherSettingsDropdown.classList.remove('show');
                this.dropdownOpen = false;
            }
        },
        showLangSettings() {
            if (this.$refs.multilanguageSwticherSettingsDropdown && this.$refs.multilanguageSwticherSettingsDropdown.classList) {
                this.$refs.multilanguageSwticherSettingsDropdown.classList.remove('show');
                this.dropdownOpen = false;
            }
             // go to admin settings page

            var url = mw.settings.adminUrl + 'multilanguage-settings-admin';

            //open in new tab

           window.open(url, '_blank');

            // or you can use mw.url.open(url);
            // mw.url.open(url);


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
                    this.languages[item.locale] = item.display_name ?? item.language;
                    this.languagesIcons[item.locale] = item.icon;
                })
                this.currentLanguage = liveEditIframeData.multiLanguageCurrentLanguage;

                this.isReady = true;
            }


        })
    }
}
</script>
