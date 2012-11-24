mw.CSSParser = function(el){
    var css = window.getComputedStyle(el, null);

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
    this.margin = function(){
        return {
          top:css.marginTop,
          left:css.marginLeft,
          right:css.marginRight,
          bottom:css.marginBottom
        }
    }
    this.padding = function(){
        return {
          top:css.paddingTop,
          left:css.paddingLeft,
          right:css.paddingRight,
          bottom:css.paddingBottom
        }
    }
    this.opacity = function(){return css.opacity}

    this.radius = function(){
      return {
        tl:css.borderTopLeftRadius,
        tr:css.borderTopRightRadius,
        br:css.borderBottomRightRadius,
        bl:css.borderBottomLeftRadius
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