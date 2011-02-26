<?php

$table_name = false;
$table_name = TABLE_PREFIX . "countries";
$query = CI::db()->query ( "show tables like '$table_name'" );
$query = $query->row_array ();
$query = (array_values ( $query ));

if ($query [0] != $table_name) {
	$sql = "CREATE TABLE " . $table_name . " (
		id int(11) NOT NULL auto_increment,
		UNIQUE KEY id (id)
		);";
	CI::db()->query ( $sql );
}

$sql = "show tables like '$table_name'";
$query = CI::db()->query ( $sql );
$query = $query->row_array ();
$query = (array_values ( $query ));
if ($query [0] == $table_name) {
	//$columns = $db->fetchAll("show columns from $table_name");
	$sql = "show columns from $table_name";
	$query = CI::db()->query ( $sql );
	$columns = $query->result_array ();

	$exisiting_fields = array ();
	foreach ( $columns as $fivesdraft ) {
		$fivesdraft = array_change_key_case ( $fivesdraft, CASE_LOWER );
		$exisiting_fields [strtolower ( $fivesdraft ['field'] )] = true;
	}
	$fields_to_add = array ();
	$fields_to_add [] = array ('code', "char(3) NOT NULL default ''" );
	$fields_to_add [] = array ('name', "char(52) NOT NULL default ''" );
	$fields_to_add [] = array ('continent', "enum('Asia','Europe','North America','Africa','Oceania','Antarctica','South America') NOT NULL default 'Europe'" );
	$fields_to_add [] = array ('surfacearea', " float(10,2) NOT NULL default '0.00' ");
	$fields_to_add [] = array ('population', " int(11) NOT NULL default '0'  " );
	$fields_to_add [] = array ('localname', "char(252) NOT NULL default ''" );


 



	foreach ( $fields_to_add as $the_field ) {
		$sql = false;
		$the_field [0] = strtolower ( $the_field [0] );
		if ($exisiting_fields [$the_field [0]] != true) {
			$sql = "alter table $table_name add column {$the_field[0]} {$the_field[1]} ";
			CI::db()->query ( $sql );
		} else {
			$sql = "alter table $table_name modify {$the_field[0]} {$the_field[1]} ";
			CI::db()->query ( $sql );
		}

	}
	
	/*
	$sql = "delete from $table_name ";
	CI::db()->query ( $sql );
	 
	$q = "
INSERT INTO `$table_name` (`id`, `code`, `name`, `continent`, `surfaceArea`, `population`, `localname`) VALUES 
(1, 'AFG', 'Afghanistan', 'Asia', 652090.00, 22720000, 'Afganistan/Afqanestan'),
(2, 'NLD', 'Netherlands', 'Europe', 41526.00, 15864000, 'Nederland'),
(3, 'ANT', 'Netherlands Antilles', 'North America', 800.00, 217000, 'Nederlandse Antillen'),
(4, 'ALB', 'Albania', 'Europe', 28748.00, 3401200, 'Shqipëria'),
(5, 'DZA', 'Algeria', 'Africa', 2381741.00, 31471000, 'Al-Jaza’ir/Algérie'),
(6, 'ASM', 'American Samoa', 'Oceania', 199.00, 68000, 'Amerika Samoa'),
(7, 'AND', 'Andorra', 'Europe', 468.00, 78000, 'Andorra'),
(8, 'AGO', 'Angola', 'Africa', 1246700.00, 12878000, 'Angola'),
(9, 'AIA', 'Anguilla', 'North America', 96.00, 8000, 'Anguilla'),
(10, 'ATG', 'Antigua and Barbuda', 'North America', 442.00, 68000, 'Antigua and Barbuda'),
(11, 'ARE', 'United Arab Emirates', 'Asia', 83600.00, 2441000, 'Al-Imarat al-´Arabiya al-Muttahida'),
(12, 'ARG', 'Argentina', 'South America', 2780400.00, 37032000, 'Argentina'),
(13, 'ARM', 'Armenia', 'Asia', 29800.00, 3520000, 'Hajastan'),
(14, 'ABW', 'Aruba', 'North America', 193.00, 103000, 'Aruba'),
(15, 'AUS', 'Australia', 'Oceania', 7741220.00, 18886000, 'Australia'),
(16, 'AZE', 'Azerbaijan', 'Asia', 86600.00, 7734000, 'Azärbaycan'),
(17, 'BHS', 'Bahamas', 'North America', 13878.00, 307000, 'The Bahamas'),
(18, 'BHR', 'Bahrain', 'Asia', 694.00, 617000, 'Al-Bahrayn'),
(19, 'BGD', 'Bangladesh', 'Asia', 143998.00, 129155000, 'Bangladesh'),
(20, 'BRB', 'Barbados', 'North America', 430.00, 270000, 'Barbados'),
(21, 'BEL', 'Belgium', 'Europe', 30518.00, 10239000, 'België/Belgique'),
(22, 'BLZ', 'Belize', 'North America', 22696.00, 241000, 'Belize'),
(23, 'BEN', 'Benin', 'Africa', 112622.00, 6097000, 'Bénin'),
(24, 'BMU', 'Bermuda', 'North America', 53.00, 65000, 'Bermuda'),
(25, 'BTN', 'Bhutan', 'Asia', 47000.00, 2124000, 'Druk-Yul'),
(26, 'BOL', 'Bolivia', 'South America', 1098581.00, 8329000, 'Bolivia'),
(27, 'BIH', 'Bosnia and Herzegovina', 'Europe', 51197.00, 3972000, 'Bosna i Hercegovina'),
(28, 'BWA', 'Botswana', 'Africa', 581730.00, 1622000, 'Botswana'),
(29, 'BRA', 'Brazil', 'South America', 8547403.00, 170115000, 'Brasil'),
(30, 'GBR', 'United Kingdom', 'Europe', 242900.00, 59623400, 'United Kingdom'),
(31, 'VGB', 'Virgin Islands, British', 'North America', 151.00, 21000, 'British Virgin Islands'),
(32, 'BRN', 'Brunei', 'Asia', 5765.00, 328000, 'Brunei Darussalam'),
(33, 'BGR', 'Bulgaria', 'Europe', 110994.00, 8190900, 'Balgarija'),
(34, 'BFA', 'Burkina Faso', 'Africa', 274000.00, 11937000, 'Burkina Faso'),
(35, 'BDI', 'Burundi', 'Africa', 27834.00, 6695000, 'Burundi/Uburundi'),
(36, 'CYM', 'Cayman Islands', 'North America', 264.00, 38000, 'Cayman Islands'),
(37, 'CHL', 'Chile', 'South America', 756626.00, 15211000, 'Chile'),
(38, 'COK', 'Cook Islands', 'Oceania', 236.00, 20000, 'The Cook Islands'),
(39, 'CRI', 'Costa Rica', 'North America', 51100.00, 4023000, 'Costa Rica'),
(40, 'DJI', 'Djibouti', 'Africa', 23200.00, 638000, 'Djibouti/Jibuti'),
(41, 'DMA', 'Dominica', 'North America', 751.00, 71000, 'Dominica'),
(42, 'DOM', 'Dominican Republic', 'North America', 48511.00, 8495000, 'República Dominicana'),
(43, 'ECU', 'Ecuador', 'South America', 283561.00, 12646000, 'Ecuador'),
(44, 'EGY', 'Egypt', 'Africa', 1001449.00, 68470000, 'Misr'),
(45, 'SLV', 'El Salvador', 'North America', 21041.00, 6276000, 'El Salvador'),
(46, 'ERI', 'Eritrea', 'Africa', 117600.00, 3850000, 'Ertra'),
(47, 'ESP', 'Spain', 'Europe', 505992.00, 39441700, 'España'),
(48, 'ZAF', 'South Africa', 'Africa', 1221037.00, 40377000, 'South Africa'),
(49, 'ETH', 'Ethiopia', 'Africa', 1104300.00, 62565000, 'YeItyop´iya'),
(50, 'FLK', 'Falkland Islands', 'South America', 12173.00, 2000, 'Falkland Islands'),
(51, 'FJI', 'Fiji Islands', 'Oceania', 18274.00, 817000, 'Fiji Islands'),
(52, 'PHL', 'Philippines', 'Asia', 300000.00, 75967000, 'Pilipinas'),
(53, 'FRO', 'Faroe Islands', 'Europe', 1399.00, 43000, 'Føroyar'),
(54, 'GAB', 'Gabon', 'Africa', 267668.00, 1226000, 'Le Gabon'),
(55, 'GMB', 'Gambia', 'Africa', 11295.00, 1305000, 'The Gambia'),
(56, 'GEO', 'Georgia', 'Asia', 69700.00, 4968000, 'Sakartvelo'),
(57, 'GHA', 'Ghana', 'Africa', 238533.00, 20212000, 'Ghana'),
(58, 'GIB', 'Gibraltar', 'Europe', 6.00, 25000, 'Gibraltar'),
(59, 'GRD', 'Grenada', 'North America', 344.00, 94000, 'Grenada'),
(60, 'GRL', 'Greenland', 'North America', 2166090.00, 56000, 'Kalaallit Nunaat/Grønland'),
(61, 'GLP', 'Guadeloupe', 'North America', 1705.00, 456000, 'Guadeloupe'),
(62, 'GUM', 'Guam', 'Oceania', 549.00, 168000, 'Guam'),
(63, 'GTM', 'Guatemala', 'North America', 108889.00, 11385000, 'Guatemala'),
(64, 'GIN', 'Guinea', 'Africa', 245857.00, 7430000, 'Guinée'),
(65, 'GNB', 'Guinea-Bissau', 'Africa', 36125.00, 1213000, 'Guiné-Bissau'),
(66, 'GUY', 'Guyana', 'South America', 214969.00, 861000, 'Guyana'),
(67, 'HTI', 'Haiti', 'North America', 27750.00, 8222000, 'Haïti/Dayti'),
(68, 'HND', 'Honduras', 'North America', 112088.00, 6485000, 'Honduras'),
(69, 'HKG', 'Hong Kong', 'Asia', 1075.00, 6782000, 'Xianggang/Hong Kong'),
(70, 'SJM', 'Svalbard and Jan Mayen', 'Europe', 62422.00, 3200, 'Svalbard og Jan Mayen'),
(71, 'IDN', 'Indonesia', 'Asia', 1904569.00, 212107000, 'Indonesia'),
(72, 'IND', 'India', 'Asia', 3287263.00, 1013662000, 'Bharat/India'),
(73, 'IRQ', 'Iraq', 'Asia', 438317.00, 23115000, 'Al-´Iraq'),
(74, 'IRN', 'Iran', 'Asia', 1648195.00, 67702000, 'Iran'),
(75, 'IRL', 'Ireland', 'Europe', 70273.00, 3775100, 'Ireland/Éire'),
(76, 'ISL', 'Iceland', 'Europe', 103000.00, 279000, 'Ísland'),
(77, 'ISR', 'Israel', 'Asia', 21056.00, 6217000, 'Yisra’el/Isra’il'),
(78, 'ITA', 'Italy', 'Europe', 301316.00, 57680000, 'Italia'),
(79, 'TMP', 'East Timor', 'Asia', 14874.00, 885000, 'Timor Timur'),
(80, 'AUT', 'Austria', 'Europe', 83859.00, 8091800, 'Österreich'),
(81, 'JAM', 'Jamaica', 'North America', 10990.00, 2583000, 'Jamaica'),
(82, 'JPN', 'Japan', 'Asia', 377829.00, 126714000, 'Nihon/Nippon'),
(83, 'YEM', 'Yemen', 'Asia', 527968.00, 18112000, 'Al-Yaman'),
(84, 'JOR', 'Jordan', 'Asia', 88946.00, 5083000, 'Al-Urdunn'),
(85, 'CXR', 'Christmas Island', 'Oceania', 135.00, 2500, 'Christmas Island'),
(86, 'YUG', 'Yugoslavia', 'Europe', 102173.00, 10640000, 'Jugoslavija'),
(87, 'KHM', 'Cambodia', 'Asia', 181035.00, 11168000, 'Kâmpuchéa'),
(88, 'CMR', 'Cameroon', 'Africa', 475442.00, 15085000, 'Cameroun/Cameroon'),
(89, 'CAN', 'Canada', 'North America', 9970610.00, 31147000, 'Canada'),
(90, 'CPV', 'Cape Verde', 'Africa', 4033.00, 428000, 'Cabo Verde'),
(91, 'KAZ', 'Kazakstan', 'Asia', 2724900.00, 16223000, 'Qazaqstan'),
(92, 'KEN', 'Kenya', 'Africa', 580367.00, 30080000, 'Kenya'),
(93, 'CAF', 'Central African Republic', 'Africa', 622984.00, 3615000, 'Centrafrique/Bê-Afrîka'),
(94, 'CHN', 'China', 'Asia', 9572900.00, 1277558000, 'Zhongquo'),
(95, 'KGZ', 'Kyrgyzstan', 'Asia', 199900.00, 4699000, 'Kyrgyzstan'),
(96, 'KIR', 'Kiribati', 'Oceania', 726.00, 83000, 'Kiribati'),
(97, 'COL', 'Colombia', 'South America', 1138914.00, 42321000, 'Colombia'),
(98, 'COM', 'Comoros', 'Africa', 1862.00, 578000, 'Komori/Comores'),
(99, 'COG', 'Congo', 'Africa', 342000.00, 2943000, 'Congo'),
(100, 'COD', 'Congo, The Democratic Republic of the', 'Africa', 2344858.00, 51654000, 'République Démocratique du Congo'),
(101, 'CCK', 'Cocos (Keeling) Islands', 'Oceania', 14.00, 600, 'Cocos (Keeling) Islands'),
(102, 'PRK', 'North Korea', 'Asia', 120538.00, 24039000, 'Choson Minjujuui In´min Konghwaguk (Bukhan)'),
(103, 'KOR', 'South Korea', 'Asia', 99434.00, 46844000, 'Taehan Min’guk (Namhan)'),
(104, 'GRC', 'Greece', 'Europe', 131626.00, 10545700, 'Elláda'),
(105, 'HRV', 'Croatia', 'Europe', 56538.00, 4473000, 'Hrvatska'),
(106, 'CUB', 'Cuba', 'North America', 110861.00, 11201000, 'Cuba'),
(107, 'KWT', 'Kuwait', 'Asia', 17818.00, 1972000, 'Al-Kuwayt'),
(108, 'CYP', 'Cyprus', 'Asia', 9251.00, 754700, 'Kýpros/Kibris'),
(109, 'LAO', 'Laos', 'Asia', 236800.00, 5433000, 'Lao'),
(110, 'LVA', 'Latvia', 'Europe', 64589.00, 2424200, 'Latvija'),
(111, 'LSO', 'Lesotho', 'Africa', 30355.00, 2153000, 'Lesotho'),
(112, 'LBN', 'Lebanon', 'Asia', 10400.00, 3282000, 'Lubnan'),
(113, 'LBR', 'Liberia', 'Africa', 111369.00, 3154000, 'Liberia'),
(114, 'LBY', 'Libyan Arab Jamahiriya', 'Africa', 1759540.00, 5605000, 'Libiya'),
(115, 'LIE', 'Liechtenstein', 'Europe', 160.00, 32300, 'Liechtenstein'),
(116, 'LTU', 'Lithuania', 'Europe', 65301.00, 3698500, 'Lietuva'),
(117, 'LUX', 'Luxembourg', 'Europe', 2586.00, 435700, 'Luxembourg/Lëtzebuerg'),
(118, 'ESH', 'Western Sahara', 'Africa', 266000.00, 293000, 'As-Sahrawiya'),
(119, 'MAC', 'Macao', 'Asia', 18.00, 473000, 'Macau/Aomen'),
(120, 'MDG', 'Madagascar', 'Africa', 587041.00, 15942000, 'Madagasikara/Madagascar'),
(121, 'MKD', 'Macedonia', 'Europe', 25713.00, 2024000, 'Makedonija'),
(122, 'MWI', 'Malawi', 'Africa', 118484.00, 10925000, 'Malawi'),
(123, 'MDV', 'Maldives', 'Asia', 298.00, 286000, 'Dhivehi Raajje/Maldives'),
(124, 'MYS', 'Malaysia', 'Asia', 329758.00, 22244000, 'Malaysia'),
(125, 'MLI', 'Mali', 'Africa', 1240192.00, 11234000, 'Mali'),
(126, 'MLT', 'Malta', 'Europe', 316.00, 380200, 'Malta'),
(127, 'MAR', 'Morocco', 'Africa', 446550.00, 28351000, 'Al-Maghrib'),
(128, 'MHL', 'Marshall Islands', 'Oceania', 181.00, 64000, 'Marshall Islands/Majol'),
(129, 'MTQ', 'Martinique', 'North America', 1102.00, 395000, 'Martinique'),
(130, 'MRT', 'Mauritania', 'Africa', 1025520.00, 2670000, 'Muritaniya/Mauritanie'),
(131, 'MUS', 'Mauritius', 'Africa', 2040.00, 1158000, 'Mauritius'),
(132, 'MYT', 'Mayotte', 'Africa', 373.00, 149000, 'Mayotte'),
(133, 'MEX', 'Mexico', 'North America', 1958201.00, 98881000, 'México'),
(134, 'FSM', 'Micronesia, Federated States of', 'Oceania', 702.00, 119000, 'Micronesia'),
(135, 'MDA', 'Moldova', 'Europe', 33851.00, 4380000, 'Moldova'),
(136, 'MCO', 'Monaco', 'Europe', 1.50, 34000, 'Monaco'),
(137, 'MNG', 'Mongolia', 'Asia', 1566500.00, 2662000, 'Mongol Uls'),
(138, 'MSR', 'Montserrat', 'North America', 102.00, 11000, 'Montserrat'),
(139, 'MOZ', 'Mozambique', 'Africa', 801590.00, 19680000, 'Moçambique'),
(140, 'MMR', 'Myanmar', 'Asia', 676578.00, 45611000, 'Myanma Pye'),
(141, 'NAM', 'Namibia', 'Africa', 824292.00, 1726000, 'Namibia'),
(142, 'NRU', 'Nauru', 'Oceania', 21.00, 12000, 'Naoero/Nauru'),
(143, 'NPL', 'Nepal', 'Asia', 147181.00, 23930000, 'Nepal'),
(144, 'NIC', 'Nicaragua', 'North America', 130000.00, 5074000, 'Nicaragua'),
(145, 'NER', 'Niger', 'Africa', 1267000.00, 10730000, 'Niger'),
(146, 'NGA', 'Nigeria', 'Africa', 923768.00, 111506000, 'Nigeria'),
(147, 'NIU', 'Niue', 'Oceania', 260.00, 2000, 'Niue'),
(148, 'NFK', 'Norfolk Island', 'Oceania', 36.00, 2000, 'Norfolk Island'),
(149, 'NOR', 'Norway', 'Europe', 323877.00, 4478500, 'Norge'),
(150, 'CIV', 'Côte d’Ivoire', 'Africa', 322463.00, 14786000, 'Côte d’Ivoire'),
(151, 'OMN', 'Oman', 'Asia', 309500.00, 2542000, '´Uman'),
(152, 'PAK', 'Pakistan', 'Asia', 796095.00, 156483000, 'Pakistan'),
(153, 'PLW', 'Palau', 'Oceania', 459.00, 19000, 'Belau/Palau'),
(154, 'PAN', 'Panama', 'North America', 75517.00, 2856000, 'Panamá'),
(155, 'PNG', 'Papua New Guinea', 'Oceania', 462840.00, 4807000, 'Papua New Guinea/Papua Niugini'),
(156, 'PRY', 'Paraguay', 'South America', 406752.00, 5496000, 'Paraguay'),
(157, 'PER', 'Peru', 'South America', 1285216.00, 25662000, 'Perú/Piruw'),
(158, 'PCN', 'Pitcairn', 'Oceania', 49.00, 50, 'Pitcairn'),
(159, 'MNP', 'Northern Mariana Islands', 'Oceania', 464.00, 78000, 'Northern Mariana Islands'),
(160, 'PRT', 'Portugal', 'Europe', 91982.00, 9997600, 'Portugal'),
(161, 'PRI', 'Puerto Rico', 'North America', 8875.00, 3869000, 'Puerto Rico'),
(162, 'POL', 'Poland', 'Europe', 323250.00, 38653600, 'Polska'),
(163, 'GNQ', 'Equatorial Guinea', 'Africa', 28051.00, 453000, 'Guinea Ecuatorial'),
(164, 'QAT', 'Qatar', 'Asia', 11000.00, 599000, 'Qatar'),
(165, 'FRA', 'France', 'Europe', 551500.00, 59225700, 'France'),
(166, 'GUF', 'French Guiana', 'South America', 90000.00, 181000, 'Guyane française'),
(167, 'PYF', 'French Polynesia', 'Oceania', 4000.00, 235000, 'Polynésie française'),
(168, 'REU', 'Réunion', 'Africa', 2510.00, 699000, 'Réunion'),
(169, 'ROM', 'Romania', 'Europe', 238391.00, 22455500, 'România'),
(170, 'RWA', 'Rwanda', 'Africa', 26338.00, 7733000, 'Rwanda/Urwanda'),
(171, 'SWE', 'Sweden', 'Europe', 449964.00, 8861400, 'Sverige'),
(172, 'SHN', 'Saint Helena', 'Africa', 314.00, 6000, 'Saint Helena'),
(173, 'KNA', 'Saint Kitts and Nevis', 'North America', 261.00, 38000, 'Saint Kitts and Nevis'),
(174, 'LCA', 'Saint Lucia', 'North America', 622.00, 154000, 'Saint Lucia'),
(175, 'VCT', 'Saint Vincent and the Grenadines', 'North America', 388.00, 114000, 'Saint Vincent and the Grenadines'),
(176, 'SPM', 'Saint Pierre and Miquelon', 'North America', 242.00, 7000, 'Saint-Pierre-et-Miquelon'),
(177, 'DEU', 'Germany', 'Europe', 357022.00, 82164700, 'Deutschland'),
(178, 'SLB', 'Solomon Islands', 'Oceania', 28896.00, 444000, 'Solomon Islands'),
(179, 'ZMB', 'Zambia', 'Africa', 752618.00, 9169000, 'Zambia'),
(180, 'WSM', 'Samoa', 'Oceania', 2831.00, 180000, 'Samoa'),
(181, 'SMR', 'San Marino', 'Europe', 61.00, 27000, 'San Marino'),
(182, 'STP', 'Sao Tome and Principe', 'Africa', 964.00, 147000, 'São Tomé e Príncipe'),
(183, 'SAU', 'Saudi Arabia', 'Asia', 2149690.00, 21607000, 'Al-´Arabiya as-Sa´udiya'),
(184, 'SEN', 'Senegal', 'Africa', 196722.00, 9481000, 'Sénégal/Sounougal'),
(185, 'SYC', 'Seychelles', 'Africa', 455.00, 77000, 'Sesel/Seychelles'),
(186, 'SLE', 'Sierra Leone', 'Africa', 71740.00, 4854000, 'Sierra Leone'),
(187, 'SGP', 'Singapore', 'Asia', 618.00, 3567000, 'Singapore/Singapura/Xinjiapo/Singapur'),
(188, 'SVK', 'Slovakia', 'Europe', 49012.00, 5398700, 'Slovensko'),
(189, 'SVN', 'Slovenia', 'Europe', 20256.00, 1987800, 'Slovenija'),
(190, 'SOM', 'Somalia', 'Africa', 637657.00, 10097000, 'Soomaaliya'),
(191, 'LKA', 'Sri Lanka', 'Asia', 65610.00, 18827000, 'Sri Lanka/Ilankai'),
(192, 'SDN', 'Sudan', 'Africa', 2505813.00, 29490000, 'As-Sudan'),
(193, 'FIN', 'Finland', 'Europe', 338145.00, 5171300, 'Suomi'),
(194, 'SUR', 'Suriname', 'South America', 163265.00, 417000, 'Suriname'),
(195, 'SWZ', 'Swaziland', 'Africa', 17364.00, 1008000, 'kaNgwane'),
(196, 'CHE', 'Switzerland', 'Europe', 41284.00, 7160400, 'Schweiz/Suisse/Svizzera/Svizra'),
(197, 'SYR', 'Syria', 'Asia', 185180.00, 16125000, 'Suriya'),
(198, 'TJK', 'Tajikistan', 'Asia', 143100.00, 6188000, 'Toçikiston'),
(199, 'TWN', 'Taiwan', 'Asia', 36188.00, 22256000, 'T’ai-wan'),
(200, 'TZA', 'Tanzania', 'Africa', 883749.00, 33517000, 'Tanzania'),
(201, 'DNK', 'Denmark', 'Europe', 43094.00, 5330000, 'Danmark'),
(202, 'THA', 'Thailand', 'Asia', 513115.00, 61399000, 'Prathet Thai'),
(203, 'TGO', 'Togo', 'Africa', 56785.00, 4629000, 'Togo'),
(204, 'TKL', 'Tokelau', 'Oceania', 12.00, 2000, 'Tokelau'),
(205, 'TON', 'Tonga', 'Oceania', 650.00, 99000, 'Tonga'),
(206, 'TTO', 'Trinidad and Tobago', 'North America', 5130.00, 1295000, 'Trinidad and Tobago'),
(207, 'TCD', 'Chad', 'Africa', 1284000.00, 7651000, 'Tchad/Tshad'),
(208, 'CZE', 'Czech Republic', 'Europe', 78866.00, 10278100, '¸esko'),
(209, 'TUN', 'Tunisia', 'Africa', 163610.00, 9586000, 'Tunis/Tunisie'),
(210, 'TUR', 'Turkey', 'Asia', 774815.00, 66591000, 'Türkiye'),
(211, 'TKM', 'Turkmenistan', 'Asia', 488100.00, 4459000, 'Türkmenostan'),
(212, 'TCA', 'Turks and Caicos Islands', 'North America', 430.00, 17000, 'The Turks and Caicos Islands'),
(213, 'TUV', 'Tuvalu', 'Oceania', 26.00, 12000, 'Tuvalu'),
(214, 'UGA', 'Uganda', 'Africa', 241038.00, 21778000, 'Uganda'),
(215, 'UKR', 'Ukraine', 'Europe', 603700.00, 50456000, 'Ukrajina'),
(216, 'HUN', 'Hungary', 'Europe', 93030.00, 10043200, 'Magyarország'),
(217, 'URY', 'Uruguay', 'South America', 175016.00, 3337000, 'Uruguay'),
(218, 'NCL', 'New Caledonia', 'Oceania', 18575.00, 214000, 'Nouvelle-Calédonie'),
(219, 'NZL', 'New Zealand', 'Oceania', 270534.00, 3862000, 'New Zealand/Aotearoa'),
(220, 'UZB', 'Uzbekistan', 'Asia', 447400.00, 24318000, 'Uzbekiston'),
(221, 'BLR', 'Belarus', 'Europe', 207600.00, 10236000, 'Belarus'),
(222, 'WLF', 'Wallis and Futuna', 'Oceania', 200.00, 15000, 'Wallis-et-Futuna'),
(223, 'VUT', 'Vanuatu', 'Oceania', 12189.00, 190000, 'Vanuatu'),
(224, 'VAT', 'Holy See (Vatican City State)', 'Europe', 0.40, 1000, 'Santa Sede/Citt�  del Vaticano'),
(225, 'VEN', 'Venezuela', 'South America', 912050.00, 24170000, 'Venezuela'),
(226, 'RUS', 'Russian Federation', 'Europe', 17075400.00, 146934000, 'Rossija'),
(227, 'VNM', 'Vietnam', 'Asia', 331689.00, 79832000, 'Viêt Nam'),
(228, 'EST', 'Estonia', 'Europe', 45227.00, 1439200, 'Eesti'),
(229, 'USA', 'United States', 'North America', 9363520.00, 278357000, 'United States'),
(230, 'VIR', 'Virgin Islands, U.S.', 'North America', 347.00, 93000, 'Virgin Islands of the United States'),
(231, 'ZWE', 'Zimbabwe', 'Africa', 390757.00, 11669000, 'Zimbabwe'),
(232, 'PSE', 'Palestine', 'Asia', 6257.00, 3101000, 'Filastin'),
(233, 'ATA', 'Antarctica', 'Antarctica', 13120000.00, 0, '–'),
(234, 'BVT', 'Bouvet Island', 'Antarctica', 59.00, 0, 'Bouvetøya'),
(235, 'IOT', 'British Indian Ocean Territory', 'Africa', 78.00, 0, 'British Indian Ocean Territory'),
(236, 'SGS', 'South Georgia and the South Sandwich Islands', 'Antarctica', 3903.00, 0, 'South Georgia and the South Sandwich Islands'),
(237, 'HMD', 'Heard Island and McDonald Islands', 'Antarctica', 359.00, 0, 'Heard and McDonald Islands'),
(238, 'ATF', 'French Southern territories', 'Antarctica', 7780.00, 0, 'Terres australes françaises'),
(239, 'UMI', 'United States Minor Outlying Islands', 'Oceania', 16.00, 0, 'United States Minor Outlying Islands');";

	CI::db()->query ( $q);*/
}

?>