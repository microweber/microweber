<template>
  <div>

      <div>
          <b class="mw-admin-action-links" v-on:click="toggleClasses">
              Classes 
          </b>
      </div>

      <div v-if="showClasses">

          <div>
              <input type="hidden" v-model="classesToKeepInput" @input="updateClassesToKeep"/>

              <vue3-tags-input :tags="classes"
                               placeholder="Enter classes separated by commas"
                               :validate="customValidateClasses"

                               @on-tags-changed="handleChangeClasses"
              />
          </div>


     </div>

  </div>
</template>

<script>
import Vue3TagsInput from 'vue3-tags-input';

export default {
  components: {
    Vue3TagsInput
  },
  data() {
    return {
    showClasses: false,
      activeNode: null,
      isReady: false,
      classesToKeepInput: '',
      classesToKeep: [
        'element',
        'no-typing',
        'safe-mode',
        'edit',
        'nodrop',
        'allow-drop',
        'module',
        'module-overlap',
        'module-overlap-on-hover',
        'allow-typing',
        'allow-edit',
        'img-as-background',
        'image-holder',
      ],
      classes: []
    };
  },
  methods: {
      toggleClasses: function () {
          this.showClasses = !this.showClasses;
          this.emitter.emit('element-style-editor-show', 'classes');
      },
    customValidateClasses(value) {
      // Use regex to validate input classes
      const regex = /^[a-zA-Z\s,]*$/;
      const validClasses = value
          .split(',')
          .map(className => className.trim())
          .filter(className => regex.test(className) && this.classesToKeep.includes(className));
      return validClasses;
    },
    handleChangeClasses(classes) {
      this.classes = classes;
    },
    updateClassesToKeep() {
      this.classesToKeep = this.classesToKeepInput.split(',').map(className => className.trim());
    },
    applyClasses() {
      if (!this.isReady) {
        return;
      }
      if (this.activeNode) {
        // Remove any existing classes from the active node
        this.activeNode.className = '';

        // Add the new classes to the active node from classes array
        this.classes.forEach(className => {
          if (className) {
            this.activeNode.classList.add(className);
          }
        });

        // Add the new classes from classesToKeepInput
        if (this.classesToKeepInput) {
          const additionalClasses = this.classesToKeepInput.split(',')
              .map(className => className.trim())
              .filter(className => !!className);

          additionalClasses.forEach(className => {
            if (!this.activeNode.classList.contains(className)) {
              this.activeNode.classList.add(className);
            }
          });
        }

        if(mw.top().app){
          mw.top().app.registerChangedState(this.activeNode);
        }
      }
    },

    populateStyleEditor(element) {
      if (element && element.nodeType === 1) {
        this.isReady = false;
        this.activeNode = element;

        // Populate the classes array with the existing classes of the active node
        this.classes = this.activeNode.className
            .split(' ')
            .filter(className => !!className)
            .filter(className => {
              // Filter out classes that start with "animate__"
              if (className.startsWith('animate__')) {
                return false;
              }
              return !this.classesToKeep.includes(className);
            });


        // Check if the activeNode has any classes to include in classesToKeepInput
        const activeNodeClasses = this.activeNode.className.split(' ');
        const classesToInclude = this.classesToKeep.filter(className =>
            activeNodeClasses.includes(className)
        );
        this.classesToKeepInput = classesToInclude.join(', ');


        setTimeout(() => {
          this.isReady = true;
        }, 100);
      }
    }

  },
  mounted() {
    mw.top().app.on('mw.elementStyleEditor.selectNode', (element) => {
      this.populateStyleEditor(element);
    });
  },
  watch: {
    classes(newValue, oldValue) {
      // Apply the classes whenever the classes array changes
      this.applyClasses();
    },
  },
};
</script>
