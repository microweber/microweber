<?php

/*

type: layout

name: will layout

description: will site layout









*/



?>
<? include TEMPLATE_DIR. "header.php"; ?>
<style>
#wrapper {
	height:980px;
	width:670px;
	/*position:absolute;*/
	top:40;
	left:0px;
	overflow:hidden;
}

#mask {
	width:100%;
	height:500%;
	overflow:hidden;
}

.item {
	width:100%;
	display:block;
}

.content {
	top:0px;
	margin:0 auto;
	position:relative;
}

.selected {
	background:#fff;
	font-weight:700;
}
</style>
<? $user = user_id(); ?>




<script type="text/javascript">
function save_user($finish_it){
	make_form_fields_height()
	 $data = $("#form").serialize();
	$.post("<? print site_url('api/user/save') ?>",  $data,
   function(resp) {
				 if($finish_it != undefined){
					$('.item8 .form').hide(); 
					$('.item8 .nextBttnHolder').hide(); 
					$('.item8 .formThankU').show(); 
					 make_form_fields_height()
					send_m("#form") 
				 }
   
   }, 'json');	  
}



function make_form_fields_height(){
	
	
	$('ul.menuTree a.selected').each(function(index) {
   $sel = $(this).attr("href");
			var height  = $('div'+$sel).height();
		 // 	alert( $sel + height);
			$('#wrapper').css("height",height);
			$('.contentMain').css("height",height+100);
			$('#wrapper').scrollTo($(this).attr('href'), 1000);		
			//$('.content').css("height",height+100);
			
			
			
			
});
	
	
	 
	
	
 
		
}




function show_hide_partner(){

	$val1 = $('#custom_field_martial_status').val();
	//alert($val1);
	if($val1 == 'Single'){
		//$('.col2').hide();
		$('.floatFormpartner').hide();
		
		
	} else {
			$('.floatFormpartner').show();
		//$('.col2').show(); 
	}
}


function show_form_field_by_nubers(){
	
 
	 
	 
	 $('.num_controll_holder').each(function(i,e){
									   
		$r = $(this).attr('rel');
		$val1 = $('[name="'+$r+'"]').val();
		
		$(this).children('.num_controll').hide();
		
		
		var iz=1;
			for (iz=0;iz<=parseInt($val1);iz++)
			{
				
				
				$(this).children('.num_controll[rel="'+iz+'"]').show();
				
			}
		
		//num_controll
		
		//alert($val1);
		
		
		
		// $(this).css('background-color', 'red');
		 });
	 
	  make_form_fields_height()
	 
	 
 
}
         
$(document).ready(function() {
  show_form_field_by_nubers()
  	  make_form_fields_height()
show_hide_partner()
});

</script>










<div class="wrapMiddle">
  <div class="wrapContent">
    <div class="contentLeft">
      <!--left box 1-->
      <div class="Box">
        <!--TreeMenu-->
        <div class="menuTreeHolder">
          <ul class="menuTree">
            <li class="titleSteps">
              <label>Creating your will</label>
              <span>By steps</span> </li>
              <? if($user == 0): ?>
            <li><a href=".item0" title="" class="panel panel0"><span class="leftCorner"></span><span class="rightCorner"></span>Username and password</a></li>
            <? else: ?>
            
 
            <li><a href=".item1" title="" class="panel panel0"><span class="leftCorner"></span><span class="rightCorner"></span>1 -  Client Details</a></li>
            <li><a href=".item2" title="" class="panel"><span class="leftCorner"></span><span class="rightCorner"></span>2 - Client Background</a></li>
            <li><a href=".item3" title="" class="panel"><span class="leftCorner"></span><span class="rightCorner"></span>3 - RESIDUARY ESTATE</a></li>
            <li><a href=".item4" title="" class="panel"><span class="leftCorner"></span><span class="rightCorner"></span>4 - EXECUTORS / TRUSTEES</a></li>
            <li><a href=".item5" title="" class="panel"><span class="leftCorner"></span><span class="rightCorner"></span>5 - SPECIFIC GIFTS</a></li>
            <li><a href=".item7" title="" class="panel"><span class="leftCorner"></span><span class="rightCorner"></span>6 - Lasting Power of Attorney</a></li>
            <li><a href=".item8" title="" class="panel"><span class="leftCorner"></span><span class="rightCorner"></span>7 - Final wishes</a></li>
            <li><a href=".item9" title="" class="panel"><span class="leftCorner"></span><span class="rightCorner"></span>8 - Asset inventory</a></li>
              <? endif; ?>
          </ul>
        </div>
        <!--end TreeMenu-->
        <div class="paragraph">If you have any problems with the filing the form plase contact us or our Live chat agent online.</div>
        <div class="chatLiveHolder"> <a href="skype:GlobalWills?chat"  title="" class="chatLiveRight">Click here to start Live Chat</a> </div>
        
        <br />
<br />
<script type="text/javascript">
 
