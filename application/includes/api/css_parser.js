mw.CSSParser = function(el){
    if(el === null ) return false;
    if(typeof el !== 'object') return false;
    if(el.nodeName === undefined || el.nodeName === '#text') return false;
    var css = window.getComputedStyle(el, null);

    this.isit = function(){
        return {
          bold:parseFloat(css.fontWeight)>600 || css.fontWeight=='bold' || css.fontWeight =='bolder',
          italic:css.fontStyle=='italic'||css.fontStyle=='oblique',
          underlined:css.textDecoration=='underline'
        }
    }
    this.font = function(){
      if(css===null) return false;
      return {
        size:css.fontSize,
        weight:css.fontWeight,
        style:css.fontStyle,
        height:css.lineHeight,
        family:css.fontFamily,
        color:css.color
      }
    }
    this.alignNormalize = function(){
        if(!!css){
        var a = css.textAlign;
        var final = a.contains('left')?'left':a.contains('center')?'center':a.contains('justify')?'justify':a.contains('right')?'right':'left';
        return final;
      }
    }
    this.border = function(){
        return {
            top:{width:css.borderTopWidth, style:css.borderTopStyle, color:css.borderTopColor},
            left:{width:css.borderLeftWidth, style:css.borderLeftStyle, color:css.borderLeftColor},
            right:{width:css.borderRightWidth, style:css.borderRightStyle, color:css.borderRightColor},
            bottom:{width:css.borderBottomWidth, style:css.borderBottomStyle, color:css.borderBottomColor}
        }
    }
    this.width = function(){
        return css.width;
    }
    this.background = function(){
        return {
            image:css.backgroundImage,
            color:css.backgroundColor,
            position:css.backgroundPosition,
            repeat:css.backgroundRepeat
        }
    }
    this.margin = function(parse){
      if(!parse){
        return {
          top:css.marginTop,
          left:css.marginLeft,
          right:css.marginRight,
          bottom:css.marginBottom
        }
      }
      else{
        return {
          top:parseFloat(css.marginTop),
          left:parseFloat(css.marginLeft),
          right:parseFloat(css.marginRight),
          bottom:parseFloat(css.marginBottom)
        }
      }
    }
    this.padding = function(parse){
      if(!parse){
        return {
          top:css.paddingTop,
          left:css.paddingLeft,
          right:css.paddingRight,
          bottom:css.paddingBottom
        }
      }
      else{
         return {
          top:parseFloat(css.paddingTop),
          left:parseFloat(css.paddingLeft),
          right:parseFloat(css.paddingRight),
          bottom:parseFloat(css.paddingBottom)
        }
      }
    }
    this.opacity = function(){return css.opacity}

    this.radius = function(parse){
      if(!parse){
        return {
          tl:css.borderTopLeftRadius,
          tr:css.borderTopRightRadius,
          br:css.borderBottomRightRadius,
          bl:css.borderBottomLeftRadius
        }
      }
      else{
        return {
          tl:parseFloat(css.borderTopLeftRadius),
          tr:parseFloat(css.borderTopRightRadius),
          br:parseFloat(css.borderBottomRightRadius),
          bl:parseFloat(css.borderBottomLeftRadius)
        }
      }
    }

    this.transform = function(){
     var transform = mw.JSPrefix('transform');
     var transform = css[transform];
     if(transform==="" || transform==="none"){
       return [1, 0, 0, 1, 0, 0];
     }
     else{
       var transform = transform.substr(7, transform.length - 8).split(", ");
       return transform;
     }
    }

    this.shadow = function(){
      var shadow =  mw.JSPrefix('boxShadow');
      var shadow = css[shadow].replace(/, /g, ",").split(" ");
      return {
        color: shadow[0],
        left:shadow[1],
        top:shadow[2],
        strength:shadow[3]
      }
    }

    return {
        el:el,
        css:css,
        get:this
    }
}