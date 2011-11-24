<?
class tree {
	// Structure table and fields
	var $s_table	= "";
	var $s_fields	= array(
			"id"		=> false,
			"parent_id"	=> false,
			"position"	=> false,
			"left"		=> false,
			"right"		=> false,
			"level"		=> false
		);

	// Additional fields (stored in format `table_name.field_name`)
	var $d_fields	= array();

	// Tree type (or types)
	var $adjacency	= false;
	var $nestedset	= false;

	// Database
	var $db			= false;

	// Constructor
	function __construct($tables = array()) {
		if(!is_array($tables) || !count($tables)) return;
		foreach($tables as $table_name => $fields) {
			if(is_array($fields)) {
				foreach($fields as $key => $field) {
					switch($key) {
						case "id":
						case "parent_id":
						case "position":
						case "left":
						case "right":
						case "level":
							$this->s_table = $table_name;
							$this->s_fields[$key] = $field;
							break;
						default:
							$this->d_fields[] = $table_name.".".$field;
							break;
					}
				}
			}
		}

		// Determine what kind of a tree is used (or both)
		if($this->s_fields["id"] && $this->s_fields["position"])														$this->adjacency = true;
		if($this->s_fields["id"] && $this->s_fields["left"] && $this->s_fields["right"] && $this->s_fields["level"])	$this->nestedset = true;

		// Database
		$this->db = new DB;
	}
	function tree($tables = array()) { return $this->__construct($tables); } // PHP 4 compatibilty

