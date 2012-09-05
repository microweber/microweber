<?php

/*

type: layout

name:  layout

description: layout









*/



?>

<form method="post" action="#" id="the_contact_form">
  <h1>Revenue Cycle Management Practice Profile </h1>
  <p><span >Please  complete each section in its entirety. Please mark "N/A" in fields for which there is no answer. When finished, click the send button at the bottom</span></p>
  <h2>Practice Information</h2>
  <table border="0" cellspacing="5" cellpadding="5" width="100%">
    <tr>
      <td >Name of Practice</td>
      <td ><div class="field">
          <input type="text" class="required"  name="Name of Practice" />
          <span class="err">Please enter the name of your practice</span> </div></td>
    </tr>
    <tr>
      <td >Specialty </td>
      <td><div class="field">
          <input type="text" class="required"  name="Specialty" />
          <span class="err">Please enter your speciality</span> </div></td>
    </tr>
    <tr>
      <td >Total Number of Providers </td>
      <td><div class="field">
          <input type="text" class="required"  name="Total Number of Providers" />
          <span class="err">Please enter the total number of your providers</span> </div></td>
    </tr>
    <tr>
      <td >Total Number of Practice Locations</td>
      <td><div class="field">
          <input type="text" class="required"  name="Total Number of Practice Locations" />
          <span class="err">Please enter the number of your practice locations</span> </div></td>
    </tr>
    <tr>
      <td >Hospital Affiliations (applies only to practices who perform in-patient services)</td>
      <td><div class="field">
          <input type="text" class="required"  name="Hospital Affiliations" />
          <span class="err">Please enter your hospital affilitations</span> </div></td>
    </tr>
  
  
    <tr>
      <td colspan="2" ><h2>Payer Mix</h2>
      
<small>Percentage of patients with specified payer</small>
      
      </td>
    </tr>
  
  
  
  
  
  
   
    <tr>
      <td >Medicare </td>
      <td><div class="field">
          <input type="text" class="required"  name="Medicare" />
          <span class="err">Please enter Medicare</span> </div></td>
    </tr>
    <tr>
      <td >Medicaid </td>
      <td><div class="field">
          <input type="text" class="required"  name="Medicaid" />
          <span class="err">Please enter Medicaid</span> </div></td>
    </tr>
    <tr>
      <td >Commercial</td>
      <td><div class="field">
          <input type="text" class="required"  name="Commercial" />
          <span class="err">Please enter Commercial</span> </div></td>
    </tr>
    <tr>
      <td >Worker's Compensation</td>
      <td><div class="field">
          <input type="text" class="required"  name="Workers Compensation" />
          <span class="err">Please enter Worker's Compensation</span> </div></td>
    </tr>
    <tr>
      <td >Other</td>
      <td><div class="field">
          <input type="text" class="required"  name="Other" />
          <span class="err">Please enter Other</span> </div></td>
    </tr>
  
  
  
      <tr>
      <td colspan="2" ><h2>Financial Information</h2>
  </td>
    
    </tr>
  
  

    <tr>
      <td >Estimated number of claims per day</td>
      <td ><div class="field">
          <input type="text" class="required"  name="Estimated number of claims per day" />
          <span class="err">Please enter Estimated number of claims filed per day</span> </div></td>
    </tr>
    <tr>
      <td > Estimated monthly insurance revenue </td>
      <td><div class="field">
          <input type="text" class="required"  name=" Estimated monthly insurance revenue" />
          <span class="err"> Estimated monthly insurance revenue</span> </div></td>
    </tr>
    <!--<tr>
      <td >Estimated insurance accounts receivable</td>
      <td><div class="field">
          <input type="text" class="required"  name="Estimated insurance accounts receivable" />
          <span class="err">Please enter Estimated insurance accounts receivable</span> </div></td>
    </tr>
    <tr>
      <td >Estimated patient accounts receivable</td>
      <td><div class="field">
          <input type="text" class="required"  name="Estimated patient accounts receivable" />
          <span class="err">Please enter Estimated patient accounts receivable</span> </div></td>
    </tr>-->
     <tr>
      <td colspan="2" ><h2>Your Contact Information</h2></td>
      
    </tr>
   
  
     <tr>
      <td >Please enter your name</td>
      <td ><div class="field">
          <input type="text" class="required"  name="name" />
          <span class="err">Please enter your name</span> </div></td>
    </tr>
    <tr>
      <td >Please enter your E-mail</td>
      <td><div class="field">
          <input type="text" class="required" default="Your E-mail" name="email" id="email" />
          <span class="err">Please enter your E-mail</span> </div></td>
    </tr>
    <tr>
      <td >Your phone</td>
      <td><div class="field">
          <input type="text" default="Phone" id="cphone" name="phone" />
        </div></td>
    </tr>
    <tr>
      <td >Additional comments</td>
      <td><div class="area">
          <textarea default="Message"  id="message" name="message"></textarea>
          <span class="err">Please enter your message</span> </div></td>
    </tr>
  </table>
  <input type="submit" class="xhidden" />
  <a href="#" class="a action-submit text-centered right"><strong style="width:160px;">Send</strong></a> <br />
</form>
