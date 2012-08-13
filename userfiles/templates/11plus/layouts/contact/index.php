<?php

/*

type: layout

name: Home layout

description: Home site layout

*/

?>
<? include TEMPLATE_DIR. "header.php"; ?>

<div id="subpage">	
	
	<div class="container">
		
		<div class="row">
			
			<div class="span8">
					
					<h3><span class="slash">//</span> Get in Touch</h3>
					<p>We want to hear from you! Just enter your name, email address, and message into the form below and send away.</p>
					
					<hr />
					
					
					
						<iframe width="100%" height="180" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="http://maps.google.ca/maps?f=q&amp;source=s_q&amp;hl=en&amp;geocode=&amp;q=New+York&amp;sll=49.891235,-97.15369&amp;sspn=47.259509,86.923828&amp;ie=UTF8&amp;hq=&amp;hnear=New+York,+United+States&amp;ll=40.714867,-74.005537&amp;spn=0.019517,0.018797&amp;z=14&amp;iwloc=near&amp;output=embed"></iframe>
					
						
					
					<br />
					<hr />
					
					<h3><span class="slash">//</span> Send a Message</h3>
					
					<form method="post" action="http://wbpreview.com/contact">
						<fieldset>
							<div class="clearfix">
								<label for="name"><span>Name:</span></label>
								<div class="input">
									<input tabindex="1" size="18" id="name" name="name" label="Name" type="text" value="">
								</div>
							</div>
							
							<div class="clearfix">
								<label for="email"><span>Email:</span></label>
								<div class="input">
									<input tabindex="2" size="25" id="email" name="email" label="Email" type="text" value="" class="input-xlarge">
								</div>
							</div>
							
							<div class="clearfix">
								<label for="message"><span>Message:</span></label>
								<div class="input">
									<textarea tabindex="3" class="input-xlarge" id="message" name="body" label="Message" rows="7"></textarea>
								</div>
							</div>
							
							<div class="actions">
								<input tabindex="3" type="submit" class="btn btn-primary btn-large" value="Send message">
							</div>
						</fieldset>
					</form>

				
			</div> <!-- /span8 -->
			
			<div class="span4">
				
				<div class="sidebar">
					
					<h3><span class="slash">//</span> More Information</h3>
					
					<p>
						<strong>Address</strong> <br />
						123 Street Name, Suite # <br />
						City, State 12345, Country
						
						<br /><br />
						<strong>Phone</strong><br />
						Phone: (123) 123-4567<br />
						Fax: (123) 123-4567<br />
						Toll Free: (800) 123-4567

					</p>
					
					<p>
					Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore.</p>
					
				</div> <!-- /sidebar -->					
				
			</div> <!-- /span4 -->
		</div> <!-- /row -->
		
	</div> <!-- /container -->	
	
</div> <!-- /subpage -->   

<? include   TEMPLATE_DIR.  "footer.php"; ?>