	// WRITING FUNCTIONS
	// Function for moving nodes
	// ID is the node that is being moved - 0 is creating a new NODE
	// REF_ID is the reference node in the move
	// TYPE is one of "after", "before" or "inside"
	function move($id, $ref_id, $type, $mode = "move") {
		if(!in_array($type, array("after", "before", "inside"))) return false;

		// Queries executed at the end
		$sql	= array();

		if(!(int)$id) $mode = "create";

		if($mode == "create") {
			// Fields and values that will be inserted
			$fields	= array();
			$values	= array();
			// Inserting an ID
			$fields[] = "`".$this->s_fields["id"]."`";
			$values[] = "NULL";
		}

		// If the tree maintains an ID->PARENT_ID relation
		if($this->adjacency) {
			$this->db->query("SELECT `".$this->s_fields["parent_id"]."`, `".$this->s_fields["position"]."` FROM `".$this->s_table."` WHERE `".$this->s_fields["id"]."` = ".(int)$ref_id);
			$this->db->nextr();

			// Determine new parent and position
			if($type == "inside") {
				$new_parent_id = $ref_id;
				$new_position = 1;
			}
			else {
				$new_parent_id = (int)$this->db->f(0);
				if($type == "before")	$new_position = $this->db->f(1);
				if($type == "after")	$new_position = $this->db->f(1) + 1;
			}

			// Cleanup old parent
			if($mode == "create") {
				$old_parent_id	= -1;
				$old_position	= 0;
			}
			else {
				$this->db->query("SELECT `".$this->s_fields["parent_id"]."`, `".$this->s_fields["position"]."` FROM `".$this->s_table."` WHERE `".$this->s_fields["id"]."` = ".(int)$id);
				$this->db->nextr();
				$old_parent_id	= $this->db->f(0);
				$old_position	= $this->db->f(1);
			}

			// A reorder was made
			if($old_parent_id == $new_parent_id) {
				if($new_position > $old_position) {
					$new_position = $new_position - 1;
					$sql[] = "UPDATE `".$this->s_table."` SET `".$this->s_fields["position"]."` = `".$this->s_fields["position"]."` - 1 WHERE `".$this->s_fields["parent_id"]."` = ".$old_parent_id." AND `".$this->s_fields["position"]."` BETWEEN ".($old_position + 1)." AND ".$new_position;
				}
				if($new_position < $old_position) {
					$sql[] = "UPDATE `".$this->s_table."` SET `".$this->s_fields["position"]."` = `".$this->s_fields["position"]."` + 1 WHERE `".$this->s_fields["parent_id"]."` = ".$old_parent_id." AND `".$this->s_fields["position"]."` BETWEEN ".$new_position." AND ".($old_position - 1);
				}
			}
			else {
				// Fix old parent (move siblings up)
				$sql[] = "UPDATE `".$this->s_table."` SET `".$this->s_fields["position"]."` = `".$this->s_fields["position"]."` - 1 WHERE `".$this->s_fields["parent_id"]."` = ".$old_parent_id." AND `".$this->s_fields["position"]."` > ".$old_position;
				// Prepare new parent (move sibling down)
				$sql[] = "UPDATE `".$this->s_table."` SET `".$this->s_fields["position"]."` = `".$this->s_fields["position"]."` + 1 WHERE `".$this->s_fields["parent_id"]."` = ".$new_parent_id." AND `".$this->s_fields["position"]."` >".($type != "after" ? "=" : "")." ".$new_position;
			}
			// Move the node to the new position
			if($mode == "create") {
				$fields[] = "`".$this->s_fields["parent_id"]."`";
				$fields[] = "`".$this->s_fields["position"]."`";
				$values[] = $new_parent_id;
				$values[] = $new_position;
			}
			else {
				$sql[] = "UPDATE `".$this->s_table."` SET `".$this->s_fields["position"]."` = ".$new_position.", `".$this->s_fields["parent_id"]."` = ".$new_parent_id." WHERE `".$this->s_fields["id"]."` = ".(int)$id;
			}
		}

		// If the tree maintains a nested set
		if($this->nestedset) {
			$this->db->query("SELECT `".$this->s_fields["id"]."` AS id, `".$this->s_fields["left"]."` AS lft, `".$this->s_fields["right"]."` AS rgt, `".$this->s_fields["level"]."` AS lvl FROM `".$this->s_table."` WHERE `".$this->s_fields["id"]."` IN(".(int)$id.",".(int)$ref_id.")");
			while($this->db->nextr()) {
				if($id == $this->db->f("id")) {
					$nod_lft = (int)$this->db->f("lft");
					$nod_rgt = (int)$this->db->f("rgt");
					$dif = $nod_rgt - $nod_lft + 1;
				}
				if($ref_id == $this->db->f("id")) {
					$ref_lft = (int)$this->db->f("lft");
					$ref_rgt = (int)$this->db->f("rgt");
					$ref_lvl = (int)$this->db->f("lvl");
				}
			}

			if($mode == "move") {
				$sql[] = "UPDATE `".$this->s_table."` SET `".$this->s_fields["left"]."` = `".$this->s_fields["left"]."` - ".$dif." WHERE `".$this->s_fields["left"]."` > ".$nod_rgt;
				$sql[] = "UPDATE `".$this->s_table."` SET `".$this->s_fields["right"]."` = `".$this->s_fields["right"]."` - ".$dif." WHERE `".$this->s_fields["right"]."` > ".$nod_rgt;
				if($ref_lft > $nod_rgt) $ref_lft -= $dif;
				if($ref_rgt > $nod_rgt) $ref_rgt -= $dif;
			}
			else $dif = 2;

			$ids = array();
			if($mode == "move") {
				$this->db->query("SELECT `".$this->s_fields["id"]."` FROM `".$this->s_table."` WHERE `".$this->s_fields["left"]."` >= ".$nod_lft." AND `".$this->s_fields["right"]."` <= ".$nod_rgt);
				while($this->db->nextr()) $ids[] = (int)$this->db->f(0);
			} 
			else $ids[] = -1;

			switch($type) {
				case "before":
					$sql[] = "UPDATE `".$this->s_table."` SET `".$this->s_fields["left"]."` = `".$this->s_fields["left"]."` + ".$dif." WHERE `".$this->s_fields["left"]."` >= ".$ref_lft." AND `".$this->s_fields["id"]."` NOT IN(".implode(",",$ids).") ";
					$sql[] = "UPDATE `".$this->s_table."` SET `".$this->s_fields["right"]."` = `".$this->s_fields["right"]."` + ".$dif." WHERE `".$this->s_fields["right"]."` > ".$ref_lft." AND `".$this->s_fields["id"]."` NOT IN(".implode(",",$ids).") ";
					if($mode == "move") {
						$dif = $ref_lft - $nod_lft;
						$sql[] = "UPDATE `".$this->s_table."` SET `".$this->s_fields["level"]."` = ".(int)$ref_lvl.", `".$this->s_fields["left"]."` = `".$this->s_fields["left"]."` + (".$dif."), `".$this->s_fields["right"]."` = `".$this->s_fields["right"]."` + (".$dif.") WHERE `".$this->s_fields["id"]."` IN (".implode(",",$ids).") ";
					}
					else {
						$fields[] = "`".$this->s_fields["level"]."`";
						$fields[] = "`".$this->s_fields["left"]."`";
						$fields[] = "`".$this->s_fields["right"]."`";
						$values[] = (int)$ref_lvl;
						$values[] = (int)$ref_lft;
						$values[] = ((int)$ref_lft + 2);
					}
					break;
				case "after":
					$sql[] = "UPDATE `".$this->s_table."` SET `".$this->s_fields["left"]."` = `".$this->s_fields["left"]."` + ".$dif." WHERE `".$this->s_fields["left"]."` > ".$ref_rgt." AND `".$this->s_fields["id"]."` NOT IN(".implode(",",$ids).") ";
					$sql[] = "UPDATE `".$this->s_table."` SET `".$this->s_fields["right"]."` = `".$this->s_fields["right"]."` + ".$dif." WHERE `".$this->s_fields["right"]."` > ".$ref_rgt." AND `".$this->s_fields["id"]."` NOT IN(".implode(",",$ids).") ";
					if($mode == "move") {
						$dif = ($ref_rgt + 1) - $nod_lft;
						$sql[] = "UPDATE `".$this->s_table."` SET `".$this->s_fields["level"]."` = ".(int)$ref_lvl.", `".$this->s_fields["left"]."` = `".$this->s_fields["left"]."` + (".$dif."), `".$this->s_fields["right"]."` = `".$this->s_fields["right"]."` + (".$dif.") WHERE `".$this->s_fields["id"]."` IN (".implode(",",$ids).") ";
					} else {
						$fields[] = "`".$this->s_fields["level"]."`";
						$fields[] = "`".$this->s_fields["left"]."`";
						$fields[] = "`".$this->s_fields["right"]."`";
						$values[] = (int)$ref_lvl;
						$values[] = ((int)$ref_rgt + 1);
						$values[] = ((int)$ref_rgt + 3);
					}
					break;
				case "inside":
				default:
					$sql[] = "UPDATE `".$this->s_table."` SET `".$this->s_fields["left"]."` = `".$this->s_fields["left"]."` + ".$dif." WHERE `".$this->s_fields["left"]."` > ".$ref_lft." AND `".$this->s_fields["id"]."` NOT IN(".implode(",",$ids).") ";
					$sql[] = "UPDATE `".$this->s_table."` SET `".$this->s_fields["right"]."` = `".$this->s_fields["right"]."` + ".$dif." WHERE `".$this->s_fields["right"]."` > ".$ref_lft." AND `".$this->s_fields["id"]."` NOT IN(".implode(",",$ids).") ";
					if($mode == "move") {
						$dif = ($ref_lft + 1) - $nod_lft;
						$sql[] = "UPDATE `".$this->s_table."` SET `".$this->s_fields["level"]."` = ".(int)($ref_lvl + 1).", `".$this->s_fields["left"]."` = `".$this->s_fields["left"]."` + (".$dif."), `".$this->s_fields["right"]."` = `".$this->s_fields["right"]."` + (".$dif.") WHERE `".$this->s_fields["id"]."` IN (".implode(",",$ids).") ";
					}
					else {
						$fields[] = "`".$this->s_fields["level"]."`";
						$fields[] = "`".$this->s_fields["left"]."`";
						$fields[] = "`".$this->s_fields["right"]."`";
						$values[] = ((int)$ref_lvl + 1);
						$values[] = ((int)$ref_lft + 1);
						$values[] = ((int)$ref_lft + 3);
					}
					break;
			}
		}
		
		// If creating a new node
		if($mode == "create") $sql[] = "INSERT INTO `".$this->s_table."` (".implode(",",$fields).") VALUES (".implode(",",$values).")";

		// Applying all changes - there should be a transaction here
		foreach($sql as $q) { $this->db->query($q); }

		if($mode == "create") return mysql_insert_id();
	}

