<?php

/*
-------------------------------------
-- finding ways that are not connected to the rest of the map
-------------------------------------

thesis: any point in the whole map should be connected to any other node
in other words: from any point in the whole map one should be able to reach
any one of well known points that have to be defined (for performance reasons:
at least one point on every continent).
This check includes even small islands because ferries are considered to be
highways. So it is not neccessary to define starting points on every island.

algorithm: starting in a few nodes find ways connected to given nodes
now find nodes that are member of these ways.
do this until no more nodes/ways are found
any way that now is not member of the list of found ways is an island (not connected)

*/



// these are way_ids picked randomly in central locations
// ways are chosen that seem to be "well-connected" (motorways typically)
$islands = array(
	'europe' => array(
		'Kufstein, Austria' => 132797359,
		'Vösendorf, Austria' => 51103634,
		'Mödling, Austria' => 27174552,
		'Ponta Delgada, Azores' => 26644602,
		'Antwerpen, Belgium' => 4400616,
		'Bruxelles, Belgium' => 15371932,
		'Minsk, Belarus' => 25455453,
		'Sarajevo, Bosnia and Herzegowina' => 182044038,
		'Split, Croatia' => 142932114,
		'Nicosia, Cyprus' => 4746551,
		'Pilsen, Czech Republic' => 49057180,
		'Praha, Czech Republic' => 26167667,
		'Kopenhagen, Denmark' => 5056358,
		'Tallinn, Estonia' => 4554198,
		'Torshavn, Faröer' => 4967431,
		'Berlin, Germany' => 156597518,
		'Bremen, Germany' => 153180756,
		'Düsseldorf, Germany' => 143229689,
		'Frankfurt aM, Germany' => 25119827,
		'Ingolstadt, Germany' => 235715209,
		'Leipzig, Germany' => 3700745,
		'Paderborn, Germany' => 30724055,
		'Stuttgart, Germany' => 25239173,
		'Memmingen, Germany' => 25480706,
		'Athens, Greece' => 123272451,
		'Crete, Greece' => 80368843,
		'Crete, Greece' => 308776625,
		'Crete, Greece' => 180828255,
		'Crete, Greece' => 204164554,
		'Lahti, Finland' => 24318266,
		'Kemi, Finland' => 28896179,
		'Carhaix-Plouguer, France' => 33327261,
		'Marseille, France' => 6313524,
		'Paris, France' => 78454008,
		'Lyon, France' => 4360392,
		'Rennes, France' => 5097929,
		'Nantes, France' => 23453045,
		'Chatelleraut, France' => 51335790,
		'Limoges, France' => 72585963,
		'Clermont, France' => 25847180,
		'Nancy, France' => 4797115,
		'Besancon, France' => 30854188,
		'Vierzon, France' => 4322989,
		'London, Great Britain' => 144718992,
		'Sheffield, Great Britain' => 93168220,
		'Inverness, Great Britain' => 26717646,
		'Narsaq, Greenland' => 534873941,
		'Budapest, Hungaria' => 34923072,
		'Reykjavik, Iceland' => 22529614,
		'Roma, Italy' => 28604181,
		'Maglie, Italia' => 38705203,
		'Mestre, Italy' => 34694832,
		'Milano, Italy' => 36874245,
		'Caltanissetta, Italy' => 60761631,
		'Cosenza, Italy' => 44290658,
		'Pula, Istria' => 173526459,
		'Riga, Latvia' => 38788862,
		'Vilnius, Lithuania' => 4914187,
		'Luxembourg,Luxembourg' => 133396387,
		'Funchal, Madeira' => 27354062,
		'Maó, Menorca' => 82720519,
		'Amsterdam, Netherlands' => 7382660,
		'Zwolle, Netherlands' => 6504013,
		'Oslo, Norway' => 4394237,
		'Warszawa, Poland' => 272607927,
		'Leczyca, Poland' => 300042735,
		'Gorzow Wielkopolski' => 173113530,
		'Guarda, Portugal' => 121543175,
		'Bucharest, Romania' => 23485603,
		'Russia, Ukraine' => 59193645,
		'Ukraine' => 38723151,
		'Russia II' => 173561216,
		'Russia III' => 111539636,
		'Barcelona, Spain' => 116315120,
		'Benissa, Spain' => 23670051,
		'Cuenca, Spain' => 103975857,
		'Madrid, Spain' => 4680727,
		'Palma de Majorca' => 32694069,
		'Majorca' => 5123287,
		'Stockholm, Sweden' => 39068318,
		'Bern, Switzerland' => 23584688,
		'St. Gallen, Switzerland' => 140561120,
		'Kiew, Ukraine' => 4375099,
	),
	'australia' => array(
		'Melbourne' => 157699828,
		'Sydney' => 5152283,
		'Samoa' => 54773836
	),
	'africa' => array(
		'Algier, Algeria' => 245873840,
		'Ruanda' => 25830659,
		'Lagos, Nigeria' => 93337385,
		'Santa Cruz de Tenerife' => 25458412,
		'Pretoria, South Africa' => 26990144,
		'Marrakesh, Morocco' => 26140716,
		'Tunis, Tunisia' => 26278273,
		'Cairo, Egypt' => 688463107,
		'Antananarivo, Madagascar' => 28916012,
		'Nairobi, Kenya' => 4742016,
		'Saint-Denis, La Reunion' => 49117192,
		'Port Lois, Mauritius' => 22821395,
		'Kinshasa, Kongo' => 4450237,
		'Addis Abeba, Ethiopia' => 8104263,
		'Abijan, Ivory Coast' => 30605308,
		'Mogadishu, Somalia' => 5069961,
		'Doha, Qatar' => 87819718
	),
	'asia' => array(
		'Kuala Lumpur, Malaysia' => 24405048,
		'Seoul, South Korea' => 46622817,
		'Pyongyang, North Korea' => 4918162,
		'Baghdad, Iraq' => 4075154,
		'Damascus, Syria' => 28653226,
		'Dubai, U.A.E.' => 24151186,
		'New Delhi, India' => 5873630,
		'Chelyabinsk, Russia' => 32731560,
		'Khabarovsk, Russia' => 27218733,
		'Workuta, Russia' => 77295285,
		'Hanoi, Vietnam' => 9656730,
		'Colombo, Sri Lanka' => 24791916,
		'Wadomari, China' => 713605137,
		'Jiayuguan, China' => 137428513,
		'Bejing, China' => 152637113,
		'Tokyo, Japan' => 24039781,
		'Higashi-Fukuma, Japan' => 170340108,
		'Sapporo, Japan' => 30705114,
		'Hiroshima, Japan' => 24818479,
		'Amagi, Japan' => 123677727,
		'Amami, Ryukyu Islands, Japan' => 123668804,
		'Amami, Ryukyu Islands, Japan' => 169721884,
		'Amami, Ryukyu Islands, Japan' => 114363315,
		'Aomori, Japan' => 100001887,
		'Yaeyama, Ryukyu Islands, Japan' => 169107858,
		'Miyako, Ryukyu Islands, Japan' => 123674610,
		'Miyako, Ryukyu Islands, Japan' => 168592970,
		'Tanegashima, Japan' => 114084853,
		'Toki, Japan' => 186247943,
		'Japan' => 157629100,
		'Japan' => 115272480,
		'Taipeh, Taiwan' => 48776359,
		'Singapore' => 49961799,
		'Medan, Sumatra' => 34337328,
		'Jakarta, Java' => 28781825,
		'Surabaya, Java' => 28376237,
		'Makassar, Celebes' => 28919409,
		'Dilli, Timor' => 41199461,
		'Lombok' => 263188859,
		'Bali' => 25132045,
		'Bandar Seri Begawan, Borneo' => 46102068,
		'Labuan, Borneo' => 28717158,
		'Pontianak, Borneo' => 186985677,
		'Davao City, Mindanao, Philippines' => 106648663,
		'Puerto Princesa (Palawan island), Philippines' => 36983719,
		'Masbate (Masbate island), Philippines' =>28257030,
		'Cebu City, Philippines' => 77593974,
		'Talubhan, Philippines' => 4276933,
		'Jin Island, Philippines' => 27480514,
		'Wellington, New Zealand' => 121064648,
		'Noumea, New Caledonia' => 37668011,
		'Alotau, Papua New Guinea'=> 252138734,
		'Port Moresby, Papua New Guinea'=> 25179464,
		'Saipan, Northern Mariana Islands' => 237969702,
		'Tinian, Northern Mariana Islands' => 24201962,
		'Rota, Northern Mariana Islands' => 165350700,
		'Viti' => 23812429
	),
	'north america' => array(
		'Seattle, WA' => 4757176,	//47.4648998, -122.2422833
		'Boise, ID' => 13691966,	//43.5895317, -116.2731857
		'Helena, MT' => 37334501,	//46.5973428, -112.0027184
		'Bismarck, ND' => 9737740,	//46.8227032, -100.8320165
		'Minneapolis, MN' => 125438266,	//44.978026, -93.232939
		'Madison, WI' => 38460764,	//43.0663626, -89.277777
		'Lansing, MI' => 32729219,	//42.725782, -84.5473576
		'Albany, NY' => 5566703,	//42.650337, -73.748229
		'Boston, MA' => 9126212,	//42.3565072, -71.1842717
		'Salem, OR' => 29164563,	//44.9283728, -122.9901275
		'Cheyenne, WY' => 15736905,	//41.0617686, -104.8754388
		'Pierre, SD' => 9921998,	//44.354112, -100.370616
		'Santa Clara, CA' => 28433666,	//37.2950053, -121.8729502
		'Sacramento, CA' => 10527056,	//38.576462, -121.485926
		'Reno, NV' => 32776300,	//39.5367148, -119.8036587
		'Salt Lake City, UT' => 639942544, //40.7658591, -111.9348698
		'Denver, CO' => 37356219,	//39.7909665, -104.9884513
		'Lincoln, NE' => 51804962,	//40.8204001, -96.9105351
		'Topeka, KS' => 13251159,	//38.8693256, -95.8354975
		'Des Moines, IA' => 34105509,	//41.5953603, -93.6154316
		'Springfield, IL' => 22012674,	//39.8005698, -89.6478647
		'Indianapolis, IN' => 95290523,	//39.773804, -86.142797
		'Hammond, IN' => 51878120,	//41.5863006, -87.4808194
		'Columbus, OH' => 28827020,	//39.958635, -83.01838
		'Williamsport, PA' => 11999251,	//41.2366445, -76.996752
		'Muddy Creek, PA' => 464381509,
		'Philadelphia, NJ' => 27053603,	//39.982139, -74.797372
		'Louiseville, KY' => 34149669,	//38.2192664, -85.4325223
		'Elizabethtown, KY' => 252840561,//37.691664, -85.861506
		'Cumberland, KY' => 116434750,	//37.000000, -83.000000
		'Charleston, WV' => 15636322,	//38.4298094, -81.8248983
		'Richmond, VA' => 38232904,	//37.536227, -77.4293533
		'Roanoke, VA' => 44017746,	//37.263769, -79.937871
		'Winchester, VA' => 20616715,	//39.183713, -78.164157
		'Phenix, AZ' => 37439180,	//33.4294139, -112.0827328
		'Santa Fe, NM' => 14612892,	//35.502096, -106.248635
		'Houston, TX' => 15446151,	//29.7360998, -95.3685672
		'Lubbock, TX' => 44530340,	//33.594531, -101.855227
		'Oklahoma City, OK' => 103284921,//35.4628604, -97.4875224
		'Little Rock, AR' => 38287741,	//34.6403829, -92.4451611
		'Baton Rouge, LA' => 12625704,	//30.4391683, -91.1829017
		'Jackson, MS' => 30493865,	//32.2857821, -90.2153904
		'Atlanta, GA' => 28851747,	//33.691751, -84.402198
		'Raleigh, NC' => 18898636,	//35.755875, -78.657918
		'Asheville, NC' => 43596376,	//35.5884879, -82.5254032
		'Columbia, SC' => 158977007,	//34.0101336, -81.0625124
		'Augusta, SC' => 26944246,	//33.4690182, -81.9588167
		'Tallahassee, FL' => 11049098,	//30.483967, -84.041192
		'Orlando, FL' => 32075497,	//28.4792, -81.44891
		'Hartford, CT' => 22772677,	//41.7617184, -72.706668
		'Nashville, TN' => 49577554,	//36.1490548, -86.7802671
		'Knoxville, TN' => 49826007, //35.959582, -83.960007
		'Honolulu, HI' => 32005193,	//21.30547, -157.84595
		'Mauna Loa, HI' => 208345015,
		'Kauai, HI' => 45812082,
		'Kahului, HI' => 44711139,
		'Hilo, HI' => 45622102,
		'New Orleans, LA' => 12689320,	//29.95869, -90.077
		'Duluth, Canada' => 177041645,
		'Canada' => 146832708,
		'Mexico City' => 24723212,
		'Panama City' => 29425444,
		'Tuxtla Gutierrez, Mexico' => 38049141,
		'La Paz, Baja California' => 24503430,
		'Havanna, Cuba' => 38448366,
		'Ottawa, Canada' => 76434558,
		'Quebec, Canada' => 16229066,
		'Quebec, Canada' => 32982539,
		'Calgary, Canada' => 5386816,
		'Campbell River, Canada' => 62099057,
		'Cortes Island, Canada' => 40559534,
		'Santo Domingo, Dominican Republic' => 154964833,
		'Ile de la Gonave, Haiti' => 49014482,
		'San Juan, Puerto Rico' => 22231517,
		'Isla de Vieques' => 22256504,
		'Virgin Islands 1' => 38291731,
		'Virgin Islands 2' => 219639915,
		'Virgin Islands 3' => 24434624,
		'Saint Croix' => 257198715,
		'Basseterre' => 24762738,
		'All Saints' => 13511960,
		'Baie Mahault' => 27266571,
		'Roseau' => 26296017,
		'Dillon, Matinique' => 52035824,
		'St. Lucia' => 25578440,
		'Saint Vincent' => 25577931,
		'St. George\'s' => 25570824,
		'Tobago' => 15242966,
		'Trinidad' => 37576224,
		'Jamaica, Kingston' => 325848380,
		'Cayman Islands' => 65739646,
		'Cayman Islands' => 25782267,
		'Cayman Islands' => 68794348
	),
	'south america' => array(
		'Lima, Peru' => 338546493,
		'Iquitos, Peru' => 33297573,
		'Fortaleza, Brazil' => 23340440,
		'Bogota, Colombia' => 541116576,
		'Sao Paulo, Brazil' => 4615026,
		'Recife, Brazil' => 30811117,
		'Belem, Brazil' => 25171652,
		'Santiago, Chile' => 15729697,
		'Caracas, Venezuela' => 171440996,
		'Georgetown, Guyana' => 30755813,
		'Pt. Stanley, Falkland Islands' => 14143339,
		'Paramaribo, Suriname' => 4383404,
		'Cayenne, Fr. Guyana' => 34316537,
		'Bridgetown, Barbados' => 25679744,
		'Oranjestad, Aruba' => 354862415,
		'Willemstad, Curaçao' => 30383830,
		'Kralendijk, Bonaire' => 25567887,
		'Nassau, New Providence, Bahamas' => 377933292,
		'Freeport, Grand Bahama, Bahamas' => 178027131
	)
);


