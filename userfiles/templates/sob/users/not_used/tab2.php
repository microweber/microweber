<script type="text/javascript">
function toolTip(txt){

}

function showWithdraw(){

    $(".withdraw").hide();
    var val = $("#withdraw_type").val();

    $("#withdraw_form" + val).show();
}



</script>

<div id="tab2table">
 <h2 class="blue-title">Profile Information</h2>

             <div class="f">
              <label>Username</label>
              <input type="text" value="" maxlength="32" style="width: 183px;" class="text disabled data" readonly="readonly" name="username"/>
             </div>
             <div class="f">
              <label>Password</label>
              <input type="password" value="" class="password data" maxlength="32" style="width: 183px;" name="password"/>
              <a onmouseout="toolTip();" class="Xtip" onmouseover="toolTip('4-32 symbols')" onclick="javascript:return false;" tabindex="100" href="#"><img width="16" height="16" border="0" src="http://workspace.ooyes.net/wfl_dev/aff3/templates/images/help.png"/></a></td>
             </div>

             <div class="f">
                <label>Confirm Password</label>
                <input type="password" value="" class="password data" maxlength="32" style="width: 183px;" name="password_c"/>
                <a onmouseout="toolTip();" class="Xtip" onmouseover="toolTip('4-32 symbols')" onclick="javascript:return false;" tabindex="100" href="#"><img width="16" height="16" border="0" src="http://workspace.ooyes.net/wfl_dev/aff3/templates/images/help.png"/></a></td>
             </div>
             <div class="f">
                <label>Birthday</label>
                <select id="month" style="width: 60px;" name="month"><option value="1">Jan</option><option value="2">Feb</option><option value="3">Mar</option><option value="4">Apr</option><option value="5">May</option><option value="6">Jun</option><option value="7">Jul</option><option value="8">Aug</option><option value="9">Sep</option><option value="10">Oct</option><option value="11">Nov</option><option value="12">Dec</option></select>
                <select id="day" style="width: 60px;" name="day"><option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option><option value="6">6</option><option value="7">7</option><option value="8">8</option><option value="9">9</option><option value="10">10</option><option value="11">11</option><option value="12">12</option><option value="13">13</option><option value="14">14</option><option value="15">15</option><option value="16">16</option><option value="17">17</option><option value="18">18</option><option value="19">19</option><option value="20">20</option><option value="21">21</option><option value="22">22</option><option value="23">23</option><option value="24">24</option><option value="25">25</option><option value="26">26</option><option value="27">27</option><option value="28">28</option><option value="29">29</option><option value="30">30</option><option value="31">31</option></select>
                <select id="year" style="width: 60px;" name="year"><option value="1993">1993</option><option value="1992">1992</option><option value="1991">1991</option><option value="1990">1990</option><option value="1989">1989</option><option value="1988">1988</option><option value="1987">1987</option><option value="1986">1986</option><option value="1985">1985</option><option value="1984">1984</option><option value="1983">1983</option><option value="1982">1982</option><option value="1981">1981</option><option value="1980">1980</option><option value="1979">1979</option><option value="1978">1978</option><option value="1977">1977</option><option value="1976">1976</option><option value="1975">1975</option><option value="1974">1974</option><option value="1973">1973</option><option value="1972">1972</option><option value="1971">1971</option><option value="1970">1970</option><option value="1969">1969</option><option value="1968">1968</option><option value="1967">1967</option><option value="1966">1966</option><option value="1965">1965</option><option value="1964">1964</option><option value="1963">1963</option><option value="1962">1962</option><option value="1961">1961</option><option value="1960">1960</option><option value="1959">1959</option><option value="1958">1958</option><option value="1957">1957</option><option value="1956">1956</option><option value="1955">1955</option><option value="1954">1954</option><option value="1953">1953</option><option value="1952">1952</option><option value="1951">1951</option><option value="1950">1950</option><option value="1949">1949</option><option value="1948">1948</option><option value="1947">1947</option><option value="1946">1946</option><option value="1945">1945</option><option value="1944">1944</option><option value="1943">1943</option><option value="1942">1942</option><option value="1941">1941</option><option value="1940">1940</option><option value="1939">1939</option><option value="1938">1938</option><option value="1937">1937</option><option value="1936">1936</option><option value="1935">1935</option><option value="1934">1934</option><option value="1933">1933</option><option value="1932">1932</option><option value="1931">1931</option><option value="1930">1930</option><option value="1929">1929</option><option value="1928">1928</option><option value="1927">1927</option><option value="1926">1926</option><option value="1925">1925</option><option value="1924">1924</option><option value="1923">1923</option><option value="1922">1922</option><option value="1921">1921</option><option value="1920">1920</option><option value="1919">1919</option><option value="1918">1918</option><option value="1917">1917</option><option value="1916">1916</option><option value="1915">1915</option><option value="1914">1914</option><option value="1913">1913</option><option value="1912">1912</option><option value="1911">1911</option><option value="1910">1910</option><option value="1909">1909</option><option value="1908">1908</option><option value="1907">1907</option><option value="1906">1906</option><option value="1905">1905</option><option value="1904">1904</option><option value="1903">1903</option><option value="1902">1902</option><option value="1901">1901</option><option value="1900">1900</option><option value="1899">1899</option><option value="1898">1898</option></select>
                <a style="visibility: hidden;" onclick="javascript:return false;" tabindex="100" href="#"><img width="16" height="16" border="0" src="http://workspace.ooyes.net/wfl_dev/aff3/templates/images/help.png"/></a>
            </div>
            <div class="f">
              <label>Website URL</label>
              <input type="text" value="" class="text data" style="width: 183px;" name="url"/>
              <a style="visibility: hidden;" tabindex="100" href="#"><img width="16" height="16" border="0" src="http://workspace.ooyes.net/wfl_dev/aff3/templates/images/help.png"/></a></td>
            </div>


            <h2 class="blue-title-small">Personal Data </h2>
            <h2 class="blue-title-small">Banking Details</h2>
            <div class="f">
                <label>First Name</label>
                <input type="text" value="" class="text data" style="" name="f_name"/>
                *<a style="visibility: hidden;" href="#">
    			<img width="16" height="16" border="0" src="http://workspace.ooyes.net/wfl_dev/aff3/templates/images/help.png"/></a>
            </div>
            <div class="f">
                <label>Last Name</label>
                <input type="text" value="" class="text data" style="" name="l_name"/>
                *<a style="visibility: hidden;" href="#"><img width="16" height="16" border="0" src="http://workspace.ooyes.net/wfl_dev/aff3/templates/images/help.png"/></a></td>
             </div>
            <div class="f">
                <label>Email</label>
                <input type="text" value="" class="text data" style="" name="email"/>
                *<a style="visibility: hidden;" href="#">
    				<img width="16" height="16" border="0" src="http://workspace.ooyes.net/wfl_dev/aff3/templates/images/help.png"/>
    			</a>
            </div>
            <div class="f">
                <label>Phone</label>
                <input type="text" value="" class="text data" style="" name="phone"/>
                *<a style="visibility: hidden;" href="#">
    			    <img width="16" height="16" border="0" src="http://workspace.ooyes.net/wfl_dev/aff3/templates/images/help.png"/>
    			</a>
            </div>
            <div class="f">
              <label>Fax No.</label>
              <input type="text" value="" class="text data" style="" name="fax"/>
              <a style="visibility: hidden;" href="#"><img width="16" height="16" border="0" src="http://workspace.ooyes.net/wfl_dev/aff3/templates/images/help.png"/></a></td>
            </div>
            <div class="f">
              <label>Tax ID or SSN</label>
              <input type="text" value="" class="text data" style="" name="tax_id_ssn"/>
              <a onmouseout="toolTip();" class="Xtip" onmouseover="toolTip('NON-US users, leave empty')" onclick="javascript:return false;" href="#"><img width="16" height="16" border="0" src="http://workspace.ooyes.net/wfl_dev/aff3/templates/images/help.png"/></a></td>
            </div>
            <div class="f">
              <label>Address</label>
              <input type="text" value="" class="text data" style="" name="address_one"/>
              *<a style="visibility: hidden;" href="#"><img width="16" height="16" border="0" src="http://workspace.ooyes.net/wfl_dev/aff3/templates/images/help.png"/></a></td>
            </div>
            <div class="f">
              <label>Second Address</label>
              <input type="text" value="" class="text data" style="" name="address_two"/>
              <a style="visibility: hidden;" href="#"><img width="16" height="16" border="0" src="http://workspace.ooyes.net/wfl_dev/aff3/templates/images/help.png"/></a></td>
            </div>
            <div class="f">
              <label>City</label>
              <input type="text" value="" class="text data" style="" name="city"/>
              *<a style="visibility: hidden;" href="#"><img width="16" height="16" border="0" src="http://workspace.ooyes.net/wfl_dev/aff3/templates/images/help.png"/></a></td>
            </div>
            <div class="f">
            <label>Postal/Zip Code</label>
                <input type="text" value="" class="text data" style="" name="zip"/>
                *<a style="visibility: hidden;" href="#"><img width="16" height="16" border="0" src="http://workspace.ooyes.net/wfl_dev/aff3/templates/images/help.png"/></a></td>
            </div>
            <div class="f">
              <label>County/State</label>
              <input type="text" value="" class="text data" style="" name="state"/>
              <a style="visibility: hidden;" href="#"><img width="16" height="16" border="0" src="http://workspace.ooyes.net/wfl_dev/aff3/templates/images/help.png"/></a></td>
            </div>
            <div class="f">
              <label>Country</label>
              <select id="country" class="data" style="" name="country"><option value="5">Afghanistan</option><option value="8">Albania</option><option value="61">Algeria</option><option value="14">American Samoa</option><option value="3">Andorra</option><option value="11">Angola</option><option value="7">Anguilla</option><option value="12">Antarctica</option><option value="6">Antigua and Barbuda</option><option value="13">Argentina</option><option value="9">Armenia</option><option value="17">Aruba</option><option value="1">Asia/Pacific Region</option><option value="16">Australia</option><option value="15">Austria</option><option value="18">Azerbaijan</option><option value="32">Bahamas</option><option value="25">Bahrain</option><option value="21">Bangladesh</option><option value="20">Barbados</option><option value="36">Belarus</option><option value="22">Belgium</option><option value="37">Belize</option><option value="27">Benin</option><option value="28">Bermuda</option><option value="33">Bhutan</option><option value="30">Bolivia</option><option value="19">Bosnia and Herzegovina</option><option value="35">Botswana</option><option value="34">Bouvet Island</option><option value="31">Brazil</option><option value="104">British Indian Ocean Territory</option><option value="29">Brunei Darussalam</option><option value="24">Bulgaria</option><option value="23">Burkina Faso</option><option value="26">Burundi</option><option value="114">Cambodia</option><option value="47">Cameroon</option><option value="38">Canada</option><option value="52">Cape Verde</option><option value="121">Cayman Islands</option><option value="41">Central African Republic</option><option value="207">Chad</option><option value="46">Chile</option><option value="48">China</option><option value="49">Colombia</option><option value="116">Comoros</option><option value="42">Congo</option><option value="40">Congo, The Democratic Republic of the</option><option value="45">Cook Islands</option><option value="50">Costa Rica</option><option value="44">Cote D\'Ivoire</option><option value="97">Croatia</option><option value="51">Cuba</option><option value="54">Cyprus</option><option value="55">Czech Republic</option><option value="58">Denmark</option><option value="57">Djibouti</option><option value="59">Dominica</option><option value="60">Dominican Republic</option><option value="62">Ecuador</option><option value="64">Egypt</option><option value="203">El Salvador</option><option value="87">Equatorial Guinea</option><option value="66">Eritrea</option><option value="63">Estonia</option><option value="68">Ethiopia</option><option value="71">Falkland Islands (Malvinas)</option><option value="73">Faroe Islands</option><option value="70">Fiji</option><option value="69">Finland</option><option value="74">France</option><option value="80">French Guiana</option><option value="170">French Polynesia</option><option value="208">French Southern Territories</option><option value="76">Gabon</option><option value="84">Gambia</option><option value="79">Georgia</option><option value="56">Germany</option><option value="81">Ghana</option><option value="82">Gibraltar</option><option value="88">Greece</option><option value="83">Greenland</option><option value="78">Grenada</option><option value="86">Guadeloupe</option><option value="91">Guam</option><option value="90">Guatemala</option><option value="85">Guinea</option><option value="92">Guinea-Bissau</option><option value="93">Guyana</option><option value="98">Haiti</option><option value="95">Heard Island and McDonald Islands</option><option value="228">Holy See (Vatican City State)</option><option value="96">Honduras</option><option value="94">Hong Kong</option><option value="99">Hungary</option><option value="107">Iceland</option><option value="103">India</option><option value="100">Indonesia</option><option value="106">Iran, Islamic Republic of</option><option value="105">Iraq</option><option value="101">Ireland</option><option value="102">Israel</option><option value="108">Italy</option><option value="109">Jamaica</option><option value="111">Japan</option><option value="110">Jordan</option><option value="122">Kazakstan</option><option value="112">Kenya</option><option value="115">Kiribati</option><option value="118">Korea, Democratic People\'s Republic of</option><option value="119">Korea, Republic of</option><option value="120">Kuwait</option><option value="113">Kyrgyzstan</option><option value="123">Lao People\'s Democratic Republic</option><option value="132">Latvia</option><option value="124">Lebanon</option><option value="129">Lesotho</option><option value="128">Liberia</option><option value="133">Libyan Arab Jamahiriya</option><option value="126">Liechtenstein</option><option value="130">Lithuania</option><option value="131">Luxembourg</option><option value="143">Macau</option><option value="139">Macedonia</option><option value="137">Madagascar</option><option value="151">Malawi</option><option value="153">Malaysia</option><option value="150">Maldives</option><option value="140">Mali</option><option value="148">Malta</option><option value="138">Marshall Islands</option><option value="145">Martinique</option><option value="146">Mauritania</option><option value="149">Mauritius</option><option value="238">Mayotte</option><option value="152">Mexico</option><option value="72">Micronesia, Federated States of</option><option value="136">Moldova, Republic of</option><option value="135">Monaco</option><option value="142">Mongolia</option><option value="147">Montserrat</option><option value="134">Morocco</option><option value="154">Mozambique</option><option value="141">Myanmar</option><option value="155">Namibia</option><option value="164">Nauru</option><option value="163">Nepal</option><option value="161">Netherlands</option><option value="10">Netherlands Antilles</option><option value="156">New Caledonia</option><option value="166">New Zealand</option><option value="160">Nicaragua</option><option value="157">Niger</option><option value="159">Nigeria</option><option value="165">Niue</option><option value="158">Norfolk Island</option><option value="144">Northern Mariana Islands</option><option value="162">Norway</option><option value="167">Oman</option><option value="173">Pakistan</option><option value="180">Palau</option><option value="178">Palestinian Territory, Occupied</option><option value="168">Panama</option><option value="171">Papua New Guinea</option><option value="181">Paraguay</option><option value="169">Peru</option><option value="172">Philippines</option><option value="174">Poland</option><option value="179">Portugal</option><option value="177">Puerto Rico</option><option value="182">Qatar</option><option value="183">Reunion</option><option value="184">Romania</option><option value="185">Russian Federation</option><option value="186">Rwanda</option><option value="117">Saint Kitts and Nevis</option><option value="125">Saint Lucia</option><option value="229">Saint Vincent and the Grenadines</option><option value="236">Samoa</option><option value="198">San Marino</option><option value="202">Sao Tome and Principe</option><option value="187">Saudi Arabia</option><option value="199">Senegal</option><option value="239">Serbia and Montenegro</option><option value="189">Seychelles</option><option value="197">Sierra Leone</option><option value="192">Singapore</option><option value="196">Slovakia</option><option value="194">Slovenia</option><option value="188">Solomon Islands</option><option value="200">Somalia</option><option value="240">South Africa</option><option value="67">Spain</option><option value="127">Sri Lanka</option><option value="190">Sudan</option><option value="201">Suriname</option><option value="205">Swaziland</option><option value="191">Sweden</option><option value="43">Switzerland</option><option value="204">Syrian Arab Republic</option><option value="220">Taiwan</option><option value="211">Tajikistan</option><option value="221">Tanzania, United Republic of</option><option value="210">Thailand</option><option value="209">Togo</option><option value="212">Tokelau</option><option value="215">Tonga</option><option value="218">Trinidad and Tobago</option><option value="214">Tunisia</option><option value="217">Turkey</option><option value="213">Turkmenistan</option><option value="206">Turks and Caicos Islands</option><option value="219">Tuvalu</option><option value="223">Uganda</option><option value="222">Ukraine</option><option value="4">United Arab Emirates</option><option value="77">United Kingdom</option><option selected="" value="225">United States</option><option value="224">United States Minor Outlying Islands</option><option value="226">Uruguay</option><option value="227">Uzbekistan</option><option value="234">Vanuatu</option><option value="230">Venezuela</option><option value="233">Vietnam</option><option value="231">Virgin Islands, British</option><option value="232">Virgin Islands, U.S.</option><option value="235">Wallis and Futuna</option><option value="237">Yemen</option><option value="241">Zambia</option><option value="243">Zimbabwe</option></select>
              <a style="visibility: hidden;" href="#"><img width="16" height="16" border="0" src="http://workspace.ooyes.net/wfl_dev/aff3/templates/images/help.png"/></a></td>
            </div>
            <div class="f">
              <label>Payment Type <input type="hidden" value="-1" name="wid"/></label>
              <select class="data" onchange="showWithdraw();" style="" id="withdraw_type" name="withdraw_type">
              <option selected="" value="2">Bank Check</option>
                <option value="1">Bank transfer</option>
                <option value="7">CapitalCollect</option>
                <option value="5">Epassporte</option>
                <option value="8">Fethard</option>
                <option value="6">Ikobo</option>
                <option value="4">Moneybookers</option>
                <option value="3">PayPal transfer</option>
                <option value="9">WebMoney</option>
                <option value="11">Yandex.Money</option>
                <option value="10">Other</option>
              </select>
            </div>
            <div class="f">
              <label>Payment Currency</label>
              <select id="cur" style="" class="data" name="cur"><option selected="" value="840">$ - USD</option></select>
            </div>
