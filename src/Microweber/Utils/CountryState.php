<?php

namespace Microweber\Utils;

/** 
 
 */

/* 
Example of use
==============

$sd = new CountryState;
$p = $sd->states();
$x = 1;
foreach($p as $m){
    echo $x++.": ".ucwords($m)."<br>";
} */

class CountryState
{
    /**
     Please feel free to correct and build on this.
     */
    private $sorted_countries = [
 ['Afghanistan', 'AF', 'AFG', '004', 'ISO 3166-2:AF', 'Badakhshan|Badghis|Baghlan|Balkh|Bamian|Farah|Faryab|Ghazni|Ghowr|Helmand|Herat|Jowzjan|Kabol|Kandahar|Kapisa|Konar|Kondoz|Laghman|Lowgar|Nangarhar|Nimruz|Oruzgan|Paktia|Paktika|Parvan|Samangan|Sar-e Pol|Takhar|Vardak|Zabol', '93'],
 ['Aland Islands', 'AX', 'ALA', '248', 'ISO 3166-2:AX', 'Aland Islands', ''],
 ['Albania', 'AL', 'ALB', '008', 'ISO 3166-2:AL', 'Berat|Bulqize|Delvine|Devoll [Bilisht]|Diber [Peshkopi]|Durres|Elbasan|Fier|Gjirokaster|Gramsh|Has [Krume]|Kavaje|Kolonje [Erseke]|Korce|Kruje|Kucove|Kukes|Kurbin|Lezhe|Librazhd|Lushnje|Malesi e Madhe [Koplik]|Mallakaster [Ballsh]|Mat [Burrel]|Mirdite [Rreshen]|Peqin|Permet|Pogradec|Puke|Sarande|Shkoder|Skrapar [Corovode]|Tepelene|Tirane [Tirana]|Tirane [Tirana]|Tropoje [Bajram Curri]|Vlore', '355'],
 ['Algeria', 'DZ', 'DZA', '012', 'ISO 3166-2:DZ', "Adrar|Ain Defla|Ain Temouchent|Alger|Annaba|Batna|Bechar|Bejaia|Biskra|Blida|Bordj Bou Arreridj|Bouira|Boumerdes|Chlef|Constantine|Djelfa|El Bayadh|El Oued|El Tarf|Ghardaia|Guelma|Illizi|Jijel|Khenchela|Laghouat|M'Sila|Mascara|Medea|Mila|Mostaganem|Naama|Oran|Ouargla|Oum el Bouaghi|Relizane|Saida|Setif|Sidi Bel Abbes|Skikda|Souk Ahras|Tamanghasset|Tebessa|Tiaret|Tindouf|Tipaza|Tissemsilt|Tizi Ouzou|Tlemcen", '213'],
 ['American Samoa', 'AS', 'ASM', '016', 'ISO 3166-2:AS', "Eastern|Manu'a|Rose Island|Swains Island|Western", '1 684'],
 ['Andorra', 'AD', 'AND', '020', 'ISO 3166-2:AD', 'Andorra', '376'],
 ['Angola', 'AO', 'AGO', '024', 'ISO 3166-2:AO', 'Andorra la Vella|Bengo|Benguela|Bie|Cabinda|Canillo|Cuando Cubango|Cuanza Norte|Cuanza Sul|Cunene|Encamp|Escaldes-Engordany|Huambo|Huila|La Massana|Luanda|Lunda Norte|Lunda Sul|Malanje|Moxico|Namibe|Ordino|Sant Julia de Loria|Uige|Zaire', '244'],
 ['Anguilla', 'AI', 'AIA', '660', 'ISO 3166-2:AI', 'Anguilla', '1 264'],
 ['Antarctica', 'AQ', 'ATA', '010', 'ISO 3166-2:AQ', 'Antarctica', '672'],
 ['Antigua and Barbuda', 'AG', 'ATG', '028', 'ISO 3166-2:AG', 'Barbuda|Redonda|Saint George|Saint John|Saint Mary|Saint Paul|Saint Peter|Saint Philip', '1 268'],
 ['Argentina', 'AR', 'ARG', '032', 'ISO 3166-2:AR', 'Antartica e Islas del Atlantico Sur|Buenos Aires|Buenos Aires Capital Federal|Catamarca|Chaco|Chubut|Cordoba|Corrientes|Entre Rios|Formosa|Jujuy|La Pampa|La Rioja|Mendoza|Misiones|Neuquen|Rio Negro|Salta|San Juan|San Luis|Santa Cruz|Santa Fe|Santiago del Estero|Tierra del Fuego|Tucuman', '54'],
 ['Armenia', 'AM', 'ARM', '051', 'ISO 3166-2:AM', "Aragatsotn|Ararat|Armavir|Geghark'unik'|Kotayk'|Lorri|Shirak|Syunik'|Tavush|Vayots' Dzor|Yerevan", '374'],
 ['Aruba', 'AW', 'ABW', '533', 'ISO 3166-2:AW', 'Aruba', '297'],
 ['Australia', 'AU', 'AUS', '036', 'ISO 3166-2:AU', 'Australian Capital Territory|New South Wales|Northern Territory|Queensland|South Australia|Tasmania|Victoria|Western Australia', '61'],
 ['Austria', 'AT', 'AUT', '040', 'ISO 3166-2:AT', 'Burgenland|Kaernten|Niederoesterreich|Oberoesterreich|Salzburg|Steiermark|Tirol|Vorarlberg|Wien', '43'],
 ['Azerbaijan', 'AZ', 'AZE', '031', 'ISO 3166-2:AZ', 'Abseron Rayonu|Agcabadi Rayonu|Agdam Rayonu|Agdas Rayonu|Agstafa Rayonu|Agsu Rayonu|Ali Bayramli Sahari|Astara Rayonu|Baki Sahari|Balakan Rayonu|Barda Rayonu|Beylaqan Rayonu|Bilasuvar Rayonu|Cabrayil Rayonu|Calilabad Rayonu|Daskasan Rayonu|Davaci Rayonu|Fuzuli Rayonu|Gadabay Rayonu|Ganca Sahari|Goranboy Rayonu|Goycay Rayonu|Haciqabul Rayonu|Imisli Rayonu|Ismayilli Rayonu|Kalbacar Rayonu|Kurdamir Rayonu|Lacin Rayonu|Lankaran Rayonu|Lankaran Sahari|Lerik Rayonu|Masalli Rayonu|Mingacevir Sahari|Naftalan Sahari|Naxcivan Muxtar Respublikasi|Neftcala Rayonu|Oguz Rayonu|Qabala Rayonu|Qax Rayonu|Qazax Rayonu|Qobustan Rayonu|Quba Rayonu|Qubadli Rayonu|Qusar Rayonu|Saatli Rayonu|Sabirabad Rayonu|Saki Rayonu|Saki Sahari|Salyan Rayonu|Samaxi Rayonu|Samkir Rayonu|Samux Rayonu|Siyazan Rayonu|Sumqayit Sahari|Susa Rayonu|Susa Sahari|Tartar Rayonu|Tovuz Rayonu|Ucar Rayonu|Xacmaz Rayonu|Xankandi Sahari|Xanlar Rayonu|Xizi Rayonu|Xocali Rayonu|Xocavand Rayonu|Yardimli Rayonu|Yevlax Rayonu|Yevlax Sahari|Zangilan Rayonu|Zaqatala Rayonu|Zardab Rayonu', '994'],
 ['Bahamas', 'BS', 'BHS', '044', 'ISO 3166-2:BS', "Acklins and Crooked Islands|Bimini|Cat Island|Exuma|Freeport|Fresh Creek|Governor's Harbour|Green Turtle Cay|Harbour Island|High Rock|Inagua|Kemps Bay|Long Island|Marsh Harbour|Mayaguana|New Providence|Nicholls Town and Berry Islands|Ragged Island|Rock Sound|San Salvador and Rum Cay|Sandy Point", '1 242'],
 ['Bahrain', 'BH', 'BHR', '048', 'ISO 3166-2:BH', "Al Hadd|Al Manamah|Al Mintaqah al Gharbiyah|Al Mintaqah al Wusta|Al Mintaqah ash Shamaliyah|Al Muharraq|Ar Rifa' wa al Mintaqah al Janubiyah|Jidd Hafs|Juzur Hawar|Madinat 'Isa|Madinat Hamad|Sitrah", '973'],
 ['Bangladesh', 'BD', 'BGD', '050', 'ISO 3166-2:BD', "Barguna|Barisal|Bhola|Jhalokati|Patuakhali|Pirojpur|Bandarban|Brahmanbaria|Chandpur|Chittagong|Comilla|Cox's Bazar|Feni|Khagrachari|Lakshmipur|Noakhali|Rangamati|Dhaka|Faridpur|Gazipur|Gopalganj|Jamalpur|Kishoreganj|Madaripur|Manikganj|Munshiganj|Mymensingh|Narayanganj|Narsingdi|Netrokona|Rajbari|Shariatpur|Sherpur|Tangail|Bagerhat|Chuadanga|Jessore|Jhenaidah|Khulna|Kushtia|Magura|Meherpur|Narail|Satkhira|Bogra|Dinajpur|Gaibandha|Jaipurhat|Kurigram|Lalmonirhat|Naogaon|Natore|Nawabganj|Nilphamari|Pabna|Panchagarh|Rajshahi|Rangpur|Sirajganj|Thakurgaon|Habiganj|Maulvi bazar|Sunamganj|Sylhet", '880'],
 ['Barbados', 'BB', 'BRB', '052', 'ISO 3166-2:BB', 'Bridgetown|Christ Church|Saint Andrew|Saint George|Saint James|Saint John|Saint Joseph|Saint Lucy|Saint Michael|Saint Peter|Saint Philip|Saint Thomas', '1 246'],
 ['Belarus', 'BY', 'BLR', '112', 'ISO 3166-2:BY', "Brestskaya [Brest]|Homyel'skaya [Homyel']|Horad Minsk|Hrodzyenskaya [Hrodna]|Mahilyowskaya [Mahilyow]|Minskaya|Vitsyebskaya [Vitsyebsk]", '375'],
 ['Belgium', 'BE', 'BEL', '056', 'ISO 3166-2:BE', 'Antwerpen|Brabant Wallon|Brussels Capitol Region|Hainaut|Liege|Limburg|Luxembourg|Namur|Oost-Vlaanderen|Vlaams Brabant|West-Vlaanderen', '32'],
 ['Belize', 'BZ', 'BLZ', '084', 'ISO 3166-2:BZ', 'Belize|Cayo|Corozal|Orange Walk|Stann Creek|Toledo', '501'],
 ['Benin', 'BJ', 'BEN', '204', 'ISO 3166-2:BJ', 'Alibori|Atakora|Atlantique|Borgou|Collines|Couffo|Donga|Littoral|Mono|Oueme|Plateau|Zou', '229'],
 ['Bermuda', 'BM', 'BMU', '060', 'ISO 3166-2:BM', 'Devonshire|Hamilton|Hamilton|Paget|Pembroke|Saint George|Saint Georges|Sandys|Smiths|Southampton|Warwick', '1 441'],
 ['Bhutan', 'BT', 'BTN', '064', 'ISO 3166-2:BT', 'Bumthang|Chhukha|Chirang|Daga|Geylegphug|Ha|Lhuntshi|Mongar|Paro|Pemagatsel|Punakha|Samchi|Samdrup Jongkhar|Shemgang|Tashigang|Thimphu|Tongsa|Wangdi Phodrang', '975'],
 ['Bolivia Plurinational State of', 'BO', 'BOL', '068', 'ISO 3166-2:BO', 'Beni|Chuquisaca|Cochabamba|La Paz|Oruro|Pando|Potosi|Santa Cruz|Tarija', '591'],
 ['Bonaire Sint Eustatius and Saba', 'BQ', 'BES', '535', 'ISO 3166-2:BQ', 'Bonaire', ''],
 ['Bosnia and Herzegovina', 'BA', 'BIH', '070', 'ISO 3166-2:BA', 'Federation of Bosnia and Herzegovina|Republika Srpska', '387'],
 ['Botswana', 'BW', 'BWA', '072', 'ISO 3166-2:BW', 'Central|Chobe|Francistown|Gaborone|Ghanzi|Kgalagadi|Kgatleng|Kweneng|Lobatse|Ngamiland|North-East|Selebi-Pikwe|South-East|Southern', '267'],
 ['Bouvet Island', 'BV', 'BVT', '074', 'ISO 3166-2:BV', 'Bouvet Island', ''],
 ['Brazil', 'BR', 'BRA', '076', 'ISO 3166-2:BR', 'Acre|Alagoas|Amapa|Amazonas|Bahia|Ceara|Distrito Federal|Espirito Santo|Goias|Maranhao|Mato Grosso|Mato Grosso do Sul|Minas Gerais|Para|Paraiba|Parana|Pernambuco|Piaui|Rio de Janeiro|Rio Grande do Norte|Rio Grande do Sul|Rondonia|Roraima|Santa Catarina|Sao Paulo|Sergipe|Tocantins', '55'],
 ['British Indian Ocean Territory', 'IO', 'IOT', '086', 'ISO 3166-2:IO', 'British Indian Ocean Territory', '-'],
 ['Brunei Darussalam', 'BN', 'BRN', '096', 'ISO 3166-2:BN', 'Belait|Brunei and Muara|Temburong|Tutong', '673'],
 ['Bulgaria', 'BG', 'BGR', '100', 'ISO 3166-2:BG', 'Blagoevgrad|Burgas|Dobrich|Gabrovo|Khaskovo|Kurdzhali|Kyustendil|Lovech|Montana|Pazardzhik|Pernik|Pleven|Plovdiv|Razgrad|Ruse|Shumen|Silistra|Sliven|Smolyan|Sofiya|Sofiya-Grad|Stara Zagora|Turgovishte|Varna|Veliko Turnovo|Vidin|Vratsa|Yambol', '359'],
 ['Burkina Faso', 'BF', 'BFA', '854', 'ISO 3166-2:BF', 'Bale|Bam|Banwa|Bazega|Bougouriba|Boulgou|Boulkiemde|Comoe|Ganzourgou|Gnagna|Gourma|Houet|Ioba|Kadiogo|Kenedougou|Komandjari|Kompienga|Kossi|Koupelogo|Kouritenga|Kourweogo|Leraba|Loroum|Mouhoun|Nahouri|Namentenga|Naumbiel|Nayala|Oubritenga|Oudalan|Passore|Poni|Samentenga|Sanguie|Seno|Sissili|Soum|Sourou|Tapoa|Tuy|Yagha|Yatenga|Ziro|Zondomo|Zoundweogo', '226'],
 ['Burundi', 'BI', 'BDI', '108', 'ISO 3166-2:BI', 'Bubanza|Bujumbura|Bururi|Cankuzo|Cibitoke|Gitega|Karuzi|Kayanza|Kirundo|Makamba|Muramvya|Muyinga|Mwaro|Ngozi|Rutana|Ruyigi', '257'],
 ['Cambodia', 'KH', 'KHM', '116', 'ISO 3166-2:KH', 'Banteay Mean Cheay|Batdambang|Kampong Cham|Kampong Chhnang|Kampong Spoe|Kampong Thum|Kampot|Kandal|Kaoh Kong|Keb|Kracheh|Mondol Kiri|Otdar Mean Cheay|Pailin|Phnum Penh|Pouthisat|Preah Seihanu [Sihanoukville]|Preah Vihear|Prey Veng|Rotanah Kiri|Siem Reab|Stoeng Treng|Svay Rieng|Takev', '855'],
 ['Cameroon', 'CM', 'CMR', '120', 'ISO 3166-2:CM', 'Adamaoua|Centre|Est|Extreme-Nord|Littoral|Nord|Nord-Ouest|Ouest|Sud|Sud-Ouest', '237'],
 ['Canada', 'CA', 'CAN', '124', 'ISO 3166-2:CA', 'Alberta|British Columbia|Manitoba|New Brunswick|Newfoundland|Northwest Territories|Nova Scotia|Nunavut|Ontario|Prince Edward Island|Quebec|Saskatchewan|Yukon', '1'],
 ['Cape Verde', 'CV', 'CPV', '132', 'ISO 3166-2:CV', 'Boa Vista|Brava|Maio|Mosteiros|Paul|Porto Novo|Praia|Ribeira Grande|Sal|Santa Catarina|Santa Cruz|Sao Domingos|Sao Filipe|Sao Nicolau|Sao Vicente|Tarrafal', '238'],
 ['Cayman Islands', 'KY', 'CYM', '136', 'ISO 3166-2:KY', 'Creek|Eastern|Midland|South Town|Spot Bay|Stake Bay|West End|Western', '1 345'],
 ['Central African Republic', 'CF', 'CAF', '140', 'ISO 3166-2:CF', 'Bamingui-Bangoran|Bangui|Basse-Kotto|Gribingui|Haut-Mbomou|Haute-Kotto|Haute-Sangha|Kemo-Gribingui|Lobaye|Mbomou|Nana-Mambere|Ombella-Mpoko|Ouaka|Ouham|Ouham-Pende|Sangha|Vakaga', '236'],
 ['Chad', 'TD', 'TCD', '148', 'ISO 3166-2:TD', 'Batha|Biltine|Borkou-Ennedi-Tibesti|Chari-Baguirmi|Guera|Kanem|Lac|Logone Occidental|Logone Oriental|Mayo-Kebbi|Moyen-Chari|Ouaddai|Salamat|Tandjile', '235'],
 ['Chile', 'CL', 'CHL', '152', 'ISO 3166-2:CL', "Aisen del General Carlos Ibanez del Campo|Antofagasta|Araucania|Atacama|Bio-Bio|Coquimbo|Libertador General Bernardo O'Higgins|Los Lagos|Magallanes y de la Antartica Chilena|Maule|Region Metropolitana [Santiago]|Tarapaca|Valparaiso", '56'],
 ['China', 'CN', 'CHN', '156', 'ISO 3166-2:CN', 'Anhui|Beijing|Chongqing|Fujian|Gansu|Guangdong|Guangxi|Guizhou|Hainan|Hebei|Heilongjiang|Henan|Hubei|Hunan|Jiangsu|Jiangxi|Jilin|Liaoning|Nei Mongol|Ningxia|Qinghai|Shaanxi|Shandong|Shanghai|Shanxi|Sichuan|Tianjin|Xinjiang|Xizang [Tibet]|Yunnan|Zhejiang', '86'],
 ['Christmas Island', 'CX', 'CXR', '162', 'ISO 3166-2:CX', 'Christmas Island', '61'],
 ['Cocos [Keeling, ] Islands', 'CC', 'CCK', '166', 'ISO 3166-2:CC', 'Direction Island|Home Island|Horsburgh Island|North Keeling Island|South Island|West Island', '61'],
 ['Colombia', 'CO', 'COL', '170', 'ISO 3166-2:CO', 'Amazonas|Antioquia|Arauca|Atlantico|Bolivar|Boyaca|Caldas|Caqueta|Casanare|Cauca|Cesar|Choco|Cordoba|Cundinamarca|Distrito Capital de Santa Fe de Bogota|Guainia|Guaviare|Huila|La Guajira|Magdalena|Meta|Narino|Norte de Santander|Putumayo|Quindio|Risaralda|San Andres y Providencia|Santander|Sucre|Tolima|Valle del Cauca|Vaupes|Vichada', '57'],
 ['Comoros', 'KM', 'COM', '174', 'ISO 3166-2:KM', 'Anjouan [Nzwani]|Domoni|Fomboni|Grande Comore [Njazidja]|Moheli [Mwali]|Moroni|Moutsamoudou', '269'],
 ['Congo', 'CG', 'COG', '178', 'ISO 3166-2:CG', 'Bouenza|Brazzaville|Cuvette|Kouilou|Lekoumou|Likouala|Niari|Plateaux|Pool|Sangha', '242'],
 ['Congo the Democratic Republic of the', 'CD', 'COD', '180', 'ISO 3166-2:CD', 'Bandundu|Bas-Congo|Equateur|Kasai-Occidental|Kasai-Oriental|Katanga|Kinshasa|Maniema|Nord-Kivu|Orientale|Sud-Kivu', '243'],
 ['Cook Islands', 'CK', 'COK', '184', 'ISO 3166-2:CK', 'Aitutaki|Atiu|Avarua|Mangaia|Manihiki|Manuae|Mauke|Mitiaro|Nassau Island|Palmerston|Penrhyn|Pukapuka|Rakahanga|Rarotonga|Suwarrow|Takutea', '682'],
 ['Costa Rica', 'CR', 'CRI', '188', 'ISO 3166-2:CR', 'Alajuela|Cartago|Guanacaste|Heredia|Limon|Puntarenas|San Jose', '506'],
 ["Cote d'Ivoire", 'CI', 'CIV', '384', 'ISO 3166-2:CI', "Abengourou|Abidjan|Aboisso|Adiake'|Adzope|Agboville|Agnibilekrou|Ale'pe'|Bangolo|Beoumi|Biankouma|Bocanda|Bondoukou|Bongouanou|Bouafle|Bouake|Bouna|Boundiali|Dabakala|Dabon|Daloa|Danane|Daoukro|Dimbokro|Divo|Duekoue|Ferkessedougou|Gagnoa|Grand Bassam|Grand-Lahou|Guiglo|Issia|Jacqueville|Katiola|Korhogo|Lakota|Man|Mankono|Mbahiakro|Odienne|Oume|Sakassou|San-Pedro|Sassandra|Seguela|Sinfra|Soubre|Tabou|Tanda|Tiassale|Tiebissou|Tingrela|Touba|Toulepleu|Toumodi|Vavoua|Yamoussoukro|Zuenoula", '225'],
 ['Croatia', 'HR', 'HRV', '191', 'ISO 3166-2:HR', 'Bjelovarsko-Bilogorska Zupanija|Brodsko-Posavska Zupanija|Dubrovacko-Neretvanska Zupanija|Istarska Zupanija|Karlovacka Zupanija|Koprivnicko-Krizevacka Zupanija|Krapinsko-Zagorska Zupanija|Licko-Senjska Zupanija|Medimurska Zupanija|Osjecko-Baranjska Zupanija|Pozesko-Slavonska Zupanija|Primorsko-Goranska Zupanija|Sibensko-Kninska Zupanija|Sisacko-Moslavacka Zupanija|Splitsko-Dalmatinska Zupanija|Varazdinska Zupanija|Viroviticko-Podravska Zupanija|Vukovarsko-Srijemska Zupanija|Zadarska Zupanija|Zagreb|Zagrebacka Zupanija', '385'],
 ['Cuba', 'CU', 'CUB', '192', 'ISO 3166-2:CU', 'Camaguey|Ciego de Avila|Cienfuegos|Ciudad de La Habana|Granma|Guantanamo|Holguin|Isla de la Juventud|La Habana|Las Tunas|Matanzas|Pinar del Rio|Sancti Spiritus|Santiago de Cuba|Villa Clara', '53'],
 ['Curacao', 'CW', 'CUW', '531', 'ISO 3166-2:CW', 'Curacao', ''],
 ['Cyprus', 'CY', 'CYP', '196', 'ISO 3166-2:CY', 'Famagusta|Kyrenia|Larnaca|Limassol|Nicosia|Paphos', '357'],
 ['Czech Republic', 'CZ', 'CZE', '203', 'ISO 3166-2:CZ', 'Brnensky|Budejovicky|Jihlavsky|Karlovarsky|Kralovehradecky|Liberecky|Olomoucky|Ostravsky|Pardubicky|Plzensky|Praha|Stredocesky|Ustecky|Zlinsky', '420'],
 ['Denmark', 'DK', 'DNK', '208', 'ISO 3166-2:DK', 'Arhus|Bornholm|Fredericksberg|Frederiksborg|Fyn|Kobenhavn|Kobenhavns|Nordjylland|Ribe|Ringkobing|Roskilde|Sonderjylland|Storstrom|Vejle|Vestsjalland|Viborg', '45'],
 ['Djibouti', 'DJ', 'DJI', '262', 'ISO 3166-2:DJ', "'Ali Sabih|Dikhil|Djibouti|Obock|Tadjoura", '253'],
 ['Dominica', 'DM', 'DMA', '212', 'ISO 3166-2:DM', 'Saint Andrew|Saint David|Saint George|Saint John|Saint Joseph|Saint Luke|Saint Mark|Saint Patrick|Saint Paul|Saint Peter', '1 767'],
 ['Dominican Republic', 'DO', 'DOM', '214', 'ISO 3166-2:DO', 'Azua|Baoruco|Barahona|Dajabon|Distrito Nacional|Duarte|El Seibo|Elias Pina|Espaillat|Hato Mayor|Independencia|La Altagracia|La Romana|La Vega|Maria Trinidad Sanchez|Monsenor Nouel|Monte Cristi|Monte Plata|Pedernales|Peravia|Puerto Plata|Salcedo|Samana|San Cristobal|San Juan|San Pedro de Macoris|Sanchez Ramirez|Santiago|Santiago Rodriguez|Valverde', '1 809'],
 ['Ecuador', 'EC', 'ECU', '218', 'ISO 3166-2:EC', 'Azuay|Bolivar|Canar|Carchi|Chimborazo|Cotopaxi|El Oro|Esmeraldas|Galapagos|Guayas|Imbabura|Loja|Los Rios|Manabi|Morona-Santiago|Napo|Orellana|Pastaza|Pichincha|Sucumbios|Tungurahua|Zamora-Chinchipe', '593'],
 ['Egypt', 'EG', 'EGY', '818', 'ISO 3166-2:EG', "Ad Daqahliyah|Al Bahr al Ahmar|Al Buhayrah|Al Fayyum|Al Gharbiyah|Al Iskandariyah|Al Isma'iliyah|Al Jizah|Al Minufiyah|Al Minya|Al Qahirah|Al Qalyubiyah|Al Wadi al Jadid|As Suways|Ash Sharqiyah|Aswan|Asyut|Bani Suwayf|Bur Sa'id|Dumyat|Janub Sina'|Kafr ash Shaykh|Matruh|Qina|Shamal Sina'|Suhaj", '20'],
 ['El Salvador', 'SV', 'SLV', '222', 'ISO 3166-2:SV', 'Ahuachapan|Cabanas|Chalatenango|Cuscatlan|La Libertad|La Paz|La Union|Morazan|San Miguel|San Salvador|San Vicente|Santa Ana|Sonsonate|Usulutan', '503'],
 ['Equatorial Guinea', 'GQ', 'GNQ', '226', 'ISO 3166-2:GQ', 'Annobon|Bioko Norte|Bioko Sur|Centro Sur|Kie-Ntem|Litoral|Wele-Nzas', '240'],
 ['Eritrea', 'ER', 'ERI', '232', 'ISO 3166-2:ER', 'Akale Guzay|Barka|Denkel|Hamasen|Sahil|Semhar|Senhit|Seraye', '291'],
 ['Estonia', 'EE', 'EST', '233', 'ISO 3166-2:EE', 'Harjumaa [Tallinn]|Hiiumaa [Kardla]|Ida-Virumaa [Johvi]|Jarvamaa [Paide]|Jogevamaa [Jogeva]|Laane-Virumaa [Rakvere]|Laanemaa [Haapsalu]|Parnumaa [Parnu]|Polvamaa [Polva]|Raplamaa [Rapla]|Saaremaa [Kuessaare]|Tartumaa [Tartu]|Valgamaa [Valga]|Viljandimaa [Viljandi]|Vorumaa [Voru]', '372'],
 ['Ethiopia', 'ET', 'ETH', '231', 'ISO 3166-2:ET', 'Adis Abeba [Addis Ababa]|Afar|Amara|Dire Dawa|Gambela Hizboch|Hareri Hizb|Oromiya|Sumale|Tigray|YeDebub Biheroch Bihereseboch na Hizboch', '251'],
 ['Falkland Islands [Malvinas, ]', 'FK', 'FLK', '238', 'ISO 3166-2:FK', 'Falkland Islands [Islas Malvinas]', '500'],
 ['Faroe Islands', 'FO', 'FRO', '234', 'ISO 3166-2:FO', 'Bordoy|Eysturoy|Mykines|Sandoy|Skuvoy|Streymoy|Suduroy|Tvoroyri|Vagar', '298'],
 ['Fiji', 'FJ', 'FJI', '242', 'ISO 3166-2:FJ', 'Central|Eastern|Northern|Rotuma|Western', '679'],
 ['Finland', 'FI', 'FIN', '246', 'ISO 3166-2:FI', 'Aland|Etela-Suomen Laani|Ita-Suomen Laani|Lansi-Suomen Laani|Lappi|Oulun Laani', '358'],
 ['France', 'FR', 'FRA', '250', 'ISO 3166-2:FR', "Alsace|Aquitaine|Auvergne|Basse-Normandie|Bourgogne|Bretagne|Centre|Champagne-Ardenne|Corse|Franche-Comte|Haute-Normandie|Ile-de-France|Languedoc-Roussillon|Limousin|Lorraine|Midi-Pyrenees|Nord-Pas-de-Calais|Pays de la Loire|Picardie|Poitou-Charentes|Provence-Alpes-Cote d'Azur|Rhone-Alpes", '33'],
 ['French Guiana', 'GF', 'GUF', '254', 'ISO 3166-2:GF', 'French Guiana', ''],
 ['French Polynesia', 'PF', 'PYF', '258', 'ISO 3166-2:PF', 'Archipel des Marquises|Archipel des Tuamotu|Archipel des Tubuai|Iles du Vent|Iles Sous-le-Vent', '689'],
 ['French Southern Territories', 'TF', 'ATF', '260', 'ISO 3166-2:TF', 'Adelie Land|Ile Crozet|Iles Kerguelen|Iles Saint-Paul et Amsterdam', ''],
 ['Gabon', 'GA', 'GAB', '266', 'ISO 3166-2:GA', 'Estuaire|Haut-Ogooue|Moyen-Ogooue|Ngounie|Nyanga|Ogooue-Ivindo|Ogooue-Lolo|Ogooue-Maritime|Woleu-Ntem', '241'],
 ['Gambia', 'GM', 'GMB', '270', 'ISO 3166-2:GM', 'Banjul|Central River|Lower River|North Bank|Upper River|Western', '220'],
 ['Georgia', 'GE', 'GEO', '268', 'ISO 3166-2:GE', "Abashis|Abkhazia or Ap'khazet'is Avtonomiuri Respublika [Sokhumi]|Adigenis|Ajaria or Acharis Avtonomiuri Respublika [Bat'umi]|Akhalgoris|Akhalk'alak'is|Akhalts'ikhis|Akhmetis|Ambrolauris|Aspindzis|Baghdat'is|Bolnisis|Borjomis|Ch'khorotsqus|Ch'okhatauris|Chiat'ura|Dedop'listsqaros|Dmanisis|Dushet'is|Gardabanis|Gori|Goris|Gurjaanis|Javis|K'arelis|K'ut'aisi|Kaspis|Kharagaulis|Khashuris|Khobis|Khonis|Lagodekhis|Lanch'khut'is|Lentekhis|Marneulis|Martvilis|Mestiis|Mts'khet'is|Ninotsmindis|Onis|Ozurget'is|P'ot'i|Qazbegis|Qvarlis|Rust'avi|Sach'kheris|Sagarejos|Samtrediis|Senakis|Sighnaghis|T'bilisi|T'elavis|T'erjolis|T'et'ritsqaros|T'ianet'is|Tqibuli|Ts'ageris|Tsalenjikhis|Tsalkis|Tsqaltubo|Vanis|Zestap'onis|Zugdidi|Zugdidis", '995'],
 ['Germany', 'DE', 'DEU', '276', 'ISO 3166-2:DE', 'Brandenburg|Berlin|Baden-W\xfcrttemberg|Bayern [Bavaria]|Bremen|Hessen|Hamburg|Mecklenburg-Vorpommern|Niedersachsen [Lower Saxony]|Nordrhein-Westfalen|Rheinland-Pfalz [Palatinate]|Schleswig-Holstein|Saarland|Sachsen [Saxony]|Sachsen-Anhalt [Saxony-Anhalt]|Th\xfcringen [Thuringia]', '49'],
 ['Ghana', 'GH', 'GHA', '288', 'ISO 3166-2:GH', 'Ashanti|Brong-Ahafo|Central|Eastern|Greater Accra|Northern|Upper East|Upper West|Volta|Western', '233'],
 ['Gibraltar', 'GI', 'GIB', '292', 'ISO 3166-2:GI', 'Gibraltar', '350'],
 ['Greece', 'GR', 'GRC', '300', 'ISO 3166-2:GR', 'Achaea|Aetolia-Acarnania|Arcadia|Argolis|Arta|Athens|Boeotia|Chalcidice|Chania|Chios|Corfu|Corinthia|Cyclades|Dodecanese|Drama|East Attica|Elis|Euboea|Evros|Evrytania|Florina|Grevena|Heraklion|Imathia|Ioannina|Karditsa|Kastoria|Kavala|Kefalonia and Ithaka|Kilkis|Kozani|Laconia|Larissa|Lasithi|Lefkada|Lesbos|Magnesia|Messenia|Pella|Phocis|Phthiotis|Pieria|Piraeus|Preveza|Rethymno|Rhodope|Samos|Serres|Thesprotia|Thessaloniki|Trikala|West Attica|Xanthi|Zakynthos', '30'],
 ['Greenland', 'GL', 'GRL', '304', 'ISO 3166-2:GL', 'Avannaa [Nordgronland]|Kitaa [Vestgronland]|Tunu [Ostgronland]', '299'],
 ['Grenada', 'GD', 'GRD', '308', 'ISO 3166-2:GD', 'Carriacou and Petit Martinique|Saint Andrew|Saint David|Saint George|Saint John|Saint Mark|Saint Patrick', '1 473'],
 ['Guadeloupe', 'GP', 'GLP', '312', 'ISO 3166-2:GP', 'Basse-Terre|Grande-Terre|Iles de la Petite Terre|Iles des Saintes|Marie-Galante', ''],
 ['Guam', 'GU', 'GUM', '316', 'ISO 3166-2:GU', 'Guam', '1 671'],
 ['Guatemala', 'GT', 'GTM', '320', 'ISO 3166-2:GT', 'Alta Verapaz|Baja Verapaz|Chimaltenango|Chiquimula|El Progreso|Escuintla|Guatemala|Huehuetenango|Izabal|Jalapa|Jutiapa|Peten|Quetzaltenango|Quiche|Retalhuleu|Sacatepequez|San Marcos|Santa Rosa|Solola|Suchitepequez|Totonicapan|Zacapa', '502'],
 ['Guernsey', 'GG', 'GGY', '831', 'ISO 3166-2:GG', 'Castel|Forest|St. Andrew|St. Martin|St. Peter Port|St. Pierre du Bois|St. Sampson|St. Saviour|Torteval|Vale', ''],
 ['Guinea', 'GN', 'GIN', '324', 'ISO 3166-2:GN', 'Beyla|Boffa|Boke|Conakry|Coyah|Dabola|Dalaba|Dinguiraye|Dubreka|Faranah|Forecariah|Fria|Gaoual|Gueckedou|Kankan|Kerouane|Kindia|Kissidougou|Koubia|Koundara|Kouroussa|Labe|Lelouma|Lola|Macenta|Mali|Mamou|Mandiana|Nzerekore|Pita|Siguiri|Telimele|Tougue|Yomou', '224'],
 ['Guinea-Bissau', 'GW', 'GNB', '624', 'ISO 3166-2:GW', 'Bafata|Biombo|Bissau|Bolama-Bijagos|Cacheu|Gabu|Oio|Quinara|Tombali', '245'],
 ['Guyana', 'GY', 'GUY', '328', 'ISO 3166-2:GY', 'Barima-Waini|Cuyuni-Mazaruni|Demerara-Mahaica|East Berbice-Corentyne|Essequibo Islands-West Demerara|Mahaica-Berbice|Pomeroon-Supenaam|Potaro-Siparuni|Upper Demerara-Berbice|Upper Takutu-Upper Essequibo', '592'],
 ['Haiti', 'HT', 'HTI', '332', 'ISO 3166-2:HT', "Artibonite|Centre|Grand'Anse|Nord|Nord-Est|Nord-Ouest|Ouest|Sud|Sud-Est", '509'],
 ['Heard Island and McDonald Islands', 'HM', 'HMD', '334', 'ISO 3166-2:HM', 'Heard Island and McDonald Islands', ''],
 ['Holy See, Vatican City State', 'VA', 'VAT', '336', 'ISO 3166-2:VA', 'Holy See [Vatican City]', '39'],
 ['Honduras', 'HN', 'HND', '340', 'ISO 3166-2:HN', 'Atlantida|Choluteca|Colon|Comayagua|Copan|Cortes|El Paraiso|Francisco Morazan|Gracias a Dios|Intibuca|Islas de la Bahia|La Paz|Lempira|Ocotepeque|Olancho|Santa Barbara|Valle|Yoro', '504'],
 ['Hong Kong', 'HK', 'HKG', '344', 'ISO 3166-2:HK', 'Hong Kong', '852'],
 ['Hungary', 'HU', 'HUN', '348', 'ISO 3166-2:HU', 'Bacs-Kiskun|Baranya|Bekes|Bekescsaba|Borsod-Abauj-Zemplen|Budapest|Csongrad|Debrecen|Dunaujvaros|Eger|Fejer|Gyor|Gyor-Moson-Sopron|Hajdu-Bihar|Heves|Hodmezovasarhely|Jasz-Nagykun-Szolnok|Kaposvar|Kecskemet|Komarom-Esztergom|Miskolc|Nagykanizsa|Nograd|Nyiregyhaza|Pecs|Pest|Somogy|Sopron|Szabolcs-Szatmar-Bereg|Szeged|Szekesfehervar|Szolnok|Szombathely|Tatabanya|Tolna|Vas|Veszprem|Veszprem|Zala|Zalaegerszeg', '36'],
 ['Iceland', 'IS', 'ISL', '352', 'ISO 3166-2:IS', 'Akranes|Akureyri|Arnessysla|Austur-Bardhastrandarsysla|Austur-Hunavatnssysla|Austur-Skaftafellssysla|Borgarfjardharsysla|Dalasysla|Eyjafjardharsysla|Gullbringusysla|Hafnarfjordhur|Husavik|Isafjordhur|Keflavik|Kjosarsysla|Kopavogur|Myrasysla|Neskaupstadhur|Nordhur-Isafjardharsysla|Nordhur-Mulasys-la|Nordhur-Thingeyjarsysla|Olafsfjordhur|Rangarvallasysla|Reykjavik|Saudharkrokur|Seydhisfjordhur|Siglufjordhur|Skagafjardharsysla|Snaefellsnes-og Hnappadalssysla|Strandasysla|Sudhur-Mulasysla|Sudhur-Thingeyjarsysla|Vesttmannaeyjar|Vestur-Bardhastrandarsysla|Vestur-Hunavatnssysla|Vestur-Isafjardharsysla|Vestur-Skaftafellssysla', '354'],
 ['India', 'IN', 'IND', '356', 'ISO 3166-2:IN', 'Andaman and Nicobar Islands|Andhra Pradesh|Arunachal Pradesh|Assam|Bihar|Chandigarh|Chhattisgarh|Dadra and Nagar Haveli|Daman and Diu|Delhi|Goa|Gujarat|Haryana|Himachal Pradesh|Jammu and Kashmir|Jharkhand|Karnataka|Kerala|Lakshadweep|Madhya Pradesh|Maharashtra|Manipur|Meghalaya|Mizoram|Nagaland|Orissa|Pondicherry|Punjab|Rajasthan|Sikkim|Tamil Nadu|Tripura|Uttar Pradesh|Uttaranchal|West Bengal', '91'],
 ['Indonesia', 'ID', 'IDN', '360', 'ISO 3166-2:ID', 'Aceh|Bali|Banten|Bengkulu|East Timor|Gorontalo|Irian Jaya|Jakarta Raya|Jambi|Jawa Barat|Jawa Tengah|Jawa Timur|Kalimantan Barat|Kalimantan Selatan|Kalimantan Tengah|Kalimantan Timur|Kepulauan Bangka Belitung|Lampung|Maluku|Maluku Utara|Nusa Tenggara Barat|Nusa Tenggara Timur|Riau|Sulawesi Selatan|Sulawesi Tengah|Sulawesi Tenggara|Sulawesi Utara|Sumatera Barat|Sumatera Selatan|Sumatera Utara|Yogyakarta', '62'],
 ['Iran Islamic Republic of', 'IR', 'IRN', '364', 'ISO 3166-2:IR', 'Ardabil|Azarbayjan-e Gharbi|Azarbayjan-e Sharqi|Bushehr|Chahar Mahall va Bakhtiari|Esfahan|Fars|Gilan|Golestan|Hamadan|Hormozgan|Ilam|Kerman|Kermanshah|Khorasan|Khuzestan|Kohgiluyeh va Buyer Ahmad|Kordestan|Lorestan|Markazi|Mazandaran|Qazvin|Qom|Semnan|Sistan va Baluchestan|Tehran|Yazd|Zanjan', '98'],
 ['Iraq', 'IQ', 'IRQ', '368', 'ISO 3166-2:IQ', "Al Anbar|Al Basrah|Al Muthanna|Al Qadisiyah|An Najaf|Arbil|As Sulaymaniyah|At Ta'mim|Babil|Baghdad|Dahuk|Dhi Qar|Diyala|Karbala'|Maysan|Ninawa|Salah ad Din|Wasit", '964'],
 ['Ireland', 'IE', 'IRL', '372', 'ISO 3166-2:IE', 'Co. Carlow|Co. Cavan|Co. Clare|Co. Cork|Co. Donegal|Co. Dublin|Co. Galway|Co. Kerry|Co. Kildare|Co. Kilkenny|Co. Laois|Co. Leitrim|Co. Limerick|Co. Longford|Co. Louth|Co. Mayo|Co. Meath|Co. Monaghan|Co. Offaly|Co. Roscommon|Co. Sligo|Co. Tipperary|Co. Waterford|Co. Westmeath|Co. Wexford|Co. Wicklow', '353'],
 ['Isle of Man', 'IM', 'IMN', '833', 'ISO 3166-2:IM', 'Isle of Man', '44'],
 ['Israel', 'IL', 'ISR', '376', 'ISO 3166-2:IL', 'Central|Haifa|Jerusalem|Northern|Southern|Tel Aviv', '972'],
 ['Italy', 'IT', 'ITA', '380', 'ISO 3166-2:IT', "Agrigento|Alessandria|Ancona|Aosta|Ascoli Piceno|L'Aquila|Arezzo|Asti|Avellino|Bari|Bergamo|Biella|Belluno|Benevento|Bologna|Brindisi|Brescia|Barletta-Andria-Trani|Bolzano-Bozen|Cagliari|Campobasso|Caserta|Chieti|Carbonia-Inglesias|Caltanissetta|Cuneo|Como|Cremona|Cosenza|Catania|Catanzaro|Enna|Forl\xec-Cesena|Ferrara|Foggia|Firenze|Fermo|Frosinone|Genova|Gorizia|Grosseto|Imperia|Isernia|Crotone|Lecco|Lecce|Livorno|Lodi|Latina|Lucca|Monza e Brianza|Macerata|Messina|Milano|Mantova|Modena|Massa-Carrara|Matera|Napoli|Novara|Nuoro|Ogliastra|Oristano|Olbia-Tempio|Palermo|Piacenza|Padova|Pescara|Perugia|Pisa|Pordenone|Prato|Parma|Pistoia|Pesaro e Urbino|Pavia|Potenza|Ravenna|Reggio Calabria|Reggio Elilia|Ragusa|Rieti|Roma|Rimini|Rovigo|Salerno|Siena|Sondrio|La Spezia|Siracusa|Sassari|Savona|Taranto|Teramo|Trento|Torino|Trapani|Terni|Trieste|Treviso|Udine|Varese|Verbano-Cusio-Ossola|Vercelli|Venezia|Vicenza|Verona|Medio Campidano|Viterbo|Vibo Valentia", '39'],
 ['Jamaica', 'JM', 'JAM', '388', 'ISO 3166-2:JM', 'Clarendon|Hanover|Kingston|Manchester|Portland|Saint Andrew|Saint Ann|Saint Catherine|Saint Elizabeth|Saint James|Saint Mary|Saint Thomas|Trelawny|Westmoreland', '1 876'],
 ['Japan', 'JP', 'JPN', '392', 'ISO 3166-2:JP', 'Aichi|Akita|Aomori|Chiba|Ehime|Fukui|Fukuoka|Fukushima|Gifu|Gunma|Hiroshima|Hokkaido|Hyogo|Ibaragi|Ishikawa|Iwate|Kagawa|Kagoshima|Kanagawa|Kochi|Kumamoto|Kyoto|Mie|Miyagi|Miyazaki|Nagano|Nagasaki|Nara|Niigata|Oita|Okayama|Okinawa|Osaka|Saga|Saitama|Shiga|Shimane|Shizuoka|Tochigi|Tokushima|Tokyo|Tottori|Toyama|Wakayama|Yamagata|Yamaguchi|Yamanashi', '81'],
 ['Jersey', 'JE', 'JEY', '832', 'ISO 3166-2:JE', 'Jersey', '-'],
 ['Jordan', 'JO', 'JOR', '400', 'ISO 3166-2:JO', "'Amman|Ajlun|Al 'Aqabah|Al Balqa'|Al Karak|Al Mafraq|At Tafilah|Az Zarqa'|Irbid|Jarash|Ma'an|Madaba", '962'],
 ['Kazakhstan', 'KZ', 'KAZ', '398', 'ISO 3166-2:KZ', 'Almaty|Aqmola|Aqtobe|Astana|Atyrau|Batys Qazaqstan|Bayqongyr|Mangghystau|Ongtustik Qazaqstan|Pavlodar|Qaraghandy|Qostanay|Qyzylorda|Shyghys Qazaqstan|Soltustik Qazaqstan|Zhambyl', '7'],
 ['Kenya', 'KE', 'KEN', '404', 'ISO 3166-2:KE', 'Central|Coast|Eastern|Nairobi Area|North Eastern|Nyanza|Rift Valley|Western', '254'],
 ['Kiribati', 'KI', 'KIR', '296', 'ISO 3166-2:KI', 'Abaiang|Abemama|Aranuka|Arorae|Banaba|Banaba|Beru|Butaritari|Central Gilberts|Gilbert Islands|Kanton|Kiritimati|Kuria|Line Islands|Line Islands|Maiana|Makin|Marakei|Nikunau|Nonouti|Northern Gilberts|Onotoa|Phoenix Islands|Southern Gilberts|Tabiteuea|Tabuaeran|Tamana|Tarawa|Tarawa|Teraina', '686'],
 ["Korea, Democratic People's Republic of", 'KP', 'PRK', '408', 'ISO 3166-2:KP', "Chagang-do [Chagang Province]|Hamgyong-bukto [North Hamgyong Province]|Hamgyong-namdo [South Hamgyong Province]|Hwanghae-bukto [North Hwanghae Province]|Hwanghae-namdo [South Hwanghae Province]|Kaesong-si [Kaesong City]|Kangwon-do [Kangwon Province]|Namp'o-si [Namp'o City]|P'yongan-bukto [North P'yongan Province]|P'yongan-namdo [South P'yongan Province]|P'yongyang-si [P'yongyang City]|Yanggang-do [Yanggang Province]", '850'],
 ['Korea, Republic of', 'KR', 'KOR', '410', 'ISO 3166-2:KR', "Ch'ungch'ong-bukto|Ch'ungch'ong-namdo|Cheju-do|Cholla-bukto|Cholla-namdo|Inch'on-gwangyoksi|Kangwon-do|Kwangju-gwangyoksi|Kyonggi-do|Kyongsang-bukto|Kyongsang-namdo|Pusan-gwangyoksi|Soul-t'ukpyolsi|Taegu-gwangyoksi|Taejon-gwangyoksi|Ulsan-gwangyoksi", '82'],
 ['Kuwait', 'KW', 'KWT', '414', 'ISO 3166-2:KW', "Al 'Asimah|Al Ahmadi|Al Farwaniyah|Al Jahra'|Hawalli", '965'],
 ['Kyrgyzstan', 'KG', 'KGZ', '417', 'ISO 3166-2:KG', 'Batken Oblasty|Bishkek Shaary|Chuy Oblasty [Bishkek]|Jalal-Abad Oblasty|Naryn Oblasty|Osh Oblasty|Talas Oblasty|Ysyk-Kol Oblasty [Karakol]', '996'],
 ["Lao People's Democratic Republic", 'LA', 'LAO', '418', 'ISO 3166-2:LA', 'Attapu|Bokeo|Bolikhamxai|Champasak|Houaphan|Khammouan|Louangnamtha|Louangphabang|Oudomxai|Phongsali|Salavan|Savannakhet|Viangchan|Viangchan|Xaignabouli|Xaisomboun|Xekong|Xiangkhoang', '856'],
 ['Latvia', 'LV', 'LVA', '428', 'ISO 3166-2:LV', 'Aizkraukles Rajons|Aluksnes Rajons|Balvu Rajons|Bauskas Rajons|Cesu Rajons|Daugavpils|Daugavpils Rajons|Dobeles Rajons|Gulbenes Rajons|Jekabpils Rajons|Jelgava|Jelgavas Rajons|Jurmala|Kraslavas Rajons|Kuldigas Rajons|Leipaja|Liepajas Rajons|Limbazu Rajons|Ludzas Rajons|Madonas Rajons|Ogres Rajons|Preilu Rajons|Rezekne|Rezeknes Rajons|Riga|Rigas Rajons|Saldus Rajons|Talsu Rajons|Tukuma Rajons|Valkas Rajons|Valmieras Rajons|Ventspils|Ventspils Rajons', '371'],
 ['Lebanon', 'LB', 'LBN', '422', 'ISO 3166-2:LB', 'Beyrouth|Ech Chimal|Ej Jnoub|El Bekaa|Jabal Loubnane', '961'],
 ['Lesotho', 'LS', 'LSO', '426', 'ISO 3166-2:LS', "Berea|Butha-Buthe|Leribe|Mafeteng|Maseru|Mohales Hoek|Mokhotlong|Qacha's Nek|Quthing|Thaba-Tseka", '266'],
 ['Liberia', 'LR', 'LBR', '430', 'ISO 3166-2:LR', 'Bomi|Bong|Grand Bassa|Grand Cape Mount|Grand Gedeh|Grand Kru|Lofa|Margibi|Maryland|Montserrado|Nimba|River Cess|Sinoe', '231'],
 ['Libyan Arab Jamahiriya', 'LY', 'LBY', '434', 'ISO 3166-2:LY', "Ajdabiya|Al 'Aziziyah|Al Fatih|Al Jabal al Akhdar|Al Jufrah|Al Khums|Al Kufrah|An Nuqat al Khams|Ash Shati'|Awbari|Az Zawiyah|Banghazi|Darnah|Ghadamis|Gharyan|Misratah|Murzuq|Sabha|Sawfajjin|Surt|Tarabulus|Tarhunah|Tubruq|Yafran|Zlitan", '218'],
 ['Liechtenstein', 'LI', 'LIE', '438', 'ISO 3166-2:LI', 'Balzers|Eschen|Gamprin|Mauren|Planken|Ruggell|Schaan|Schellenberg|Triesen|Triesenberg|Vaduz', '423'],
 ['Lithuania', 'LT', 'LTU', '440', 'ISO 3166-2:LT', 'Akmenes Rajonas|Alytaus Rajonas|Alytus|Anyksciu Rajonas|Birstonas|Birzu Rajonas|Druskininkai|Ignalinos Rajonas|Jonavos Rajonas|Joniskio Rajonas|Jurbarko Rajonas|Kaisiadoriu Rajonas|Kaunas|Kauno Rajonas|Kedainiu Rajonas|Kelmes Rajonas|Klaipeda|Klaipedos Rajonas|Kretingos Rajonas|Kupiskio Rajonas|Lazdiju Rajonas|Marijampole|Marijampoles Rajonas|Mazeikiu Rajonas|Moletu Rajonas|Neringa Pakruojo Rajonas|Palanga|Panevezio Rajonas|Panevezys|Pasvalio Rajonas|Plunges Rajonas|Prienu Rajonas|Radviliskio Rajonas|Raseiniu Rajonas|Rokiskio Rajonas|Sakiu Rajonas|Salcininku Rajonas|Siauliai|Siauliu Rajonas|Silales Rajonas|Silutes Rajonas|Sirvintu Rajonas|Skuodo Rajonas|Svencioniu Rajonas|Taurages Rajonas|Telsiu Rajonas|Traku Rajonas|Ukmerges Rajonas|Utenos Rajonas|Varenos Rajonas|Vilkaviskio Rajonas|Vilniaus Rajonas|Vilnius|Zarasu Rajonas', '370'],
 ['Luxembourg', 'LU', 'LUX', '442', 'ISO 3166-2:LU', 'Diekirch|Grevenmacher|Luxembourg', '352'],
 ['Macao', 'MO', 'MAC', '446', 'ISO 3166-2:MO', 'Macao', '853'],
 ['Macedonia', 'MK', 'MKD', '807', 'ISO 3166-2:MK', 'Aracinovo|Bac|Belcista|Berovo|Bistrica|Bitola|Blatec|Bogdanci|Bogomila|Bogovinje|Bosilovo|Brvenica|Cair [Skopje]|Capari|Caska|Cegrane|Centar [Skopje]|Centar Zupa|Cesinovo|Cucer-Sandevo|Debar|Delcevo|Delogozdi|Demir Hisar|Demir Kapija|Dobrusevo|Dolna Banjica|Dolneni|Dorce Petrov [Skopje]|Drugovo|Dzepciste|Gazi Baba [Skopje]|Gevgelija|Gostivar|Gradsko|Ilinden|Izvor|Jegunovce|Kamenjane|Karbinci|Karpos [Skopje]|Kavadarci|Kicevo|Kisela Voda [Skopje]|Klecevce|Kocani|Konce|Kondovo|Konopiste|Kosel|Kratovo|Kriva Palanka|Krivogastani|Krusevo|Kuklis|Kukurecani|Kumanovo|Labunista|Lipkovo|Lozovo|Lukovo|Makedonska Kamenica|Makedonski Brod|Mavrovi Anovi|Meseista|Miravci|Mogila|Murtino|Negotino|Negotino-Poloska|Novaci|Novo Selo|Oblesevo|Ohrid|Orasac|Orizari|Oslomej|Pehcevo|Petrovec|Plasnia|Podares|Prilep|Probistip|Radovis|Rankovce|Resen|Rosoman|Rostusa|Samokov|Saraj|Sipkovica|Sopiste|Sopotnika|Srbinovo|Star Dojran|Staravina|Staro Nagoricane|Stip|Struga|Strumica|Studenicani|Suto Orizari [Skopje]|Sveti Nikole|Tearce|Tetovo|Topolcani|Valandovo|Vasilevo|Veles|Velesta|Vevcani|Vinica|Vitoliste|Vranestica|Vrapciste|Vratnica|Vrutok|Zajas|Zelenikovo|Zileno|Zitose|Zletovo|Zrnovci', '389'],
 ['Madagascar', 'MG', 'MDG', '450', 'ISO 3166-2:MG', 'Antananarivo|Antsiranana|Fianarantsoa|Mahajanga|Toamasina|Toliara', '261'],
 ['Malawi', 'MW', 'MWI', '454', 'ISO 3166-2:MW', 'Balaka|Blantyre|Chikwawa|Chiradzulu|Chitipa|Dedza|Dowa|Karonga|Kasungu|Likoma|Lilongwe|Machinga [Kasupe]|Mangochi|Mchinji|Mulanje|Mwanza|Mzimba|Nkhata Bay|Nkhotakota|Nsanje|Ntcheu|Ntchisi|Phalombe|Rumphi|Salima|Thyolo|Zomba', '265'],
 ['Malaysia', 'MY', 'MYS', '458', 'ISO 3166-2:MY', 'Johor|Kedah|Kelantan|Labuan|Melaka|Negeri Sembilan|Pahang|Perak|Perlis|Pulau Pinang|Sabah|Sarawak|Selangor|Terengganu|Wilayah Persekutuan', '60'],
 ['Maldives', 'MV', 'MDV', '462', 'ISO 3166-2:MV', 'Alifu|Baa|Dhaalu|Faafu|Gaafu Alifu|Gaafu Dhaalu|Gnaviyani|Haa Alifu|Haa Dhaalu|Kaafu|Laamu|Lhaviyani|Maale|Meemu|Noonu|Raa|Seenu|Shaviyani|Thaa|Vaavu', '960'],
 ['Mali', 'ML', 'MLI', '466', 'ISO 3166-2:ML', 'Gao|Kayes|Kidal|Koulikoro|Mopti|Segou|Sikasso|Tombouctou', '223'],
 ['Malta', 'MT', 'MLT', '470', 'ISO 3166-2:MT', 'Valletta', '356'],
 ['Marshall Islands', 'MH', 'MHL', '584', 'ISO 3166-2:MH', 'Ailinginae|Ailinglaplap|Ailuk|Arno|Aur|Bikar|Bikini|Bokak|Ebon|Enewetak|Erikub|Jabat|Jaluit|Jemo|Kili|Kwajalein|Lae|Lib|Likiep|Majuro|Maloelap|Mejit|Mili|Namorik|Namu|Rongelap|Rongrik|Toke|Ujae|Ujelang|Utirik|Wotho|Wotje', '692'],
 ['Martinique', 'MQ', 'MTQ', '474', 'ISO 3166-2:MQ', 'Martinique', ''],
 ['Mauritania', 'MR', 'MRT', '478', 'ISO 3166-2:MR', 'Adrar|Assaba|Brakna|Dakhlet Nouadhibou|Gorgol|Guidimaka|Hodh Ech Chargui|Hodh El Gharbi|Inchiri|Nouakchott|Tagant|Tiris Zemmour|Trarza', '222'],
 ['Mauritius', 'MU', 'MUS', '480', 'ISO 3166-2:MU', 'Agalega Islands|Black River|Cargados Carajos Shoals|Flacq|Grand Port|Moka|Pamplemousses|Plaines Wilhems|Port Louis|Riviere du Rempart|Rodrigues|Savanne', '230'],
 ['Mayotte', 'YT', 'MYT', '175', 'ISO 3166-2:YT', 'Mayotte', '262'],
 ['Mexico', 'MX', 'MEX', '484', 'ISO 3166-2:MX', 'Aguascalientes|Baja California|Baja California Sur|Campeche|Chiapas|Chihuahua|Coahuila de Zaragoza|Colima|Distrito Federal|Durango|Guanajuato|Guerrero|Hidalgo|Jalisco|Mexico|Michoacan de Ocampo|Morelos|Nayarit|Nuevo Leon|Oaxaca|Puebla|Queretaro de Arteaga|Quintana Roo|San Luis Potosi|Sinaloa|Sonora|Tabasco|Tamaulipas|Tlaxcala|Veracruz-Llave|Yucatan|Zacatecas', '52'],
 ['Micronesia Federated States of', 'FM', 'FSM', '583', 'ISO 3166-2:FM', 'Chuuk [Truk]|Kosrae|Pohnpei|Yap', '691'],
 ['Moldova', 'MD', 'MDA', '498', 'ISO 3166-2:MD', 'Balti|Cahul|Chisinau|Chisinau|Dubasari|Edinet|Gagauzia|Lapusna|Orhei|Soroca|Tighina|Ungheni', '373'],
 ['Monaco', 'MC', 'MCO', '492', 'ISO 3166-2:MC', 'Fontvieille|La Condamine|Monaco-Ville|Monte-Carlo', '377'],
 ['Mongolia', 'MN', 'MNG', '496', 'ISO 3166-2:MN', 'Arhangay|Bayan-Olgiy|Bayanhongor|Bulgan|Darhan|Dornod|Dornogovi|Dundgovi|Dzavhan|Erdenet|Govi-Altay|Hentiy|Hovd|Hovsgol|Omnogovi|Ovorhangay|Selenge|Suhbaatar|Tov|Ulaanbaatar|Uvs', '976'],
 ['Montenegro', 'ME', 'MNE', '499', 'ISO 3166-2:ME', 'Montenegro', '382'],
 ['Montserrat', 'MS', 'MSR', '500', 'ISO 3166-2:MS', "Saint Anthony|Saint Georges|Saint Peter's", '1 664'],
 ['Morocco', 'MA', 'MAR', '504', 'ISO 3166-2:MA', 'Agadir|Al Hoceima|Azilal|Ben Slimane|Beni Mellal|Boulemane|Casablanca|Chaouen|El Jadida|El Kelaa des Srarhna|Er Rachidia|Essaouira|Fes|Figuig|Guelmim|Ifrane|Kenitra|Khemisset|Khenifra|Khouribga|Laayoune|Larache|Marrakech|Meknes|Nador|Ouarzazate|Oujda|Rabat-Sale|Safi|Settat|Sidi Kacem|Tan-Tan|Tanger|Taounate|Taroudannt|Tata|Taza|Tetouan|Tiznit', '212'],
 ['Mozambique', 'MZ', 'MOZ', '508', 'ISO 3166-2:MZ', 'Cabo Delgado|Gaza|Inhambane|Manica|Maputo|Nampula|Niassa|Sofala|Tete|Zambezia', '258'],
 ['Myanmar', 'MM', 'MMR', '104', 'ISO 3166-2:MM', 'Ayeyarwady|Bago|Chin State|Kachin State|Kayah State|Kayin State|Magway|Mandalay|Mon State|Rakhine State|Sagaing|Shan State|Tanintharyi|Yangon', '95'],
 ['Namibia', 'NA', 'NAM', '516', 'ISO 3166-2:NA', 'Caprivi|Erongo|Hardap|Karas|Khomas|Kunene|Ohangwena|Okavango|Omaheke|Omusati|Oshana|Oshikoto|Otjozondjupa', '264'],
 ['Nauru', 'NR', 'NRU', '520', 'ISO 3166-2:NR', 'Aiwo|Anabar|Anetan|Anibare|Baiti|Boe|Buada|Denigomodu|Ewa|Ijuw|Meneng|Nibok|Uaboe|Yaren', '674'],
 ['Nepal', 'NP', 'NPL', '524', 'ISO 3166-2:NP', 'Bagmati|Bheri|Dhawalagiri|Gandaki|Janakpur|Karnali|Kosi|Lumbini|Mahakali|Mechi|Narayani|Rapti|Sagarmatha|Seti', '977'],
 ['Netherlands', 'NL', 'NLD', '528', 'ISO 3166-2:NL', 'Drenthe|Flevoland|Friesland|Gelderland|Groningen|Limburg|Noord-Brabant|Noord-Holland|Overijssel|Utrecht|Zeeland|Zuid-Holland', '31'],
 ['New Caledonia', 'NC', 'NCL', '540', 'ISO 3166-2:NC', 'Iles Loyaute|Nord|Sud', '687'],
 ['New Zealand', 'NZ', 'NZL', '554', 'ISO 3166-2:NZ', "Akaroa|Amuri|Ashburton|Bay of Islands|Bruce|Buller|Chatham Islands|Cheviot|Clifton|Clutha|Cook|Dannevirke|Egmont|Eketahuna|Ellesmere|Eltham|Eyre|Featherston|Franklin|Golden Bay|Great Barrier Island|Grey|Hauraki Plains|Hawera|Hawke's Bay|Heathcote|Hikurangi|Hobson|Hokianga|Horowhenua|Hurunui|Hutt|Inangahua|Inglewood|Kaikoura|Kairanga|Kiwitea|Lake|Mackenzie|Malvern|Manaia|Manawatu|Mangonui|Maniototo|Marlborough|Masterton|Matamata|Mount Herbert|Ohinemuri|Opotiki|Oroua|Otamatea|Otorohanga|Oxford|Pahiatua|Paparua|Patea|Piako|Pohangina|Raglan|Rangiora|Rangitikei|Rodney|Rotorua|Runanga|Saint Kilda|Silverpeaks|Southland|Stewart Island|Stratford|Strathallan|Taranaki|Taumarunui|Taupo|Tauranga|Thames-Coromandel|Tuapeka|Vincent|Waiapu|Waiheke|Waihemo|Waikato|Waikohu|Waimairi|Waimarino|Waimate|Waimate West|Waimea|Waipa|Waipawa|Waipukurau|Wairarapa South|Wairewa|Wairoa|Waitaki|Waitomo|Waitotara|Wallace|Wanganui|Waverley|Westland|Whakatane|Whangarei|Whangaroa|Woodville", '64'],
 ['Nicaragua', 'NI', 'NIC', '558', 'ISO 3166-2:NI', 'Atlantico Norte|Atlantico Sur|Boaco|Carazo|Chinandega|Chontales|Esteli|Granada|Jinotega|Leon|Madriz|Managua|Masaya|Matagalpa|Nueva Segovia|Rio San Juan|Rivas', '505'],
 ['Niger', 'NE', 'NER', '562', 'ISO 3166-2:NE', 'Agadez|Diffa|Dosso|Maradi|Niamey|Tahoua|Tillaberi|Zinder', '227'],
 ['Nigeria', 'NG', 'NGA', '566', 'ISO 3166-2:NG', 'Abia|Abuja Federal Capital Territory|Adamawa|Akwa Ibom|Anambra|Bauchi|Bayelsa|Benue|Borno|Cross River|Delta|Ebonyi|Edo|Ekiti|Enugu|Gombe|Imo|Jigawa|Kaduna|Kano|Katsina|Kebbi|Kogi|Kwara|Lagos|Nassarawa|Niger|Ogun|Ondo|Osun|Oyo|Plateau|Rivers|Sokoto|Taraba|Yobe|Zamfara', '234'],
 ['Niue', 'NU', 'NIU', '570', 'ISO 3166-2:NU', 'Niue', '683'],
 ['Norfolk Island', 'NF', 'NFK', '574', 'ISO 3166-2:NF', 'Norfolk Island', ''],
 ['Northern Mariana Islands', 'MP', 'MNP', '580', 'ISO 3166-2:MP', 'Northern Islands|Rota|Saipan|Tinian', '1 670'],
 ['Norway', 'NO', 'NOR', '578', 'ISO 3166-2:NO', 'Akershus|Aust-Agder|Buskerud|Finnmark|Hedmark|Hordaland|More og Romsdal|Nord-Trondelag|Nordland|Oppland|Oslo|Ostfold|Rogaland|Sogn og Fjordane|Sor-Trondelag|Telemark|Troms|Vest-Agder|Vestfold', '47'],
 ['Oman', 'OM', 'OMN', '512', 'ISO 3166-2:OM', 'Ad Dakhiliyah|Al Batinah|Al Wusta|Ash Sharqiyah|Az Zahirah|Masqat|Musandam|Zufar', '968'],
 ['Pakistan', 'PK', 'PAK', '586', 'ISO 3166-2:PK', 'Balochistan|Federally Administered Tribal Areas|Islamabad Capital Territory|North-West Frontier Province|Punjab|Sindh', '92'],
 ['Palau', 'PW', 'PLW', '585', 'ISO 3166-2:PW', 'Aimeliik|Airai|Angaur|Hatobohei|Kayangel|Koror|Melekeok|Ngaraard|Ngarchelong|Ngardmau|Ngatpang|Ngchesar|Ngeremlengui|Ngiwal|Palau Island|Peleliu|Sonsoral|Tobi', '680'],
 ['Palestinian Territory Occupied', 'PS', 'PSE', '275', 'ISO 3166-2:PS', 'West Bank|Gaza Strip', ''],
 ['Panama', 'PA', 'PAN', '591', 'ISO 3166-2:PA', 'Bocas del Toro|Chiriqui|Cocle|Colon|Darien|Herrera|Los Santos|Panama|San Blas|Veraguas', '507'],
 ['Papua New Guinea', 'PG', 'PNG', '598', 'ISO 3166-2:PG', 'Bougainville|Central|Chimbu|East New Britain|East Sepik|Eastern Highlands|Enga|Gulf|Madang|Manus|Milne Bay|Morobe|National Capital|New Ireland|Northern|Sandaun|Southern Highlands|West New Britain|Western|Western Highlands', '675'],
 ['Paraguay', 'PY', 'PRY', '600', 'ISO 3166-2:PY', 'Alto Paraguay|Alto Parana|Amambay|Asuncion [city]|Boqueron|Caaguazu|Caazapa|Canindeyu|Central|Concepcion|Cordillera|Guaira|Itapua|Misiones|Neembucu|Paraguari|Presidente Hayes|San Pedro', '595'],
 ['Peru', 'PE', 'PER', '604', 'ISO 3166-2:PE', 'Amazonas|Ancash|Apurimac|Arequipa|Ayacucho|Cajamarca|Callao|Cusco|Huancavelica|Huanuco|Ica|Junin|La Libertad|Lambayeque|Lima|Loreto|Madre de Dios|Moquegua|Pasco|Piura|Puno|San Martin|Tacna|Tumbes|Ucayali', '51'],
 ['Philippines', 'PH', 'PHL', '608', 'ISO 3166-2:PH', 'Abra|Agusan del Norte|Agusan del Sur|Aklan|Albay|Angeles|Antique|Aurora|Bacolod|Bago|Baguio|Bais|Basilan|Basilan City|Bataan|Batanes|Batangas|Batangas City|Benguet|Bohol|Bukidnon|Bulacan|Butuan|Cabanatuan|Cadiz|Cagayan|Cagayan de Oro|Calbayog|Caloocan|Camarines Norte|Camarines Sur|Camiguin|Canlaon|Capiz|Catanduanes|Cavite|Cavite City|Cebu|Cebu City|Cotabato|Dagupan|Danao|Dapitan|Davao City Davao|Davao del Sur|Davao Oriental|Dipolog|Dumaguete|Eastern Samar|General Santos|Gingoog|Ifugao|Iligan|Ilocos Norte|Ilocos Sur|Iloilo|Iloilo City|Iriga|Isabela|Kalinga-Apayao|La Carlota|La Union|Laguna|Lanao del Norte|Lanao del Sur|Laoag|Lapu-Lapu|Legaspi|Leyte|Lipa|Lucena|Maguindanao|Mandaue|Manila|Marawi|Marinduque|Masbate|Mindoro Occidental|Mindoro Oriental|Misamis Occidental|Misamis Oriental|Mountain|Naga|Negros Occidental|Negros Oriental|North Cotabato|Northern Samar|Nueva Ecija|Nueva Vizcaya|Olongapo|Ormoc|Oroquieta|Ozamis|Pagadian|Palawan|Palayan|Pampanga|Pangasinan|Pasay|Puerto Princesa|Quezon|Quezon City|Quirino|Rizal|Romblon|Roxas|Samar|San Carlos [in Negros Occidental]|San Carlos [in Pangasinan]|San Jose|San Pablo|Silay|Siquijor|Sorsogon|South Cotabato|Southern Leyte|Sultan Kudarat|Sulu|Surigao|Surigao del Norte|Surigao del Sur|Tacloban|Tagaytay|Tagbilaran|Tangub|Tarlac|Tawitawi|Toledo|Trece Martires|Zambales|Zamboanga|Zamboanga del Norte|Zamboanga del Sur', '63'],
 ['Pitcairn', 'PN', 'PCN', '612', 'ISO 3166-2:PN', 'Pitcarin Islands', '870'],
 ['Poland', 'PL', 'POL', '616', 'ISO 3166-2:PL', 'Dolnoslaskie|Kujawsko-Pomorskie|Lodzkie|Lubelskie|Lubuskie|Malopolskie|Mazowieckie|Opolskie|Podkarpackie|Podlaskie|Pomorskie|Slaskie|Swietokrzyskie|Warminsko-Mazurskie|Wielkopolskie|Zachodniopomorskie', '48'],
 ['Portugal', 'PT', 'PRT', '620', 'ISO 3166-2:PT', 'Acores [Azores]|Aveiro|Beja|Braga|Braganca|Castelo Branco|Coimbra|Evora|Faro|Guarda|Leiria|Lisboa|Madeira|Portalegre|Porto|Santarem|Setubal|Viana do Castelo|Vila Real|Viseu', '351'],
 ['Puerto Rico', 'PR', 'PRI', '630', 'ISO 3166-2:PR', 'Adjuntas|Aguada|Aguadilla|Aguas Buenas|Aibonito|Anasco|Arecibo|Arroyo|Barceloneta|Barranquitas|Bayamon|Cabo Rojo|Caguas|Camuy|Canovanas|Carolina|Catano|Cayey|Ceiba|Ciales|Cidra|Coamo|Comerio|Corozal|Culebra|Dorado|Fajardo|Florida|Guanica|Guayama|Guayanilla|Guaynabo|Gurabo|Hatillo|Hormigueros|Humacao|Isabela|Jayuya|Juana Diaz|Juncos|Lajas|Lares|Las Marias|Las Piedras|Loiza|Luquillo|Manati|Maricao|Maunabo|Mayaguez|Moca|Morovis|Naguabo|Naranjito|Orocovis|Patillas|Penuelas|Ponce|Quebradillas|Rincon|Rio Grande|Sabana Grande|Salinas|San German|San Juan|San Lorenzo|San Sebastian|Santa Isabel|Toa Alta|Toa Baja|Trujillo Alto|Utuado|Vega Alta|Vega Baja|Vieques|Villalba|Yabucoa|Yauco', '1'],
 ['Qatar', 'QA', 'QAT', '634', 'ISO 3166-2:QA', 'Ad Dawhah|Al Ghuwayriyah|Al Jumayliyah|Al Khawr|Al Wakrah|Ar Rayyan|Jarayan al Batinah|Madinat ash Shamal|Umm Salal', '974'],
 ['Reunion', 'RE', 'REU', '638', 'ISO 3166-2:RE', 'Reunion', ''],
 ['Romania', 'RO', 'ROU', '642', 'ISO 3166-2:RO', 'Alba|Arad|Arges|Bacau|Bihor|Bistrita-Nasaud|Botosani|Braila|Brasov|Bucuresti|Buzau|Calarasi|Caras-Severin|Cluj|Constanta|Covasna|Dimbovita|Dolj|Galati|Giurgiu|Gorj|Harghita|Hunedoara|Ialomita|Iasi|Maramures|Mehedinti|Mures|Neamt|Olt|Prahova|Salaj|Satu Mare|Sibiu|Suceava|Teleorman|Timis|Tulcea|Vaslui|Vilcea|Vrancea', '40'],
 ['Russian Federation', 'RU', 'RUS', '643', 'ISO 3166-2:RU', '\u0420\u0435\u0441\u043f\u0443\u0431\u043b\u0438\u043a\u0430 \u0410\u0434\u044b\u0433\u0435\u044f|\u0420\u0435\u0441\u043f\u0443\u0431\u043b\u0438\u043a\u0430 \u0410\u043b\u0442\u0430\u0439|\u0410\u043c\u0443\u0440\u0441\u043a\u0430\u044f \u043e\u0431\u043b\u0430\u0441\u0442\u044c|\u0410\u0440\u0445\u0430\u043d\u0433\u0435\u043b\u044c\u0441\u043a\u0430\u044f \u043e\u0431\u043b\u0430\u0441\u0442\u044c|\u0410\u0441\u0442\u0440\u0430\u0445\u0430\u043d\u0441\u043a\u0430\u044f \u043e\u0431\u043b\u0430\u0441\u0442\u044c|\u0420\u0435\u0441\u043f\u0443\u0431\u043b\u0438\u043a\u0430 \u0411\u0430\u0448\u043a\u043e\u0440\u0442\u043e\u0441\u0442\u0430\u043d|\u0411\u0435\u043b\u0433\u043e\u0440\u043e\u0434\u0441\u043a\u0430\u044f \u043e\u0431\u043b\u0430\u0441\u0442\u044c|\u0411\u0440\u044f\u043d\u0441\u043a\u0430\u044f \u043e\u0431\u043b\u0430\u0441\u0442\u044c|\u0420\u0435\u0441\u043f\u0443\u0431\u043b\u0438\u043a\u0430 \u0411\u0443\u0440\u044f\u0442\u0438\u044f|\u0427\u0435\u0447\u0435\u043d\u0441\u043a\u0430\u044f \u0420\u0435\u0441\u043f\u0443\u0431\u043b\u0438\u043a\u0430|\u0427\u0435\u043b\u044f\u0431\u0438\u043d\u0441\u043a\u0430\u044f \u043e\u0431\u043b\u0430\u0441\u0442\u044c|\u0427\u0443\u043a\u043e\u0442\u0441\u043a\u0438\u0439 \u0410\u041e|\u0427\u0443\u0432\u0430\u0448\u0441\u043a\u0430\u044f \u0420\u0435\u0441\u043f\u0443\u0431\u043b\u0438\u043a\u0430|\u0420\u0435\u0441\u043f\u0443\u0431\u043b\u0438\u043a\u0430 \u0414\u0430\u0433\u0435\u0441\u0442\u0430\u043d|\u0420\u0435\u0441\u043f\u0443\u0431\u043b\u0438\u043a\u0430 \u0418\u043d\u0433\u0443\u0448\u0435\u0442\u0438\u044f|\u0418\u0440\u043a\u0443\u0442\u0441\u043a\u0430\u044f \u043e\u0431\u043b\u0430\u0441\u0442\u044c|\u0418\u0432\u0430\u043d\u043e\u0432\u0441\u043a\u0430\u044f \u043e\u0431\u043b\u0430\u0441\u0442\u044c|\u041a\u0430\u043c\u0447\u0430\u0442\u0441\u043a\u0438\u0439 \u043a\u0440\u0430\u0439|\u041a\u0430\u0431\u0430\u0440\u0434\u0438\u043d\u043e-\u0411\u0430\u043b\u043a\u0430\u0440\u0441\u043a\u0430\u044f \u0420\u0435\u0441\u043f\u0443\u0431\u043b\u0438\u043a\u0430|\u041a\u0430\u0440\u0430\u0447\u0430\u0435\u0432\u043e-\u0427\u0435\u0440\u043a\u0435\u0441\u0441\u043a\u0430\u044f \u0420\u0435\u0441\u043f\u0443\u0431\u043b\u0438\u043a\u0430|\u041a\u0440\u0430\u0441\u043d\u043e\u0434\u0430\u0440\u0441\u043a\u0438\u0439 \u043a\u0440\u0430\u0439|\u041a\u0435\u043c\u0435\u0440\u043e\u0432\u0441\u043a\u0430\u044f \u043e\u0431\u043b\u0430\u0441\u0442\u044c|\u041a\u0430\u043b\u0438\u043d\u0438\u043d\u0433\u0440\u0430\u0434\u0441\u043a\u0430\u044f \u043e\u0431\u043b\u0430\u0441\u0442\u044c|\u041a\u0443\u0440\u0433\u0430\u043d\u0441\u043a\u0430\u044f \u043e\u0431\u043b\u0430\u0441\u0442\u044c|\u0425\u0430\u0431\u0430\u0440\u043e\u0432\u0441\u043a\u0438\u0439 \u043a\u0440\u0430\u0439|\u0425\u0430\u043d\u0442\u044b-\u041c\u0430\u043d\u0441\u0438\u0439\u0441\u043a\u0438\u0439 \u0410\u041e|\u041a\u0438\u0440\u043e\u0432\u0441\u043a\u0430\u044f \u043e\u0431\u043b\u0430\u0441\u0442\u044c|\u0420\u0435\u0441\u043f\u0443\u0431\u043b\u0438\u043a\u0430 \u0425\u0430\u043a\u0430\u0441\u0438\u044f|\u0420\u0435\u0441\u043f\u0443\u0431\u043b\u0438\u043a\u0430 \u041a\u0430\u043b\u043c\u044b\u043a\u0438\u044f|\u041a\u0430\u043b\u0443\u0436\u0441\u043a\u0430\u044f \u043e\u0431\u043b\u0430\u0441\u0442\u044c|\u0420\u0435\u0441\u043f\u0443\u0431\u043b\u0438\u043a\u0430 \u041a\u043e\u043c\u0438|\u041a\u043e\u0441\u0442\u0440\u043e\u043c\u0441\u043a\u0430\u044f \u043e\u0431\u043b\u0430\u0441\u0442\u044c|\u0420\u0435\u0441\u043f\u0443\u0431\u043b\u0438\u043a\u0430 \u041a\u0430\u0440\u0435\u043b\u0438\u044f|\u041a\u0443\u0440\u0441\u043a\u0430\u044f \u043e\u0431\u043b\u0430\u0441\u0442\u044c|\u041a\u0440\u0430\u0441\u043d\u043e\u044f\u0440\u0441\u043a\u0438\u0439 \u043a\u0440\u0430\u0439|\u041b\u0435\u043d\u0438\u043d\u0433\u0440\u0430\u0434\u0441\u043a\u0430\u044f \u043e\u0431\u043b\u0430\u0441\u0442\u044c|\u041b\u0438\u043f\u0435\u0446\u043a\u0430\u044f \u043e\u0431\u043b\u0430\u0441\u0442\u044c|\u0410\u043b\u0442\u0430\u0439\u0441\u043a\u0438\u0439 \u043a\u0440\u0430\u0439|\u041c\u0430\u0433\u0430\u0434\u0430\u043d\u0441\u043a\u0430\u044f \u043e\u0431\u043b\u0430\u0441\u0442\u044c|\u0420\u0435\u0441\u043f\u0443\u0431\u043b\u0438\u043a\u0430 \u041c\u0430\u0440\u0438\u0439 \u042d\u043b|\u0420\u0435\u0441\u043f\u0443\u0431\u043b\u0438\u043a\u0430 \u041c\u043e\u0440\u0434\u043e\u0432\u0438\u044f|\u041c\u043e\u0441\u043a\u043e\u0432\u0441\u043a\u0430\u044f \u043e\u0431\u043b\u0430\u0441\u0442\u044c|\u041c\u043e\u0441\u043a\u0432\u0430|\u041c\u0443\u0440\u043c\u0430\u043d\u0441\u043a\u0430\u044f \u043e\u0431\u043b\u0430\u0441\u0442\u044c|\u041d\u0435\u043d\u0435\u0446\u043a\u0438\u0439 \u0410\u041e|\u041d\u043e\u0432\u0433\u043e\u0440\u043e\u0434\u0441\u043a\u0430\u044f \u043e\u0431\u043b\u0430\u0441\u0442\u044c|\u041d\u0438\u0436\u0435\u0433\u043e\u0440\u043e\u0434\u0441\u043a\u0430\u044f \u043e\u0431\u043b\u0430\u0441\u0442\u044c|\u041d\u043e\u0432\u043e\u0441\u0438\u0431\u0438\u0440\u0441\u043a\u0430\u044f \u043e\u0431\u043b\u0430\u0441\u0442\u044c|\u041e\u043c\u0441\u043a\u0430\u044f \u043e\u0431\u043b\u0430\u0441\u0442\u044c|\u041e\u0440\u0435\u043d\u0431\u0443\u0440\u0433\u0441\u043a\u0430\u044f \u043e\u0431\u043b\u0430\u0441\u0442\u044c|\u041e\u0440\u043b\u043e\u0432\u0441\u043a\u0430\u044f \u043e\u0431\u043b\u0430\u0441\u0442\u044c|\u041f\u0435\u0440\u043c\u0441\u043a\u0438\u0439 \u043a\u0440\u0430\u0439|\u041f\u0435\u043d\u0437\u0435\u043d\u0441\u043a\u0430\u044f \u043e\u0431\u043b\u0430\u0441\u0442\u044c|\u041f\u0440\u0438\u043c\u043e\u0440\u0441\u043a\u0438\u0439 \u043a\u0440\u0430\u0439|\u041f\u0441\u043a\u043e\u0432\u0441\u043a\u0430\u044f \u043e\u0431\u043b\u0430\u0441\u0442\u044c|\u0420\u043e\u0441\u0442\u043e\u0432\u0441\u043a\u0430\u044f \u043e\u0431\u043b\u0430\u0441\u0442\u044c|\u0420\u044f\u0437\u0430\u043d\u0441\u043a\u0430\u044f \u043e\u0431\u043b\u0430\u0441\u0442\u044c|\u0420\u0435\u0441\u043f\u0443\u0431\u043b\u0438\u043a\u0430 \u0421\u0430\u0445\u0430 [\u042f\u043a\u0443\u0442\u0438\u044f]|\u0421\u0430\u0445\u0430\u043b\u0438\u043d\u0441\u043a\u0430\u044f \u043e\u0431\u043b\u0430\u0441\u0442\u044c|\u0421\u0430\u043c\u0430\u0440\u0441\u043a\u0430\u044f \u043e\u0431\u043b\u0430\u0441\u0442\u044c|\u0421\u0430\u0440\u0430\u0442\u043e\u0432\u0441\u043a\u0430\u044f \u043e\u0431\u043b\u0430\u0441\u0442\u044c|\u0420\u0435\u0441\u043f\u0443\u0431\u043b\u0438\u043a\u0430 \u0421\u0435\u0432. \u041e\u0441\u0435\u0442\u0438\u044f-\u0410\u043b\u0430\u043d\u0438\u044f|\u0421\u043c\u043e\u043b\u0435\u043d\u0441\u043a\u0430\u044f \u043e\u0431\u043b\u0430\u0441\u0442\u044c|\u0421\u0430\u043d\u043a\u0442-\u041f\u0435\u0442\u0435\u0440\u0431\u0443\u0440\u0433|\u0421\u0442\u0430\u0432\u0440\u043e\u043f\u043e\u043b\u044c\u0441\u043a\u0438\u0439 \u043a\u0440\u0430\u0439|\u0421\u0432\u0435\u0440\u0434\u043b\u043e\u0432\u0441\u043a\u0430\u044f \u043e\u0431\u043b\u0430\u0441\u0442\u044c|\u0420\u0435\u0441\u043f\u0443\u0431\u043b\u0438\u043a\u0430 \u0422\u0430\u0442\u0430\u0440\u0441\u0442\u0430\u043d|\u0422\u0430\u043c\u0431\u043e\u0432\u0441\u043a\u0430\u044f \u043e\u0431\u043b\u0430\u0441\u0442\u044c|\u0422\u043e\u043c\u0441\u043a\u0430\u044f \u043e\u0431\u043b\u0430\u0441\u0442\u044c|\u0422\u0443\u043b\u044c\u0441\u043a\u0430\u044f \u043e\u0431\u043b\u0430\u0441\u0442\u044c|\u0422\u0432\u0435\u0440\u0441\u043a\u0430\u044f \u043e\u0431\u043b\u0430\u0441\u0442\u044c|\u0420\u0435\u0441\u043f\u0443\u0431\u043b\u0438\u043a\u0430 \u0422\u044b\u0432\u0430|\u0422\u044e\u043c\u0435\u043d\u0441\u043a\u0430\u044f \u043e\u0431\u043b\u0430\u0441\u0442\u044c|\u0423\u0434\u043c\u0443\u0440\u0442\u0441\u043a\u0430\u044f \u0420\u0435\u0441\u043f\u0443\u0431\u043b\u0438\u043a\u0430|\u0423\u043b\u044c\u044f\u043d\u043e\u0432\u0441\u043a\u0430\u044f \u043e\u0431\u043b\u0430\u0441\u0442\u044c|\u0412\u043e\u043b\u0433\u043e\u0433\u0440\u0430\u0434\u0441\u043a\u0430\u044f \u043e\u0431\u043b\u0430\u0441\u0442\u044c|\u0412\u043b\u0430\u0434\u0438\u043c\u0438\u0440\u0441\u043a\u0430\u044f \u043e\u0431\u043b\u0430\u0441\u0442\u044c|\u0412\u043e\u043b\u043e\u0433\u043e\u0434\u0441\u043a\u0430\u044f \u043e\u0431\u043b\u0430\u0441\u0442\u044c|\u0412\u043e\u0440\u043e\u043d\u0435\u0436\u0441\u043a\u0430\u044f \u043e\u0431\u043b\u0430\u0441\u0442\u044c|\u042f\u043c\u0430\u043b\u043e-\u041d\u0435\u043d\u0435\u0446\u043a\u0438\u0439 \u0410\u041e|\u042f\u0440\u043e\u0441\u043b\u0430\u0432\u0441\u043a\u0430\u044f \u043e\u0431\u043b\u0430\u0441\u0442\u044c|\u0415\u0432\u0440\u0435\u0439\u0441\u043a\u0430\u044f \u0410\u041e|\u0417\u0430\u0431\u0430\u0439\u043a\u0430\u043b\u044c\u0441\u043a\u0438\u0439 \u043a\u0440\u0430\u0439', '7'],
 ['Rwanda', 'RW', 'RWA', '646', 'ISO 3166-2:RW', 'Butare|Byumba|Cyangugu|Gikongoro|Gisenyi|Gitarama|Kibungo|Kibuye|Kigali Rurale|Kigali-ville|Ruhengeri|Umutara', '250'],
 ['Saint Barthelemy', 'BL', 'BLM', '652', 'ISO 3166-2:BL', 'Saint Barthelemy', '590'],
 ['Saint Helena Ascension and Tristan da Cunha', 'SH', 'SHN', '654', 'ISO 3166-2:SH', 'Ascension|Saint Helena|Tristan da Cunha', '290'],
 ['Saint Kitts and Nevis', 'KN', 'KNA', '659', 'ISO 3166-2:KN', 'Christ Church Nichola Town|Saint Anne Sandy Point|Saint George Basseterre|Saint George Gingerland|Saint James Windward|Saint John Capisterre|Saint John Figtree|Saint Mary Cayon|Saint Paul Capisterre|Saint Paul Charlestown|Saint Peter Basseterre|Saint Thomas Lowland|Saint Thomas Middle Island|Trinity Palmetto Point', '1 869'],
 ['Saint Lucia', 'LC', 'LCA', '662', 'ISO 3166-2:LC', 'Anse-la-Raye|Castries|Choiseul|Dauphin|Dennery|Gros Islet|Laborie|Micoud|Praslin|Soufriere|Vieux Fort', '1 758'],
 ['Saint Martin [French part]', 'MF', 'MAF', '663', 'ISO 3166-2:MF', 'Saint Martin [French part]', '1 599'],
 ['Saint Pierre and Miquelon', 'PM', 'SPM', '666', 'ISO 3166-2:PM', 'Miquelon|Saint Pierre', '508'],
 ['Saint Vincent and the Grenadines', 'VC', 'VCT', '670', 'ISO 3166-2:VC', 'Charlotte|Grenadines|Saint Andrew|Saint David|Saint George|Saint Patrick', '1 784'],
 ['Samoa', 'WS', 'WSM', '882', 'ISO 3166-2:WS', "A'ana|Aiga-i-le-Tai|Atua|Fa'asaleleaga|Gaga'emauga|Gagaifomauga|Palauli|Satupa'itea|Tuamasaga|Va'a-o-Fonoti|Vaisigano", '685'],
 ['San Marino', 'SM', 'SMR', '674', 'ISO 3166-2:SM', 'Acquaviva|Borgo Maggiore|Chiesanuova|Domagnano|Faetano|Fiorentino|Monte Giardino|San Marino|Serravalle', '378'],
 ['Sao Tome and Principe', 'ST', 'STP', '678', 'ISO 3166-2:ST', 'Principe|Sao Tome', '239'],
 ['Saudi Arabia', 'SA', 'SAU', '682', 'ISO 3166-2:SA', "'Asir|Al Bahah|Al Hudud ash Shamaliyah|Al Jawf|Al Madinah|Al Qasim|Ar Riyad|Ash Sharqiyah [Eastern Province]|Ha'il|Jizan|Makkah|Najran|Tabuk", '966'],
 ['Senegal', 'SN', 'SEN', '686', 'ISO 3166-2:SN', 'Dakar|Diourbel|Fatick|Kaolack|Kolda|Louga|Saint-Louis|Tambacounda|Thies|Ziguinchor', '221'],
 ['Serbia', 'RS', 'SRB', '688', 'ISO 3166-2:RS', 'Serbia', '381'],
 ['Seychelles', 'SC', 'SYC', '690', 'ISO 3166-2:SC', "Anse aux Pins|Anse Boileau|Anse Etoile|Anse Louis|Anse Royale|Baie Lazare|Baie Sainte Anne|Beau Vallon|Bel Air|Bel Ombre|Cascade|Glacis|Grand' Anse [on Mahe]|Grand' Anse [on Praslin]|La Digue|La Riviere Anglaise|Mont Buxton|Mont Fleuri|Plaisance|Pointe La Rue|Port Glaud|Saint Louis|Takamaka", '248'],
 ['Sierra Leone', 'SL', 'SLE', '694', 'ISO 3166-2:SL', 'Eastern|Northern|Southern|Western', '232'],
 ['Singapore', 'SG', 'SGP', '702', 'ISO 3166-2:SG', 'Singapore', '65'],
 ['Sint Maarten [Dutch part]', 'SX', 'SXM', '534', 'ISO 3166-2:SX', 'Sint Maarten [Dutch part]', ''],
 ['Slovakia', 'SK', 'SVK', '703', 'ISO 3166-2:SK', 'Banskobystricky|Bratislavsky|Kosicky|Nitriansky|Presovsky|Trenciansky|Trnavsky|Zilinsky', '421'],
 ['Slovenia', 'SI', 'SVN', '705', 'ISO 3166-2:SI', 'Ajdovscina|Beltinci|Bled|Bohinj|Borovnica|Bovec|Brda|Brezice|Brezovica|Cankova-Tisina|Celje|Cerklje na Gorenjskem|Cerknica|Cerkno|Crensovci|Crna na Koroskem|Crnomelj|Destrnik-Trnovska Vas|Divaca|Dobrepolje|Dobrova-Horjul-Polhov Gradec|Dol pri Ljubljani|Domzale|Dornava|Dravograd|Duplek|Gorenja Vas-Poljane|Gorisnica|Gornja Radgona|Gornji Grad|Gornji Petrovci|Grosuplje|Hodos Salovci|Hrastnik|Hrpelje-Kozina|Idrija|Ig|Ilirska Bistrica|Ivancna Gorica|Izola|Jesenice|Jursinci|Kamnik|Kanal|Kidricevo|Kobarid|Kobilje|Kocevje|Komen|Koper|Kozje|Kranj|Kranjska Gora|Krsko|Kungota|Kuzma|Lasko|Lenart|Lendava|Litija|Ljubljana|Ljubno|Ljutomer|Logatec|Loska Dolina|Loski Potok|Luce|Lukovica|Majsperk|Maribor|Medvode|Menges|Metlika|Mezica|Miren-Kostanjevica|Mislinja|Moravce|Moravske Toplice|Mozirje|Murska Sobota|Muta|Naklo|Nazarje|Nova Gorica|Novo Mesto|Odranci|Ormoz|Osilnica|Pesnica|Piran|Pivka|Podcetrtek|Podvelka-Ribnica|Postojna|Preddvor|Ptuj|Puconci|Race-Fram|Radece|Radenci|Radlje ob Dravi|Radovljica|Ravne-Prevalje|Ribnica|Rogasevci|Rogaska Slatina|Rogatec|Ruse|Semic|Sencur|Sentilj|Sentjernej|Sentjur pri Celju|Sevnica|Sezana|Skocjan|Skofja Loka|Skofljica|Slovenj Gradec|Slovenska Bistrica|Slovenske Konjice|Smarje pri Jelsah|Smartno ob Paki|Sostanj|Starse|Store|Sveti Jurij|Tolmin|Trbovlje|Trebnje|Trzic|Turnisce|Velenje|Velike Lasce|Videm|Vipava|Vitanje|Vodice|Vojnik|Vrhnika|Vuzenica|Zagorje ob Savi|Zalec|Zavrc|Zelezniki|Ziri|Zrece', '386'],
 ['Solomon Islands', 'SB', 'SLB', '090', 'ISO 3166-2:SB', 'Bellona|Central|Choiseul [Lauru]|Guadalcanal|Honiara|Isabel|Makira|Malaita|Rennell|Temotu|Western', '677'],
 ['Somalia', 'SO', 'SOM', '706', 'ISO 3166-2:SO', 'Awdal|Bakool|Banaadir|Bari|Bay|Galguduud|Gedo|Hiiraan|Jubbada Dhexe|Jubbada Hoose|Mudug|Nugaal|Sanaag|Shabeellaha Dhexe|Shabeellaha Hoose|Sool|Togdheer|Woqooyi Galbeed', '252'],
 ['South Africa', 'ZA', 'ZAF', '710', 'ISO 3166-2:ZA', 'Eastern Cape|Free State|Gauteng|KwaZulu-Natal|Limpopo|Mpumalanga|North West|Northern Cape|Western Cape', '27'],
 ['South Georgia and the South Sandwich Islands', 'GS', 'SGS', '239', 'ISO 3166-2:GS', 'Bird Island|Bristol Island|Clerke Rocks|Montagu Island|Saunders Island|South Georgia|Southern Thule|Traversay Islands', ''],
 ['South Sudan', 'SS', 'SSD', '728', 'ISO 3166-2:SS', 'South Sudan', ''],
 ['Spain', 'ES', 'ESP', '724', 'ISO 3166-2:ES', 'Albacete|Alicante|Almer\xeda|Asturias|Badajoz|Balearic Islands|Barcelona|Biscay|Burgos|Cantabria|Castell\xf3n|Ciudad Real|Cuenca|C\xe1ceres|C\xe1diz|C\xf3rdoba|Gerona|Granada|Guadalajara|Guip\xfazcoa|Huelva|Huesca|Ja\xe9n|La Coru\xf1a|La Rioja|Las Palmas|Le\xf3n|Lugo|L\xe9rida|Madrid|Murcia|M\xe1laga|Navarre|Orense|Palencia|Pontevedra|Salamanca|Santa Cruz|Segovia|Sevilla|Soria|Tarragona|Teruel|Toledo|Valencia|Valladolid|Zamora|Zaragoza|\xc1lava|\xc1vila', '34'],
 ['Sri Lanka', 'LK', 'LKA', '144', 'ISO 3166-2:LK', 'Central|Eastern|North Central|North Eastern|North Western|Northern|Sabaragamuwa|Southern|Uva|Western', '94'],
 ['Sudan', 'SD', 'SDN', '729', 'ISO 3166-2:SD', "A'ali an Nil|Al Bahr al Ahmar|Al Buhayrat|Al Jazirah|Al Khartum|Al Qadarif|Al Wahdah|An Nil al Abyad|An Nil al Azraq|Ash Shamaliyah|Bahr al Jabal|Gharb al Istiwa'iyah|Gharb Bahr al Ghazal|Gharb Darfur|Gharb Kurdufan|Janub Darfur|Janub Kurdufan|Junqali|Kassala|Nahr an Nil|Shamal Bahr al Ghazal|Shamal Darfur|Shamal Kurdufan|Sharq al Istiwa'iyah|Sinnar|Warab", '249'],
 ['Suriname', 'SR', 'SUR', '740', 'ISO 3166-2:SR', 'Brokopondo|Commewijne|Coronie|Marowijne|Nickerie|Para|Paramaribo|Saramacca|Sipaliwini|Wanica', '597'],
 ['Svalbard and Jan Mayen', 'SJ', 'SJM', '744', 'ISO 3166-2:SJ', 'Barentsoya|Bjornoya|Edgeoya|Hopen|Kvitoya|Nordaustandet|Prins Karls Forland|Spitsbergen', '-'],
 ['Swaziland', 'SZ', 'SWZ', '748', 'ISO 3166-2:SZ', 'Hhohho|Lubombo|Manzini|Shiselweni', '268'],
 ['Sweden', 'SE', 'SWE', '752', 'ISO 3166-2:SE', 'Blekinge|Dalarnas|Gavleborgs|Gotlands|Hallands|Jamtlands|Jonkopings|Kalmar|Kronobergs|Norrbottens|Orebro|Ostergotlands|Skane|Sodermanlands|Stockholms|Uppsala|Varmlands|Vasterbottens|Vasternorrlands|Vastmanlands|Vastra Gotalands', '46'],
 ['Switzerland', 'CH', 'CHE', '756', 'ISO 3166-2:CH', 'Aargau|Ausser-Rhoden|Basel-Landschaft|Basel-Stadt|Bern|Fribourg|Geneve|Glarus|Graubunden|Inner-Rhoden|Jura|Luzern|Neuchatel|Nidwalden|Obwalden|Sankt Gallen|Schaffhausen|Schwyz|Solothurn|Thurgau|Ticino|Uri|Valais|Vaud|Zug|Zurich', '41'],
 ['Syrian Arab Republic', 'SY', 'SYR', '760', 'ISO 3166-2:SY', "Al Hasakah|Al Ladhiqiyah|Al Qunaytirah|Ar Raqqah|As Suwayda'|Dar'a|Dayr az Zawr|Dimashq|Halab|Hamah|Hims|Idlib|Rif Dimashq|Tartus", '963'],
 ['Taiwan', 'TW', 'TWN', '158', 'ISO 3166-2:TW', "Chang-hua|Chi-lung|Chia-i|Chia-i|Chung-hsing-hsin-ts'un|Hsin-chu|Hsin-chu|Hua-lien|I-lan|Kao-hsiung|Kao-hsiung|Miao-li|Nan-t'ou|P'eng-hu|P'ing-tung|T'ai-chung|T'ai-chung|T'ai-nan|T'ai-nan|T'ai-pei|T'ai-pei|T'ai-tung|T'ao-yuan|Yun-lin", '886'],
 ['Tajikistan', 'TJ', 'TJK', '762', 'ISO 3166-2:TJ', 'Viloyati Khatlon|Viloyati Leninobod|Viloyati Mukhtori Kuhistoni Badakhshon', '992'],
 ['Tanzania United Republic of', 'TZ', 'TZA', '834', 'ISO 3166-2:TZ', 'Arusha|Dar es Salaam|Dodoma|Iringa|Kagera|Kigoma|Kilimanjaro|Lindi|Mara|Mbeya|Morogoro|Mtwara|Mwanza|Pemba North|Pemba South|Pwani|Rukwa|Ruvuma|Shinyanga|Singida|Tabora|Tanga|Zanzibar Central/South|Zanzibar North|Zanzibar Urban/West', '255'],
 ['Thailand', 'TH', 'THA', '764', 'ISO 3166-2:TH', 'Amnat Charoen|Ang Thong|Buriram|Chachoengsao|Chai Nat|Chaiyaphum|Chanthaburi|Chiang Mai|Chiang Rai|Chon Buri|Chumphon|Kalasin|Kamphaeng Phet|Kanchanaburi|Khon Kaen|Krabi|Krung Thep Mahanakhon [Bangkok]|Lampang|Lamphun|Loei|Lop Buri|Mae Hong Son|Maha Sarakham|Mukdahan|Nakhon Nayok|Nakhon Pathom|Nakhon Phanom|Nakhon Ratchasima|Nakhon Sawan|Nakhon Si Thammarat|Nan|Narathiwat|Nong Bua Lamphu|Nong Khai|Nonthaburi|Pathum Thani|Pattani|Phangnga|Phatthalung|Phayao|Phetchabun|Phetchaburi|Phichit|Phitsanulok|Phra Nakhon Si Ayutthaya|Phrae|Phuket|Prachin Buri|Prachuap Khiri Khan|Ranong|Ratchaburi|Rayong|Roi Et|Sa Kaeo|Sakon Nakhon|Samut Prakan|Samut Sakhon|Samut Songkhram|Sara Buri|Satun|Sing Buri|Sisaket|Songkhla|Sukhothai|Suphan Buri|Surat Thani|Surin|Tak|Trang|Trat|Ubon Ratchathani|Udon Thani|Uthai Thani|Uttaradit|Yala|Yasothon', '66'],
 ['Timor-Leste', 'TL', 'TLS', '626', 'ISO 3166-2:TL', 'Timor-Leste', '670'],
 ['Togo', 'TG', 'TGO', '768', 'ISO 3166-2:TG', 'De La Kara|Des Plateaux|Des Savanes|Du Centre|Maritime', '228'],
 ['Tokelau', 'TK', 'TKL', '772', 'ISO 3166-2:TK', 'Atafu|Fakaofo|Nukunonu', '690'],
 ['Tonga', 'TO', 'TON', '776', 'ISO 3166-2:TO', "Ha'apai|Tongatapu|Vava'u", '676'],
 ['Trinidad and Tobago', 'TT', 'TTO', '780', 'ISO 3166-2:TT', 'Arima|Caroni|Mayaro|Nariva|Port-of-Spain|Saint Andrew|Saint David|Saint George|Saint Patrick|San Fernando|Victoria|Tobago', '1 868'],
 ['Tunisia', 'TN', 'TUN', '788', 'ISO 3166-2:TN', 'Ariana|Beja|Ben Arous|Bizerte|El Kef|Gabes|Gafsa|Jendouba|Kairouan|Kasserine|Kebili|Mahdia|Medenine|Monastir|Nabeul|Sfax|Sidi Bou Zid|Siliana|Sousse|Tataouine|Tozeur|Tunis|Zaghouan', '216'],
 ['Turkey', 'TR', 'TUR', '792', 'ISO 3166-2:TR', 'Adana|Adiyaman|Afyon|Agri|Aksaray|Amasya|Ankara|Antalya|Ardahan|Artvin|Aydin|Balikesir|Bartin|Batman|Bayburt|Bilecik|Bingol|Bitlis|Bolu|Burdur|Bursa|Canakkale|Cankiri|Corum|Denizli|Diyarbakir|Duzce|Edirne|Elazig|Erzincan|Erzurum|Eskisehir|Gaziantep|Giresun|Gumushane|Hakkari|Hatay|Icel|Igdir|Isparta|Istanbul|Izmir|Kahramanmaras|Karabuk|Karaman|Kars|Kastamonu|Kayseri|Kilis|Kirikkale|Kirklareli|Kirsehir|Kocaeli|Konya|Kutahya|Malatya|Manisa|Mardin|Mugla|Mus|Nevsehir|Nigde|Ordu|Osmaniye|Rize|Sakarya|Samsun|Sanliurfa|Siirt|Sinop|Sirnak|Sivas|Tekirdag|Tokat|Trabzon|Tunceli|Usak|Van|Yalova|Yozgat|Zonguldak', '90'],
 ['Turkmenistan', 'TM', 'TKM', '795', 'ISO 3166-2:TM', 'Ahal Welayaty|Balkan Welayaty|Dashhowuz Welayaty|Lebap Welayaty|Mary Welayaty', '993'],
 ['Turks and Caicos Islands', 'TC', 'TCA', '796', 'ISO 3166-2:TC', 'Turks and Caicos Islands', '1 649'],
 ['Tuvalu', 'TV', 'TUV', '798', 'ISO 3166-2:TV', 'Tuvalu', '688'],
 ['Uganda', 'UG', 'UGA', '800', 'ISO 3166-2:UG', 'Adjumani|Apac|Arua|Bugiri|Bundibugyo|Bushenyi|Busia|Gulu|Hoima|Iganga|Jinja|Kabale|Kabarole|Kalangala|Kampala|Kamuli|Kapchorwa|Kasese|Katakwi|Kibale|Kiboga|Kisoro|Kitgum|Kotido|Kumi|Lira|Luwero|Masaka|Masindi|Mbale|Mbarara|Moroto|Moyo|Mpigi|Mubende|Mukono|Nakasongola|Nebbi|Ntungamo|Pallisa|Rakai|Rukungiri|Sembabule|Soroti|Tororo', '256'],
 ['Ukraine', 'UA', 'UKR', '804', 'ISO 3166-2:UA', "Avtonomna Respublika Krym [Simferopol']|Cherkas'ka [Cherkasy]|Chernihivs'ka [Chernihiv]|Chernivets'ka [Chernivtsi]|Dnipropetrovs'ka [Dnipropetrovs'k]|Donets'ka [Donets'k]|Ivano-Frankivs'ka [Ivano-Frankivs'k]|Kharkivs'ka [Kharkiv]|Khersons'ka [Kherson]|Khmel'nyts'ka [Khmel'nyts'kyy]|Kirovohrads'ka [Kirovohrad]|Kyyiv|Kyyivs'ka [Kiev]|L'vivs'ka [L'viv]|Luhans'ka [Luhans'k]|Mykolayivs'ka [Mykolayiv]|Odes'ka [Odesa]|Poltavs'ka [Poltava]|Rivnens'ka [Rivne]|Sevastopol'|Sums'ka [Sumy]|Ternopil's'ka [Ternopil']|Vinnyts'ka [Vinnytsya]|Volyns'ka [Luts'k]|Zakarpats'ka [Uzhhorod]|Zaporiz'ka [Zaporizhzhya]|Zhytomyrs'ka [Zhytomyr]", '380'],
 ['United Arab Emirates', 'AE', 'ARE', '784', 'ISO 3166-2:AE', "'Ajman|Abu Zaby [Abu Dhabi]|Al Fujayrah|Ash Shariqah [Sharjah]|Dubayy [Dubai]|Ra's al Khaymah|Umm al Qaywayn", '971'],
 ['United Kingdom', 'GB', 'GBR', '826', 'ISO 3166-2:GB', 'Aberdeenshire|Alderney|Angus|Antrim|Argyll|Armagh|Avon|Ayrshire|Banffshire|Bedfordshire|Berkshire|Berwickshire|Blaenau Gwent|Borders|Bridgend|Buckinghamshire|Bute|Caerphilly|Caithness|Cambridgeshire|Cardiff|Carmarthenshire|Central|Ceredigion|Cheshire|Clackmannanshire|Cleveland|Clwyd|Co. Antrim|Co. Armagh|Co. Derry|Co. Down|Co. Fermanagh|Co. Tyrone|Conwy|Cornwall|Cumberland|Cumbria|Denbighshire|Derbyshire|Derry|Devon|Dorset|Down|Dumfries & Galloway|Dumfriesshire|Dunbartonshire|Durham|Dyfed|East Lothian|East Sussex|Essex|Fermanagh|Fife|Flintshire|Gloucestershire|Grampian|Greater London|Greater Manchester|Guernsey|Gwent|Gwynedd|Hampshire|Hereford & Worcester|Herefordshire|Hertfordshire|Highland|Humberside|Huntingdonshire|Inverness|Isle Of Man|Isle of Anglesey|Isle of Wight|Jersey|Kent|Kincardineshire|Kinross-shire|Kirkcudbrightshire|Lanarkshire|Lancashire|Leicestershire|Lincolnshire|Lothian|Merseyside|Merthyr Tydfil|Mid Glamorgan|Middlesex|Midlothian|Monmouthshire|Moray|Nairnshire|Neath Port Talbot|Newport|Norfolk|North Yorkshire|Northamptonshire|Northumberland|Nottinghamshire|Orkney|Oxfordshire|Peebleshire|Pembrokeshire|Perthshire|Powys|Renfrewshire|Rhondda Cynon Taff|Ross & Cromarty|Roxburghshire|Rutland|Scotland|Selkirkshire|Shetland|Shropshire|Somerset|South Glamorgan|South Yorkshire|Staffordshire|Stirlingshire|Strathclyde|Suffolk|Surrey|Sussex|Sutherland|Swansea|Tayside|Torfaen|Tyne & Wear|Tyne and Wear|Tyrone|Vale of Glamorgan|Wales|Warwickshire|West Glamorgan|West Lothian|West Midlands|West Riding|West Sussex|West Yorkshire|Western Isles|Westmoorland|Westmorland|Wigtownshire|Wiltshire|Worcestershire|Wrexham|Yorkshire', '44'],
 ['United States', 'US', 'USA', '840', 'ISO 3166-2:US', 'Alaska|Alabama|Arkansas|Arizona|California|Colorado|Connecticut|Washington, D.C.|Delaware|Florida|Georgia|Hawaii|Iowa|Idaho|Illinois|Indiana|Kansas|Kentucky|Louisiana|Massachusetts|Maryland|Maine|Michigan|Minnesota|Missouri|Mississippi|Montana|North Carolina|North Dakota|Nebraska|New Hampshire|New Jersey|New Mexico|Nevada|New York|Ohio|Oklahoma|Oregon|Pennsylvania|Rhode Island|South Carolina|South Dakota|Tennessee|Texas|Utah|Virginia|Vermont|Washington|Wisconsin|West Virginia|Wyoming', '1'],
 ['United States Minor Outlying Islands', 'UM', 'UMI', '581', 'ISO 3166-2:UM', '', ''],
 ['Uruguay', 'UY', 'URY', '858', 'ISO 3166-2:UY', 'Artigas|Canelones|Cerro Largo|Colonia|Durazno|Flores|Florida|Lavalleja|Maldonado|Montevideo|Paysandu|Rio Negro|Rivera|Rocha|Salto|San Jose|Soriano|Tacuarembo|Treinta y Tres', '598'],
 ['Uzbekistan', 'UZ', 'UZB', '860', 'ISO 3166-2:UZ', 'Andijon Wiloyati|Bukhoro Wiloyati|Farghona Wiloyati|Jizzakh Wiloyati|Khorazm Wiloyati [Urganch]|Namangan Wiloyati|Nawoiy Wiloyati|Qashqadaryo Wiloyati [Qarshi]|Qoraqalpoghiston [Nukus]|Samarqand Wiloyati|Sirdaryo Wiloyati [Guliston]|Surkhondaryo Wiloyati [Termiz]|Toshkent Shahri|Toshkent Wiloyati', '998'],
 ['Vanuatu', 'VU', 'VUT', '548', 'ISO 3166-2:VU', 'Malampa|Penama|Sanma|Shefa|Tafea|Torba', '678'],
 ['Venezuela, Bolivarian Republic of', 'VE', 'VEN', '862', 'ISO 3166-2:VE', 'Amazonas|Anzoategui|Apure|Aragua|Barinas|Bolivar|Carabobo|Cojedes|Delta Amacuro|Dependencias Federales|Distrito Federal|Falcon|Guarico|Lara|Merida|Miranda|Monagas|Nueva Esparta|Portuguesa|Sucre|Tachira|Trujillo|Vargas|Yaracuy|Zulia', '58'],
 ['Viet Nam', 'VN', 'VNM', '704', 'ISO 3166-2:VN', 'An Giang|Ba Ria-Vung Tau|Bac Giang|Bac Kan|Bac Lieu|Bac Ninh|Ben Tre|Binh Dinh|Binh Duong|Binh Phuoc|Binh Thuan|Ca Mau|Can Tho|Cao Bang|Da Nang|Dac Lak|Dong Nai|Dong Thap|Gia Lai|Ha Giang|Ha Nam|Ha Noi|Ha Tay|Ha Tinh|Hai Duong|Hai Phong|Ho Chi Minh|Hoa Binh|Hung Yen|Khanh Hoa|Kien Giang|Kon Tum|Lai Chau|Lam Dong|Lang Son|Lao Cai|Long An|Nam Dinh|Nghe An|Ninh Binh|Ninh Thuan|Phu Tho|Phu Yen|Quang Binh|Quang Nam|Quang Ngai|Quang Ninh|Quang Tri|Soc Trang|Son La|Tay Ninh|Thai Binh|Thai Nguyen|Thanh Hoa|Thua Thien-Hue|Tien Giang|Tra Vinh|Tuyen Quang|Vinh Long|Vinh Phuc|Yen Bai', '84'],
 ['Virgin Islands British', 'VG', 'VGB', '092', 'ISO 3166-2:VG', 'Anegada|Jost Van Dyke|Tortola|Virgin Gorda', '1 284'],
 ['Virgin Islands U.S.', 'VI', 'VIR', '850', 'ISO 3166-2:VI', 'Saint Croix|Saint John|Saint Thomas', '1 340'],
 ['Wallis and Futuna', 'WF', 'WLF', '876', 'ISO 3166-2:WF', 'Alo|Sigave|Wallis', '681'],
 ['Western Sahara', 'EH', 'ESH', '732', 'ISO 3166-2:EH', 'Western Sahara', '-'],
 ['Yemen', 'YE', 'YEM', '887', 'ISO 3166-2:YE', "'Adan|'Ataq|Abyan|Al Bayda'|Al Hudaydah|Al Jawf|Al Mahrah|Al Mahwit|Dhamar|Hadhramawt|Hajjah|Ibb|Lahij|Ma'rib|Sa'dah|San'a'|Ta'izz", '967'],
 ['Zambia', 'ZM', 'ZMB', '894', 'ISO 3166-2:ZM', 'Central|Copperbelt|Eastern|Luapula|Lusaka|North-Western|Northern|Southern|Western', '260'],
 ['Zimbabwe', 'ZW', 'ZWE', '716', 'ISO 3166-2:ZW', 'Bulawayo|Harare|ManicalandMashonaland Central|Mashonaland East|Mashonaland West|Masvingo|Matabeleland North|Matabeleland South|Midlands', '263'],
];