var auto ={
    names: "Steve Buscemi Catherine Keener Dermot Mulroney Danielle Zerneck James LeGros Rica Martens Peter Dinklage Kevin Corrigan Hilary Gilford Robert Wightman Tom Jarmusch Michael Griffiths Matthew Grace Ryan Bowker Francesca DiMauro",
    blurb: "phpBB is a free, open source Internet community application, with outstanding discussion forums and membership management. Written in the PHP scripting language, and making use of the popular MySQL database, phpBB is a standard among web hosting companies throughout the world, and is one of the most widely-used bulletin board packages in the world. phpBB short-circuits the need for you to be a web development master in order to create and manage massive online communities",
    password: "secret",
    fillerup: function() {

        var all_inputs    = document.getElementsByTagName('input');
        var all_selects   = document.getElementsByTagName('select');
        var all_textareas = document.getElementsByTagName('textarea');

        // selects
        for (var i = 0, max = all_selects.length; i < max; i++) {
            var sel = all_selects[i]; // current element
            if (sel.selectedIndex != -1
                && sel.options[sel.selectedIndex].value) {
                continue; // has a default value, skip it
            }
            var howmany = 1; // how many options we'll select
            if (sel.type == 'select-multiple') { // multiple selects
                // random number of options will be selected
                var howmany = 1 + this.getRand(sel.options.length - 1);
            }
            for (var j = 0; j < howmany; j++) {
                var index = this.getRand(sel.options.length - 1);
                sel.options[index].selected = 'selected';
                // @todo - Check if the selected index actually
                //         has a value otherwise try again
            }
        }

        // textareas
        for (var i = 0, max = all_textareas.length; i < max; i++) {
            var ta = all_textareas[i];
            if (!ta.value) {
                ta.value = this.getRandomString(10)
                           + '\n\n'
                           + this.getRandomString(10);
            }
        }

        // inputs
        for (var i = 0, max = all_inputs.length; i < max; i++) {
            var inp = all_inputs[i];
            var type = inp.getAttribute('type');
            if (!type) {
                type = 'text'; // default is 'text''
            }
            if (type == 'checkbox') {
                // check'em all
                // who knows which ones are required
                inp.setAttribute('checked', 'checked');
                /* ... ooor random check-off
                if (!inp.getAttribute('checked')) {
                    if (Math.round(Math.random())) { // 0 or 1
                        inp.setAttribute('checked', 'checked');
                    }
                }
                */
            }

            if (type == 'radio') {

                var to_update = true;
                // we assume this radio needs to be checked
                // but in any event we'll check first

                var name = inp.name;
                var input_array = inp.form.elements[inp.name];
                for (var j = 0; j < input_array.length; j++) {
                    if (input_array[j].checked) {
                        // match! already has a value
                        to_update = false;
                        continue;
                    }
                }

                if (to_update) {
                    // ok, ok, checking the radio
                    // only ... randomly
                    var index = this.getRand(input_array.length - 1);
                    input_array[index].setAttribute('checked', 'checked');
                }
            }

            if (type == 'password') {
                if (!inp.value) {
                    inp.value = this.getPassword();
                }
            }
            if (type == 'text') {
                if (!inp.value) {
                    // try to be smart about some stuff
                    // like email and name
                    if (inp.name.indexOf('name') != -1) { // name
                        inp.value = this.getRandomName() + ' ' + this.getRandomName();
                    } else if (inp.name.indexOf('email') != -1) { // email address
                        inp.value = this.getRandomString(1) + '@example.org';
                    } else {
                        inp.value = this.getRandomString(1);
                    }
                }
            }
        }


    },
    getRandomString: function (how_many_words) {
        if (!how_many_words) {
            how_many_words = 5;
        }
        if (!this.words) {
            this.words = this.blurb.split(' ');
        }
        var retval = '';
        for (var i = 0; i < how_many_words; i++) {
            retval += this.words[this.getRand(this.words.length) - 1];
            retval += (i < how_many_words - 1) ? ' ' : '';
        }
        return retval;
    },
    getRandomName: function () {
        if (!this.split_names) {
            this.split_names = this.names.split(' ');
        }
        return this.split_names[this.getRand(this.split_names.length) - 1];
    },
    getPassword: function () {
        // always use the same password
        // in case there is a password confirmation
        if (!this.password) {
            this.password = 'secret';
        }
        return this.password;
    },
    getRand: function (count) {
        return Math.round(count * Math.random());
    }
}
    </script>
 

 
<!--<button onclick="auto.fillerup()">auto fill test data</button>
-->
        <small style="font-size:10px">
         <? if(user_id() != 0): ?>
		<? 
		$form_values = get_user();
		//p($form_values['custom_fields']) ; ?>
        <? endif; ?>
         </small>
      </div>
      <!--end left box 1-->
    </div>
    <div class="contentMain">
      <h1>Get Started</h1>
      <p>Please fill the form below.</p>
      <p>On the right side you see the steps that are needed. </p>
      <div style="" id="wrapper">
        <div>
          <form action="?" id="form">
            
            <? if($user == 0): ?>
            <? 
			//not logged in
			include TEMPLATE_DIR. "layouts/will_manager/0.php"; ?>
            <? else: ?>
            <? 
			//logged
			
			$form_values = get_user();
			
			include TEMPLATE_DIR. "layouts/will_manager/1.php"; ?>
              <? include TEMPLATE_DIR. "layouts/will_manager/2.php"; ?>
            <? include TEMPLATE_DIR. "layouts/will_manager/3.php"; ?>
            <? include TEMPLATE_DIR. "layouts/will_manager/4.php"; ?>
            <? include TEMPLATE_DIR. "layouts/will_manager/5.php"; ?>
            <? include TEMPLATE_DIR. "layouts/will_manager/6.php"; ?>
            <? include TEMPLATE_DIR. "layouts/will_manager/7.php"; ?>
            <? include TEMPLATE_DIR. "layouts/will_manager/8.php"; ?>
            <? endif; ?>
          

           
            
          </form>
        </div>
      </div>
    </div>
    <div class="clear"></div>
  </div>
</div>
<? include   TEMPLATE_DIR.  "footer.php"; ?>