<div class="withdraw" id="withdraw_form1" style="display: none;">

           <h2 class="blue-title-small">Bank Information</h2>
            <div class="f">
              <label>Bank Name:</label>
              <input type="text" value="" class="text data" style="" name="V1[0]"/>
             *
            </div>
            <div class="f">
                <label>Bank Address:</label>
                <input type="text" value="" class="text data" style="" name="V1[1]"/>
             *
             </div>
            <div class="f">
              <label>Bank City:</label>
              <input type="text" value="" class="text data" style="" name="V1[2]"/>
               *
            </div>
            <div class="f">
                <label>Bank ZIP:</label>
                <input type="text" value="" class="text data" style="" name="V1[3]"/>
                 *
             </div>
            <div class="f">
              <label>Bank Country:</label>
  			  <select id="V1[4]" class="data" style="" name="V1[4]"><option value="5">Afghanistan</option><option value="8">Albania</option><option value="61">Algeria</option><option value="14">American Samoa</option><option value="3">Andorra</option><option value="11">Angola</option><option value="7">Anguilla</option><option value="12">Antarctica</option><option value="6">Antigua and Barbuda</option><option value="13">Argentina</option><option value="9">Armenia</option><option value="17">Aruba</option><option value="1">Asia/Pacific Region</option><option value="16">Australia</option><option value="15">Austria</option><option value="18">Azerbaijan</option><option value="32">Bahamas</option><option value="25">Bahrain</option><option value="21">Bangladesh</option><option value="20">Barbados</option><option value="36">Belarus</option><option value="22">Belgium</option><option value="37">Belize</option><option value="27">Benin</option><option value="28">Bermuda</option><option value="33">Bhutan</option><option value="30">Bolivia</option><option value="19">Bosnia and Herzegovina</option><option value="35">Botswana</option><option value="34">Bouvet Island</option><option value="31">Brazil</option><option value="104">British Indian Ocean Territory</option><option value="29">Brunei Darussalam</option><option value="24">Bulgaria</option><option value="23">Burkina Faso</option><option value="26">Burundi</option><option value="114">Cambodia</option><option value="47">Cameroon</option><option value="38">Canada</option><option value="52">Cape Verde</option><option value="121">Cayman Islands</option><option value="41">Central African Republic</option><option value="207">Chad</option><option value="46">Chile</option><option value="48">China</option><option value="49">Colombia</option><option value="116">Comoros</option><option value="42">Congo</option><option value="40">Congo, The Democratic Republic of the</option><option value="45">Cook Islands</option><option value="50">Costa Rica</option><option value="44">Cote D\'Ivoire</option><option value="97">Croatia</option><option value="51">Cuba</option><option value="54">Cyprus</option><option value="55">Czech Republic</option><option value="58">Denmark</option><option value="57">Djibouti</option><option value="59">Dominica</option><option value="60">Dominican Republic</option><option value="62">Ecuador</option><option value="64">Egypt</option><option value="203">El Salvador</option><option value="87">Equatorial Guinea</option><option value="66">Eritrea</option><option value="63">Estonia</option><option value="68">Ethiopia</option><option value="71">Falkland Islands (Malvinas)</option><option value="73">Faroe Islands</option><option value="70">Fiji</option><option value="69">Finland</option><option value="74">France</option><option value="80">French Guiana</option><option value="170">French Polynesia</option><option value="208">French Southern Territories</option><option value="76">Gabon</option><option value="84">Gambia</option><option value="79">Georgia</option><option value="56">Germany</option><option value="81">Ghana</option><option value="82">Gibraltar</option><option value="88">Greece</option><option value="83">Greenland</option><option value="78">Grenada</option><option value="86">Guadeloupe</option><option value="91">Guam</option><option value="90">Guatemala</option><option value="85">Guinea</option><option value="92">Guinea-Bissau</option><option value="93">Guyana</option><option value="98">Haiti</option><option value="95">Heard Island and McDonald Islands</option><option value="228">Holy See (Vatican City State)</option><option value="96">Honduras</option><option value="94">Hong Kong</option><option value="99">Hungary</option><option value="107">Iceland</option><option value="103">India</option><option value="100">Indonesia</option><option value="106">Iran, Islamic Republic of</option><option value="105">Iraq</option><option value="101">Ireland</option><option value="102">Israel</option><option value="108">Italy</option><option value="109">Jamaica</option><option value="111">Japan</option><option value="110">Jordan</option><option value="122">Kazakstan</option><option value="112">Kenya</option><option value="115">Kiribati</option><option value="118">Korea, Democratic People\'s Republic of</option><option value="119">Korea, Republic of</option><option value="120">Kuwait</option><option value="113">Kyrgyzstan</option><option value="123">Lao People\'s Democratic Republic</option><option value="132">Latvia</option><option value="124">Lebanon</option><option value="129">Lesotho</option><option value="128">Liberia</option><option value="133">Libyan Arab Jamahiriya</option><option value="126">Liechtenstein</option><option value="130">Lithuania</option><option value="131">Luxembourg</option><option value="143">Macau</option><option value="139">Macedonia</option><option value="137">Madagascar</option><option value="151">Malawi</option><option value="153">Malaysia</option><option value="150">Maldives</option><option value="140">Mali</option><option value="148">Malta</option><option value="138">Marshall Islands</option><option value="145">Martinique</option><option value="146">Mauritania</option><option value="149">Mauritius</option><option value="238">Mayotte</option><option value="152">Mexico</option><option value="72">Micronesia, Federated States of</option><option value="136">Moldova, Republic of</option><option value="135">Monaco</option><option value="142">Mongolia</option><option value="147">Montserrat</option><option value="134">Morocco</option><option value="154">Mozambique</option><option value="141">Myanmar</option><option value="155">Namibia</option><option value="164">Nauru</option><option value="163">Nepal</option><option value="161">Netherlands</option><option value="10">Netherlands Antilles</option><option value="156">New Caledonia</option><option value="166">New Zealand</option><option value="160">Nicaragua</option><option value="157">Niger</option><option value="159">Nigeria</option><option value="165">Niue</option><option value="158">Norfolk Island</option><option value="144">Northern Mariana Islands</option><option value="162">Norway</option><option value="167">Oman</option><option value="173">Pakistan</option><option value="180">Palau</option><option value="178">Palestinian Territory, Occupied</option><option value="168">Panama</option><option value="171">Papua New Guinea</option><option value="181">Paraguay</option><option value="169">Peru</option><option value="172">Philippines</option><option value="174">Poland</option><option value="179">Portugal</option><option value="177">Puerto Rico</option><option value="182">Qatar</option><option value="183">Reunion</option><option value="184">Romania</option><option value="185">Russian Federation</option><option value="186">Rwanda</option><option value="117">Saint Kitts and Nevis</option><option value="125">Saint Lucia</option><option value="229">Saint Vincent and the Grenadines</option><option value="236">Samoa</option><option value="198">San Marino</option><option value="202">Sao Tome and Principe</option><option value="187">Saudi Arabia</option><option value="199">Senegal</option><option value="239">Serbia and Montenegro</option><option value="189">Seychelles</option><option value="197">Sierra Leone</option><option value="192">Singapore</option><option value="196">Slovakia</option><option value="194">Slovenia</option><option value="188">Solomon Islands</option><option value="200">Somalia</option><option value="240">South Africa</option><option value="67">Spain</option><option value="127">Sri Lanka</option><option value="190">Sudan</option><option value="201">Suriname</option><option value="205">Swaziland</option><option value="191">Sweden</option><option value="43">Switzerland</option><option value="204">Syrian Arab Republic</option><option value="220">Taiwan</option><option value="211">Tajikistan</option><option value="221">Tanzania, United Republic of</option><option value="210">Thailand</option><option value="209">Togo</option><option value="212">Tokelau</option><option value="215">Tonga</option><option value="218">Trinidad and Tobago</option><option value="214">Tunisia</option><option value="217">Turkey</option><option value="213">Turkmenistan</option><option value="206">Turks and Caicos Islands</option><option value="219">Tuvalu</option><option value="223">Uganda</option><option value="222">Ukraine</option><option value="4">United Arab Emirates</option><option value="77">United Kingdom</option><option selected="" value="225">United States</option><option value="224">United States Minor Outlying Islands</option><option value="226">Uruguay</option><option value="227">Uzbekistan</option><option value="234">Vanuatu</option><option value="230">Venezuela</option><option value="233">Vietnam</option><option value="231">Virgin Islands, British</option><option value="232">Virgin Islands, U.S.</option><option value="235">Wallis and Futuna</option><option value="237">Yemen</option><option value="241">Zambia</option><option value="243">Zimbabwe</option></select>
            </div>
            <div class="f">
              <label>Bank Account Number:</label>
              <input type="text" value="" class="text data" style="" name="V1[5]"/>
               *
            </div>
            <div class="f">
              <label>Bank SWIFT-BIC/ABA:</label>
              <input type="text" value="" class="text data" style="" name="V1[6]"/>
               *
            </div>
            <div class="f">
              <label>Account Holder:</label>
              <input type="text" value="" class="text data" style="" name="V1[7]"/>
               *
            </div>


            <div style="clear: both">&nbsp;</div>
            <div class="f">
              <label>Additional information:</label>
              <textarea rows="5" class="text data" style="" name="V1[8]"></textarea>
            </div>
       </div>