if (isset($argv) && $argc=2 && $argv[1]=='checkways') {

	exit (checkways());

}

// helper function for checking if all starting ways still exist and all schemas have starting points
function checkways() {
	global $islands;
	require_once('../config/schemas.php');
	$points=array();

	echo "checking starting ways for existence\n";
	foreach ($islands as $island=>$ways) foreach ($ways as $dontcare=>$way) {

		// query way data via API
		$response = file_get_contents("http://www.openstreetmap.org/api/0.6/way/$way");

		// you get http error "410: Gone" if the way doesn't exist any more
		if (!(strlen($response)>1))
			echo "missing way $way\n";

		// wait for 0.5 seconds
		usleep(500000);

		$xml = new SimpleXMLElement($response);

		// query first-node data via API
		$response = file_get_contents("http://www.openstreetmap.org/api/0.6/node/" . $xml->way[0]->nd[0]['ref']);
		$xml = new SimpleXMLElement($response);
		
		$points[]=array('lat' => $xml->node[0]['lat'], 'lon' => $xml->node[0]['lon']);

		// wait for 0.5 seconds
		usleep(500000);
	}

	echo "checking if all schemas have at least one starting point\n";
	foreach($schemas as $schema=>$s) {
		foreach ($points as $dontcare=>$point) {

			if ($point['lat']>=$s['bottom'] && $point['lat']<=$s['top'] && $point['lon']>=$s['left'] && $point['lon']<=$s['right'])
				continue(2);

		}
		echo "schema without starting point: $schema\n";
	}
}



