<template>
  <div class="d-flex gap-2">
    <div class="dropdown">
      <button
          class="btn btn-outline btn-sm dropdown-toggle"
          type="button"
          ref="dropdownButton"
          id="fontDropdown"
          data-bs-toggle="dropdown"

          aria-haspopup="true"
          aria-expanded="false">
        <div v-show="fontFamily">
          <span class="font-picker-selected-font" :style="{ fontFamily: fontFamily }">
            {{ fontFamily }}
          </span>
        </div>
        <div v-show="!fontFamily">
          <span class="font-picker-selected-font">
            Select Font
          </span>
        </div>
      </button>
      <ul class="dropdown-menu" aria-labelledby="fontDropdown">
        <li
            v-for="(fontFamilyItem, index) in supportedFonts"
            :key="index"
            :class="{ 'active': fontFamilyItem === fontFamily }">
          <a class="dropdown-item" href="#" @click="selectFont(fontFamilyItem)" :style="{ fontFamily: fontFamilyItem }">
            {{ fontFamilyItem }}
          </a>
        </li>
      </ul>
    </div>
    <button type="button" class="btn btn-outline-dark btn-sm" v-on:click="loadMoreFonts()">
      Load more
    </button>
  </div>
</template>

<script>
export default {
  props: {
    value: String
  },
  watch: {
    value(newFontFamily) {
      this.fontFamily = newFontFamily;
    },
  },

  methods: {
    loadMoreFonts() {
      mw.top().app.fontManager.manageFonts();
    },

    selectFont(fontFamily) {
      this.fontFamily = fontFamily;
      this.$emit('change', fontFamily);
    },
  },

  mounted() {
    setTimeout(() => {
      this.supportedFonts = mw.top().app.fontManager.getFonts();
      this.$forceUpdate();

      mw.top().app.fontManager.subscribe((fonts) => {
        if (fonts) {
          this.supportedFonts = fonts;
        }
        this.$forceUpdate();
      });
    }, 1000);
  },
  data() {
    return {
      supportedFonts: [],
      fontFamily: this.value,
    };
  },
};
</script>