<div class="withdraw" id="withdraw_form2" style="display: block;">

                  <h2 class="blue-title-small">Check Information</h2>

                  <div class="f">
                    <label>First Name:</label>
                    <input type="text" value="" class="text data" style="" name="V2[0]"/>*
                  </div>
                  <div class="f">
                    <label>Last Name:</label>
                    <input type="text" value="" class="text data" style="" name="V2[1]"/>*
                  </div>
                  <div class="f">
                    <label>Tax ID or SSN:</label>
                    <input type="text" value="" class="text data" style="" name="V2[2]"/>
                  </div>

                  <div class="f">
                    <label>Address:</label>
                    <input type="text" value="" class="text data" style="" name="V2[3]"/>*
                  </div>
                  <div class="f">
                    <label>Second Address:</label>
                    <input type="text" value="" class="text data" style="" name="V2[4]"/>
                  </div>
                  <div class="f">
                    <label>City:</label>
                    <input type="text" value="" class="text data" style="" name="V2[5]"/>*
                  </div>
                  <div class="f">
                    <label>Postal/Zip Code:</label>
                    <input type="text" value="" class="text data" style="" name="V2[6]"/>*
                  </div>
                  <div class="f">
                    <label>County/State:</label>
                    <input type="text" value="" class="text data" style="" name="V2[7]"/>
                  </div>
                  <div class="f">
                    <label>Country:</label>
  				    <select id="V2[8]" class="data" style="" name="V2[8]"><option value="5">Afghanistan</option><option value="8">Albania</option><option value="61">Algeria</option><option value="14">American Samoa</option><option value="3">Andorra</option><option value="11">Angola</option><option value="7">Anguilla</option><option value="12">Antarctica</option><option value="6">Antigua and Barbuda</option><option value="13">Argentina</option><option value="9">Armenia</option><option value="17">Aruba</option><option value="1">Asia/Pacific Region</option><option value="16">Australia</option><option value="15">Austria</option><option value="18">Azerbaijan</option><option value="32">Bahamas</option><option value="25">Bahrain</option><option value="21">Bangladesh</option><option value="20">Barbados</option><option value="36">Belarus</option><option value="22">Belgium</option><option value="37">Belize</option><option value="27">Benin</option><option value="28">Bermuda</option><option value="33">Bhutan</option><option value="30">Bolivia</option><option value="19">Bosnia and Herzegovina</option><option value="35">Botswana</option><option value="34">Bouvet Island</option><option value="31">Brazil</option><option value="104">British Indian Ocean Territory</option><option value="29">Brunei Darussalam</option><option value="24">Bulgaria</option><option value="23">Burkina Faso</option><option value="26">Burundi</option><option value="114">Cambodia</option><option value="47">Cameroon</option><option value="38">Canada</option><option value="52">Cape Verde</option><option value="121">Cayman Islands</option><option value="41">Central African Republic</option><option value="207">Chad</option><option value="46">Chile</option><option value="48">China</option><option value="49">Colombia</option><option value="116">Comoros</option><option value="42">Congo</option><option value="40">Congo, The Democratic Republic of the</option><option value="45">Cook Islands</option><option value="50">Costa Rica</option><option value="44">Cote D\'Ivoire</option><option value="97">Croatia</option><option value="51">Cuba</option><option value="54">Cyprus</option><option value="55">Czech Republic</option><option value="58">Denmark</option><option value="57">Djibouti</option><option value="59">Dominica</option><option value="60">Dominican Republic</option><option value="62">Ecuador</option><option value="64">Egypt</option><option value="203">El Salvador</option><option value="87">Equatorial Guinea</option><option value="66">Eritrea</option><option value="63">Estonia</option><option value="68">Ethiopia</option><option value="71">Falkland Islands (Malvinas)</option><option value="73">Faroe Islands</option><option value="70">Fiji</option><option value="69">Finland</option><option value="74">France</option><option value="80">French Guiana</option><option value="170">French Polynesia</option><option value="208">French Southern Territories</option><option value="76">Gabon</option><option value="84">Gambia</option><option value="79">Georgia</option><option value="56">Germany</option><option value="81">Ghana</option><option value="82">Gibraltar</option><option value="88">Greece</option><option value="83">Greenland</option><option value="78">Grenada</option><option value="86">Guadeloupe</option><option value="91">Guam</option><option value="90">Guatemala</option><option value="85">Guinea</option><option value="92">Guinea-Bissau</option><option value="93">Guyana</option><option value="98">Haiti</option><option value="95">Heard Island and McDonald Islands</option><option value="228">Holy See (Vatican City State)</option><option value="96">Honduras</option><option value="94">Hong Kong</option><option value="99">Hungary</option><option value="107">Iceland</option><option value="103">India</option><option value="100">Indonesia</option><option value="106">Iran, Islamic Republic of</option><option value="105">Iraq</option><option value="101">Ireland</option><option value="102">Israel</option><option value="108">Italy</option><option value="109">Jamaica</option><option value="111">Japan</option><option value="110">Jordan</option><option value="122">Kazakstan</option><option value="112">Kenya</option><option value="115">Kiribati</option><option value="118">Korea, Democratic People\'s Republic of</option><option value="119">Korea, Republic of</option><option value="120">Kuwait</option><option value="113">Kyrgyzstan</option><option value="123">Lao People\'s Democratic Republic</option><option value="132">Latvia</option><option value="124">Lebanon</option><option value="129">Lesotho</option><option value="128">Liberia</option><option value="133">Libyan Arab Jamahiriya</option><option value="126">Liechtenstein</option><option value="130">Lithuania</option><option value="131">Luxembourg</option><option value="143">Macau</option><option value="139">Macedonia</option><option value="137">Madagascar</option><option value="151">Malawi</option><option value="153">Malaysia</option><option value="150">Maldives</option><option value="140">Mali</option><option value="148">Malta</option><option value="138">Marshall Islands</option><option value="145">Martinique</option><option value="146">Mauritania</option><option value="149">Mauritius</option><option value="238">Mayotte</option><option value="152">Mexico</option><option value="72">Micronesia, Federated States of</option><option value="136">Moldova, Republic of</option><option value="135">Monaco</option><option value="142">Mongolia</option><option value="147">Montserrat</option><option value="134">Morocco</option><option value="154">Mozambique</option><option value="141">Myanmar</option><option value="155">Namibia</option><option value="164">Nauru</option><option value="163">Nepal</option><option value="161">Netherlands</option><option value="10">Netherlands Antilles</option><option value="156">New Caledonia</option><option value="166">New Zealand</option><option value="160">Nicaragua</option><option value="157">Niger</option><option value="159">Nigeria</option><option value="165">Niue</option><option value="158">Norfolk Island</option><option value="144">Northern Mariana Islands</option><option value="162">Norway</option><option value="167">Oman</option><option value="173">Pakistan</option><option value="180">Palau</option><option value="178">Palestinian Territory, Occupied</option><option value="168">Panama</option><option value="171">Papua New Guinea</option><option value="181">Paraguay</option><option value="169">Peru</option><option value="172">Philippines</option><option value="174">Poland</option><option value="179">Portugal</option><option value="177">Puerto Rico</option><option value="182">Qatar</option><option value="183">Reunion</option><option value="184">Romania</option><option value="185">Russian Federation</option><option value="186">Rwanda</option><option value="117">Saint Kitts and Nevis</option><option value="125">Saint Lucia</option><option value="229">Saint Vincent and the Grenadines</option><option value="236">Samoa</option><option value="198">San Marino</option><option value="202">Sao Tome and Principe</option><option value="187">Saudi Arabia</option><option value="199">Senegal</option><option value="239">Serbia and Montenegro</option><option value="189">Seychelles</option><option value="197">Sierra Leone</option><option value="192">Singapore</option><option value="196">Slovakia</option><option value="194">Slovenia</option><option value="188">Solomon Islands</option><option value="200">Somalia</option><option value="240">South Africa</option><option value="67">Spain</option><option value="127">Sri Lanka</option><option value="190">Sudan</option><option value="201">Suriname</option><option value="205">Swaziland</option><option value="191">Sweden</option><option value="43">Switzerland</option><option value="204">Syrian Arab Republic</option><option value="220">Taiwan</option><option value="211">Tajikistan</option><option value="221">Tanzania, United Republic of</option><option value="210">Thailand</option><option value="209">Togo</option><option value="212">Tokelau</option><option value="215">Tonga</option><option value="218">Trinidad and Tobago</option><option value="214">Tunisia</option><option value="217">Turkey</option><option value="213">Turkmenistan</option><option value="206">Turks and Caicos Islands</option><option value="219">Tuvalu</option><option value="223">Uganda</option><option value="222">Ukraine</option><option value="4">United Arab Emirates</option><option value="77">United Kingdom</option><option selected="" value="225">United States</option><option value="224">United States Minor Outlying Islands</option><option value="226">Uruguay</option><option value="227">Uzbekistan</option><option value="234">Vanuatu</option><option value="230">Venezuela</option><option value="233">Vietnam</option><option value="231">Virgin Islands, British</option><option value="232">Virgin Islands, U.S.</option><option value="235">Wallis and Futuna</option><option value="237">Yemen</option><option value="241">Zambia</option><option value="243">Zimbabwe</option></select>
                  </div>
                  <div style="clear: both">&nbsp;</div>
                  <div class="f">
                    <label>Additional information:</label>
                    <textarea rows="5" class="text data" style="" name="V2[9]"></textarea>
                  </div>