    # returns a list of all countries
    public function countries()
    {
        $list = array();
        foreach ($this->sorted_countries as $rarray) {
            array_push($list, $rarray[0]);
        }
        sort($list);

        return $list;
    }

    # returns a list of all states
    public function states()
    {
        $list = array();
        foreach ($this->sorted_countries as $rarray) {
            $t = explode('|', $rarray[5]);
            foreach ($t as $m) {
                array_push($list, $m);
            }
        }
        sort($list);

        return $list;
    }

    # getStates accepts a name, iso_2_code, iso_3_code, iso_num_code or iso_something_code of a country
    public function getStates($country = null)
    {
        if (!empty($country) && (is_string($country) || is_numeric($country))) {
            $list = array();
            $country = strtoupper($country);
            foreach ($this->sorted_countries as $rarray) {
                if ((is_string($country) && ((strlen($country) > 3 && strtolower($rarray[0]) == strtolower($country)) || (strlen($country) == 2 && strtoupper($rarray[1]) == $country) || (strlen($country) == 3 && strtoupper($rarray[2]) == $country) || (strlen($country) > 3 && strtoupper(str_replace(' ', '', $rarray[4])) == str_replace(' ', '', $country)))) || (is_numeric($country) && $rarray[3] == $country)) {
                    $t = explode('|', $rarray[5]);
                    for ($i = 0; $i < sizeof($t); ++$i) {
                        array_push($list, $t[$i]);
                    }
                }
            }
            sort($list);

            return $list;
        } else {
            return array();
        }
    }

