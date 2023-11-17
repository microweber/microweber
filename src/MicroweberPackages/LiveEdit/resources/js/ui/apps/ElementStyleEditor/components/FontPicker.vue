<template>
  <div class="form-control-live-edit-label-wrapper my-4 d-flex align-items-center flex-wrap gap-2">

    <label class="live-edit-label px-0 col-4">Font</label>
      <button
          class="form-control-live-edit-input form-select"
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
            Select
          </span>
        </div>
      </button>
      <ul class="dropdown-menu" aria-labelledby="fontDropdown">


          <li>
              <a class="dropdown-item" @click="selectFont('')">
                  Default
              </a>

          </li>

          <li
            v-for="(fontFamilyItem, index) in supportedFonts"
            :key="index"
            :class="{ 'active': fontFamilyItem === fontFamily }">
          <a class="dropdown-item" href="#" @click="selectFont(fontFamilyItem)" :style="{ fontFamily: fontFamilyItem }">
            {{ fontFamilyItem }}
          </a>
        </li>
      </ul>
        <small class="cursor-pointer ms-auto" v-on:click="loadMoreFonts()">Load more fonts </small>

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
