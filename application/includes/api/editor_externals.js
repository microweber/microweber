
    hash = window.location.hash.replace(/#/g, '');

    afterInput = function(url, todo){   //what to do after image is uploaded (depending on the hash in the url)

      var todo = todo || false;

      if(url == false){
          parent.mw.tools.modal.remove('mw_rte_image');
          return false;
      }


      if(!todo){
          if(hash!==''){
            if(hash=='editimage'){
              parent.mw.image.currentResizing.attr("src", url);

            }
            else if(hash=='set_bg_image'){
              parent.mw.wysiwyg.set_bg_image(url);

            }
            else{
              parent.mw.exec(hash, url);
            }
          }
          else{
            /*
              parent.mw.wysiwyg.restore_selection();
              parent.mw.wysiwyg.insert_image(url, true);

            */
          }
      }
      else{
        if(todo=='video'){
          parent.mw.wysiwyg.insert_html('<div class="element mw-embed-embed"><embed controller="true" wmode="transparent" windowlessVideo="true" loop="false" autoplay="false" width="560" height="315" src="'+url+'"></embed></div>');

        }
        else if(todo=='files'){

          var name = mw.tools.get_filename(url);
          var extension = url.split(".").pop();
          var html = "<a class='mw-uploaded-file mw-filetype-"+extension+"' href='" + url + "'><span></span>" + name + "." + extension + "</a>";
          parent.mw.wysiwyg.insert_html(html);
        }
      }
    }