    # getCountry accepts a name, iso_2_code, iso_3_code, iso_num_code or iso_something_code of a country
    public function getCountry($country = null)
    {
        if (!empty($country) && (is_string($country) || is_numeric($country))) {
            $list = array();
            $country = strtoupper($country);
            foreach ($this->sorted_countries as $rarray) {
                if ((is_string($country) && ((strlen($country) > 3 && strtolower($rarray[0]) == strtolower($country)) || (strlen($country) == 2 && strtoupper($rarray[1]) == $country) || (strlen($country) == 3 && strtoupper($rarray[2]) == $country) || (strlen($country) > 3 && strtoupper(str_replace(' ', '', $rarray[4])) == str_replace(' ', '', $country)))) || (is_numeric($country) && $rarray[3] == $country)) {
                    $last = sizeof($rarray) - 1;
                    $list = array_combine(array('name', 'iso2', 'iso3', 'iso_num', 'something_code', 'dial_code', 'states'), array($rarray[0], $rarray[1], $rarray[2], $rarray[3], $rarray[4], $rarray[$last], $this->getStates($rarray[1])));
                }
            }
            sort($list);

            return $list;
        } else {
            return array();
        }
    }

    # getCountry accepts only name of state
    public function findState($state = null)
    {
        if (!empty($state) && is_string($state)) {
            $list = array();
            $state = strtolower($state);
            foreach ($this->sorted_countries as $rarray) {
                if (preg_match("@\b{$state}\b@", strtolower($rarray[5]))) {
                    //array_push($list, $rarray[0]);
                    $last = sizeof($rarray) - 1;
                    array_push($list, array_combine(array('country', 'iso2', 'iso3', 'iso_num', 'something_code', 'dial_code'), array($rarray[0], $rarray[1], $rarray[2], $rarray[3], $rarray[4], $rarray[$last])));
                }
            }
            sort($list);

            return $list;
        } else {
            return array();
        }
    }

    public function isState($state)
    {
        if ($state and ($this->findState($state))) {
            return true;
        } else {
            return false;
        }
    }

    public function isCountry($state)
    {
        if (($this->getCountry($state))) {
            return true;
        } else {
            return false;
        }
    }
}
