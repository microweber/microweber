<?php

/*

  type: layout
  content_type: static
  name: Plans
  description: Plans layout

*/

?>
<?php include "header.php"; ?>
	<div class="container">
	<?php $hosting = whm_get_hosting_products(); 
			
			$plan1_id = false;
			$plan2_id = false;
			$plan3_id = false;
			$plan4_id = false;
			
			
			if(!empty($hosting)){
				foreach($hosting as $host){
					
					if(isset( $host['name'])){
						$name = strtolower($host['name']);
						if(stristr($name,'free')){
							$plan1_id = $host['id'];
						}
						if(stristr($name,'micro')){
							$plan2_id = $host['id'];
						}
						if(stristr($name,'weber')){
							$plan3_id = $host['id'];
						}
						if(stristr($name,'unlimited')){
							$plan4_id = $host['id'];
						}
					}
					
					
				}
				
				
			}
			
			
			
			?>
		<div class="main">
			<h1 class="text-center">Microweber is 14 days free for all plans</h1>
			<h4 class="orange text-center">No Credit cart required! No Transaction fee!</h4>
			<br>
			<br>
			<h6 class="text-center">Choose the plan that best fits to your needs. You can change it anytime.</h6>
			
			
			<a href="<? print site_url('get-started') ?>?plan=<?php print $plan1_id; ?>">there is also free plan</a> 
			
			
			
			
			
			
			
			
			
			<div class="plans text-center" id="mw-plans">
				<div class="plan plan-blue">
					<div class="theplan">
						<div class="theplan-content">
							<h2 class="price blue"><strong>$</strong>19<small>/  month</small></h2>
							<p>MICRO</p>
							<hr>
							<p>Free Instalation</p>
							<p>Use your own domain</p>
							<p>Unlimited Bandwidth</p>
							<p>Unlimited Product</p>
							<p><strong>1 GB</strong> storage space</p>
							<p><strong>NO</strong> Transaction fee</p>
							<p><strong>5</strong> hours custom support</p>
							<p>FTP & cPanel access</p>
							<div class="vpad"> <a href="<? print site_url('get-started') ?>?plan=<?php print $plan2_id; ?>" class="fbtn fitem-blue no-shadow">Get Started Free</a> </div>
						</div>
					</div>
				</div>
				<div class="plan plan-white">
					<div class="theplan">
						<div class="theplan-content">
							<p class="orange"><s>$79/MONTH</s></p>
							<h2 class="price orange"><strong>$</strong>59<small>/  month</small></h2>
							<p>WEBER</p>
							<hr>
							<p>Free Instalation</p>
							<p>Use your own domain</p>
							<p>Unlimited Bandwidth</p>
							<p>Unlimited Product</p>
							<p><strong>10 GB</strong> storage space</p>
							<p><strong>NO</strong> Transaction fee</p>
							<p>10 GB hours custom support</p>
							<p>10 GB Pro Themes & Modules</p>
							<p>FTP & cPanel access</p>
							<div class="vpad"> <a href="<? print site_url('get-started') ?>?plan=<?php print $plan3_id; ?>" class="fbtn fitem-orange no-shadow">Get Started Free</a> </div>
						</div>
					</div>
				</div>
				<div class="plan plan-blue">
					<div class="theplan">
						<div class="theplan-content">
							<h2 class="price blue"><strong>$</strong>169<small>/  month</small></h2>
							<p>UNLIMITED</p>
							<hr>
							<p>Free Instalation</p>
							<p>Use your own domain</p>
							<p>Unlimited Bandwidth</p>
							<p><strong>Unlimited Space</strong></p>
							<p><strong>NO</strong> Transaction fee</p>
							<p>Unlimited hours support</p>
							<p>Unlimited Themes & Modules</p>
							<p>FTP & cPanel access</p>
							<div class="vpad"> <a href="<? print site_url('get-started') ?>?plan=<?php print $plan4_id; ?>" class="fbtn fitem-blue no-shadow">Get Started Free</a> </div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="gray-container">
		<div class="container">
			<div class="main">
				<div class="row">
					<div class="span6">
						<h5>How long are your contract?</h5>
						<p>Our plans are month-to-month, yearly, or two years. We make it simple to start — and stop — your service at any time.</p>
					</div>
					<div class="span6">
						<h5>Do i have a online shop included?</h5>
						<p>Our plans are month-to-month, yearly, or two years. We make it simple to start — and stop — your service at any time.</p>
					</div>
				</div>
				<div class="row">
					<div class="span6">
						<h5>Can I donwoad Microweber?</h5>
						<p> Sure. MW is a fully-managed web service. We do not have plans to
							make a downloadable version. Squarespace does provide many
							standard methods for exporting your data. </p>
					</div>
					<div class="span6">
						<h5>Which payment methods can I use?</h5>
						<p>Sure. MW is a fully-managed web service. We do not have plans to make a downloadable version. Squarespace does provide many standard methods for exporting your data.</p>
					</div>
				</div>
				<div class="row">
					<div class="span6">
						<h5>Do you offer email accounts?</h5>
						<p> While Squarespace does not provide email accounts, you can
							easily link your domain to Google Apps and get email. </p>
					</div>
					<div class="span6">
						<h5>Can i get mobile version of my website?</h5>
						<p>Our plans are month-to-month, yearly, or two years. We make it simple to start — and stop — your service at any time.</p>
					</div>
				</div>
				<div class="row">
					<div class="span6">
						<h5>Do I need another web host?</h5>
						<p>No. All Squarespace plans include our fully-managed cloud hosting, ensuring your website remains available at all times.</p>
					</div>
					<div class="span6">
						<h5>Do you offer a free plan?</h5>
						<p>Our plans are month-to-month, yearly, or two years. We make it simple to start — and stop — your service at any time.</p>
					</div>
				</div>
				<div class="row">
					<div class="span6">
						<h5>Do I have support?</h5>
						<p>Our plans are month-to-month, yearly, or two years. We make it simple to start — and stop — your service at any time.</p>
						<div class="vpad">
							<h5>Can I use my own domain name?</h5>
							<p>Our plans are month-to-month, yearly, or two years. We make it simple to start — and stop — your service at any time.</p>
						</div>
					</div>
					<div class="span6">
						<div class="yellow-box">
							<div class="pad text-center">
								<h6>Want to know which plan is best for you?</h6>
								<h6>Call on our sales team at <?php print $phone; ?></h6>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
 <?php include "footer.php"; ?>
