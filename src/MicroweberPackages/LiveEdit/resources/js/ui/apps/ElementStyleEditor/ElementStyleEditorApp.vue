<template>

  <div>
    aaaaaaaaa

    marginTop: {{ marginTop }}

    <input v-model="marginTop" placeholder="marginTop">
    <br>


    marginRight: {{ marginRight }}
    <input v-model="marginRight" placeholder="marginRight">
    <br>


    marginBottom: {{ marginBottom }}
    <input v-model="marginBottom" placeholder="marginBottom">
    <br>


    marginLeft: {{ marginLeft }}
    <input v-model="marginLeft" placeholder="marginLeft">
    <br>

    borderSize: {{ borderSize }}
    <input v-model="borderSize" placeholder="borderSize">

    <br>
    borderColor: {{ borderColor }}
    <input v-model="borderColor" placeholder="borderColor">
    <br>


    borderStyle: {{ borderStyle }}
    <input v-model="borderStyle" placeholder="borderStyle">
    <br>

    borderPosition: {{ borderPosition }}
    <input v-model="borderPosition" placeholder="borderPosition">
    <br>


    borderTopLeftRadius: {{ borderTopLeftRadius }}

    <input v-model="borderTopLeftRadius" placeholder="borderTopLeftRadius">


    borderTopRightRadius: {{ borderTopRightRadius }}
    <input v-model="borderTopRightRadius" placeholder="borderTopRightRadius">
    <br>


    borderBottomRightRadius: {{ borderBottomRightRadius }}
    <input v-model="borderBottomRightRadius" placeholder="borderBottomRightRadius">
    <br>

    borderBottomLeftRadius: {{ borderBottomLeftRadius }}
    <input v-model="borderBottomLeftRadius" placeholder="borderBottomLeftRadius">
    <br>


    paddingTop: {{ paddingTop }}
    <input v-model="paddingTop" placeholder="paddingTop">
    <br>


    paddingRight: {{ paddingRight }}
    <input v-model="paddingRight" placeholder="paddingRight">
    <br>


    paddingBottom: {{ paddingBottom }}
    <input v-model="paddingBottom" placeholder="paddingBottom">
    <br>


    paddingLeft: {{ paddingLeft }}
    <input v-model="paddingLeft" placeholder="paddingLeft">
    <br>

    textAlign: {{ textAlign }}
    <input v-model="textAlign" placeholder="textAlign">


    fontSize: {{ fontSize }}
    <input v-model="fontSize" placeholder="fontSize">
    <br>


    fontWeight: {{ fontWeight }}
    <input v-model="fontWeight" placeholder="fontWeight">
    <br>


    fontStyle: {{ fontStyle }}
    <input v-model="fontStyle" placeholder="fontStyle">
    <br>


    lineHeight: {{ lineHeight }}
    <input v-model="lineHeight" placeholder="lineHeight">
    <br>


    fontFamily: {{ fontFamily }}
    <input v-model="fontFamily" placeholder="fontFamily">

    <br>
    color: {{ color }}
    <input v-model="color" placeholder="color">
    <br>

    backgroundImage: {{ backgroundImage }}
    <input v-model="backgroundImage" placeholder="backgroundImage">
    <br>


    backgroundColor: {{ backgroundColor }}
    <input v-model="backgroundColor" placeholder="backgroundColor">
    <br>
    backgroundPosition: {{ backgroundPosition }}
    <input v-model="backgroundPosition" placeholder="backgroundPosition">
    <br>
    backgroundRepeat: {{ backgroundRepeat }}
    <input v-model="backgroundRepeat" placeholder="backgroundRepeat">
    <br>


    position: {{ position }}
    display: {{ display }}

    textDecorationIsBold: {{ textDecorationIsBold }}
    textDecorationIsItalic: {{ textDecorationIsItalic }}
    textDecorationIsUnderline: {{ textDecorationIsUnderline }}
    textDecorationIsStrikethrough: {{ textDecorationIsStrikethrough }}

    width: {{ width }}


  </div>

</template>

<script>