// include all ways tagged as highway (but exclude emergency_access_points and
// highway=construction as they are left unconnected intentionally),
// highway=services is applicable on nodes or areas, not "highways",
// route=ferry or railway=platform (a platform may connect roads in rare cases),
// don't forget about parking spaces as they can connect roads in some cases.
// include furthermore ways that are part of a route=ferry relation even
// though the ways themselves are not tagged as ferry
query("DROP TABLE IF EXISTS _tmp_ways", $db1);
query("
	CREATE UNLOGGED TABLE _tmp_ways AS
	SELECT wt.way_id FROM way_tags wt WHERE (
		(wt.k='highway' AND wt.v NOT IN ('emergency_access_point', 'construction', 'services', 'preproposed', 'proposed', 'rest_area', 'stopline')) OR
		(wt.k='route' AND wt.v='ferry') OR
		(wt.k='man_made' AND wt.v='pier') OR
		(wt.k='aeroway' AND wt.v IN ('taxiway', 'runway', 'apron')) OR
		(wt.k='amenity' AND wt.v='parking') OR
		(wt.k IN ('railway', 'public_transport') AND wt.v='platform')
	)

	UNION

	SELECT rm.member_id
	FROM relation_members rm
	WHERE rm.member_type='W' AND rm.relation_ID IN (
		SELECT rt.relation_id
		FROM relation_tags rt
		WHERE rt.k='route' AND rt.v='ferry'
	)
", $db1);

query("CREATE INDEX idx_tmp_ways_way_id ON _tmp_ways (way_id)", $db1);
query("ANALYZE _tmp_ways", $db1);


// leave out intermediate-nodes that don't interest anybody:
// just these nodes are important, that are used at least twice
// in way_nodes (aka junctions)
// select nodes of ways (and ferries) used at least twice
query("DROP TABLE IF EXISTS _tmp_junctions", $db1);
query("
	CREATE UNLOGGED TABLE _tmp_junctions AS
	SELECT wn.node_id
	FROM way_nodes wn INNER JOIN _tmp_ways w USING (way_id)
	GROUP BY wn.node_id
	HAVING COUNT(DISTINCT wn.way_id)>1
", $db1);

query("CREATE UNIQUE INDEX idx_tmp_junctions_node_id ON _tmp_junctions (node_id)", $db1);
query("ANALYZE _tmp_junctions", $db1);


// first of all find ways that don't have any connection with
// any other way. (these ways are not covered by the rest of the algorithm)
// in _tmp_junctions we only see nodes that are used at least twice
// not finding a record in _tmp_junctions means the way is a not connected way

// don't complain about amenity=parking ways or piers; they are included here only for connecting highways
// the same is valid for ways on airports (aeroway = taxiway|runway|apron)

query("
	INSERT INTO _tmp_errors (error_type, object_type, object_id, msgid, last_checked)
	SELECT DISTINCT $error_type, CAST('way' AS type_object_type), w.way_id, 'This way is not connected to the rest of the map', NOW()
	FROM _tmp_ways w
	WHERE NOT EXISTS (

		SELECT wn.node_id FROM
		way_nodes wn INNER JOIN _tmp_junctions j USING (node_id)
		WHERE wn.way_id=w.way_id
	) AND NOT EXISTS (

		SELECT wt.way_id FROM way_tags wt WHERE (
			wt.way_id=w.way_id AND (
				(wt.k='man_made' AND wt.v='pier') OR
				(wt.k='aeroway' AND wt.v IN ('taxiway', 'runway', 'apron')) OR
				(wt.k='amenity' AND wt.v='parking') OR
				(wt.k IN ('railway', 'public_transport') AND wt.v='platform')
			)
		)
	)
", $db1);


// this is our optimized (==reduced) version of way_nodes with junctions only
query("DROP TABLE IF EXISTS _tmp_wn", $db1, false);
query("
	CREATE UNLOGGED TABLE _tmp_wn AS
	SELECT wn.way_id, j.node_id
	FROM _tmp_junctions j INNER JOIN way_nodes wn USING (node_id)
	INNER JOIN _tmp_ways w ON (wn.way_id=w.way_id)
", $db1);
query("CREATE INDEX idx_tmp_wn_node_id ON _tmp_wn (node_id)", $db1);
query("CREATE INDEX idx_tmp_wn_way_id ON _tmp_wn (way_id)", $db1);
query("ANALYZE _tmp_wn", $db1);

// store the newly found way ids for the current round
query("DROP TABLE IF EXISTS _tmp_ways_found_now ", $db1, false);
query("
	CREATE UNLOGGED TABLE _tmp_ways_found_now (
	way_id bigint NOT NULL default 0,
	PRIMARY KEY (way_id)
	)
", $db1);

// store the way ids that were already known from the last rounds
query("DROP TABLE IF EXISTS _tmp_ways_found_before", $db1, false);
query("
	CREATE UNLOGGED TABLE _tmp_ways_found_before (
	way_id bigint NOT NULL default 0,
	PRIMARY KEY (way_id)
	)
", $db1);

// temporary table used for newly found nodes
query("DROP TABLE IF EXISTS _tmp_nodes", $db1, false);
query("
	CREATE UNLOGGED TABLE _tmp_nodes (
	node_id bigint NOT NULL default 0
	)
", $db1);
query("CREATE INDEX idx_tmp_nodes_node_id ON _tmp_nodes (node_id)", $db1);


// add starting way_ids that are part of islands
$sql = "INSERT INTO _tmp_ways_found_now (way_id) VALUES ";
foreach ($islands as $island=>$ways) foreach ($ways as $dontcare=>$way) $sql.="($way),";

query(substr($sql, 0, -1), $db1);
query("INSERT INTO _tmp_ways_found_before SELECT way_id FROM _tmp_ways_found_now ", $db1);
$analyze_counter=0;
do {
	// first find nodes that belong to ways found in the last round
	// it is sufficient to only consider ways found during the round before here!
	query("TRUNCATE TABLE _tmp_nodes", $db1, false);
	query("
		INSERT INTO _tmp_nodes (node_id)
		SELECT DISTINCT wn.node_id
		FROM _tmp_ways_found_now w INNER JOIN _tmp_wn wn USING (way_id)
	", $db1, false);
	if (++$analyze_counter % 10 == 0) query("ANALYZE _tmp_nodes", $db1, false);

	// remove ways of last round
	query("TRUNCATE TABLE _tmp_ways_found_now ", $db1, false);

	// insert ways that are connected to nodes found before. these make the starting
	// set for the next round
	$result=query("
		INSERT INTO _tmp_ways_found_now (way_id)
		SELECT DISTINCT wn.way_id
		FROM (_tmp_wn wn INNER JOIN _tmp_nodes n USING (node_id)) LEFT JOIN _tmp_ways_found_before w ON wn.way_id=w.way_id
		WHERE w.way_id IS NULL
	", $db1, false);
	$count=pg_affected_rows($result);
	if ($analyze_counter % 10 == 0) {
		query("ANALYZE _tmp_ways_found_now ", $db1, false);
		query("ANALYZE _tmp_ways_found_before", $db1, false);
	}

	// finally add newly found ways in collector table containing all ways
	query("INSERT INTO _tmp_ways_found_before SELECT way_id FROM _tmp_ways_found_now ", $db1, false);
	echo "found $count additional ways\n";
} while ($count>0);



// any way that exists in way-temp-table but is not member of any island is an error
// don't complain about amenity=parking ways; they are included here only for connecting highways
// the same for platforms and aeroway-related items
query("
	INSERT INTO _tmp_errors (error_type, object_type, object_id, msgid, last_checked)
	SELECT DISTINCT $error_type, CAST('way' AS type_object_type), wn.way_id, 'This way is not connected to the rest of the map', NOW()
	FROM _tmp_wn wn LEFT JOIN _tmp_ways_found_before w USING (way_id)
	WHERE w.way_id IS NULL AND NOT EXISTS (

		SELECT wt.way_id FROM way_tags wt WHERE (
			wt.way_id=wn.way_id AND (
				(wt.k='man_made' AND wt.v='pier') OR
				(wt.k='aeroway' AND wt.v IN ('taxiway', 'runway', 'apron')) OR
				(wt.k='amenity' AND wt.v='parking') OR
				(wt.k IN ('railway', 'public_transport') AND wt.v='platform')
			)
		)
	)
", $db1);



print_index_usage($db1);

query("DROP TABLE IF EXISTS _tmp_ways", $db1, false);
query("DROP TABLE IF EXISTS _tmp_nodes", $db1, false);
query("DROP TABLE IF EXISTS _tmp_junctions", $db1, false);
query("DROP TABLE IF EXISTS _tmp_island_members", $db1, false);
query("DROP TABLE IF EXISTS _tmp_wn", $db1, false);
query("DROP TABLE IF EXISTS _tmp_ways_found_now ", $db1, false);
query("DROP TABLE IF EXISTS _tmp_ways_found_before", $db1, false);

?>
