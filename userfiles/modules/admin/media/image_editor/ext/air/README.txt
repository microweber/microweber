The ext-air.js file should be included AFTER ext-all.js.

The resources/ext-air.css file should be included AFTER ext-all.css.

XTemplates must be loaded inline while the js file loads (not in an onready block) when used in the application sandbox. Take a look at samples/tasks/js/Templates.js for an example.

For additional date formats other than the ones defined in src/ext-air-adapter.js, you will need to specify them inline similar to XTemplates using Date.precompileFormats(). For multiple formats, separate them with the | character.