export default {
  components: {},

  data() {
    return {

      'activeNode': null,
      'isReady': false,


      'marginTop': null,
      'marginRight': null,
      'marginBottom': null,
      'marginLeft': null,

      'borderPosition': null,
      'borderSize': null,
      'borderColor': null,
      'borderStyle': null,

      'borderTopLeftRadius': null,
      'borderTopRightRadius': null,
      'borderBottomRightRadius': null,
      'borderBottomLeftRadius': null,


      'paddingTop': null,
      'paddingRight': null,
      'paddingBottom': null,
      'paddingLeft': null,

      'textAlign': null,
      'fontSize': null,
      'fontWeight': null,
      'fontStyle': null,
      'lineHeight': null,
      'fontFamily': null,
      'color': null,


      'backgroundImage': null,
      'backgroundColor': null,
      'backgroundPosition': null,
      'backgroundRepeat': null,

      'position': null,
      'display': null,

      'textDecorationIsBold': null,
      'textDecorationIsItalic': null,
      'textDecorationIsUnderline': null,
      'textDecorationIsStrikethrough': null,

      'width': null,

    }
  },

  methods: {
    resetAllProperties: function () {
      this.activeNode = null;
      this.marginTop = null;
      this.marginRight = null;
      this.marginBottom = null;
      this.marginLeft = null;
      this.borderPosition = null;
      this.borderSize = null;
      this.borderColor = null;
      this.borderStyle = null;
      this.paddingTop = null;
      this.paddingRight = null;
      this.paddingBottom = null;
      this.paddingLeft = null;
      this.textAlign = null;
      this.fontSize = null;
      this.fontWeight = null;
      this.fontStyle = null;
      this.lineHeight = null;
      this.fontFamily = null;
      this.color = null;
      this.textDecorationIsBold = null;
      this.textDecorationIsItalic = null;
      this.textDecorationIsUnderline = null;
      this.textDecorationIsStrikethrough = null;
      this.backgroundImage = null;
      this.backgroundColor = null;
      this.backgroundPosition = null;
      this.backgroundRepeat = null;
      this.position = null;
      this.borderTopLeftRadius = null;
      this.borderTopRightRadius = null;
      this.borderBottomRightRadius = null;
      this.borderBottomLeftRadius = null;
      this.display = null;
      this.width = null;
    },
    populateStyleEditor: function (node) {
      if (node && node && node.nodeType === 1) {
        var css = mw.CSSParser(node);
        this.isReady = false;
        this.resetAllProperties();
        this.activeNode = node;

        this.populateCssMargin(css);
        this.populateCssPadding(css);
        this.populateCssBorder(css);
        this.populateCssTextAlign(css);
        this.populateCssTextDecoration(css);
        this.populateCssFont(css);
        this.populateCssBackground(css);
        this.populateCssPosition(css);
        this.populateCssDisplay(css);
        this.populateCssWidth(css);
        this.isReady = true;
      }
    },
    populateCssMargin: function (css) {
      if (!css || !css.get) return;
      var margin = css.get.margin(undefined, true);
      this.marginTop = parseFloat(margin.top);
      this.marginRight = parseFloat(margin.right);
      this.marginBottom = parseFloat(margin.bottom);
      this.marginLeft = parseFloat(margin.left);
    },
    populateCssPadding: function (css) {
      var padding = css.get.padding(undefined, true);
      this.paddingTop = parseFloat(padding.top);
      this.paddingRight = parseFloat(padding.right);
      this.paddingBottom = parseFloat(padding.bottom);
      this.paddingLeft = parseFloat(padding.left);
    },
    populateCssBorder: function (css) {
      if (!css || !css.get) return;
      var border = css.get.border(true);

      var frst = {};
      for (var i in border) {
        if (border[i].width !== 0) {
          frst = border[i];
          break;
        }
      }
      var size = frst.width || 0;
      var color = frst.color || 'rgba(0,0,0,1)';
      var style = frst.style || 'none';

      this.borderSize = size;
      this.borderColor = color;
      this.borderStyle = style;
      this.borderPosition = 'all';


    },
    populateCssBorderRadius: function (css) {
      if (!css || !css.get) return;

      var borderRadius = css.get.radius(true);

      this.borderTopLeftRadius = borderRadius.tl;
      this.borderTopRightRadius = borderRadius.tr;
      this.borderBottomRightRadius = borderRadius.br;
      this.borderBottomLeftRadius = borderRadius.bl;

    },

    populateCssTextAlign: function (css) {
      if (!css || !css.get) return;
      var align = css.get.alignNormalize();
      this.textAlign = align;
    },
    populateCssTextDecoration: function (css) {
      if (!css || !css.get) return;
      var is = css.get.is();

      this.textDecorationIsBold = is.bold;
      this.textDecorationIsItalic = is.italic;
      this.textDecorationIsUnderline = is.underlined;
      this.textDecorationIsStrikethrough = is.striked;

    },

    populateCssFont: function (css) {
      if (!css || !css.get) return;

      var font = css.get.font();

      this.fontSize = font.size;
      this.fontWeight = font.weight;
      this.fontStyle = font.style;
      this.lineHeight = font.lineHeight;
      this.fontFamily = font.family;
      this.color = font.color;

    },
    populateCssBackground: function (css) {
      if (!css || !css.get) return;
      var bg = css.get.background();
      this.backgroundImage = bg.image;
      this.backgroundColor = bg.color;
      this.backgroundPosition = bg.position;
      this.backgroundRepeat = bg.repeat;
    },

    populateCssPosition: function (css) {
      var position = css.get.position();
      this.position = position;
    },
    populateCssDisplay: function (css) {
      var display = css.get.display();
      this.display = display;
    },
    populateCssWidth: function (css) {
      var width = css.get.width();
      this.width = width;
    },

    applyPropertyToActiveNode: function (prop, val) {
      if (!this.isReady) {
        return;
      }

      mw.log(1111111111111111)
      if (this.activeNode) {
        mw.top().app.dispatch('mw.elementStyleEditor.applyCssPropertyToNode', {
          node: this.activeNode,
          prop: prop,
          val: val
        });
      }
    },

  },
  mounted() {

    mw.top().app.on('mw.elementStyleEditor.selectNode', (element) => {

      this.populateStyleEditor(element)

      // mw.top().app.htmlEditorIframe.contentWindow.selectNode(element

    });


  },


  watch: {
    // whenever changes, this function will run
    fontSize: function (newValue, oldValue) {
      this.applyPropertyToActiveNode('fontSize', newValue)
    },
    fontWeight: function (newValue, oldValue) {

      this.applyPropertyToActiveNode('fontWeight', newValue)
    },
    fontStyle: function (newValue, oldValue) {

      this.applyPropertyToActiveNode('fontStyle', newValue)
    },
    lineHeight: function (newValue, oldValue) {
      this.applyPropertyToActiveNode('lineHeight', newValue)
    },
    fontFamily: function (newValue, oldValue) {
      this.applyPropertyToActiveNode('fontFamily', newValue)
    },
    color: function (newValue, oldValue) {
      this.applyPropertyToActiveNode('color', newValue)
    },
    backgroundImage: function (newValue, oldValue) {
      this.applyPropertyToActiveNode('backgroundImage', newValue)
    },
    backgroundColor: function (newValue, oldValue) {
      this.applyPropertyToActiveNode('backgroundColor', newValue)
    },
    backgroundPosition: function (newValue, oldValue) {
      this.applyPropertyToActiveNode('backgroundPosition', newValue)
    },
    backgroundRepeat: function (newValue, oldValue) {
      this.applyPropertyToActiveNode('backgroundRepeat', newValue)
    },
    position: function (newValue, oldValue) {
      this.applyPropertyToActiveNode('position', newValue)
    },
    display: function (newValue, oldValue) {
      this.applyPropertyToActiveNode('display', newValue)
    },
    width: function (newValue, oldValue) {
      this.applyPropertyToActiveNode('width', newValue)
    },
    marginTop: function (newValue, oldValue) {
      this.applyPropertyToActiveNode('marginTop', newValue)
    },
    marginRight: function (newValue, oldValue) {
      this.applyPropertyToActiveNode('marginRight', newValue)
    },
    marginBottom: function (newValue, oldValue) {
      this.applyPropertyToActiveNode('marginBottom', newValue)
    },
    marginLeft: function (newValue, oldValue) {
      this.applyPropertyToActiveNode('marginLeft', newValue)
    },
    borderTopLeftRadius: function (newValue, oldValue) {
      this.applyPropertyToActiveNode('borderTopLeftRadius', newValue)
    },
    borderTopRightRadius: function (newValue, oldValue) {
      this.applyPropertyToActiveNode('borderTopRightRadius', newValue)
    },
    borderBottomRightRadius: function (newValue, oldValue) {
      this.applyPropertyToActiveNode('borderBottomRightRadius', newValue)
    },
    borderBottomLeftRadius: function (newValue, oldValue) {
      this.applyPropertyToActiveNode('borderBottomLeftRadius', newValue)
    },
    borderSize: function (newValue, oldValue) {
      this.applyPropertyToActiveNode('borderSize', newValue)
    },
    borderColor: function (newValue, oldValue) {
      this.applyPropertyToActiveNode('borderColor', newValue)
    },
    borderStyle: function (newValue, oldValue) {
      this.applyPropertyToActiveNode('borderStyle', newValue)
    },
    borderPosition: function (newValue, oldValue) {
      this.applyPropertyToActiveNode('borderPosition', newValue)
    },
    paddingTop: function (newValue, oldValue) {
      this.applyPropertyToActiveNode('paddingTop', newValue)
    },
    paddingRight: function (newValue, oldValue) {
      this.applyPropertyToActiveNode('paddingRight', newValue)
    },
    paddingBottom: function (newValue, oldValue) {
      this.applyPropertyToActiveNode('paddingBottom', newValue)
    },
    paddingLeft: function (newValue, oldValue) {
      this.applyPropertyToActiveNode('paddingLeft', newValue)
    },
    textAlign: function (newValue, oldValue) {
      this.applyPropertyToActiveNode('textAlign', newValue)
    },
    textDecorationIsBold: function (newValue, oldValue) {
      this.applyPropertyToActiveNode('textDecorationIsBold', newValue)
    },
    textDecorationIsItalic: function (newValue, oldValue) {
      this.applyPropertyToActiveNode('textDecorationIsItalic', newValue)
    },
    textDecorationIsUnderline: function (newValue, oldValue) {
      this.applyPropertyToActiveNode('textDecorationIsUnderline', newValue)
    },
    textDecorationIsStrikethrough: function (newValue, oldValue) {
      this.applyPropertyToActiveNode('textDecorationIsStrikethrough', newValue)
    },

  }


}
</script>