</div>
<div class="withdraw" id="withdraw_form3" style="display: none;">

<label>PayPal Information</label>
          <label>PayPal Account:</label>

              <input type="text" value="" class="text data" style="" name="V3[0]"/>
             *
</div>
<div class="withdraw" id="withdraw_form4" style="display: none;">

<label>Moneybookers Information</label>
          <label>Moneybookers Profile:</label>

              <input type="text" value="" class="text data" style="" name="V4[0]"/>
             *
</div>
<div class="withdraw" id="withdraw_form5" style="display: none;">
<label>Epassporte Information</label>
		 <label>Epassporte profile:</label>
		   <input type="text" value="" class="text data" style="" name="V5[0]"/>
		   *
</div>
<div class="withdraw" id="withdraw_form6" style="display: none;">
<label>Ikobo Information</label>
		 <label>Ikobo profile:</label>
		  <input type="text" value="" class="text data" style="" name="V6[0]"/>
		   *
</div>
<div class="withdraw" id="withdraw_form7" style="display: none;">
<label>CapitalCollect profile:</label>
		   <td width="50%" nowrap="nowrap"><input type="text" value="" class="text data" style="" name="V7[0]"/>
		   * </td>

</div>
<div class="withdraw" id="withdraw_form8" style="display: none;">
<label>Fethard Profile:</label>
		   <input type="text" value="" class="text data" style="" name="V8[0]"/>
		   *
</div>
<div class="withdraw" id="withdraw_form9" style="display: none;">
<label>WebMoney Profile:</label>
		   <input type="text" value="" class="text data" style="" name="V9[0]"/>
		   *


</div>
<div class="withdraw" id="withdraw_form10" style="display: none;">

		   <label>Other Withdraw Type</label>

		  <textarea style="width: 240px; height: 200px;" class="text data" name="V10[0]"></textarea> *
</div>
<div class="withdraw" id="withdraw_form11" style="display: none;">
<label>Yandex.Money Information</label>
          <label>Yandex.Money Account:</label>
              <input type="text" value="" class="text data" style="" name="V11[0]"/>
             *
</div>


</div>