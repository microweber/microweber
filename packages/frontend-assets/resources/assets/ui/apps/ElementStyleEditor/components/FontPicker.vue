<template>
  <div class="form-control-live-edit-label-wrapper my-4">

    <label class="live-edit-label">Font</label>
      <button class="form-control-live-edit-input form-select" type="button">
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
              <a class="dropdown-item " @click="selectFont('')">
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
        <small class="cursor-pointer d-flex ms-auto justify-content-end" v-on:click="loadMoreFonts()">Add more fonts</small>

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
    dropdown: e => {
        let target = mw.tools.firstParentOrCurrentWithClass(e.target, 'form-control-live-edit-input'),
        parent;
        if(target) {
            parent = mw.tools.firstParentOrCurrentWithClass(target, 'form-control-live-edit-label-wrapper');

        }
        if(parent) {
            parent.classList.toggle('dropdown-active');
        }

        document.querySelectorAll('.dropdown-active').forEach(node => {
            if(node !== parent) {
                node.classList.remove('dropdown-active')
            }
        })




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
    document.body.addEventListener('click', this.dropdown)

  },
  data() {
    return {
      supportedFonts: [],
      fontFamily: this.value,
    };
  },
};
</script>
