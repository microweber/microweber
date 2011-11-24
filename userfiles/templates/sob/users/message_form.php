<script type="text/javascript">
	$(document).ready(function(){
		
    	var options ={
        	target:        'test.php',
            beforeSubmit:  bSubmit,
            success:       aSubmit
		}
		
        $("#comments-form").submit(function(){
        	if($(this).hasClass("error")){}
            else{
            	$(this).ajaxSubmit(options);
			}
            return false;
		});

	});

	function bSubmit(formData){
		
    	alert($.param(formData))

	}

	function aSubmit(){
    	alert("Comment sent")
	}
</script>

<script type="text/javascript">

function refresh_after_post_comment(){
		var refresh_the_page = "<?php print $this->content_model->contentGetHrefForPostId($post['id']) ; ?>#the_comments_anchor";
		//window.location=refresh_the_page;
		window.location.reload();
	}



    $(document).ready(function(){
       $("#comments_form input, #comments_form textarea").focus(function(){$(this).addClass("focus")});
       $("#comments_form input, #comments_form textarea").blur(function(){$(this).removeClass("focus")});


    CommentOptions = {

		url:       '<?php print site_url('main/comments_post'); ?>'  ,
		clearForm: true,
		type:      'post',
        beforeSubmit:  comments_before,  //
        success:       comments_after

    };

    $('#comments_form').submit(function(){

        $(this).ajaxSubmit(CommentOptions);
        return false;
    });

    function comments_before(){
        var TF;
        if($("#comments_form").hasClass("error")){
            TF = false;
        }
        else{
          TF=true
        }
       //alert(TF)
       return TF;

    }
    function  comments_after(){
       var success_elem = document.createElement("span");
       success_elem.className = "success_elem";
       //success_elem.innerHTML = "Your message has been sent."
       $("#comments_form").append(success_elem);
	   refresh_after_post_comment();
    }



    });

</script>
<div class="border-top">&nbsp;</div>
<h2 class="title" style="padding: 15px 0 10px 0">Leave your comment</h2>
<div id="commentForm">
  <?php $table_content = base64_encode($this->core_model->securityEncryptString('table_content')); ?>
  <?php $table_id = base64_encode($this->core_model->securityEncryptString($post['id']));
//var_dump($table_content, $table_id);
?>
  <form method="post" action="#" id="comments_form" class="validate xform">
    <input type="hidden" name="to_table_id" id="to_table_id"  value="<?php print ($table_id) ; ?>"  />
    <input type="hidden" name="to_table" id="to_table"  value="<?php print ($table_content ); ?>"  />
    <div style="width: 224px;">
      <label>Name:*</label>
      <input type="text" name="comment_name" class="required cinput" />
      <span class="errmsg">This field is required</span> </div>
    <div style="width: 224px;">
      <label>Email:*</label>
      <input type="text" name="comment_email" class="required-email cinput" />
      <span class="errmsg">Valid E-mail is required</span> </div style="width: 224px;">
    <div style="width: 224px;">
      <label>Website:</label>
      <input name="comment_website" class="cinput" type="text" />
    </div>
    <div style="width: 404px;">
      <label>Comment:*</label>
      <textarea rows="" cols="" id="comment_body" name="comment_body" class="required cinput"></textarea>
      <span class="errmsg">This field is required</span> </div>
    <a class="btn left submit" href="#"><span>Post comment</span></a>
  </form>
</div>
