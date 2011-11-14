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
	height:788px;
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
            <li><a href=".item1" title="" class="panel panel0"><span class="leftCorner"></span><span class="rightCorner"></span>1 -  Client Details</a></li>
            <li><a href=".item2" title="" class="panel"><span class="leftCorner"></span><span class="rightCorner"></span>2 - Client Background</a></li>
            <li><a href=".item3" title="" class="panel"><span class="leftCorner"></span><span class="rightCorner"></span>3 - RESIDUARY ESTATE</a></li>
            <li><a href=".item4" title="" class="panel" ><span class="leftCorner"></span><span class="rightCorner"></span>4 - EXECUTORS / TRUSTEES</a></li>
            <li><a href=".item5" title="" class="panel"><span class="leftCorner"></span><span class="rightCorner"></span>5 - SPECIFIC GIFTS</a></li>
            <li><a href=".item7" title="" class="panel"><span class="leftCorner"></span><span class="rightCorner"></span>6 - Lasting Power of Attorney</a></li>
            <li><a href=".item8" title="" class="panel"><span class="leftCorner"></span><span class="rightCorner"></span>7 - Final wishes</a></li>
            <li><a href=".item9" title="" class="panel"><span class="leftCorner"></span><span class="rightCorner"></span>8 - Asset inventory</a></li>
          </ul>
        </div>
        <!--end TreeMenu-->
        <div class="paragraph">If you have any problems with the filing the form plase contact us in our Live chat agent online.</div>
        <div class="chatLiveHolder"> <a href="#" title="" class="chatLiveRight">Click here to start Live Chat</a> </div>
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
            <div class="item item1" >
              <div class="content">
                <h2>STEP 1 - Client Details</h2>
                <div class="intro"> <span class="introText">Instructions for </span> <span class="select">
                  <select name="custom_field_form_1" class="styled">
                    <option>Single</option>
                    <option>Couple</option>
                  </select>
                  </span>
                  <div class="clear"></div>
                </div>
                <div class="floatForm floatForm1">
                  <h2>YOUR DETAILS</h2>
                  <label for="csurname">Full first names</label>
                  <div class="itemIn"> <span class="inputHolder">
                    <input type="text"  id="csurname" name="surname" class="required" />
                    </span> </div>
                  <div class="itemIn">
                    <label for="cfirstname">Surname</label>
                    <span class="inputHolder">
                    <input type="text"  id="cfirstname" name="firstname" class="required" />
                    </span> </div>
                  <div class="itemIn">
                    <label for="cAddress">Address</label>
                    <span class="inputHolder">
                    <input type="text"  id="cAddress" name="Address" class="required"  />
                    </span> </div>
                  <div class="itemIn">
                    <label for="cpostcode">Postcode</label>
                    <span class="inputHolder">
                    <input type="text"  id="cpostcode" name="postcode" class="required" />
                    </span> </div>
                  <div class="itemIn">
                    <label for="cphone">Landline number</label>
                    <span class="inputHolder">
                    <input type="text" id="cphone" name="phone" class="required"/>
                    </span> </div>
                  <div class="itemIn">
                    <label for="cphone">Mobile number</label>
                    <span class="inputHolder">
                    <input type="text" id="cphone" name="phone" class="required"/>
                    </span> </div>
                  <div class="itemIn">
                    <label for="cphone">Email</label>
                    <span class="inputHolder">
                    <input type="text" id="cphone" name="phone" class="required"/>
                    </span> </div>
                  <div class="itemIn">
                    <label>Date of birth</label>
                    <div class="itemIn1">
                      <select class="styled">
                        <option>Date</option>
                        <option>Date1</option>
                        <option>Date2</option>
                      </select>
                    </div>
                    <div class="itemIn2">
                      <select class="styled">
                        <option>Date</option>
                        <option>Date1</option>
                        <option>Date2</option>
                      </select>
                    </div>
                    <div class="itemIn3">
                      <select class="styled">
                        <option>Date</option>
                        <option>Date1</option>
                        <option>Date2</option>
                      </select>
                    </div>
                    <div class="clear"></div>
                  </div>
                  <div class="clear"></div>
                  <br />
                  <label for="">Martial status</label>
                  <div class="itemIn"  style="padding-top:00px">
                    <div class="itemIn4">
                      <input type="radio" name="" class="radio"/>
                      <span>Single </span> </div>
                    <div class="itemIn5">
                      <input type="radio" name="" class="radio"/>
                      <span>Married </span> </div>
                    <div class="itemIn6" >
                      <input type="radio" name="" class="radio"/>
                      <span>Civil Partnership </span> </div>
                    <div class="itemIn7">
                      <input type="radio" name="" class="radio"/>
                      <span>Engaged to be married </span> </div>
                    <div class="itemIn8">
                      <input type="radio" name="" class="radio"/>
                      <span>Living together, unmarried </span> </div>
                    <div class="clear"></div>
                  </div>
                </div>
                <div class="floatForm">
                  <h2>YOUR PARTNER DETAILS</h2>
                  <label for="psurname">Full first names</label>
                  <div class="itemIn"> <span class="inputHolder">
                    <input type="text"  id="psurname" name="psurname" class="required" />
                    </span> </div>
                  <div class="itemIn">
                    <label for="pfirstname">Surname</label>
                    <span class="inputHolder">
                    <input type="text"  id="pfirstname" name="pfirstname" class="required" />
                    </span> </div>
                  <div class="itemIn">
                    <label for="pAddress">Address</label>
                    <span class="inputHolder">
                    <input type="text"  id="pAddress" name="pAddress" class="required"  />
                    </span> </div>
                  <div class="itemIn">
                    <label for="ppostcode">Postcode</label>
                    <span class="inputHolder">
                    <input type="text"  id="ppostcode" name="ppostcode" class="required" />
                    </span> </div>
                  <div class="itemIn">
                    <label for="pphone">Landline number</label>
                    <span class="inputHolder">
                    <input type="text" id="pphone" name="pphone" class="required"/>
                    </span> </div>
                  <div class="itemIn">
                    <label for="cphone">Mobile number</label>
                    <span class="inputHolder">
                    <input type="text" id="cphone" name="phone" class="required"/>
                    </span> </div>
                  <div class="itemIn">
                    <label for="cphone">Email</label>
                    <span class="inputHolder">
                    <input type="text" id="cphone" name="phone" class="required"/>
                    </span> </div>
                  <div class="itemIn">
                    <label>Date of birth</label>
                    <div class="itemIn1">
                      <select class="styled">
                        <option>Date</option>
                        <option>Date1</option>
                        <option>Date2</option>
                      </select>
                    </div>
                    <div class="itemIn2">
                      <select class="styled">
                        <option>Date</option>
                        <option>Date1</option>
                        <option>Date2</option>
                      </select>
                    </div>
                    <div class="itemIn3">
                      <select class="styled">
                        <option>Date</option>
                        <option>Date1</option>
                        <option>Date2</option>
                      </select>
                    </div>
                    <div class="clear"></div>
                  </div>
                  <div class="clear"></div>
                  <br />
                  <label for="">Martial status</label>
                  <div class="itemIn" style="padding-top:0px">
                    <div class="itemIn4">
                      <input type="radio" name="" class="radio"/>
                      <span>Single </span> </div>
                    <div class="itemIn5">
                      <input type="radio" name="" class="radio"/>
                      <span>Married </span> </div>
                    <div class="itemIn6">
                      <input type="radio" name="" class="radio"/>
                      <span>Civil Partnership </span> </div>
                    <div class="itemIn7">
                      <input type="radio" name="" class="radio"/>
                      <span>Engaged to be married </span> </div>
                    <div class="itemIn8">
                      <input type="radio" name="" class="radio"/>
                      <span>Living together, unmarried </span> </div>
                    <div class="clear"></div>
                  </div>
                </div>
                <div class="clear"></div>
              </div>
              <div class="nextBttnHolder">
                <div class="ErrorMessage">* More Information Needed</div>
                <div class="toTheNextStep"> <span>Continue to the next step</span> <a href=".item2" class="panel bttnHolder"> <span class="bttn">Next Step</span> </a> </div>
                <div class="clear"></div>
              </div>
              <div class="clear"></div>
            </div>
            <div class="item item2">
              <div class="content">
                <h2>STEP 2 - Client Background</h2>
                <div class="form">
                  <div class="itemIn">
                    <label>Do  you have any children ?</label>
                    <div class="rightCol">
                      <input type="radio" name="" class="radio"/>
                      <span>Yes </span>&nbsp;
                      <input type="radio" name="" class="radio"/>
                      <span>No </span> </div>
                    <div class="clear"></div>
                  </div>
                  <div class="itemIn">
                    <label>Have  you any children by another partner ?</label>
                    <div class="rightCol">
                      <input type="radio" name="" class="radio"/>
                      <span>Yes </span>&nbsp;
                      <input type="radio" name="" class="radio"/>
                      <span>No </span> </div>
                    <div class="clear"></div>
                  </div>
                  <div class="itemIn">
                    <label>Have  you  changed your name or used a different name ?</label>
                    <div class="rightCol">
                      <input type="radio" name="" class="radio"/>
                      <span>Yes </span>&nbsp;
                      <input type="radio" name="" class="radio"/>
                      <span>No </span> </div>
                    <div class="clear"></div>
                  </div>
                  <div class="itemIn">
                    <label>Do  you own any assets sited outside England & Wales ?</label>
                    <div class="rightCol">
                      <input type="radio" name="" class="radio"/>
                      <span>Yes </span>&nbsp;
                      <input type="radio" name="" class="radio"/>
                      <span>No </span> </div>
                    <div class="clear"></div>
                  </div>
                  <div class="itemIn">
                    <label>Do you own a business ?</label>
                    <div class="rightCol">
                      <input type="radio" name="" class="radio"/>
                      <span>Yes </span>&nbsp;
                      <input type="radio" name="" class="radio"/>
                      <span>No </span> </div>
                    <div class="clear"></div>
                  </div>
                  <div class="itemIn">
                    <label>If Yes, would the business continue after your death ?</label>
                    <div class="rightCol">
                      <input type="radio" name="" class="radio"/>
                      <span>Yes </span>&nbsp;
                      <input type="radio" name="" class="radio"/>
                      <span>No </span> </div>
                    <div class="clear"></div>
                  </div>
                  <div class="itemIn Short">
                    <label>How ?</label>
                    <div class="rightCol">
                      <div class="inputHolder">
                        <input type="text" name="" />
                      </div>
                    </div>
                    <div class="clear"></div>
                  </div>
                  <div class="itemIn">
                    <label>Is your Estate above &pound;325,000 (approx &euro;400,000)?</label>
                    <div class="rightCol">
                      <input type="radio" name="" class="radio"/>
                      <span>Yes </span>&nbsp;
                      <input type="radio" name="" class="radio"/>
                      <span>No </span>&nbsp;
                      <!--<input type="radio" name="" class="radio"/>
                      <span>Global</span> -->
                    </div>
                    <div class="clear"></div>
                  </div>
                  <div class="itemIn">
                    <label>If yes, are you interested in planning to minimise inheritance tax?</label>
                    <div class="rightCol">
                      <input type="radio" name="" class="radio"/>
                      <span>Yes </span>&nbsp;
                      <input type="radio" name="" class="radio"/>
                      <span>No </span>&nbsp;
                      <!--<input type="radio" name="" class="radio"/>
                      <span>Global</span> -->
                    </div>
                    <div class="clear"></div>
                  </div>
                </div>
                <div class="nextBttnHolder">
                  <div class="ErrorMessage">* More Information Needed</div>
                  <div class="toTheNextStep"> <span>Continue to the next step</span> <a href=".item3" class="panel bttnHolder"> <span class="bttn">Next Step</span> </a> </div>
                  <div class="clear"></div>
                </div>
                <div class="clear"></div>
              </div>
            </div>
            <div class="item item3" >
              <div class="content">
                <h2>STEP 3 - Residuary Estate First Death</h2>
                <div class="clear"></div>
                <div class="intro"> <span class="introText">How many people will share your estate? </span> <span class="select">
                  <select name="custom_field_form_1" class="styled">
                    <option>1</option>
                    <option>2</option>
                  </select>
                  </span>
                  <div class="clear"></div>
                </div>
                <div class="form">
                  <!--section 1-->
                  <div class="section">
                    <div class="colLong">
                      <label>Full name of beneficiary</label>
                      <div class="inputHolder">
                        <input type="text" name="" />
                      </div>
                    </div>
                    <!--   <div class="col2">
                      <label>Address</label>
                      <div class="inputHolder">
                        <input type="text" name="" />
                      </div>
                    </div>-->
                    <div class="clear"></div>
                    <div class="colLong">
                      <label>Address</label>
                      <div class="inputHolder">
                        <input type="text" name="" />
                      </div>
                    </div>
                    <div class="clear"></div>
                    <div class="colLong">
                      <label>Address 2</label>
                      <div class="inputHolder">
                        <input type="text" name="" />
                      </div>
                    </div>
                    <div class="clear"></div>
                    <div class="colLong">
                      <label>Address 3</label>
                      <div class="inputHolder">
                        <input type="text" name="" />
                      </div>
                    </div>
                    <div class="col1">
                      <label>Relationship to Client 1</label>
                      <div class="inputHolder">
                        <input type="text" name="" />
                      </div>
                    </div>
                    <div class="col2">
                      <label>Relationship to Client 2</label>
                      <div class="inputHolder">
                        <input type="text" name="" />
                      </div>
                    </div>
                    <div class="clear"></div>
                    <div class="colLong">
                      <label>Precentage share to inherit</label>
                      <div class="inputHolder">
                        <input type="text" name="" />
                      </div>
                    </div>
                    <div class="clear"></div>
                    <div class="itemIn">
                      <label>Are any of the above named beneficiaries under the age of 18?</label>
                      <div class="rightCol">
                        <input type="radio" name="" class="radio"/>
                        <span>Yes </span>&nbsp;
                        <input type="radio" name="" class="radio"/>
                        <span>No </span> </div>
                      <div class="clear"></div>
                    </div>
                  </div>
                  <!--section 1-->
                  <!--section 2-->
                  <!--section 2-->
                  <!--section 3-->
                  <!--section 3-->
                  <h2>Residuary Estate - Second death/Joint death</h2>
                  <!--section 4-->
                  <div class="clear"></div>
                  <div class="intro"> <span class="introText">How many people will share your estate? </span> <span class="select">
                    <select name="custom_field_form_1" class="styled">
                      <option>1</option>
                      <option>2</option>
                    </select>
                    </span>
                    <div class="clear"></div>
                  </div>
                  <div class="section">
                    <div class="colLong">
                      <label>Full name of beneficiary</label>
                      <div class="inputHolder">
                        <input type="text" name="" />
                      </div>
                    </div>
                    <!--   <div class="col2">
                      <label>Address</label>
                      <div class="inputHolder">
                        <input type="text" name="" />
                      </div>
                    </div>-->
                    <div class="clear"></div>
                    <div class="colLong">
                      <label>Address</label>
                      <div class="inputHolder">
                        <input type="text" name="" />
                      </div>
                    </div>
                    <div class="clear"></div>
                    <div class="colLong">
                      <label>Address 2</label>
                      <div class="inputHolder">
                        <input type="text" name="" />
                      </div>
                    </div>
                    <div class="clear"></div>
                    <div class="colLong">
                      <label>Address 3</label>
                      <div class="inputHolder">
                        <input type="text" name="" />
                      </div>
                    </div>
                    <div class="col1">
                      <label>Relationship to Client 1</label>
                      <div class="inputHolder">
                        <input type="text" name="" />
                      </div>
                    </div>
                    <div class="col2">
                      <label>Relationship to Client 2</label>
                      <div class="inputHolder">
                        <input type="text" name="" />
                      </div>
                    </div>
                    <div class="clear"></div>
                    <div class="colLong">
                      <label>Precentage share to inherit</label>
                      <div class="inputHolder">
                        <input type="text" name="" />
                      </div>
                    </div>
                    <div class="clear"></div>
                    <div class="itemIn">
                      <label>Are any of the above named beneficiaries under the age of 18?</label>
                      <div class="rightCol">
                        <input type="radio" name="" class="radio"/>
                        <span>Yes </span>&nbsp;
                        <input type="radio" name="" class="radio"/>
                        <span>No </span> </div>
                      <div class="clear"></div>
                    </div>
                  </div>
                </div>
                <div class="nextBttnHolder">
                  <div class="ErrorMessage">* More Information Needed</div>
                  <div class="toTheNextStep"> <span>Continue to the next step</span> <a href=".item4" class="panel bttnHolder"> <span class="bttn">Next Step</span> </a> </div>
                  <div class="clear"></div>
                </div>
              </div>
            </div>
            <div class="item item4">
              <div class="content">
                <h2>STEP 4 - APPOINTMENT OF EXECUTORS AND TRUSTEES</h2>
                <div class="form">
                  <!--section 1-->
                  <div class="section">
                    <div class="intro"> If you wish to appoint an additional executor, please give their details here </div>
                    <div class="colLong">
                      <label>Full name of executor</label>
                      <div class="inputHolder">
                        <input type="text" name="">
                      </div>
                    </div>
                    <!--   <div class="col2">
                      <label>Address</label>
                      <div class="inputHolder">
                        <input type="text" name="" />
                      </div>
                    </div>-->
                    <div class="clear"></div>
                    <div class="colLong">
                      <label>Address</label>
                      <div class="inputHolder">
                        <input type="text" name="">
                      </div>
                    </div>
                    <div class="clear"></div>
                    <div class="colLong">
                      <label>Address 2</label>
                      <div class="inputHolder">
                        <input type="text" name="">
                      </div>
                    </div>
                    <div class="clear"></div>
                    <div class="colLong">
                      <label>Address 3</label>
                      <div class="inputHolder">
                        <input type="text" name="">
                      </div>
                    </div>
                    <div class="clear"></div>
                  </div>
                  <div class="section">
                    <div class="intro"> In the event of second death/joint death who do you wish to be your executors? </div>
                    <br />
                    <div class="colLong">
                      <label>Full name of executor 2</label>
                      <div class="inputHolder">
                        <input type="text" name="">
                      </div>
                    </div>
                    <!--   <div class="col2">
                      <label>Address</label>
                      <div class="inputHolder">
                        <input type="text" name="" />
                      </div>
                    </div>-->
                    <div class="clear"></div>
                    <div class="colLong">
                      <label>Address</label>
                      <div class="inputHolder">
                        <input type="text" name="">
                      </div>
                    </div>
                    <div class="clear"></div>
                    <div class="colLong">
                      <label>Address 2</label>
                      <div class="inputHolder">
                        <input type="text" name="">
                      </div>
                    </div>
                    <div class="clear"></div>
                    <div class="colLong">
                      <label>Address 3</label>
                      <div class="inputHolder">
                        <input type="text" name="">
                      </div>
                    </div>
                    <div class="clear"></div>
                    <br />
                    <br />
                    <div class="colLong">
                      <label>Full name of executor 3</label>
                      <div class="inputHolder">
                        <input type="text" name="">
                      </div>
                    </div>
                    <!--   <div class="col2">
                      <label>Address</label>
                      <div class="inputHolder">
                        <input type="text" name="" />
                      </div>
                    </div>-->
                    <div class="clear"></div>
                    <div class="colLong">
                      <label>Address</label>
                      <div class="inputHolder">
                        <input type="text" name="">
                      </div>
                    </div>
                    <div class="clear"></div>
                    <div class="colLong">
                      <label>Address 2</label>
                      <div class="inputHolder">
                        <input type="text" name="">
                      </div>
                    </div>
                    <div class="clear"></div>
                    <div class="colLong">
                      <label>Address 3</label>
                      <div class="inputHolder">
                        <input type="text" name="">
                      </div>
                    </div>
                    <div class="clear"></div>
                    <br />
                    <br />
                    <div class="clear"></div>
                    <div class="clear"></div>
                    <div class="itemIn">
                      <label>Are any of your executors acting in a professional capacity?</label>
                      <div class="rightCol">
                        <input type="radio" name="" class="radio"/>
                        <span>Yes </span>&nbsp;
                        <input type="radio" name="" class="radio"/>
                        <span>No </span> </div>
                      <div class="clear"></div>
                    </div>
                    <div class="clear"></div>
                    <div class="itemIn">
                      <label>Are they to be paid?</label>
                      <div class="rightCol">
                        <input type="radio" name="" class="radio"/>
                        <span>Yes </span>&nbsp;
                        <input type="radio" name="" class="radio"/>
                        <span>No </span> </div>
                      <div class="clear"></div>
                    </div>
                  </div>
                  <!--section 1-->
                  <!--section 2-->
                  <!--section 2-->
                  <!--section 3-->
                  <!--section 3-->
                  <!--section 4-->
                  <div class="clear"></div>
                </div>
                <div class="nextBttnHolder">
                  <div class="ErrorMessage">* More Information Needed</div>
                  <div class="toTheNextStep"> <span>Continue to the next step</span> <a href=".item5" class="panel bttnHolder"> <span class="bttn">Next Step</span> </a> </div>
                  <div class="clear"></div>
                </div>
              </div>
            </div>
            <div  class="item item5" > <br />
              <h2>STEP 5 - SPECIFIC GIFTS AND LEGACIES</h2>
              <div class="clear"></div>
              <div class="intro"> <span class="introText"> Are there any specific gifts or sums of money that either of you would like to leave? </span> <span class="select">
                <select name="custom_field_form_1" class="styled">
                  <option>No</option>
                  <option>Yes</option>
                </select>
                </span>
                <div class="clear"><br />
                  <br />
                </div>
              </div>
              <div class="intro"> <span class="introText">How many people or charities do you wish to leave specific gifts or legacies to? </span> <span class="select">
                <select name="custom_field_form_1" class="styled">
                  <option>1</option>
                  <option>10</option>
                </select>
                </span>
                <div class="clear"></div>
              </div>
              <div class="content">
                <div class="form">
                  <div class="section">
                    <div class="colLong">
                      <label>Full name of beneficiary</label>
                      <div class="inputHolder">
                        <input type="text" name="" />
                      </div>
                    </div>
                    <!--   <div class="col2">
                      <label>Address</label>
                      <div class="inputHolder">
                        <input type="text" name="" />
                      </div>
                    </div>-->
                    <div class="clear"></div>
                    <div class="colLong">
                      <label>Address</label>
                      <div class="inputHolder">
                        <input type="text" name="" />
                      </div>
                    </div>
                    <div class="clear"></div>
                    <div class="colLong">
                      <label>Address 2</label>
                      <div class="inputHolder">
                        <input type="text" name="" />
                      </div>
                    </div>
                    <div class="clear"></div>
                    <div class="colLong">
                      <label>Address 3</label>
                      <div class="inputHolder">
                        <input type="text" name="" />
                      </div>
                    </div>
                    <div class="col1">
                      <label>Relationship to Client 1</label>
                      <div class="inputHolder">
                        <input type="text" name="" />
                      </div>
                    </div>
                    <div class="col2">
                      <label>Relationship to Client 2</label>
                      <div class="inputHolder">
                        <input type="text" name="" />
                      </div>
                    </div>
                    <div class="clear"></div>
                    <div class="colLong">
                      <label>Item or amount to gift</label>
                      <div class="inputHolder">
                        <input type="text" name="" />
                      </div>
                    </div>
                    <div class="clear"></div>
                  </div>
                </div>
                <div class="nextBttnHolder">
                  <div class="ErrorMessage">* More Information Needed</div>
                  <div class="toTheNextStep"> <span>Continue to the next step</span> <a href=".item6" class="panel bttnHolder"> <span class="bttn">Next Step</span> </a> </div>
                  <div class="clear"></div>
                </div>
              </div>
            </div>
            <div class="item item7">
              <div class="content">
                <h2>STEP 6 - Lasting Power of Attorney</h2>
                <div class="form">
                  <!--section 1-->
                  <div class="intro"><a href="#">Click here for further information about the Lasting Power of Attorney</a>
                    <div class="clear"></div>
                  </div>
                  <div class="section">
                    <!--line 1-->
                    <div class="itemIn">
                      <label>To proceed with Lasting Power of Attorney</label>
                      <span class="radioHolder">
                      <input type="radio" name="" class="radio"/>
                      <span>Yes </span>&nbsp;
                      <input type="radio" name="" class="radio"/>
                      <span>No </span> </span> </div>
                    <div class="clear"></div>
                    <!--line 1-->
                    <!--line 2-->
                    <div class="col1">
                      <h2>Yourself</h2>
                    </div>
                    <div class="col2">
                      <h2>Your partner</h2>
                    </div>
                    <div class="clear"></div>
                    <!--line 2-->
                    <!--line 3-->
                    <div class="col1">
                      <label>Executors as attorneys?</label>
                      <span class="radioHolder">
                      <input type="radio" name="" class="radio"/>
                      <span>Yes </span>&nbsp;
                      <input type="radio" name="" class="radio"/>
                      <span>No </span> </span> </div>
                    <div class="col2">
                      <label>Executors as attorneys?</label>
                      <span class="radioHolder">
                      <input type="radio" name="" class="radio"/>
                      <span>Yes </span>&nbsp;
                      <input type="radio" name="" class="radio"/>
                      <span>No </span> </span> </div>
                    <div class="clear"></div>
                    <!--line 3-->
                    <!--line 4-->
                    <div class="col1">
                      <label>
                      <h3>Names of attorneys</h3>
                      <br />
                      </label>
                      <div class="clear"></div>
                    </div>
                    <div class="col2">
                      <label>
                      <h3>Names of attorneys</h3>
                      <br />
                      </label>
                      <div class="clear"></div>
                    </div>
                    <div class="clear"></div>
                    <!--line 4-->
                    <!--line 5-->
                    <div class="col1">
                      <label>Name of attorney 1</label>
                      <span class="inputHolder">
                      <input type="text" />
                      </span> </div>
                    <div class="col2">
                      <label>Name of attorney 1</label>
                      <span class="inputHolder">
                      <input type="text" />
                      </span> </div>
                    <div class="clear"></div>
                    <!--line 5-->
                    <!--line 6-->
                    <div class="col1">
                      <label>Address</label>
                      <span class="textarea">
                      <textarea></textarea>
                      </span> </div>
                    <div class="col2">
                      <label>Address</label>
                      <span class="textarea">
                      <textarea></textarea>
                      </span> </div>
                    <div class="clear"></div>
                    <!--line 6-->
                    <!--line 7-->
                    <div class="col1">
                      <div class="itemIn">
                        <label>Date of birth</label>
                        <div class="clear"></div>
                        <div class="itemIn1">
                          <select class="styled">
                            <option>Date</option>
                            <option>Date1</option>
                            <option>Date2</option>
                          </select>
                        </div>
                        <div class="itemIn2">
                          <select class="styled">
                            <option>Month</option>
                            <option>January</option>
                            <option>December</option>
                          </select>
                        </div>
                        <div class="itemIn3">
                          <select class="styled">
                            <option>Year</option>
                            <option>2011</option>
                            <option>2012</option>
                          </select>
                        </div>
                        <div class="clear"></div>
                      </div>
                    </div>
                    <div class="col2">
                      <div class="itemIn">
                        <label>Date of birth</label>
                        <div class="clear"></div>
                        <div class="itemIn1">
                          <select class="styled">
                            <option>Date</option>
                            <option>Date1</option>
                            <option>Date2</option>
                          </select>
                        </div>
                        <div class="itemIn2">
                          <select class="styled">
                            <option>Month</option>
                            <option>January</option>
                            <option>December</option>
                          </select>
                        </div>
                        <div class="itemIn3">
                          <select class="styled">
                            <option>Year</option>
                            <option>2011</option>
                            <option>2012</option>
                          </select>
                        </div>
                        <div class="clear"></div>
                      </div>
                    </div>
                    <div class="clear"></div>
                    <!--line 7-->
                    <!--line 8-->
                    <div class="col1">
                      <label>Ocupation</label>
                      <span class="inputHolder">
                      <input type="text" />
                      </span> </div>
                    <div class="col2">
                      <label>Ocupation</label>
                      <span class="inputHolder">
                      <input type="text" />
                      </span> </div>
                    <div class="clear"></div>
                    <!--line 8-->
                    <div class="divider"></div>
                    <!--line 9-->
                    <div class="col1">
                      <label>Name of attorney 2</label>
                      <span class="inputHolder">
                      <input type="text" />
                      </span> </div>
                    <div class="col2">
                      <label>Name of attorney 2</label>
                      <span class="inputHolder">
                      <input type="text" />
                      </span> </div>
                    <div class="clear"></div>
                    <!--line 9-->
                    <!--line 10-->
                    <div class="col1">
                      <label>Address</label>
                      <span class="textarea">
                      <textarea></textarea>
                      </span> </div>
                    <div class="col2">
                      <label>Address</label>
                      <span class="textarea">
                      <textarea></textarea>
                      </span> </div>
                    <div class="clear"></div>
                    <!--line 10-->
                    <!--line 11-->
                    <div class="col1">
                      <div class="itemIn">
                        <label>Date of birth</label>
                        <div class="clear"></div>
                        <div class="itemIn1">
                          <select class="styled">
                            <option>Date</option>
                            <option>Date1</option>
                            <option>Date2</option>
                          </select>
                        </div>
                        <div class="itemIn2">
                          <select class="styled">
                            <option>Month</option>
                            <option>January</option>
                            <option>December</option>
                          </select>
                        </div>
                        <div class="itemIn3">
                          <select class="styled">
                            <option>Year</option>
                            <option>2011</option>
                            <option>2012</option>
                          </select>
                        </div>
                        <div class="clear"></div>
                      </div>
                    </div>
                    <div class="col2">
                      <div class="itemIn">
                        <label>Date of birth</label>
                        <div class="clear"></div>
                        <div class="itemIn1">
                          <select class="styled">
                            <option>Date</option>
                            <option>Date1</option>
                            <option>Date2</option>
                          </select>
                        </div>
                        <div class="itemIn2">
                          <select class="styled">
                            <option>Month</option>
                            <option>January</option>
                            <option>December</option>
                          </select>
                        </div>
                        <div class="itemIn3">
                          <select class="styled">
                            <option>Year</option>
                            <option>2011</option>
                            <option>2012</option>
                          </select>
                        </div>
                        <div class="clear"></div>
                      </div>
                    </div>
                    <div class="clear"></div>
                    <!--line 11-->
                    <!--line 12-->
                    <div class="col1 itemIn">
                      <label>Ocupation</label>
                      <span class="inputHolder">
                      <input type="text" />
                      </span> </div>
                    <div class="col2 itemIn">
                      <label>Ocupation</label>
                      <span class="inputHolder">
                      <input type="text" />
                      </span> </div>
                    <div class="clear"></div>
                    <!--line 12-->
                    <!--line 13-->
                    <div class="col1">
                      <label>Multiple Attorneys to act <br/>
                        Jointly / Jointly & Severally?</label>
                      <span class="textarea">
                      <textarea></textarea>
                      </span> </div>
                    <div class="col2">
                      <label>Multiple Attorneys to act <br/>
                        Jointly / Jointly & Severally?</label>
                      <span class="textarea">
                      <textarea></textarea>
                      </span> </div>
                    <div class="clear"></div>
                    <!--line 13-->
                    <!--line 14-->
                    <div class="col1">
                      <label class="strongInner">Attorneys Authority</label>
                      <label>General authority for all assets</label>
                      <br/>
                      <span class="radioHolder">
                      <input type="radio" name="" class="radio"/>
                      <span>Yes </span>&nbsp;
                      <input type="radio" name="" class="radio"/>
                      <span>No </span> </span> </div>
                    <div class="col2">
                      <label class="strongInner">Attorneys Authority</label>
                      <label>General authority for all assets</label>
                      <br/>
                      <span class="radioHolder">
                      <input type="radio" name="" class="radio"/>
                      <span>Yes </span>&nbsp;
                      <input type="radio" name="" class="radio"/>
                      <span>No </span> </span> </div>
                    <div class="clear"></div>
                    <!--line 14-->
                    <!--line 15-->
                    <div class="col1">
                      <label>If no then detail authority and assets to which powers is applicable</label>
                      <span class="textarea">
                      <textarea></textarea>
                      </span> </div>
                    <div class="col2">
                      <label>If no then detail authority and assets to which powers is applicable</label>
                      <span class="textarea">
                      <textarea></textarea>
                      </span> </div>
                    <div class="clear"></div>
                    <!--line 15-->
                    <!--line 16-->
                    <div class="col1">
                      <label class="strong">Restrictions</label>
                      <span class="textarea">
                      <textarea></textarea>
                      </span> </div>
                    <div class="col2">
                      <label class="strong">Restrictions</label>
                      <span class="textarea">
                      <textarea></textarea>
                      </span> </div>
                    <div class="clear"></div>
                    <!--line 16-->
                    <!--line 17-->
                    <div class="col1">
                      <label class="strongInner">Informing Others</label>
                      <div class="clear"></div>
                      <label>Name</label>
                      <span class="inputHolder">
                      <input type="text" />
                      </span> </div>
                    <div class="col2">
                      <label class="strongInner">Informing Others</label>
                      <div class="clear"></div>
                      <label>Name</label>
                      <span class="inputHolder">
                      <input type="text" />
                      </span> </div>
                    <div class="clear"></div>
                    <!--line 17-->
                    <!--line 18-->
                    <div class="col1">
                      <label>Address</label>
                      <span class="textarea">
                      <textarea></textarea>
                      </span> </div>
                    <div class="col2">
                      <label>Address</label>
                      <span class="textarea">
                      <textarea></textarea>
                      </span> </div>
                    <div class="clear"></div>
                    <!--line 18-->
                    <div class="clear"></div>
                  </div>
                  <!--section 1-->
                </div>
                <div class="nextBttnHolder">
                  <div class="ErrorMessage">* More Information Needed</div>
                  <div class="toTheNextStep"> <span>Continue to the next step</span> <a href=".item8" class="panel bttnHolder"> <span class="bttn">Next Step</span> </a> </div>
                  <div class="clear"></div>
                </div>
              </div>
            </div>
            <div class="item item8">
              <div class="content">
                <h2>STEP 7 - Funeral Arrangements</h2>
                <div class="form">
                  <div class="section">
                    <!--line 1-->
                    <div class="itemIn">
                      <label>Do you have a pre paid funeral plan? </label>
                      <span class="radioHolder">
                      <input type="radio" name="" class="radio"/>
                      <span>Yes </span>&nbsp;
                      <input type="radio" name="" class="radio"/>
                      <span>No </span> </span> </div>
                    <div class="clear"></div>
                    <!--line 1-->
                    <!--line 2-->
                    <div class="col1">
                      <label class="strongInner">If Yes</label>
                      <div class="clear"></div>
                      <label>Company</label>
                      <span class="inputHolder">
                      <input type="text" />
                      </span> </div>
                    <div class="col2">
                      <label class="strongInner">&nbsp;</label>
                      <div class="clear"></div>
                      <label>Plan Number(s)</label>
                      <span class="inputHolder">
                      <input type="text" />
                      </span> </div>
                    <div class="clear"></div>
                    <!--line 2-->
                    <br/>
                    <br/>
                    <!--line 3-->
                    <div class="col1">
                      <h2>YOUR DETAILS</h2>
                    </div>
                    <div class="col2">
                      <h2>YOUR PARTNER'S DETAILS</h2>
                    </div>
                    <div class="clear"></div>
                    <!--line 3-->
                    <!--line 4-->
                    <div class="col1"> <span class="radioHolder">
                      <input type="radio" name="" class="radio"/>
                      <span class="radio">Cremation </span>&nbsp;
                      <input type="radio" name="" class="radio"/>
                      <span class="radio">Burial </span>&nbsp;
                      <input type="radio" name="" class="radio"/>
                      <span class="radio">Executors Discretion </span>
                      <input type="radio" name="" class="radio"/>
                      <span class="radio">Prepaid </span> </span> </div>
                    <div class="col2"> <span class="radioHolder">
                      <input type="radio" name="" class="radio"/>
                      <span class="radio">Cremation </span>&nbsp;
                      <input type="radio" name="" class="radio"/>
                      <span class="radio">Burial </span>&nbsp;
                      <input type="radio" name="" class="radio"/>
                      <span class="radio">Executors Discretion </span>
                      <input type="radio" name="" class="radio"/>
                      <span class="radio">Prepaid </span> </span> </div>
                    <div class="clear"></div>
                    <!--line 4-->
                    <!--line 5-->
                    <div class="col1">
                      <label>Other wishes</label>
                      <span class="textarea">
                      <textarea></textarea>
                      </span> </div>
                    <div class="col2">
                      <label>Other wishes</label>
                      <span class="textarea">
                      <textarea></textarea>
                      </span> </div>
                    <div class="clear"></div>
                    <!--line 5-->
                    <!--line 6-->
                    </br>
                    <div class="clear"></div>
                    <!--line 6-->
                  </div>
                </div>
              </div>
              <div class="nextBttnHolder">
                <div class="ErrorMessage">* More Information Needed</div>
                <div class="toTheNextStep"> <span>Continue to the next step</span> <a href="" class="pane bttnHolder"> <span class="bttn">Next Step</span> </a> </div>
                <div class="clear"></div>
              </div>
            </div>
            <div class="item item9 assetInv">
              <h1>ASSET INVENTORY</h1>
              <div class="form">
                <!-- line 1-->
                <div class="col1">
                  <label>Institution</label>
                  <span class="inputHolder">
                  <input type="text" value = "" />
                  </span>
                  <div class="clear"></div>
                  <label>Phone Number (work)</label>
                  <span class="inputHolder">
                  <input type="text" value = "" />
                  </span> </div>
                <div class="col2 longCol2">
                  <label>Adress</label>
                  <div class="textarea">
                    <textarea></textarea>
                  </div>
                </div>
                <div class="clear"></div>
                <!-- line 1-->
                <!-- line 2-->
                <div class="col1">
                  <label>Account Name</label>
                  <span class="inputHolder">
                  <input type="text" value = "" />
                  </span> </div>
                <div class="col2">
                  <label>Account Number</label>
                  <span class="inputHolder">
                  <input type="text" value = "" />
                  </span> </div>
                <div class="col3">
                  <label>Contact Name</label>
                  <span class="inputHolder">
                  <input type="text" value = "" />
                  </span> </div>
                <div class="clear"></div>
                <!-- line 2-->
                <!-- line 3-->
                <div class="col1">
                  <label>Telephone Number</label>
                  <span class="inputHolder">
                  <input type="text" value = "" />
                  </span> </div>
                <div class="col2 longCol2">
                  <label>Other Information</label>
                  <span class="inputHolder">
                  <input type="text" value = "" />
                  </span> </div>
                <div class="clear"></div>
                <!-- line 3-->
                <br/>
                <!-- line 4-->
                <div class="col1"> <a href="#" class="bttnAdd" title="">Add another asset</a> </div>
                <div class="col2"> &nbsp; </div>
                <div class="col3 bttnCol3"> <a href="" class="pane bttnHolder"> <span class="bttn">send</span> </a> </div>
                <div class="clear"></div>
                <!-- line 4-->
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
    <div class="clear"></div>
  </div>
</div>
<? include   TEMPLATE_DIR.  "footer.php"; ?>
