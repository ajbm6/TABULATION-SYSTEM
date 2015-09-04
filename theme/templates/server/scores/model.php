<?php
require_once '../../server/connection.php';

class ScoreModel {

	function __construct(){
    }

	public static function create($data){
		$config= new Config();

		$mysqli = new mysqli($config->host, $config->user, $config->pass, $config->db);
		if ($mysqli->connect_errno) {
		    print json_encode(array('success' =>false,'status'=>400,'msg' =>'Failed to connect to MySQL: (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error));
		    return;
		}else{

			$criteriaid = $mysqli->real_escape_string($data['criteriaid']);
			$contestantid = $mysqli->real_escape_string($data['contestantid']);
			$scoring = $mysqli->real_escape_string($data['scoring']);

			$result = $mysqli->query("INSERT INTO scores(eventid,judgeid,criteriaid,score,contestantid)
										values ( (SELECT eventid FROM contestants WHERE contestantid=$contestantid LIMIT 1),
			        					(SELECT judgeid FROM judges WHERE eventid = (SELECT eventid FROM contestants WHERE contestantid=$contestantid LIMIT 1) LIMIT 1),
			        					$criteriaid,$scoring,$contestantid)");

			if ($result) {
				return print json_encode(array('success' =>true,'status'=>200,'msg' =>'Record successfully saved'),JSON_PRETTY_PRINT);
			}else{
				return print json_encode(array('success' =>false,'status'=>500,'msg' =>'Error message: %s\n', $mysqli->error),JSON_PRETTY_PRINT);
			}
		}
	}

	public static function read(){
		$config= new Config();
		$mysqli = new mysqli($config->host, $config->user, $config->pass, $config->db);
		if ($mysqli->connect_errno) {
		    print json_encode(array('success' =>false,'status'=>400,'msg' =>'Failed to connect to MySQL: (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error));
		    return;
		}else{
			$query1 ="SELECT c.departmentname as country, sum(a.score) as visits, '#8A0CCF' as color
						FROM scores a,contestants b, departments c, criteria d
						where a.contestantid = b.contestantid
						and b.departmentid = c.departmentid
						and a.criteriaid = d.criteriaid
						group by departmentname
						order by country asc";
			$result1 = $mysqli->query($query1);
			$data = array();
			while($row = $result1->fetch_array(MYSQLI_ASSOC)){
				array_push($data,$row);
			}
			//print json_encode(array('success' =>true,'status'=>200,'childs' =>$data),JSON_PRETTY_PRINT);
			print json_encode(array('data' =>$data),JSON_PRETTY_PRINT);
		}
	}

	public static function detail($id){
		$config= new Config();
		$mysqli = new mysqli($config->host, $config->user, $config->pass, $config->db);
		if ($mysqli->connect_errno) {
		    return print json_encode(array('success' =>false,'status'=>400,'msg' =>'Failed to connect to MySQL: (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error));
		}else{
			$query1 ="select * from criteria where eventid=$id";
			$result1 = $mysqli->query($query1);
			$data = array();
			while($row = $result1->fetch_array(MYSQLI_ASSOC)){
				array_push($data,$row);
			}
			print json_encode(array('success' =>true,'status'=>200,'childs' =>$data),JSON_PRETTY_PRINT);
		}
	}

	public static function update($id,$data){
		$config= new Config();
		$mysqli = new mysqli($config->host, $config->user, $config->pass, $config->db);
		if ($mysqli->connect_errno) {
		    print json_encode(array('success' =>false,'status'=>400,'msg' =>'Failed to connect to MySQL: (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error));
		    return;
		}else{

			$criteriaid = $mysqli->real_escape_string($data['criteriaid']);
			$contestantid = $mysqli->real_escape_string($data['contestantid']);
			$scoring = $mysqli->real_escape_string($data['scoring']);

			/*$result = $mysqli->query("INSERT INTO scores(eventid,judgeid,criteriaid,score,contestantid)
										values ( (SELECT eventid FROM contestants WHERE contestantid=$contestantid LIMIT 1),
			        					(SELECT judgeid FROM judges WHERE eventid = (SELECT eventid FROM contestants WHERE contestantid=$contestantid LIMIT 1) LIMIT 1),
			        					$criteriaid,$scoring,$contestantid)");*/

			if ($result) {
				return print json_encode(array('success' =>true,'status'=>200,'msg' =>'Record successfully updated'),JSON_PRETTY_PRINT);
			}else{
				return print json_encode(array('success' =>false,'status'=>500,'msg' =>'Error message: %s\n', $mysqli->error),JSON_PRETTY_PRINT);
			}
		}
	}

	public static function delete($id){
		/*$config= new Config();
		$mysqli = new mysqli($config->host, $config->user, $config->pass, $config->db);
		if($stmt = $mysqli->prepare('DELETE FROM judges WHERE judgeid =?')){
			$stmt->bind_param('s', $id);
			$stmt->execute();
			$stmt->close();
			print json_encode(array('success' =>true,'status'=>200,'msg' =>'Record successfully deleted'),JSON_PRETTY_PRINT);
		}else{
			print json_encode(array('success' =>false,'status'=>200,'msg' =>'Error message: %s\n', $mysqli->error),JSON_PRETTY_PRINT);
		}*/
	}
}
?>
