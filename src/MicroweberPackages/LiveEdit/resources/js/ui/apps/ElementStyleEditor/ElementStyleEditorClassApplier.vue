<template>
  <div>
    <textarea v-model="classInput" placeholder="Enter classes separated by commas"></textarea>
    <button @click="applyClasses">Apply Classes</button>
  </div>
</template>
<script>
export default {
  data() {
    return {
      activeNode: null,
      isReady: false,
      classInput: '', // Store the input value
    };
  },
  methods: {
    applyClasses() {
      if (!this.isReady) {
        return;
      }
      if (this.activeNode) {
        // Split the input string into an array of classes using commas as the separator
        const classesArray = this.classInput.split(',').map(classItem => classItem.trim());

        // Remove any existing classes from the active node
        this.activeNode.className = '';

        // Add the new classes to the active node
        classesArray.forEach(className => {
          if (className) {
            this.activeNode.classList.add(className);
          }
        });
      }
    },
    populateStyleEditor(element) {
      if (element && element.nodeType === 1) {
        this.isReady = false;
        this.activeNode = element;

        this.classInput = this.activeNode.className;
        setTimeout(() => {
          this.isReady = true;
        }, 100);
      }
    },
  },
  mounted() {

    mw.top().app.on('mw.elementStyleEditor.selectNode', (element) => {
      this.populateStyleEditor(element);
    });
  },
  watch: {
    // Add any watchers for data changes here
    // Example: activeNode(newValue, oldValue) { ... }
  },
};
</script>
