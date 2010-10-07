<?php 
function get_time_zone($country,$region) {
  switch ($country) { 
case "US":
    switch ($region) { 
  case "AL":
      $timezone = "America/Chicago";
      break; 
  case "AK":
      $timezone = "America/Anchorage";
      break; 
  case "AZ":
      $timezone = "America/Phoenix";
      break; 
  case "AR":
      $timezone = "America/Chicago";
      break; 
  case "CA":
      $timezone = "America/Los_Angeles";
      break; 
  case "CO":
      $timezone = "America/Denver";
      break; 
  case "CT":
      $timezone = "America/New_York";
      break; 
  case "DE":
      $timezone = "America/New_York";
      break; 
  case "DC":
      $timezone = "America/New_York";
      break; 
  case "FL":
      $timezone = "America/New_York";
      break; 
  case "GA":
      $timezone = "America/New_York";
      break; 
  case "HI":
      $timezone = "Pacific/Honolulu";
      break; 
  case "ID":
      $timezone = "America/Denver";
      break; 
  case "IL":
      $timezone = "America/Chicago";
      break; 
  case "IN":
      $timezone = "America/Indianapolis";
      break; 
  case "IA":
      $timezone = "America/Chicago";
      break; 
  case "KS":
      $timezone = "America/Chicago";
      break; 
  case "KY":
      $timezone = "America/New_York";
      break; 
  case "LA":
      $timezone = "America/Chicago";
      break; 
  case "ME":
      $timezone = "America/New_York";
      break; 
  case "MD":
      $timezone = "America/New_York";
      break; 
  case "MA":
      $timezone = "America/New_York";
      break; 
  case "MI":
      $timezone = "America/New_York";
      break; 
  case "MN":
      $timezone = "America/Chicago";
      break; 
  case "MS":
      $timezone = "America/Chicago";
      break; 
  case "MO":
      $timezone = "America/Chicago";
      break; 
  case "MT":
      $timezone = "America/Denver";
      break; 
  case "NE":
      $timezone = "America/Chicago";
      break; 
  case "NV":
      $timezone = "America/Los_Angeles";
      break; 
  case "NH":
      $timezone = "America/New_York";
      break; 
  case "NJ":
      $timezone = "America/New_York";
      break; 
  case "NM":
      $timezone = "America/Denver";
      break; 
  case "NY":
      $timezone = "America/New_York";
      break; 
  case "NC":
      $timezone = "America/New_York";
      break; 
  case "ND":
      $timezone = "America/Chicago";
      break; 
  case "OH":
      $timezone = "America/New_York";
      break; 
  case "OK":
      $timezone = "America/Chicago";
      break; 
  case "OR":
      $timezone = "America/Los_Angeles";
      break; 
  case "PA":
      $timezone = "America/New_York";
      break; 
  case "RI":
      $timezone = "America/New_York";
      break; 
  case "SC":
      $timezone = "America/New_York";
      break; 
  case "SD":
      $timezone = "America/Chicago";
      break; 
  case "TN":
      $timezone = "America/Chicago";
      break; 
  case "TX":
      $timezone = "America/Chicago";
      break; 
  case "UT":
      $timezone = "America/Denver";
      break; 
  case "VT":
      $timezone = "America/New_York";
      break; 
  case "VA":
      $timezone = "America/New_York";
      break; 
  case "WA":
      $timezone = "America/Los_Angeles";
      break; 
  case "WV":
      $timezone = "America/New_York";
      break; 
  case "WI":
      $timezone = "America/Chicago";
      break; 
  case "WY":
      $timezone = "America/Denver";
      break; 
  } 
  break; 
case "CA":
    switch ($region) { 
  case "AB":
      $timezone = "America/Edmonton";
      break; 
  case "BC":
      $timezone = "America/Vancouver";
      break; 
  case "MB":
      $timezone = "America/Winnipeg";
      break; 
  case "NB":
      $timezone = "America/Halifax";
      break; 
  case "NL":
      $timezone = "America/St_Johns";
      break; 
  case "NT":
      $timezone = "America/Yellowknife";
      break; 
  case "NS":
      $timezone = "America/Halifax";
      break; 
  case "NU":
      $timezone = "America/Rankin_Inlet";
      break; 
  case "ON":
      $timezone = "America/Rainy_River";
      break; 
  case "PE":
      $timezone = "America/Halifax";
      break; 
  case "QC":
      $timezone = "America/Montreal";
      break; 
  case "SK":
      $timezone = "America/Regina";
      break; 
  case "YT":
      $timezone = "America/Whitehorse";
      break; 
  } 
  break; 
case "AU":
    switch ($region) { 
  case "01":
      $timezone = "Australia/Canberra";
      break; 
  case "02":
      $timezone = "Australia/NSW";
      break; 
  case "03":
      $timezone = "Australia/North";
      break; 
  case "04":
      $timezone = "Australia/Queensland";
      break; 
  case "05":
      $timezone = "Australia/South";
      break; 
  case "06":
      $timezone = "Australia/Tasmania";
      break; 
  case "07":
      $timezone = "Australia/Victoria";
      break; 
  case "08":
      $timezone = "Australia/West";
      break; 
  } 
  break; 
case "AS":
    $timezone = "US/Samoa";
    break; 
case "CI":
    $timezone = "Africa/Abidjan";
    break; 
case "GH":
    $timezone = "Africa/Accra";
    break; 
case "DZ":
    $timezone = "Africa/Algiers";
    break; 
case "ER":
    $timezone = "Africa/Asmera";
    break; 
case "ML":
    $timezone = "Africa/Bamako";
    break; 
case "CF":
    $timezone = "Africa/Bangui";
    break; 
case "GM":
    $timezone = "Africa/Banjul";
    break; 
case "GW":
    $timezone = "Africa/Bissau";
    break; 
case "CG":
    $timezone = "Africa/Brazzaville";
    break; 
case "BI":
    $timezone = "Africa/Bujumbura";
    break; 
case "EG":
    $timezone = "Africa/Cairo";
    break; 
case "MA":
    $timezone = "Africa/Casablanca";
    break; 
case "GN":
    $timezone = "Africa/Conakry";
    break; 
case "SN":
    $timezone = "Africa/Dakar";
    break; 
case "DJ":
    $timezone = "Africa/Djibouti";
    break; 
case "SL":
    $timezone = "Africa/Freetown";
    break; 
case "BW":
    $timezone = "Africa/Gaborone";
    break; 
case "ZW":
    $timezone = "Africa/Harare";
    break; 
case "ZA":
    $timezone = "Africa/Johannesburg";
    break; 
case "UG":
    $timezone = "Africa/Kampala";
    break; 
case "SD":
    $timezone = "Africa/Khartoum";
    break; 
case "RW":
    $timezone = "Africa/Kigali";
    break; 
case "NG":
    $timezone = "Africa/Lagos";
    break; 
case "GA":
    $timezone = "Africa/Libreville";
    break; 
case "TG":
    $timezone = "Africa/Lome";
    break; 
case "AO":
    $timezone = "Africa/Luanda";
    break; 
case "ZM":
    $timezone = "Africa/Lusaka";
    break; 
case "GQ":
    $timezone = "Africa/Malabo";
    break; 
case "MZ":
    $timezone = "Africa/Maputo";
    break; 
case "LS":
    $timezone = "Africa/Maseru";
    break; 
case "SZ":
    $timezone = "Africa/Mbabane";
    break; 
case "SO":
    $timezone = "Africa/Mogadishu";
    break; 
case "LR":
    $timezone = "Africa/Monrovia";
    break; 
case "KE":
    $timezone = "Africa/Nairobi";
    break; 
case "TD":
    $timezone = "Africa/Ndjamena";
    break; 
case "NE":
    $timezone = "Africa/Niamey";
    break; 
case "MR":
    $timezone = "Africa/Nouakchott";
    break; 
case "BF":
    $timezone = "Africa/Ouagadougou";
    break; 
case "ST":
    $timezone = "Africa/Sao_Tome";
    break; 
case "LY":
    $timezone = "Africa/Tripoli";
    break; 
case "TN":
    $timezone = "Africa/Tunis";
    break; 
case "AI":
    $timezone = "America/Anguilla";
    break; 
case "AG":
    $timezone = "America/Antigua";
    break; 
case "AW":
    $timezone = "America/Aruba";
    break; 
case "BB":
    $timezone = "America/Barbados";
    break; 
case "BZ":
    $timezone = "America/Belize";
    break; 
case "CO":
    $timezone = "America/Bogota";
    break; 
case "VE":
    $timezone = "America/Caracas";
    break; 
case "KY":
    $timezone = "America/Cayman";
    break; 
case "MX":
    $timezone = "America/Chihuahua";
    break; 
case "CR":
    $timezone = "America/Costa_Rica";
    break; 
case "DM":
    $timezone = "America/Dominica";
    break; 
case "SV":
    $timezone = "America/El_Salvador";
    break; 
case "GD":
    $timezone = "America/Grenada";
    break; 
case "FR":
    $timezone = "Europe/Paris";
    break; 
case "GP":
    $timezone = "America/Guadeloupe";
    break; 
case "GT":
    $timezone = "America/Guatemala";
    break; 
case "EC":
    $timezone = "America/Guayaquil";
    break; 
case "GY":
    $timezone = "America/Guyana";
    break; 
case "CU":
    $timezone = "America/Havana";
    break; 
case "JM":
    $timezone = "America/Jamaica";
    break; 
case "BO":
    $timezone = "America/La_Paz";
    break; 
case "PE":
    $timezone = "America/Lima";
    break; 
case "NI":
    $timezone = "America/Managua";
    break; 
case "MQ":
    $timezone = "America/Martinique";
    break; 
case "AR":
    $timezone = "America/Mendoza";
    break; 
case "UY":
    $timezone = "America/Montevideo";
    break; 
case "MS":
    $timezone = "America/Montserrat";
    break; 
case "BS":
    $timezone = "America/Nassau";
    break; 
case "PA":
    $timezone = "America/Panama";
    break; 
case "SR":
    $timezone = "America/Paramaribo";
    break; 
case "PR":
    $timezone = "America/Puerto_Rico";
    break; 
case "KN":
    $timezone = "America/St_Kitts";
    break; 
case "LC":
    $timezone = "America/St_Lucia";
    break; 
case "VC":
    $timezone = "America/St_Vincent";
    break; 
case "HN":
    $timezone = "America/Tegucigalpa";
    break; 
case "YE":
    $timezone = "Asia/Aden";
    break; 
case "KZ":
    $timezone = "Asia/Almaty";
    break; 
case "JO":
    $timezone = "Asia/Amman";
    break; 
case "TM":
    $timezone = "Asia/Ashgabat";
    break; 
case "IQ":
    $timezone = "Asia/Baghdad";
    break; 
case "BH":
    $timezone = "Asia/Bahrain";
    break; 
case "AZ":
    $timezone = "Asia/Baku";
    break; 
case "TH":
    $timezone = "Asia/Bangkok";
    break; 
case "LB":
    $timezone = "Asia/Beirut";
    break; 
case "KG":
    $timezone = "Asia/Bishkek";
    break; 
case "BN":
    $timezone = "Asia/Brunei";
    break; 
case "IN":
    $timezone = "Asia/Calcutta";
    break; 
case "MN":
    $timezone = "Asia/Choibalsan";
    break; 
case "CN":
    $timezone = "Asia/Chongqing";
    break; 
case "LK":
    $timezone = "Asia/Colombo";
    break; 
case "BD":
    $timezone = "Asia/Dhaka";
    break; 
case "AE":
    $timezone = "Asia/Dubai";
    break; 
case "TJ":
    $timezone = "Asia/Dushanbe";
    break; 
case "HK":
    $timezone = "Asia/Hong_Kong";
    break; 
case "TR":
    $timezone = "Asia/Istanbul";
    break; 
case "ID":
    $timezone = "Asia/Jakarta";
    break; 
case "IL":
    $timezone = "Asia/Jerusalem";
    break; 
case "AF":
    $timezone = "Asia/Kabul";
    break; 
case "PK":
    $timezone = "Asia/Karachi";
    break; 
case "NP":
    $timezone = "Asia/Katmandu";
    break; 
case "KW":
    $timezone = "Asia/Kuwait";
    break; 
case "MO":
    $timezone = "Asia/Macao";
    break; 
case "PH":
    $timezone = "Asia/Manila";
    break; 
case "OM":
    $timezone = "Asia/Muscat";
    break; 
case "CY":
    $timezone = "Asia/Nicosia";
    break; 
case "KP":
    $timezone = "Asia/Pyongyang";
    break; 
case "QA":
    $timezone = "Asia/Qatar";
    break; 
case "MM":
    $timezone = "Asia/Rangoon";
    break; 
case "SA":
    $timezone = "Asia/Riyadh";
    break; 
case "KR":
    $timezone = "Asia/Seoul";
    break; 
case "SG":
    $timezone = "Asia/Singapore";
    break; 
case "TW":
    $timezone = "Asia/Taipei";
    break; 
case "UZ":
    $timezone = "Asia/Tashkent";
    break; 
case "GE":
    $timezone = "Asia/Tbilisi";
    break; 
case "BT":
    $timezone = "Asia/Thimphu";
    break; 
case "JP":
    $timezone = "Asia/Tokyo";
    break; 
case "LA":
    $timezone = "Asia/Vientiane";
    break; 
case "AM":
    $timezone = "Asia/Yerevan";
    break; 
case "PT":
    $timezone = "Atlantic/Azores";
    break; 
case "BM":
    $timezone = "Atlantic/Bermuda";
    break; 
case "CV":
    $timezone = "Atlantic/Cape_Verde";
    break; 
case "FO":
    $timezone = "Atlantic/Faeroe";
    break; 
case "IS":
    $timezone = "Atlantic/Reykjavik";
    break; 
case "GS":
    $timezone = "Atlantic/South_Georgia";
    break; 
case "SH":
    $timezone = "Atlantic/St_Helena";
    break; 
case "BR":
    $timezone = "Brazil/Acre";
    break; 
case "CL":
    $timezone = "Chile/Continental";
    break; 
case "NL":
    $timezone = "Europe/Amsterdam";
    break; 
case "AD":
    $timezone = "Europe/Andorra";
    break; 
case "GR":
    $timezone = "Europe/Athens";
    break; 
case "YU":
    $timezone = "Europe/Belgrade";
    break; 
case "DE":
    $timezone = "Europe/Berlin";
    break; 
case "SK":
    $timezone = "Europe/Bratislava";
    break; 
case "BE":
    $timezone = "Europe/Brussels";
    break; 
case "RO":
    $timezone = "Europe/Bucharest";
    break; 
case "HU":
    $timezone = "Europe/Budapest";
    break; 
case "DK":
    $timezone = "Europe/Copenhagen";
    break; 
case "IE":
    $timezone = "Europe/Dublin";
    break; 
case "GI":
    $timezone = "Europe/Gibraltar";
    break; 
case "FI":
    $timezone = "Europe/Helsinki";
    break; 
case "UA":
    $timezone = "Europe/Kiev";
    break; 
case "SI":
    $timezone = "Europe/Ljubljana";
    break; 
case "GB":
    $timezone = "Europe/London";
    break; 
case "LU":
    $timezone = "Europe/Luxembourg";
    break; 
case "ES":
    $timezone = "Europe/Madrid";
    break; 
case "MT":
    $timezone = "Europe/Malta";
    break; 
case "BY":
    $timezone = "Europe/Minsk";
    break; 
case "MC":
    $timezone = "Europe/Monaco";
    break; 
case "RU":
    $timezone = "Europe/Moscow";
    break; 
case "NO":
    $timezone = "Europe/Oslo";
    break; 
case "CZ":
    $timezone = "Europe/Prague";
    break; 
case "LV":
    $timezone = "Europe/Riga";
    break; 
case "IT":
    $timezone = "Europe/Rome";
    break; 
case "SM":
    $timezone = "Europe/San_Marino";
    break; 
case "BA":
    $timezone = "Europe/Sarajevo";
    break; 
case "MK":
    $timezone = "Europe/Skopje";
    break; 
case "BG":
    $timezone = "Europe/Sofia";
    break; 
case "SE":
    $timezone = "Europe/Stockholm";
    break; 
case "EE":
    $timezone = "Europe/Tallinn";
    break; 
case "AL":
    $timezone = "Europe/Tirane";
    break; 
case "LI":
    $timezone = "Europe/Vaduz";
    break; 
case "VA":
    $timezone = "Europe/Vatican";
    break; 
case "AT":
    $timezone = "Europe/Vienna";
    break; 
case "LT":
    $timezone = "Europe/Vilnius";
    break; 
case "PL":
    $timezone = "Europe/Warsaw";
    break; 
case "HR":
    $timezone = "Europe/Zagreb";
    break; 
case "IR":
    $timezone = "Asia/Tehran";
    break; 
case "NZ":
    $timezone = "Pacific/Auckland";
    break; 
case "MG":
    $timezone = "Indian/Antananarivo";
    break; 
case "CX":
    $timezone = "Indian/Christmas";
    break; 
case "CC":
    $timezone = "Indian/Cocos";
    break; 
case "KM":
    $timezone = "Indian/Comoro";
    break; 
case "MV":
    $timezone = "Indian/Maldives";
    break; 
case "MU":
    $timezone = "Indian/Mauritius";
    break; 
case "YT":
    $timezone = "Indian/Mayotte";
    break; 
case "RE":
    $timezone = "Indian/Reunion";
    break; 
case "FJ":
    $timezone = "Pacific/Fiji";
    break; 
case "TV":
    $timezone = "Pacific/Funafuti";
    break; 
case "GU":
    $timezone = "Pacific/Guam";
    break; 
case "NR":
    $timezone = "Pacific/Nauru";
    break; 
case "NU":
    $timezone = "Pacific/Niue";
    break; 
case "NF":
    $timezone = "Pacific/Norfolk";
    break; 
case "PW":
    $timezone = "Pacific/Palau";
    break; 
case "PN":
    $timezone = "Pacific/Pitcairn";
    break; 
case "CK":
    $timezone = "Pacific/Rarotonga";
    break; 
case "WS":
    $timezone = "Pacific/Samoa";
    break; 
case "KI":
    $timezone = "Pacific/Tarawa";
    break; 
case "TO":
    $timezone = "Pacific/Tongatapu";
    break; 
case "WF":
    $timezone = "Pacific/Wallis";
    break; 
case "TZ":
    $timezone = "Africa/Dar_es_Salaam";
    break; 
case "VN":
    $timezone = "Asia/Phnom_Penh";
    break; 
case "KH":
    $timezone = "Asia/Phnom_Penh";
    break; 
case "CM":
    $timezone = "Africa/Lagos";
    break; 
case "DO":
    $timezone = "America/Santo_Domingo";
    break; 
case "TL":
    $timezone = "Asia/Jakarta";
    break; 
case "ET":
    $timezone = "Africa/Addis_Ababa";
    break; 
case "FX":
    $timezone = "Europe/Paris";
    break; 
case "GL":
    $timezone = "America/Godthab";
    break; 
case "HT":
    $timezone = "America/Port-au-Prince";
    break; 
case "CH":
    $timezone = "Europe/Zurich";
    break; 
case "AN":
    $timezone = "America/Curacao";
    break; 
case "BJ":
    $timezone = "Africa/Porto-Novo";
    break; 
case "EH":
    $timezone = "Africa/El_Aaiun";
    break; 
case "FK":
    $timezone = "Atlantic/Stanley";
    break; 
case "GF":
    $timezone = "America/Cayenne";
    break; 
case "IO":
    $timezone = "Indian/Chagos";
    break; 
case "MD":
    $timezone = "Europe/Chisinau";
    break; 
case "MP":
    $timezone = "Pacific/Saipan";
    break; 
case "MW":
    $timezone = "Africa/Blantyre";
    break; 
case "NA":
    $timezone = "Africa/Windhoek";
    break; 
case "NC":
    $timezone = "Pacific/Noumea";
    break; 
case "PG":
    $timezone = "Pacific/Port_Moresby";
    break; 
case "PM":
    $timezone = "America/Miquelon";
    break; 
case "PS":
    $timezone = "Asia/Gaza";
    break; 
case "PY":
    $timezone = "America/Asuncion";
    break; 
case "SB":
    $timezone = "Pacific/Guadalcanal";
    break; 
case "SC":
    $timezone = "Indian/Mahe";
    break; 
case "SJ":
    $timezone = "Arctic/Longyearbyen";
    break; 
case "SY":
    $timezone = "Asia/Damascus";
    break; 
case "TC":
    $timezone = "America/Grand_Turk";
    break; 
case "TF":
    $timezone = "Indian/Kerguelen";
    break; 
case "TK":
    $timezone = "Pacific/Fakaofo";
    break; 
case "TT":
    $timezone = "America/Port_of_Spain";
    break; 
case "VG":
    $timezone = "America/Tortola";
    break; 
case "VI":
    $timezone = "America/St_Thomas";
    break; 
case "VU":
    $timezone = "Pacific/Efate";
    break; 
case "RS":
    $timezone = "Europe/Belgrade";
    break; 
case "ME":
    $timezone = "Europe/Podgorica";
    break; 
case "AX":
    $timezone = "Europe/Mariehamn";
    break; 
case "GG":
    $timezone = "Europe/Guernsey";
    break; 
case "IM":
    $timezone = "Europe/Isle_of_Man";
    break; 
case "JE":
    $timezone = "Europe/Jersey";
    break; 
case "BL":
    $timezone = "America/St_Barthelemy";
    break; 
case "MF":
    $timezone = "America/Marigot";
    break; 
  } 
  return $timezone; 
} 
?> 
