<template>
    <div v-if="showModal" style="visibility: hidden; position: absolute; width: 1px; height: 1px;"></div>
    <div v-if="showModal" v-on:click="showModal = false" class="mw-le-overlay active"></div>

    <Transition
        enter-active-class="animate__animated animate__zoomIn"
        leave-active-class="animate__animated animate__zoomOut"
    >
        <div v-if="showModal"
             class="mw-le-dialog-block mw-le-layouts-dialog w-100 active"
             style="inset:20px; transform:none; animation-duration: .3s;"
        >
            <!-- Close Button -->
            <button
                type="button"
                class="mw-le-dialog-close-btn"
                @click="showModal = false"
                aria-label="Close"
                style="position:absolute;top:16px;right:16px;z-index:10;background:none;border:none;font-size:2rem;line-height:1;cursor:pointer;"
            >
                &times;
            </button>

            <div class="row mw-font-picker-modal-wrapper form-control-live-edit-wrapper">
                <div class="col-md-4 h-auto bg-white">
                    <div class="mt-3 ms-2">
                        <input
                            v-model="filterKeyword"
                            type="text"
                            class="form-control-live-edit-input"
                            placeholder="Search fonts..."
                        />
                    </div>

                    <div class="d-flex flex-column align-items-start gap-2 mt-3 ms-3">
                        <button
                            v-for="(categoryName, categoryKey) in categories"
                            :key="categoryKey"
                            @click="filterCategorySubmit(categoryKey)"
                            class="btn btn-link mw-admin-action-links mw-adm-liveedit-tabs"
                            :class="{'active': categoryKey === filterCategory}"
                        >
                            {{ categoryName }}
                        </button>
                    </div>
                </div>

                <div class="col-md-8 bg-white">
                    <div class="pr-5">
                        <div v-if="fontsLoaded">
                            <div v-for="font in fontsFiltered" :key="font.family"
                                 class="d-flex justify-content-between px-3">
                                <div>
                                    <button
                                        type="button"
                                        @click="selectFont(font.family)"
                                    >
                                    <span :style="{
                                        fontSize: '18px',
                                        fontFamily: `'${font.family}', sans-serif`
                                    }">
                                        {{ font.family }}
                                    </span>
                                    </button>
                                </div>
                                <div>
                                    <div v-if="isFavorite(font.family)" class="pr-3"
                                         @click="removeFavorite(font.family)">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                             viewBox="0 0 24 24">
                                            <path fill="currentColor"
                                                  d="M22 10.1c.1-.5-.3-1.1-.8-1.1l-5.7-.8L12.9 3c-.1-.2-.2-.3-.4-.4c-.5-.3-1.1-.1-1.4.4L8.6 8.2L2.9 9c-.3 0-.5.1-.6.3c-.4.4-.4 1 0 1.4l4.1 4l-1 5.7c0 .2 0 .4.1.6c.3.5.9.7 1.4.4l5.1-2.7l5.1 2.7c.1.1.3.1.5.1h.2c.5-.1.9-.6.8-1.2l-1-5.7l4.1-4c.2-.1.3-.3.3-.5z"/>
                                        </svg>
                                    </div>
                                    <div v-else class="pr-3" @click="addFavorite(font.family)">
                                        <svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960"
                                             width="24">
                                            <path
                                                d="m354-247 126-76 126 77-33-144 111-96-146-13-58-136-58 135-146 13 111 97-33 143ZM233-80l65-281L80-550l288-25 112-265 112 265 288 25-218 189 65 281-247-149L233-80Zm247-350Z"/>
                                        </svg>
                                    </div>
                                </div>
                            </div>

                            <div v-if="fontsFiltered.length === 0" class="p-3">
                                No fonts found matching your criteria.
                            </div>

                            <!-- Modified pagination section -->
                            <div class="mt-4 d-flex justify-content-center">
                                <nav aria-label="Font pagination">
                                    <ul class="pagination pagination-sm mw-compact-pagination">
                                        <li class="page-item" :class="{ disabled: currentPage === 1 }">
                                            <button class="page-link" @click="changePage(currentPage - 1)"
                                                    :disabled="currentPage === 1">
                                                &laquo;
                                            </button>
                                        </li>
                                        <li v-if="getVisiblePages()[0] > 1" class="page-item">
                                            <button class="page-link" @click="changePage(1)">1</button>
                                        </li>
                                        <li v-if="getVisiblePages()[0] > 2" class="page-item disabled">
                                            <span class="page-link">...</span>
                                        </li>
                                        <li v-for="page in getVisiblePages()" :key="page" class="page-item"
                                            :class="{ active: page === currentPage }">
                                            <button class="page-link" @click="changePage(page)">{{ page }}</button>
                                        </li>
                                        <li v-if="getVisiblePages()[getVisiblePages().length-1] < totalPages - 1" class="page-item disabled">
                                            <span class="page-link">...</span>
                                        </li>
                                        <li v-if="getVisiblePages()[getVisiblePages().length-1] < totalPages" class="page-item">
                                            <button class="page-link" @click="changePage(totalPages)">{{ totalPages }}</button>
                                        </li>
                                        <li class="page-item" :class="{ disabled: currentPage === totalPages }">
                                            <button class="page-link" @click="changePage(currentPage + 1)"
                                                    :disabled="currentPage === totalPages">
                                                &raquo;
                                            </button>
                                        </li>
                                    </ul>
                                </nav>
                            </div>
                        </div>
                        <div v-else class="d-flex justify-content-center p-5">
                            <div class="spinner-border" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </Transition>

    <div v-if="showModal" v-on:click="showModal = false" class="mw-le-dialog-close active"></div>