	// Function for removing nodes
	// ID is the node (or array of nodes) that is being removed
	function remove($id) {
		if(is_array($id)) {
			foreach($id as $i) { $this->remove($i); }
			return;
		}
		if(!(int)$id) return false;

		// Take care of nested sets (and adjacency at the same time if applicable)
		if($this->nestedset) {
			$this->db->query("SELECT `".$this->s_fields["left"]."` AS lft, `".$this->s_fields["right"]."` AS rgt ".( ($this->adjacency) ? " , `".$this->s_fields["parent_id"]."` AS pid, `".$this->s_fields["position"]."` AS pos " : "" )." FROM `".$this->s_table."` WHERE `".$this->s_fields["id"]."` = ".(int)$id);
			$this->db->nextr();
			if($this->adjacency) {
				$pid = (int)$this->db->f("pid");
				$pos = (int)$this->db->f("pos");
			}
			$lft = (int)$this->db->f("lft");
			$rgt = (int)$this->db->f("rgt");
			$dif = $rgt - $lft + 1;

			$this->db->query("DELETE FROM `".$this->s_table."` WHERE `".$this->s_fields["left"]."` >= ".$lft." AND `".$this->s_fields["right"]."` <= ".$rgt);
			$this->db->query("UPDATE `".$this->s_table."` SET `".$this->s_fields["left"]."` = `".$this->s_fields["left"]."` - ".$dif." WHERE `".$this->s_fields["left"]."` > ".$rgt);
			$this->db->query("UPDATE `".$this->s_table."` SET `".$this->s_fields["right"]."` = `".$this->s_fields["right"]."` - ".$dif." WHERE `".$this->s_fields["right"]."` > ".$lft);
			if($this->adjacency) {
				$this->db->query("UPDATE `".$this->s_table."` SET `".$this->s_fields["position"]."` = `".$this->s_fields["position"]."` - 1 WHERE `".$this->s_fields["parent_id"]."` = ".$pid." AND `".$this->s_fields["position"]."` > ".$pos);
			}
			return;
		}
		// Only end up here if the tree is adjacency only
		if($this->adjacency) {
			$this->db->query("SELECT `".$this->s_fields["parent_id"]."` AS pid, `".$this->s_fields["position"]."` AS pos FROM `".$this->s_table."` WHERE `".$this->s_fields["id"]."` = ".(int)$id);
			$this->db->nextr();
			$pid = (int)$this->db->f("pid");
			$pos = (int)$this->db->f("pos");

			$tmp = array($id);
			$ids = array($id);
			while(count($tmp)) {
				$t = array_shift($tmp);
				if($t) {
					$this->db->query("SELECT `".$this->s_fields["id"]."` FROM `".$this->s_table."` WHERE `".$this->s_fields["parent_id"]."` = ".(int)$t);
					while($this->db->nextr()) { 
						array_push($ids, $this->db->f(0));
						array_push($tmp, $this->db->f(0));
					}
				}
			}
			$this->db->query("DELETE FROM `".$this->s_table."` WHERE `".$this->s_fields["id"]."` IN (".implode(",",$ids).")");
			$this->db->query("UPDATE `".$this->s_table."` SET `".$this->s_fields["position"]."` = `".$this->s_fields["position"]."` - 1 WHERE `".$this->s_fields["parent_id"]."` = ".$pid." AND `".$this->s_fields["position"]."` > ".$pos);
		}
	}

