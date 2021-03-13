<?php

	/*
		*** Get All Function v2.0
		*** Function To Get All Record From Any Database Table
	*/

	function getAllFrom($field, $table, $where = NULL, $and = NULL, $orderfield, $ordering = "DESC") {

		global $con;

		$getAll = $con->prepare("SELECT $field FROM $table $where $and ORDER BY $orderfield DESC");

		$getAll->execute();

		$all = $getAll->fetchAll();

		return $all;
	
	}


	/*
		*** Check If User Is Not Activted
		*** Function To check  RegStatus Of The User
	*/
	function checkUserStatus($user) {

		global $con;

		$stmtx = $con->prepare("SELECT 
									Username, RegStatus
									FROM 
										users 
									WHERE 
										Username = ?
									AND 
										RegStatus = 0");
		$stmtx->execute(array($user));
		$status = $stmtx->rowCount();
		return $status;
	}


	



/////////////////////////////////////////////////////////////////









  
























  




	/*
		*** Title Function That Evho The Page Title In Case The Page v1.0
		*** Has The Variable $pageTitle And Echo Default Title For Other Pages 
	*/

	function getTitle() {

		global $pageTitle;

		if ( isset($pageTitle) ) {
			echo $pageTitle;
		} else {
			echo 'Default'; 
		}
	} // End Function getTitle()

//////////////////////////////////////////////////////////////////////


	/*
		*** Redirect Function v2.0
		*** [ This function Accept Parameters ]
		*** $url = The Link You Want To Redirect To 
		*** $theMeg = Echo the Message [ Error | Success | Warning ]
		*** $Seconds = Seconds Before Redirecting
	*/
	function redirectHome( $errorMsg, $url = null, $seconds = 3 ){

		if ( $url === null ) {

			$url 	= 'index.php';
			$Link	= 'Homepage';

		} else {

			if (isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] !== ''){

				$url 	= $_SERVER['HTTP_REFERER'];
				$Link	= 'Previous Page';

			} else{

				$url 	= 'index.php';
				$Link	= 'Homepage';

			}

		}

		echo $errorMsg;

		echo "<div class='alert alert-info'>You Will Be Redirected To $Link After $seconds Seconds</div>";

		header("refresh:$seconds;url=$url");	
		exit();	

	}

//////////////////////////////////////////////////////////////////////

	/*
		*** Check Items Function V1.0
		*** Function To chrck Item In Database [ Function Accept Parameters ]
		*** $select = The Item To Select [ Example: user, item, category ]
		*** $form 	= the Table To Select from [ Example: users, items, categories ] 
		*** $value 	= The Value Of Select [ Example: Abdo, Box, Electronics ]
	*/

	function checkItem($select, $from, $value) {

		global $con;

		$statement = $con->prepare("SELECT $select FROM $from WHERE $select = ? ");

		$statement->execute(array($value));	

		$count = $statement->rowCount();
		
		return $count;		

	}
//////////////////////////////////////////////////////////////////////

	/*
		*** Count Number Of Items Function v1.0
		*** Function To Count Number Of Items Rows
		*** $item 	= The Item To Count
		*** $table 	= The Item To Choose From
	*/
	function countItems($items, $table) {

		global $con;

		$stmt2 = $con->prepare("SELECT COUNT($items) FROM $table");

		$stmt2->execute();

		return $stmt2->fetchColumn();	

	}

//////////////////////////////////////////////////////////////////////

	/*
		*** Get Lastest Records Function v1.0
		*** Function To Get Latest Items From Database [ Users, Items, Comments ]
		*** $select = Field To Select
		*** $table 	= The Table To Choose From
		*** $limit 	= Number Of Records To Get
	*/

	function getLatest($select, $table, $order, $limit = 5 ) {

		global $con;

		$getStmt = $con->prepare("SELECT $select FROM $table ORDER BY $order DESC LIMIT $limit");

		$getStmt->execute();

		$rows = $getStmt->fetchAll();

		return $rows;
	
	}
