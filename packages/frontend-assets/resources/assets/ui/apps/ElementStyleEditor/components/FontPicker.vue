<template>
  <div class="form-control-live-edit-label-wrapper my-4" ref="dropdownRoot">

    <label class="live-edit-label">Font</label>
      <button class="form-control-live-edit-input form-select" type="button" @click="handleButtonClick">
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
      <ul class="dropdown-menu form-control-live-edit-input" aria-labelledby="fontDropdown">
          <li>
              <a class="dropdown-item " @click="selectFontAndClose('')">
                  Default
              </a>

          </li>

          <li
            v-for="(fontFamilyItem, index) in supportedFonts"
            :key="index"
            :class="{ 'active': fontFamilyItem === fontFamily }">
          <span class="dropdown-item"  @click="selectFontAndClose(fontFamilyItem)" :style="{ fontFamily: fontFamilyItem }">
            {{ fontFamilyItem }}
          </span>
        </li>
      </ul>
        <small class="cursor-pointer d-flex ms-auto justify-content-end pt-3 pb-1" v-on:click="loadMoreFonts()">Add more fonts</small>

  </div>

</template>

<style scoped>
.font-picker-selected-font {
    display: block;
    white-space: nowrap;
    overflow: hidden;
    width: 100%;
    text-overflow: ellipsis;
}

  .dropdown-menu{
    width: 100%
  }
.dropdown-active > .dropdown-menu{

    display: block;
}


</style>

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

    selectFontAndClose(fontFamily) {
      this.selectFont(fontFamily);
      this.closeDropdown();
    },

    handleButtonClick() {
      const wrapper = this.$refs.dropdownRoot;
      if (!wrapper) return;

      const currentlyActive = wrapper.classList.contains('dropdown-active');

      // Close all other active dropdowns of this type
      document.querySelectorAll('.form-control-live-edit-label-wrapper.dropdown-active').forEach(node => {
        if (node !== wrapper) {
          node.classList.remove('dropdown-active');
        }
      });

      // Toggle the current dropdown
      if (currentlyActive) {
        wrapper.classList.remove('dropdown-active');
      } else {
        wrapper.classList.add('dropdown-active');
      }
    },

    closeDropdown() {
      if (this.$refs.dropdownRoot) {
        this.$refs.dropdownRoot.classList.remove('dropdown-active');
      }
    },

    handleBodyClick(event) {
      if (this.$refs.dropdownRoot && !this.$refs.dropdownRoot.contains(event.target)) {
        // Clicked outside of this component's dropdown area
        this.closeDropdown();
      }
    }
  },

  mounted() {
    setTimeout(() => {
      this.supportedFonts = mw.top().app.fontManager.getFonts();
      this.$forceUpdate(); // Consider if this is still needed or if Vue's reactivity handles it

      mw.top().app.fontManager.subscribe((fonts) => {
        if (fonts) {
          this.supportedFonts = fonts;
        }
        // this.$forceUpdate(); // Consider if this is still needed
      });
    }, 1000);
    document.addEventListener('click', this.handleBodyClick);
  },

  beforeUnmount() { // For Vue 3. Use beforeDestroy for Vue 2.
    document.removeEventListener('click', this.handleBodyClick);
  },

  data() {
    return {
      supportedFonts: [],
      fontFamily: this.value,
    };
  },
};
</script>