	function reconstruct() {
		if(!$this->adjacency || !$this->nestedset) return;

		// не знам защо да не е persistent
		$this->db->pcn = false;

		$q = "CREATE TEMPORARY TABLE temp_tree (".$this->s_fields["id"]." INTEGER NOT NULL, ".$this->s_fields["parent_id"]." INTEGER NOT NULL, ". $this->s_fields["position"]." INTEGER NOT NULL) type=HEAP";
		$this->db->query($q);

		$q = "INSERT INTO temp_tree SELECT ".$this->s_fields["id"].", ".$this->s_fields["parent_id"].", ".$this->s_fields["position"]." FROM ".$this->s_table;
		$this->db->query($q);


		$q = "CREATE TEMPORARY TABLE temp_stack (".$this->s_fields["id"]." INTEGER NOT NULL, ".$this->s_fields["left"]." INTEGER, ".$this->s_fields["right"]." INTEGER, ".$this->s_fields["level"]." INTEGER, stack_top INTEGER NOT NULL, ".$this->s_fields["parent_id"]." INTEGER, ".$this->s_fields["position"]." INTEGER) type=HEAP";
		$this->db->query($q);
		$counter = 2;

		$q = "SELECT COUNT(*) as maxcounter FROM temp_tree";
		$this->db->query($q);
		$this->db->nextr();
		$maxcounter = (int) $this->db->f("maxcounter") * 2;
		$currenttop = 1;

		$q = "INSERT INTO temp_stack SELECT ".$this->s_fields["id"].", 1, NULL, 0, 1, ".$this->s_fields["parent_id"].", ".$this->s_fields["position"]." FROM temp_tree WHERE ".$this->s_fields["parent_id"]." = 0";
		$this->db->query($q);

		$q = "DELETE FROM temp_tree WHERE ".$this->s_fields["parent_id"]." = 0";
		$this->db->query($q);

		while ($counter <= $maxcounter) {
			$q = "SELECT temp_tree.".$this->s_fields["id"]." AS tempmin, temp_tree.".$this->s_fields["parent_id"]." AS pid, temp_tree.".$this->s_fields["position"]." AS lid FROM temp_stack, temp_tree WHERE temp_stack.".$this->s_fields["id"]." = temp_tree.".$this->s_fields["parent_id"]." AND temp_stack.stack_top = ".$currenttop." ORDER BY temp_tree.".$this->s_fields["position"]." ASC LIMIT 1";
			$this->db->query($q);

			if ($this->db->nextr()) {
				$tmp = $this->db->f("tempmin");

				$q = "INSERT INTO temp_stack (stack_top, ".$this->s_fields["id"].", ".$this->s_fields["left"].", ".$this->s_fields["right"].", ".$this->s_fields["level"].", ".$this->s_fields["parent_id"].", ".$this->s_fields["position"].") VALUES(".($currenttop + 1).", ".$tmp.", ".$counter.", NULL, ".$currenttop.", ".$this->db->f("pid").", ".$this->db->f("lid").")";
				$this->db->query($q);
				$q = "DELETE FROM temp_tree WHERE ".$this->s_fields["id"]." = ".$tmp;
				$this->db->query($q);
				$counter++;
				$currenttop++;
			}
			else {
				$q = "UPDATE temp_stack SET ".$this->s_fields["right"]." = ".$counter.", stack_top = -stack_top WHERE stack_top = ".$currenttop;
				$this->db->query($q);
				$counter++;
				$currenttop--;
			}
		}

		$q = "TRUNCATE TABLE ".$this->s_table;
		$this->db->query($q);

		$q = "INSERT INTO ".$this->s_table." SELECT ".$this->s_fields["id"].", ".$this->s_fields["parent_id"].", ".$this->s_fields["position"].", ".$this->s_fields["left"].", ".$this->s_fields["right"].", ".$this->s_fields["level"]." FROM temp_stack ORDER BY ".$this->s_fields["id"];
		$this->db->query($q);
	}

