<?php

/*

  type: layout

  name: Choose plan form

  description: Choose plan form




*/

  ?>


<?php 
  $hosting = whm_get_hosting_products();
 ?>
  




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
							<div class="vpad"> <a href="#" class="choose-plan fbtn fitem-blue no-shadow">Get Started Free</a> </div>
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
							<div class="vpad"> <a href="#" class="choose-plan fbtn fitem-orange no-shadow">Get Started Free</a> </div>
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
							<div class="vpad"> <a href="#" class="choose-plan fbtn fitem-blue no-shadow">Get Started Free</a> </div>
						</div>
					</div>
				</div>
			</div>


















			<?php if (!empty($hosting)): ?>
			<?php foreach ($hosting as $item): ?>
			<?php if (isset($item['name']) and isset($item['id'])): ?>
			<div class="control-group form-group">
				<div class="controls">
					<label>
						<input type="radio" name="product_id" value="<?php print $item['id'] ?>">
						<?php print $item['name'] ?> </label>
				</div>
			</div>
			<?php endif; ?>
			<?php endforeach; ?>
			<?php endif; ?>




