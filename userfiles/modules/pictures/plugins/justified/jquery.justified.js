/*jshint white:false*/
/* global jQuery: true*/
// the semi-colon before function invocation is a safety net against concatenated
// scripts and/or other plugins which may not be closed properly.
;
(function($, window, document, undefined) {
    'use strict';
    // undefined is used here as the undefined global variable in ECMAScript 3 is
    // mutable (ie. it can be changed by someone else). undefined isn't really being
    // passed in so we can ensure the value of it is truly undefined. In ES5, undefined
    // can no longer be modified.

    // window and document are passed through as local variable rather than global
    // as this (slightly) quickens the resolution process and can be more efficiently
    // minified (especially when both are regularly referenced in your plugin).

    // Create the defaults once
    var pluginName = 'justifiedImages';


    // The actual plugin constructor

    function Plugin(element, options) {
        this.element = element;
        this.$el = $(element);
        this._name = pluginName;
        this.init(options);
    }

    Plugin.prototype = {
        defaults: {
            template: function(data) {
                return '<div class="photo-container" style="height:' + data.displayHeight + 'px;margin-right:' + data.marginRight + 'px;">' +
                    '<img class="image-thumb" src="' + data.src + '" style="width:' + data.displayWidth + 'px;height:' + data.displayHeight + 'px;" >' +
                    '</div>';
            },
            appendBlocks : function(){ return []; },
            rowHeight: 150,
            maxRowHeight: 350,
            handleResize: false,
            margin: 1,
            imageSelector: 'image-thumb',
            imageContainer: 'photo-container'
        },
        init: function(options) {
            this.options = $.extend({}, this.defaults, options);
            this.displayImages();
            if (this.options.handleResize) {
                this.handleResize();
            }
        },
        getBlockInRow: function(rowNum){
            var appendBlocks = this.options.appendBlocks();
            for (var i = 0; i < appendBlocks.length; i++) {
                var block = appendBlocks[i];
                if(block.rowNum === rowNum){
                    return block;
                }
            }
        },
        displayImages: function() {
            var self = this,
                ws = [],
                rowNum = 0,
                baseLine = 0,
                limit = this.options.images.length,
                photos = this.options.images,
                rows = [],
                totalWidth = 0,
                appendBlocks = this.options.appendBlocks();
            var w = this.$el.width();
            var border = parseInt(this.options.margin, 10);
            var d = this.$el,
                h = parseInt(this.options.rowHeight, 10);

            $.each(this.options.images, function(index, image) {
                var size = self.options.getSize(image);
                var wt = parseInt(size.width, 10);
                var ht = parseInt(size.height, 10);
                if (ht !== h) {
                    wt = Math.floor(wt * (h / ht));
                }
                totalWidth += wt;
                ws.push(wt);
            });

            $.each(appendBlocks, function(index, block){
                totalWidth += block.width;
            });
            var perRowWidth = totalWidth / Math.ceil(totalWidth / w);
            console.log('rows', Math.ceil(totalWidth / w));
            var tw = 0;
            while (baseLine < limit) {
                var row = {
                        width: 0,
                        photos: []
                    },
                    c = 0,
                    block = this.getBlockInRow(rows.length + 1);
                if(block){
                    row.width += block.width;
                    tw += block.width;
                }
                while ((tw + ws[baseLine + c] / 2 <= perRowWidth * (rows.length + 1)) && (baseLine + c < limit)) {
                    tw += ws[baseLine + c];
                    row.width += ws[baseLine + c];
                    row.photos.push({
                        width: ws[baseLine + c],
                        photo: photos[baseLine + c]
                    });
                    c++;
                }
                baseLine += c;
                rows.push(row);
            }
            console.log(rows.length, rows);
            /*for (var i = 1; i < rows.length; i++) {
                var row = rows[i];
                for (var j = 0; j < row.photos.length; j++) {
                    var photo = row.photos[j].photo;
                };
            }*/
            for (var i = 0; i < rows.length; i++) {
                var row = rows[i],
                    lastRow = false;
                rowNum = i + 1;
                if (this.options.maxRows && rowNum > this.options.maxRows) {
                    break;
                }
                if (i === rows.length - 1) {
                    lastRow = true;
                }
                tw = -1 * border;
                var newBlock = this.getBlockInRow(lastRow ? -1 : rowNum), availableRowWidth = w;
                if(newBlock){
                    availableRowWidth -= newBlock.width;
                    tw = 0;
                }

                // Ratio of actual width of row to total width of images to be used.
                var r = availableRowWidth / row.width, //Math.min(w / row.width, this.options.maxScale),
                    c = row.photos.length;

                // new height is not original height * ratio
                var ht = Math.min(Math.floor(h * r), parseInt(this.options.maxRowHeight,10));
                r = ht / this.options.rowHeight;
                var domRow = $('<div/>', {
                    'class': 'picrow'
                });
                domRow.height(ht + border);
                d.append(domRow);

                var imagesHtml = '';
                for (var j = 0; j < row.photos.length; j++) {
                    var photo = row.photos[j].photo;
                    // Calculate new width based on ratio
                    var wt = Math.floor(row.photos[j].width * r);
                    tw += wt + border;

                    imagesHtml += this.renderPhoto(photo, {
                        src: this.options.thumbnailPath(photo, wt, ht),
                        width: wt,
                        height: ht
                    }, newBlock ? false : j === row.photos.length - 1);
                }
                if(imagesHtml === ''){
                    domRow.remove();
                    continue;
                }

                domRow.html(imagesHtml);



                if ((Math.abs(tw - availableRowWidth) < 0.05 * availableRowWidth)) {
                    // if total width is slightly smaller than
                    // actual div width then add 1 to each
                    // photo width till they match
                    var k = 0;
                    while (tw < availableRowWidth) {
                        var div1 = domRow.find('.' + this.options.imageContainer + ':nth-child(' + (k + 1) + ')'),
                            img1 = div1.find('.' + this.options.imageSelector);
                        img1.width(img1.width() + 1);
                        k = (k + 1) % c;
                        tw++;
                    }
                    // if total width is slightly bigger than
                    // actual div width then subtract 1 from each
                    // photo width till they match
                    k = 0;
                    while (tw > availableRowWidth) {
                        var div2 = domRow.find('.' + this.options.imageContainer + ':nth-child(' + (k + 1) + ')'),
                            img2 = div2.find('.' + this.options.imageSelector);
                        img2.width(img2.width() - 1);
                        k = (k + 1) % c;
                        tw--;
                    }
                } else{
                    if( availableRowWidth - tw > 0.05 * availableRowWidth ){
                        var diff = availableRowWidth-tw,
                            adjustedDiff = 0,
                            images = domRow.find('.' + this.options.imageContainer),
                            marginTop = 0;
                        for(var l = 0 ; l < images.length ; l++ ){
                            var currentDiff = diff / (images.length),
                                imgDiv = images.eq(l),
                                img = imgDiv.find('.' + this.options.imageSelector),
                                imageWidth = img.width(),
                                imageHeight = img.height();
                            if( i === images.length - 1 ){
                                currentDiff = diff - adjustedDiff;
                            }
                            img.width( imageWidth + currentDiff );
                            img.height( ( imageHeight / imageWidth ) * (imageWidth + currentDiff) );
                            marginTop = (imageHeight - img.height()) / 2;
                            img.css('margin-top', marginTop);
                            adjustedDiff += currentDiff;
                        }
                    }
                }

                if(newBlock){
                    $('<div />', {
                        class : this.options.imageContainer + ' added-block',
                        css : {
                            width : newBlock.width,
                            height: ht
                        },
                        html : newBlock.html
                    }).appendTo(domRow);
                }
            }
        },
        renderPhoto: function(image, obj, isLast) {
            var data = {},
                d;
            d = $.extend({}, image, {
                src: obj.src,
                displayWidth: obj.width,
                displayHeight: obj.height,
                marginRight: isLast ? 0 : this.options.margin
            });
            if (this.options.dataObject) {
                data[this.options.dataObject] = d;
            } else {
                data = d;
            }
            return this.options.template(data);
        },
        handleResize: function() {},
        refresh: function(options) {
            this.options = $.extend({}, this.defaults, options);
            this.$el.empty();
            this.displayImages();
        }
    };


    // A really lightweight plugin wrapper around the constructor,
    // preventing against multiple instantiations
    $.fn[pluginName] = function(option) {
        var args = arguments,
            result;

        this.each(function() {
            var $this = $(this),
                data = $.data(this, 'plugin_' + pluginName),
                options = typeof option === 'object' && option;
            if (!data) {
                $this.data('plugin_' + pluginName, (data = new Plugin(this, options)));
            }else{
                if (typeof option === 'string') {
                    result = data[option].apply(data, Array.prototype.slice.call(args, 1));
                } else {
                    data.refresh.call(data, options);
                }
            }
        });

        // To enable plugin returns values
        return result || this;
    };

})(jQuery, window, document);