	function analyze() {
		$this->errors = array();
		if($this->adjacency) {
			$this->db->query("SELECT COUNT(*) FROM ".$this->s_table." s WHERE ".$this->s_fields["parent_id"]." != 0 AND (SELECT COUNT(*) FROM ".$this->s_table." WHERE ".$this->s_fields["id"]." = s.".$this->s_fields["parent_id"].") = 0 ");
			$this->db->nextr();
			if($this->db->f(0) > 0) $this->errors[] = "Missing parents.";
		}
		if($this->nestedset) {
			$this->db->query("SELECT MAX(".$this->s_fields["right"].") FROM ".$this->s_table);
			$this->db->nextr();
			$n = $this->db->f(0);
			$this->db->query("SELECT COUNT(*) FROM ".$this->s_table);
			$this->db->nextr();
			$c = $this->db->f(0);
			if($n/2 != $c) $this->errors[] = "Right index does not match node count.";
		}
		if($this->adjacency && $this->nestedset) {
			$this->db->query("SELECT COUNT(".$this->s_fields["id"].") FROM ".$this->s_table." s WHERE (SELECT COUNT(*) FROM ".$this->s_table." WHERE ".$this->s_fields["right"]." < s.".$this->s_fields["right"]." AND ".$this->s_fields["left"]." > s.".$this->s_fields["left"]." AND ".$this->s_fields["level"]." = s.".$this->s_fields["level"]." + 1) != (SELECT COUNT(*) FROM ".$this->s_table." WHERE ".$this->s_fields["parent_id"]." = s.".$this->s_fields["id"].") ");
			$this->db->nextr();
			if($this->db->f(0) > 0) $this->errors = "Adjacency and nested set do not match.";
		}
		return $error;
	}
}
?>