</template>

<style>
.mw-font-picker-modal-wrapper {
    height: 100%;
    overflow: hidden;
}

.mw-font-picker-modal-wrapper .col-md-4 {
    border-right: 1px solid #eee;
    height: 100%;
    overflow-y: auto;
}

.mw-font-picker-modal-wrapper .col-md-8 {
    height: 100%;
    overflow-y: auto;
    padding: 20px;
}

.mw-font-picker-modal-wrapper button.active {
    font-weight: bold;
    color: #0b5ed7;
}

.mw-font-picker-modal-wrapper .btn-link {
    text-align: left;
    text-decoration: none;
}

/* Added compact pagination styles */
.mw-compact-pagination {
    margin-bottom: 0;
}
.mw-compact-pagination .page-link {
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
    min-width: 32px;
    text-align: center;
}
</style>

<script>
export default {
    components: {},

    mounted() {
        const instance = this;

        // Initialize font manager listener
        mw.app.on('showFontsManager', (params) => {
            this.showModal = true;
            this.params = params;

            if (params && params.applySelectionToElement) {
                this.applyToSelectedElement = params.applySelectionToElement;
            }

            this.fetchFonts();
        });
        mw.app.on('hideFontsManager', (params) => {
            this.showModal = false;
            this.params = null;
            this.applyToSelectedElement = null;
        });

        // Close on Escape
        document.addEventListener('keyup', function (evt) {
            if (evt.keyCode === 27) {
                instance.showModal = false;
            }
        });
    },

    watch: {
        filterKeyword: function (newValue, oldValue) {
            console.log("filter keyword:" + newValue);
            this.filterFonts();
        },
        filterCategory: function (newValue, oldValue) {
            console.log("filter category:" + newValue);
            this.filterFonts();
        }
    },

    data() {
        return {
            showModal: false,
            filterKeyword: '',
            filterCategory: 'all',
            fonts: [],
            fontsFiltered: [],
            fontsLoaded: false,
            favorites: [],
            categories: {
                'all': 'All',
                'favorites': 'Favorites',
                'cyrillic': 'Cyrillic',
                'latin': 'Latin',
                'sans-serif': 'Sans Serif',
                'handwriting': 'Handwriting',
                'display': 'Display'
            },
            currentPage: 1,
            itemsPerPage: 20,
            totalPages: 1,
            params: null,
            applyToSelectedElement: null
        }
    },

    methods: {
        fetchFonts() {
            this.fontsLoaded = false;

            // Use fetch API to get fonts from the server
            fetch(mw.settings.api_url + 'template/get-fonts')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        this.fonts = data.data || [];
                        this.fetchFavorites();
                    } else {
                        console.error('Error fetching fonts:', data.message);
                    }
                })
                .catch(error => {
                    console.error('Error fetching fonts:', error);
                });
        },

        fetchFavorites() {
            fetch(mw.settings.api_url + 'template/get-favorite-fonts')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        this.favorites = data.data || [];
                        this.markFavorites();
                        this.filterFonts();
                        this.fontsLoaded = true;
                    } else {
                        console.error('Error fetching favorite fonts:', data.message);
                    }
                })
                .catch(error => {
                    console.error('Error fetching favorite fonts:', error);
                    this.fontsLoaded = true;
                    this.filterFonts();
                });
        },

        markFavorites() {
            this.fonts = this.fonts.map(font => {
                return {
                    ...font,
                    favorite: this.favorites.includes(font.family)
                };
            });
        },

        filterFonts() {
            let filtered = [...this.fonts];

            // Filter by keyword if present
            if (this.filterKeyword && this.filterKeyword.trim() !== '') {
                const keyword = this.filterKeyword.toLowerCase().trim();
                filtered = filtered.filter(font =>
                    font.family.toLowerCase().includes(keyword)
                );
            }

            // Filter by category if not 'all'
            if (this.filterCategory !== 'all') {
                if (this.filterCategory === 'favorites') {
                    filtered = filtered.filter(font => font.favorite);
                } else {
                    filtered = filtered.filter(font => {
                        if (font.category === this.filterCategory) {
                            return true;
                        }
                        if (font.subsets && font.subsets.includes(this.filterCategory)) {
                            return true;
                        }
                        return false;
                    });
                }
            }

            // Calculate total pages
            this.totalPages = Math.ceil(filtered.length / this.itemsPerPage);
            if (this.currentPage > this.totalPages) {
                this.currentPage = 1;
            }

            // Paginate results
            const startIndex = (this.currentPage - 1) * this.itemsPerPage;
            this.fontsFiltered = filtered.slice(startIndex, startIndex + this.itemsPerPage);

            // Load fonts in view
            this.fontsFiltered.forEach(font => {
                if (mw.top().app.fontManager) {
                    mw.top().app.fontManager.loadNewFontTemp(font.family);
                }
            });
        },

        // Added method to get visible page numbers
        getVisiblePages() {
            const maxVisiblePages = 3; // Show maximum 3 pages in the middle
            let startPage = Math.max(1, this.currentPage - Math.floor(maxVisiblePages / 2));
            let endPage = Math.min(this.totalPages, startPage + maxVisiblePages - 1);

            // Adjust start page if end page is at maximum
            if (endPage === this.totalPages) {
                startPage = Math.max(1, endPage - maxVisiblePages + 1);
            }

            const visiblePages = [];
            for (let i = startPage; i <= endPage; i++) {
                visiblePages.push(i);
            }
            return visiblePages;
        },

        changePage(page) {
            if (page >= 1 && page <= this.totalPages) {
                this.currentPage = page;
                this.filterFonts();
            }
        },

        filterCategorySubmit(category) {
            this.filterCategory = category;
            this.currentPage = 1;
        },

        selectFont(family) {
            if (mw.top().app.fontManager) {
                mw.top().app.fontManager.selectFont(family);
                mw.top().app.fontManager.loadNewFontTemp(family);
            }

            this.showModal = false;
        },

        isFavorite(family) {
            const font = this.fonts.find(f => f.family === family);
            return font && font.favorite;
        },

        addFavorite(family) {
            fetch(mw.settings.api_url + 'template/save-template-fonts', {
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify({fonts: [family]})
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Update local state
                        const fontIndex = this.fonts.findIndex(f => f.family === family);
                        if (fontIndex !== -1) {
                            this.fonts[fontIndex].favorite = true;
                            if (!this.favorites.includes(family)) {
                                this.favorites.push(family);
                            }
                        }
                        this.filterFonts();
                    }
                })
                .catch(error => {
                    console.error('Error adding favorite:', error);
                });

            // Update locally immediately for better UX
            const fontIndex = this.fonts.findIndex(f => f.family === family);
            if (fontIndex !== -1) {
                this.fonts[fontIndex].favorite = true;
                if (!this.favorites.includes(family)) {
                    this.favorites.push(family);
                }
            }
        },

        removeFavorite(family) {
            fetch(mw.settings.api_url + 'template/remove-favorite-font', {
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify({font: family})
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Update local state
                        const fontIndex = this.fonts.findIndex(f => f.family === family);
                        if (fontIndex !== -1) {
                            this.fonts[fontIndex].favorite = false;
                        }
                        this.favorites = this.favorites.filter(f => f !== family);
                        this.filterFonts();
                    }
                })
                .catch(error => {
                    console.error('Error removing favorite:', error);
                });

            // Update locally immediately for better UX
            const fontIndex = this.fonts.findIndex(f => f.family === family);
            if (fontIndex !== -1) {
                this.fonts[fontIndex].favorite = false;
            }
            this.favorites = this.favorites.filter(f => f !== family);
        }
    }
}
</script>
