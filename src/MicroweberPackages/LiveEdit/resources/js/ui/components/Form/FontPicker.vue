<template>

  <div class="d-flex gap-2">


    <div class="dropdown">
      <button
          class="btn btn-outline btn-sm dropdown-toggle"
          type="button"
          id="fontDropdown"
          data-bs-toggle="dropdown"
          aria-haspopup="true"
          aria-expanded="false"
      >
        <div v-show="selectedFontFamily">

          <span class="font-picker-selected-font" :style="{ fontFamily: selectedFontFamily }">
            {{ selectedFontFamily }}
          </span>
        </div>
        <div v-show="!selectedFontFamily">
          <span class="font-picker-selected-font">
            Select Font
          </span>
        </div>


      </button>
      <ul class="dropdown-menu" aria-labelledby="fontDropdown">
        <li
            v-for="(fontFamily, index) in supportedFonts"
            :key="index"
            :class="{ 'active': fontFamily === selectedFontFamily }"
        >
        <a class="dropdown-item" href="#" @click="selectFont(fontFamily)"  :style="{ fontFamily: fontFamily }">
          {{ fontFamily }}
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
    selectedFontFamily: String,
    value: String,
    label: String,
  },

  methods: {
    loadMoreFonts() {
      mw.top().app.fontManager.manageFonts();
    },

    selectFont(fontFamily) {
      this.selectedFontFamily = fontFamily; // Update the selectedFontFamily data property
      this.$emit('change', fontFamily); // Emit the 'change' event with the selected font family
    }

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
      selectedFontFamily: null
    }
  }
}
</script>
