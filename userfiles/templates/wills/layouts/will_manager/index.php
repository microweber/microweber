<?php

/*

type: layout

name: will layout

description: will site layout









*/



?>
<? include TEMPLATE_DIR. "header.php"; ?>
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
            <li><a href=".item6" title="" class="panel"><span class="leftCorner"></span><span class="rightCorner"></span>6 - CHARITABLE GIFTS</a></li>
            <li><a href=".item7" title="" class="panel"><span class="leftCorner"></span><span class="rightCorner"></span>7 - Lasting Power of Attorney</a></li>
            <li><a href=".item8" title="" class="panel"><span class="leftCorner"></span><span class="rightCorner"></span>8 - Funeral Arrangements</a></li>
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
                <div class="intro"> <span class="introText">Do you have a partner whose wishes and estate will precisely  mirror your own?</span> <span class="select">
                  <select class="styled">
                    <option>-- Please Chose --</option>
                    <option>-- Please Chose 1 --</option>
                    <option>-- Please Chose 2 -- </option>
                  </select>
                  </span>
                  <div class="clear"></div>
                </div>
                <div class="floatForm floatForm1">
                  <h2>YOUR DETAILS</h2>
                  <label for="csurname">Surname</label>
                  <div class="itemIn"> <span class="inputHolder">
                    <input type="text"  id="csurname" name="surname" class="required" />
                    </span> </div>
                  <div class="itemIn">
                    <label for="cfirstname">Firstname</label>
                    <span class="inputHolder">
                    <input type="text"  id="cfirstname" name="firstname" class="required" />
                    </span> </div>
                  <div class="itemIn">
                    <label for="ctitle">Title *</label>
                    <span class="inputHolder">
                    <input type="text"  id="ctitle" name="title" class="required" />
                    </span> </div>
                  <div class="itemIn">
                    <label for="cadress">Adress</label>
                    <span class="inputHolder">
                    <input type="text"  id="cadress" name="adress" class="required"  />
                    </span> </div>
                  <div class="itemIn">
                    <label for="cpostcode">Postcode</label>
                    <span class="inputHolder">
                    <input type="text"  id="cpostcode" name="postcode" class="required" />
                    </span> </div>
                  <div class="itemIn">
                    <label for="cphone">Telephone(work)</label>
                    <span class="inputHolder">
                    <input type="text" id="cphone" name="phone" class="required"/>
                    </span> </div>
                  <div class="itemIn">
                    <label>Data of birth</label>
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
                  <div class="itemIn"  style="padding-top:10px">
                    <div class="itemIn4">
                      <input type="radio" name="" class="radio"/>
                      <span>Single </span> </div>
                    <div class="itemIn5">
                      <input type="radio" name="" class="radio"/>
                      <span>Married </span> </div>
                    <div class="itemIn6" >
                      <input type="radio" name="" class="radio"/>
                      <span>Civil Partnership </span> </div>
                    <div class="clear"></div>
                  </div>
                </div>
                <div class="floatForm">
                  <h2>YOUR PARTNER DETAILS</h2>
                  <label for="psurname">Surname</label>
                  <div class="itemIn"> <span class="inputHolder">
                    <input type="text"  id="psurname" name="psurname" class="required" />
                    </span> </div>
                  <div class="itemIn">
                    <label for="pfirstname">Firstname</label>
                    <span class="inputHolder">
                    <input type="text"  id="pfirstname" name="pfirstname" class="required" />
                    </span> </div>
                  <div class="itemIn">
                    <label for="ptitle">Title *</label>
                    <span class="inputHolder">
                    <input type="text"  id="ptitle" name="ptitle" class="required" />
                    </span> </div>
                  <div class="itemIn">
                    <label for="padress">Adress</label>
                    <span class="inputHolder">
                    <input type="text"  id="padress" name="padress" class="required"  />
                    </span> </div>
                  <div class="itemIn">
                    <label for="ppostcode">Postcode</label>
                    <span class="inputHolder">
                    <input type="text"  id="ppostcode" name="ppostcode" class="required" />
                    </span> </div>
                  <div class="itemIn">
                    <label for="pphone">Telephone(work)</label>
                    <span class="inputHolder">
                    <input type="text" id="pphone" name="pphone" class="required"/>
                    </span> </div>
                  <div class="itemIn">
                    <label>Data of birth</label>
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
                  <div class="itemIn" style="padding-top:10px">
                    <div class="itemIn4">
                      <input type="radio" name="" class="radio"/>
                      <span>Single </span> </div>
                    <div class="itemIn5">
                      <input type="radio" name="" class="radio"/>
                      <span>Married </span> </div>
                    <div class="itemIn6">
                      <input type="radio" name="" class="radio"/>
                      <span>Civil Partnership </span> </div>
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
                    <label>Do the client(s) have any children ?</label>
                    <div class="rightCol">
                      <input type="radio" name="" class="radio"/>
                      <span>Yes </span>&nbsp;
                      <input type="radio" name="" class="radio"/>
                      <span>No </span> </div>
                    <div class="clear"></div>
                  </div>
                  <div class="itemIn">
                    <label>Has either client any children by another partner ?</label>
                    <div class="rightCol">
                      <input type="radio" name="" class="radio"/>
                      <span>Yes </span>&nbsp;
                      <input type="radio" name="" class="radio"/>
                      <span>No </span> </div>
                    <div class="clear"></div>
                  </div>
                  <div class="itemIn">
                    <label>Has either client changed their name or use a different name ?</label>
                    <div class="rightCol">
                      <input type="radio" name="" class="radio"/>
                      <span>Yes </span>&nbsp;
                      <input type="radio" name="" class="radio"/>
                      <span>No </span> </div>
                    <div class="clear"></div>
                  </div>
                  <div class="itemIn">
                    <label>Does the client own any assets sited outside England & Wales ?</label>
                    <div class="rightCol">
                      <input type="radio" name="" class="radio"/>
                      <span>Yes </span>&nbsp;
                      <input type="radio" name="" class="radio"/>
                      <span>No </span> </div>
                    <div class="clear"></div>
                  </div>
                  <div class="itemIn">
                    <label>Is either client in business ?</label>
                    <div class="rightCol">
                      <input type="radio" name="" class="radio"/>
                      <span>Yes </span>&nbsp;
                      <input type="radio" name="" class="radio"/>
                      <span>No </span> </div>
                    <div class="clear"></div>
                  </div>
                  <div class="itemIn">
                    <label>If Yes, would the business continue after clients death ?</label>
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
                    <label>Is Clients Estate over IHT Threshold ?</label>
                    <div class="rightCol">
                      <input type="radio" name="" class="radio"/>
                      <span>UK </span>&nbsp;
                      <input type="radio" name="" class="radio"/>
                      <span>Everywhere </span>&nbsp;
                      <input type="radio" name="" class="radio"/>
                      <span>Global</span> </div>
                    <div class="clear"></div>
                  </div>
                  <div class="itemIn Middle">
                    <label>Where is the Will to operate?</label>
                    <div class="rightCol">
                      <div class="inputHolder">
                        <input type="text" name="" />
                      </div>
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
                <h2>STEP 3 - Residuari Estate First Death</h2>
                <div class="form">
                  <!--section 1-->
                  <div class="section">
                    <div class="itemIn">
                      <label>If Mirror Will, do the Clients want to leave the residue of their estate to each other</label>
                      <div class="rightCol">
                        <input type="radio" name="" class="radio"/>
                        <span>Yes </span>&nbsp;
                        <input type="radio" name="" class="radio"/>
                        <span>No </span> </div>
                      <div class="clear"></div>
                    </div>
                    <div class="col1">
                      <label>Name</label>
                      <div class="inputHolder">
                        <input type="text" name="" />
                      </div>
                    </div>
                    <div class="col2">
                      <label>Address</label>
                      <div class="inputHolder">
                        <input type="text" name="" />
                      </div>
                    </div>
                    <div class="clear"></div>
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
                      <label>Share to inherit</label>
                      <div class="inputHolder">
                        <input type="text" name="" />
                      </div>
                    </div>
                  </div>
                  <!--section 1-->
                  <!--section 2-->
                  <div class="section">
                    <div class="col1">
                      <label>Name</label>
                      <div class="inputHolder">
                        <input type="text" name="" />
                      </div>
                    </div>
                    <div class="col2">
                      <label>Address</label>
                      <div class="inputHolder">
                        <input type="text" name="" />
                      </div>
                    </div>
                    <div class="clear"></div>
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
                      <label>Share to inherit</label>
                      <div class="inputHolder">
                        <input type="text" name="" />
                      </div>
                    </div>
                  </div>
                  <!--section 2-->
                  <!--section 3-->
                  <div class="section">
                    <div class="col1">
                      <label>Name</label>
                      <div class="inputHolder">
                        <input type="text" name="" />
                      </div>
                    </div>
                    <div class="col2">
                      <label>Address</label>
                      <div class="inputHolder">
                        <input type="text" name="" />
                      </div>
                    </div>
                    <div class="clear"></div>
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
                      <label>Share to inherit</label>
                      <div class="inputHolder">
                        <input type="text" name="" />
                      </div>
                    </div>
                    <div class="itemIn">
                      <label>Are any of the above named beneficiaries under the age of 18?</label>
                      <div class="rightCol">
                        <input type="radio" name="" class="radio"/>
                        <span>Yes </span>&nbsp;
                        <input type="radio" name="" class="radio"/>
                        <span>No </span> </div>
                      <div class="clear"></div>
                    </div>
                    <div class="Long">
                      <label>If Yes at what age does the client wish the beneficiaries to inherit their share?</label>
                      <div class="rightCol">
                        <select class="styled">
                          <option>Select</option>
                          <option>Select1</option>
                          <option>Select2</option>
                        </select>
                      </div>
                      <div class="clear"></div>
                    </div>
                  </div>
                  <!--section 3-->
                  <h2>Residuary Estate - All Death</h2>
                  <!--section 4-->
                  <div class="section">
                    <div class="col1">
                      <label>Name</label>
                      <div class="inputHolder">
                        <input type="text" name="" />
                      </div>
                    </div>
                    <div class="col2">
                      <label>Address</label>
                      <div class="inputHolder">
                        <input type="text" name="" />
                      </div>
                    </div>
                    <div class="clear"></div>
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
                      <label>Share to inherit</label>
                      <div class="inputHolder">
                        <input type="text" name="" />
                      </div>
                    </div>
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
                  <!--section 4-->
                  <!--section 5-->
                  <div class="section">
                    <div class="col1">
                      <label>Name</label>
                      <div class="inputHolder">
                        <input type="text" name="" />
                      </div>
                    </div>
                    <div class="col2">
                      <label>Address</label>
                      <div class="inputHolder">
                        <input type="text" name="" />
                      </div>
                    </div>
                    <div class="clear"></div>
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
                      <label>Share to inherit</label>
                      <div class="inputHolder">
                        <input type="text" name="" />
                      </div>
                    </div>
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
                  <!--section 5-->
                  <!--section 6-->
                  <div class="section">
                    <div class="col1">
                      <label>Name</label>
                      <div class="inputHolder">
                        <input type="text" name="" />
                      </div>
                    </div>
                    <div class="col2">
                      <label>Address</label>
                      <div class="inputHolder">
                        <input type="text" name="" />
                      </div>
                    </div>
                    <div class="clear"></div>
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
                      <label>Share to inherit</label>
                      <div class="inputHolder">
                        <input type="text" name="" />
                      </div>
                    </div>
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
                  <!--section 6-->
                  <!--section 7-->
                  <div class="section">
                    <div class="col1">
                      <label>Name</label>
                      <div class="inputHolder">
                        <input type="text" name="" />
                      </div>
                    </div>
                    <div class="col2">
                      <label>Address</label>
                      <div class="inputHolder">
                        <input type="text" name="" />
                      </div>
                    </div>
                    <div class="clear"></div>
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
                      <label>Share to inherit</label>
                      <div class="inputHolder">
                        <input type="text" name="" />
                      </div>
                    </div>
                    <div class="itemIn">
                      <label>Are any of the above named beneficiaries under the age of 18?</label>
                      <div class="rightCol">
                        <input type="radio" name="" class="radio"/>
                        <span>Yes </span>&nbsp;
                        <input type="radio" name="" class="radio"/>
                        <span>No </span> </div>
                      <div class="clear"></div>
                    </div>
                    <div class="Long">
                      <label>If Yes at what age does the client wish the beneficiaries to inherit their share?</label>
                      <div class="rightCol">
                        <select class="styled">
                          <option>Select</option>
                          <option>Select1</option>
                          <option>Select2</option>
                        </select>
                      </div>
                      <div class="clear"></div>
                    </div>
                  </div>
                  <!--section 7-->
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
                    <div class="itemIn">
                      <label>Do you wish your partner to be executor?</label>
                      <div class="rightCol">
                        <input type="radio" name="" class="radio"/>
                        <span>Yes </span>&nbsp;
                        <input type="radio" name="" class="radio"/>
                        <span>No </span> </div>
                      <div class="clear"></div>
                    </div>
                    <div class="col1">
                      <label>Name</label>
                      <div class="inputHolder">
                        <input type="text" name="" />
                      </div>
                    </div>
                    <div class="col2">
                      <label>Address</label>
                      <div class="inputHolder">
                        <input type="text" name="" />
                      </div>
                    </div>
                    <div class="clear"></div>
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
                      <label>1st death / 2nd death    Executor, Trustee, Both</label>
                      <span class="textarea">
                      <textarea class="inputHolder">
													
												</textarea>
                      </span> </div>
                  </div>
                  <!--section 1-->
                  <!--section 2-->
                  <div class="section">
                    <div class="col1">
                      <label>Name</label>
                      <div class="inputHolder">
                        <input type="text" name="" />
                      </div>
                    </div>
                    <div class="col2">
                      <label>Address</label>
                      <div class="inputHolder">
                        <input type="text" name="" />
                      </div>
                    </div>
                    <div class="clear"></div>
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
                      <label>1st death / 2nd death    Executor, Trustee, Both</label>
                      <span class="textarea">
                      <textarea>
													
												</textarea>
                      </span> </div>
                  </div>
                  <!--section 2-->
                  <!--section 3-->
                  <div class="section">
                    <div class="col1">
                      <label>Name</label>
                      <div class="inputHolder">
                        <input type="text" name="" />
                      </div>
                    </div>
                    <div class="col2">
                      <label>Address</label>
                      <div class="inputHolder">
                        <input type="text" name="" />
                      </div>
                    </div>
                    <div class="clear"></div>
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
                      <label>1st death / 2nd death    Executor, Trustee, Both</label>
                      <span class="textarea">
                      <textarea>
													
												</textarea>
                      </span> </div>
                  </div>
                  <!--section 3-->
                  <!--section 4-->
                  <div class="section">
                    <div class="col1">
                      <label>Name</label>
                      <div class="inputHolder">
                        <input type="text" name="" />
                      </div>
                    </div>
                    <div class="col2">
                      <label>Address</label>
                      <div class="inputHolder">
                        <input type="text" name="" />
                      </div>
                    </div>
                    <div class="clear"></div>
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
                      <label>1st death / 2nd death    Executor, Trustee, Both</label>
                      <span class="textarea">
                      <textarea>
													
												</textarea>
                      </span> </div>
                  </div>
                  <!--section 4-->
                  <h2>GUARDIANS TO MINOR CHILDREN</h2>
                  <!--section 5-->
                  <div class="section">
                    <div class="col1">
                      <label>Name</label>
                      <div class="inputHolder">
                        <input type="text" name="" />
                      </div>
                    </div>
                    <div class="col2">
                      <label>Address</label>
                      <div class="inputHolder">
                        <input type="text" name="" />
                      </div>
                    </div>
                    <div class="clear"></div>
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
                      <label>1st death / 2nd death    Executor, Trustee, Both</label>
                      <span class="textarea">
                      <textarea>
													
												</textarea>
                      </span> </div>
                  </div>
                  <!--section 5-->
                  <!--section 6-->
                  <div class="section">
                    <div class="col1">
                      <label>Name</label>
                      <div class="inputHolder">
                        <input type="text" name="" />
                      </div>
                    </div>
                    <div class="col2">
                      <label>Address</label>
                      <div class="inputHolder">
                        <input type="text" name="" />
                      </div>
                    </div>
                    <div class="clear"></div>
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
                      <label>1st death / 2nd death    Executor, Trustee, Both</label>
                      <span class="textarea">
                      <textarea>
													
												</textarea>
                      </span> </div>
                  </div>
                  <!--section 6-->
                  <!--section 7-->
                  <div class="section">
                    <div class="col1">
                      <label>Name</label>
                      <div class="inputHolder">
                        <input type="text" name="" />
                      </div>
                    </div>
                    <div class="col2">
                      <label>Address</label>
                      <div class="inputHolder">
                        <input type="text" name="" />
                      </div>
                    </div>
                    <div class="clear"></div>
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
                      <label>1st death / 2nd death    Executor, Trustee, Both</label>
                      <span class="textarea">
                      <textarea>
													
												</textarea>
                      </span> </div>
                  </div>
                  <!--section 7-->
                  <!--section 8-->
                  <div class="section">
                    <div class="col1">
                      <label>Name</label>
                      <div class="inputHolder">
                        <input type="text" name="" />
                      </div>
                    </div>
                    <div class="col2">
                      <label>Address</label>
                      <div class="inputHolder">
                        <input type="text" name="" />
                      </div>
                    </div>
                    <div class="clear"></div>
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
                      <label>1st death / 2nd death    Executor, Trustee, Both</label>
                      <span class="textarea">
                      <textarea>
													
												</textarea>
                      </span> </div>
                  </div>
                  <!--section 8-->
                </div>
                <div class="nextBttnHolder">
                  <div class="ErrorMessage">* More Information Needed</div>
                  <div class="toTheNextStep"> <span>Continue to the next step</span> <a href=".item5" class="panel bttnHolder"> <span class="bttn">Next Step</span> </a> </div>
                  <div class="clear"></div>
                </div>
              </div>
            </div>
            <div  class="item item5" >
              <h1>STEP 5 - SPECIFIC GIFTS AND LEGACIES</h1>
              <div class="content">
                <div class="form">
                  <label>Are there any specific gifts or sums of money that either client would like to leave?</label>
                  <!--section 1-->
                  <div class="section">
                    <div class="col1">
                      <label>Beneficiaries Name</label>
                      <span class="inputHolder">
                      <input type="text" />
                      </span> </div>
                    <div class="col2">
                      <label>Given By</label>
                      <div class="radioHolder">
                        <input type="radio" name="" class="radio"/>
                        <span>Client 1 </span>&nbsp;
                        <input type="radio" name="" class="radio"/>
                        <span>Client 2 </span>&nbsp;
                        <input type="radio" name="" class="radio"/>
                        <span>Both</span> </div>
                    </div>
                    <div class="clear"></div>
                    <div class="col1">
                      <label>Address</label>
                      <span class="inputHolder">
                      <input type="text" />
                      </span> </div>
                    <div class="col2">
                      <label>Relationship to Client </label>
                      <span class="inputHolder">
                      <input type="text" />
                      </span> </div>
                    <div class="clear"></div>
                    <div class="col1">
                      <label>Executed After</label>
                      <div class="radioHolder">
                        <input type="radio" name="" class="radio"/>
                        <span>First death </span>&nbsp;
                        <input type="radio" name="" class="radio"/>
                        <span>Second death </span>&nbsp;
                        <input type="radio" name="" class="radio"/>
                        <span>Joint death</span> </div>
                    </div>
                    <div class="clear"></div>
                  </div>
                  <!--section 1-->
                  <!--section 2-->
                  <div class="section">
                    <div class="col1">
                      <label>Description of Gift / Sum of Money</label>
                      <span class="textarea">
                      <textarea>
													
												</textarea>
                      </span> </div>
                    <div class="col2">
                      <label>Minor Beneficiary </label>
                      <div class="radioHolder">
                        <input type="radio" name="" class="radio"/>
                        <span>Yes</span>&nbsp;
                        <input type="radio" name="" class="radio"/>
                        <span>No </span> </div>
                    </div>
                    <div class="clear"></div>
                    <div class="col1">
                      <label>Beneficiaries Name</label>
                      <span class="inputHolder">
                      <input type="text" />
                      </span> </div>
                    <div class="col2">
                      <label>Given By</label>
                      <div class="radioHolder">
                        <input type="radio" name="" class="radio"/>
                        <span>Client 1 </span>&nbsp;
                        <input type="radio" name="" class="radio"/>
                        <span>Client 2 </span>&nbsp;
                        <input type="radio" name="" class="radio"/>
                        <span>Both</span> </div>
                    </div>
                    <div class="clear"></div>
                    <div class="col1">
                      <label>Address</label>
                      <span class="inputHolder">
                      <input type="text" />
                      </span> </div>
                    <div class="col2">
                      <label>Relationship to Client </label>
                      <span class="inputHolder">
                      <input type="text" />
                      </span> </div>
                    <div class="clear"></div>
                    <div class="col1">
                      <label>Executed After</label>
                      <div class="radioHolder">
                        <input type="radio" name="" class="radio"/>
                        <span>First death </span>&nbsp;
                        <input type="radio" name="" class="radio"/>
                        <span>Second death </span>&nbsp;
                        <input type="radio" name="" class="radio"/>
                        <span>Joint death</span> </div>
                    </div>
                    <div class="clear"></div>
                  </div>
                  <!--section 2-->
                  <!--section 3-->
                  <div class="section">
                    <div class="col1">
                      <label>Description of Gift / Sum of Money</label>
                      <span class="textarea">
                      <textarea>
													
												</textarea>
                      </span> </div>
                    <div class="col2">
                      <label>Minor Beneficiary </label>
                      <div class="radioHolder">
                        <input type="radio" name="" class="radio"/>
                        <span>Yes</span>&nbsp;
                        <input type="radio" name="" class="radio"/>
                        <span>No </span> </div>
                    </div>
                    <div class="clear"></div>
                    <div class="col1">
                      <label>Beneficiaries Name</label>
                      <span class="inputHolder">
                      <input type="text" />
                      </span> </div>
                    <div class="col2">
                      <label>Given By</label>
                      <div class="radioHolder">
                        <input type="radio" name="" class="radio"/>
                        <span>Client 1 </span>&nbsp;
                        <input type="radio" name="" class="radio"/>
                        <span>Client 2 </span>&nbsp;
                        <input type="radio" name="" class="radio"/>
                        <span>Both</span> </div>
                    </div>
                    <div class="clear"></div>
                    <div class="col1">
                      <label>Address</label>
                      <span class="inputHolder">
                      <input type="text" />
                      </span> </div>
                    <div class="col2">
                      <label>Relationship to Client </label>
                      <span class="inputHolder">
                      <input type="text" />
                      </span> </div>
                    <div class="clear"></div>
                    <div class="col1">
                      <label>Executed After</label>
                      <div class="radioHolder">
                        <input type="radio" name="" class="radio"/>
                        <span>First death </span>&nbsp;
                        <input type="radio" name="" class="radio"/>
                        <span>Second death </span>&nbsp;
                        <input type="radio" name="" class="radio"/>
                        <span>Joint death</span> </div>
                    </div>
                    <div class="clear"></div>
                  </div>
                  <!--section 3-->
                  <!--section 4 -->
                  <div class="section noBorder">
                    <label class="strong">Reserve Beneficiaries</label>
                    <p>This is in case if all other named beneficiaries are decease</p>
                    <div class="col1">
                      <label>Beneficiaries Name</label>
                      <span class="inputHolder">
                      <input type="text" />
                      </span> </div>
                    <div class="clear"></div>
                    <div class="col1">
                      <label>Address</label>
                      <span class="inputHolder">
                      <input type="text" />
                      </span> </div>
                    <div class="col2">
                      <label>Relationship to Client </label>
                      <span class="inputHolder">
                      <input type="text" />
                      </span> </div>
                    <div class="clear"></div>
                  </div>
                  <!--section 4 -->
                </div>
                <div class="nextBttnHolder">
                  <div class="ErrorMessage">* More Information Needed</div>
                  <div class="toTheNextStep"> <span>Continue to the next step</span> <a href=".item6" class="panel bttnHolder"> <span class="bttn">Next Step</span> </a> </div>
                  <div class="clear"></div>
                </div>
              </div>
            </div>
            <div class="item item6">
              <div class="content">
                <h2>STEP 6 - CHARITABLE GIFTS AND LEGACIES</h2>
                <div class="form">
                  <p>Is there a charity or cause that the client would like to remember in their Will?</p>
                  <!--section 1-->
                  <div class="section">
                    <div class="col1">
                      <label>Charity Name</label>
                      <span class="inputHolder">
                      <input type="text" />
                      </span> </div>
                    <div class="col2">
                      <label>Given By</label>
                      <div class="radioHolder">
                        <input type="radio" name="" class="radio"/>
                        <span>Client 1 </span>&nbsp;
                        <input type="radio" name="" class="radio"/>
                        <span>Client 2 </span>&nbsp;
                        <input type="radio" name="" class="radio"/>
                        <span>Both</span> </div>
                    </div>
                    <div class="clear"></div>
                    <div class="col1">
                      <label>Address</label>
                      <span class="inputHolder">
                      <input type="text" />
                      </span> </div>
                    <div class="col2">
                      <label>Charity Reg No</label>
                      <span class="inputHolder">
                      <input type="text" />
                      </span> </div>
                    <div class="clear"></div>
                    <div class="col1">
                      <label>Executed After</label>
                      <div class="radioHolder">
                        <input type="radio" name="" class="radio"/>
                        <span>First death </span>&nbsp;
                        <input type="radio" name="" class="radio"/>
                        <span>Second death </span>&nbsp;
                        <input type="radio" name="" class="radio"/>
                        <span>Joint death</span> </div>
                    </div>
                    <div class="clear"></div>
                    <div class="col1">
                      <label>Description of Gift / Sum of Money</label>
                      <span class="textarea">
                      <textarea>
													
												</textarea>
                      </span> </div>
                    <div class="col2">
                      <div>
                        <label>OK to advise charity of legacy?</label>
                        <div class="radioHolder">
                          <input type="radio" name="" class="radio"/>
                          <span>Yes</span>&nbsp;
                          <input type="radio" name="" class="radio"/>
                          <span>No </span> </div>
                      </div>
                      <div class="divider"></div>
                      <div>
                        <label>OK to advise charity of client’s details? </label>
                        <div class="radioHolder">
                          <input type="radio" name="" class="radio"/>
                          <span>Yes</span>&nbsp;
                          <input type="radio" name="" class="radio"/>
                          <span>No </span> </div>
                      </div>
                    </div>
                    <div class="clear"></div>
                  </div>
                  <!--section 1-->
                  <!--section 2-->
                  <div class="section lastSection">
                    <div class="col1">
                      <label>Charity Name</label>
                      <span class="inputHolder">
                      <input type="text" />
                      </span> </div>
                    <div class="col2">
                      <label>Given By</label>
                      <div class="radioHolder">
                        <input type="radio" name="" class="radio"/>
                        <span>Client 1 </span>&nbsp;
                        <input type="radio" name="" class="radio"/>
                        <span>Client 2 </span>&nbsp;
                        <input type="radio" name="" class="radio"/>
                        <span>Both</span> </div>
                    </div>
                    <div class="clear"></div>
                    <div class="col1">
                      <label>Address</label>
                      <span class="inputHolder">
                      <input type="text" />
                      </span> </div>
                    <div class="col2">
                      <label>Charity Reg No</label>
                      <span class="inputHolder">
                      <input type="text" />
                      </span> </div>
                    <div class="clear"></div>
                    <div class="col1">
                      <label>Executed After</label>
                      <div class="radioHolder">
                        <input type="radio" name="" class="radio"/>
                        <span>First death </span>&nbsp;
                        <input type="radio" name="" class="radio"/>
                        <span>Second death </span>&nbsp;
                        <input type="radio" name="" class="radio"/>
                        <span>Joint death</span> </div>
                    </div>
                    <div class="clear"></div>
                    <div class="col1">
                      <label>Description of Gift / Sum of Money</label>
                      <span class="textarea">
                      <textarea>
													
												</textarea>
                      </span> </div>
                    <div class="col2">
                      <div>
                        <label>OK to advise charity of legacy?</label>
                        <div class="radioHolder">
                          <input type="radio" name="" class="radio"/>
                          <span>Yes</span>&nbsp;
                          <input type="radio" name="" class="radio"/>
                          <span>No </span> </div>
                      </div>
                      <div class="divider"></div>
                      <div>
                        <label>OK to advise charity of client’s details? </label>
                        <div class="radioHolder">
                          <input type="radio" name="" class="radio"/>
                          <span>Yes</span>&nbsp;
                          <input type="radio" name="" class="radio"/>
                          <span>No </span> </div>
                      </div>
                    </div>
                    <div class="clear"></div>
                  </div>
                  <!--section 2-->
                </div>
                <div class="nextBttnHolder">
                  <div class="ErrorMessage">* More Information Needed</div>
                  <div class="toTheNextStep"> <span>Continue to the next step</span> <a href=".item7" class="panel bttnHolder"> <span class="bttn">Next Step</span> </a> </div>
                  <div class="clear"></div>
                </div>
              </div>
            </div>
            <div class="item item7">
              <div class="content">
                <h2>STEP 7 - Lasting Power of Attorney</h2>
                <div class="form">
                  <!--section 1-->
                  <div class="section">
                    <!--line 1-->
                    <div class="itemIn">
                      <label>Clients to proceed with Lasting Power of Attorney</label>
                      <span class="radioHolder">
                      <input type="radio" name="" class="radio"/>
                      <span>Yes </span>&nbsp;
                      <input type="radio" name="" class="radio"/>
                      <span>No </span> </span> </div>
                    <div class="clear"></div>
                    <!--line 1-->
                    <!--line 2-->
                    <div class="col1">
                      <h2>CLIENT 1</h2>
                    </div>
                    <div class="col2">
                      <h2>CLIENT 2</h2>
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
                      <label>Names of attorneys if not Executors</label>
                    </div>
                    <div class="col2">
                      <label>Names of attorneys if not Executors</label>
                    </div>
                    <div class="clear"></div>
                    <!--line 4-->
                    <!--line 5-->
                    <div class="col1">
                      <label>Name</label>
                      <span class="inputHolder">
                      <input type="text" />
                      </span> </div>
                    <div class="col2">
                      <label>Name</label>
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
                        <label>Data of birth</label>
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
                        <label>Data of birth</label>
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
                      <label>Name</label>
                      <span class="inputHolder">
                      <input type="text" />
                      </span> </div>
                    <div class="col2">
                      <label>Name</label>
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
                        <label>Data of birth</label>
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
                        <label>Data of birth</label>
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
                      <label class="strongInner">Attorneys Authorit</label>
                      <label>General authority for all assets</label>
                      <br/>
                      <span class="radioHolder">
                      <input type="radio" name="" class="radio"/>
                      <span>Yes </span>&nbsp;
                      <input type="radio" name="" class="radio"/>
                      <span>No </span> </span> </div>
                    <div class="col2">
                      <label class="strongInner">Attorneys Authorit</label>
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
                <h2>STEP 8 - Funeral Arrangements</h2>
                <div class="form">
                  <div class="section">
                    <!--line 1-->
                    <div class="itemIn">
                      <label>Have the clients a pre paid funeral plan? </label>
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
                      <h2>YOUR PARTNER’S DETAILS</h2>
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
                    <div class="itemIn">
                      <label>The current nil rate band in the UK is £325,000, does your estate exceed this?</label>
                      <span class="radioHolder">
                      <input type="radio" name="" class="radio"/>
                      <span>Yes </span>&nbsp;
                      <input type="radio" name="" class="radio"/>
                      <span>No </span> </span> </div>
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
          </form>
        </div>
      </div>
    </div>
    <div class="clear"></div>
  </div>
</div>
<? include   TEMPLATE_DIR.  "footer.php"; ?>
