<?php
require_once("application.php");
/* 
================================================================
Application Info: 
Cartweaver© 2002 - 2005, All Rights Reserved.
Developer Info: 
	Application Dynamics Inc.
	1560 NE 10th
	East Wenatchee, WA 98802
	Support Info: http://www.cartweaver.com/go/phphelp

Cartweaver Version: 2.0  -  Date: 07/22/2005
================================================================
Name: ListCountry.php
Description: List countries
================================================================
*/

/* Set location for highlighting in Nav Menu */
$strSelectNav = "Settings";

/* Set Page Archive Status */
$_GET["CountryView"] = isset($_GET["CountryView"]) ? intval($_GET["CountryView"]) : 0;
$archiveBit = $_GET["CountryView"] == 0 ? 1 : 0;
if($_GET["CountryView"] != 0){
	$currentStatus = "Archived";
}else{
	$currentStatus = "Active";
}

/* ADD Record */
if(isset($_POST["AddRecord"])){
	if($_POST["stprv_country"] == 0) {
		/*  Add a new country  */
		$query_rsCW = sprintf("INSERT INTO tbl_list_countries 
		(country_Name, country_Code, country_Sort, country_Archive) 
		VALUES('%s' ,'%s' ,%s, 0)",
		$_POST["country_Name"],
		$_POST["country_Code"],
		$_POST["country_Sort"]);
		$rsCW = $cartweaver->db->executeQuery($query_rsCW);
		
		/*  Add a phantom state for countries without states 
			Get the newly created country ID  */
		$query_rsCWNewCountry = sprintf("SELECT country_ID 
		FROM tbl_list_countries 
		WHERE country_Name = '%s' AND country_Code = '%s'",
		$_POST["country_Name"],
		$_POST["country_Code"]);
		$rsCWNewCountry = $cartweaver->db->executeQuery($query_rsCWNewCountry);
		$rsCWNewCountry_recordCount = $cartweaver->db->recordCount;
		$row_rsCWNewCountry = $cartweaver->db->db_fetch_assoc($rsCWNewCountry);
	
		/*  Insert the phantom record  */
		$query_rsCW = sprintf("INSERT INTO tbl_stateprov 
		(stprv_Code, stprv_Name, stprv_Country_ID) 
		VALUES 
		('None','None',%d)",$row_rsCWNewCountry["country_ID"]);
		$rsCW = $cartweaver->db->executeQuery($query_rsCW);
	
	}else{
		/*  Add a new state  */
		$query_rsCW = sprintf("INSERT INTO tbl_stateprov 
		(stprv_Code, stprv_Name, stprv_Country_ID, stprv_Archive) 
		VALUES ('%s' ,'%s' ,%s, 0)",
		$_POST["country_Code"],
		$_POST["country_Name"],
		$_POST["stprv_country"]);
		$rsCW = $cartweaver->db->executeQuery($query_rsCW);
		
		/*  Archive any phantom records for this country  */
		$query_rsCW = sprintf("UPDATE tbl_stateprov 
		SET stprv_Archive = 1 
		WHERE stprv_Code = 'None' 
		AND stprv_Country_ID = %s",
		$_POST["stprv_country"]);
		$rsCW = $cartweaver->db->executeQuery($query_rsCW);
	}	
	header("Location: " . $cartweaver->thisLocation);
	exit();
}


/* Update Record */
if(isset($_POST["UpdateCountries"])){
	/* DELETE States */
	if(isset($_POST["stprv_Delete"])){
		$deleteStates = join($_POST["stprv_Delete"],",");
		$query_rsCW = "DELETE FROM tbl_stateprov 
		WHERE stprv_ID IN ($deleteStates)";
		$rsCW = $cartweaver->db->executeQuery($query_rsCW);
	}
	/* Update states */
	for($i = 0; $i<count($_POST["stprv_ID"]); $i++) {	
		$query_rsCW = sprintf("UPDATE tbl_stateprov SET 
		stprv_Code = '%s',
		stprv_Name = '%s'		
		WHERE stprv_ID = %d",
		$_POST["stprv_Code"][$i],
		$_POST["stprv_Name"][$i],
		$_POST["stprv_ID"][$i]);
		$rsCW = $cartweaver->db->executeQuery($query_rsCW);
	}
	
	/* Archive States */
	if(isset($_POST["stprv_Archive"])){
		$archiveStates = join($_POST["stprv_Archive"],",");
		$query_rsCW = "UPDATE tbl_stateprov 
		SET stprv_Archive = $archiveBit
		WHERE stprv_ID IN ($archiveStates)";
		$rsCW = $cartweaver->db->executeQuery($query_rsCW);
	}
	
	/* Delete any countries marked for deletion */
	if(isset($_POST["country_Delete"])){		
		/* Delete any states, including phantom states */
		$deleteCountries = join($_POST["country_Delete"],",");
		$query_rsCW = "DELETE FROM tbl_stateprov 
		WHERE stprv_Country_ID IN ($deleteCountries)";
		$rsCW = $cartweaver->db->executeQuery($query_rsCW);	
		/* Delete the actual country record */
		$query_rsCW = "DELETE FROM tbl_list_countries 
		WHERE country_ID IN ($deleteCountries)";
		$rsCW = $cartweaver->db->executeQuery($query_rsCW);
	}
	
	/* Update countries */
	for($i = 0; $i<count($_POST["country_ID"]); $i++) {	
		if($_POST["country_ID"][$i] == $_POST["defCountry"]) {
			$setDefaultCountry = 1;
		}else{
			$setDefaultCountry = 0;
		}
		$query_rsCW = sprintf("UPDATE tbl_list_countries 
		SET 
		country_Sort = '%s',
		country_Code = '%s',
		country_Name = '%s',
		country_DefaultCountry = %d
		WHERE country_ID = %d",
		$_POST["country_Sort"][$i],
		$_POST["country_Code"][$i],
		$_POST["country_Name"][$i],
		$setDefaultCountry,
		$_POST["country_ID"][$i]);
		$rsCW = $cartweaver->db->executeQuery($query_rsCW);
	}
	
	/* Archive any countries marked for archiving */
	if(isset($_POST["country_Archive"])){		
		$archiveCountries = join($_POST["country_Archive"],",");
		$query_rsCW = "UPDATE tbl_list_countries 
		SET country_Archive = $archiveBit
		WHERE country_ID IN ($archiveCountries)";
		$rsCW = $cartweaver->db->executeQuery($query_rsCW);	
	}
	
	/* If all states for a country are archived or deleted, unarchive the phantom state */
	$query_rsCWCheckActive = "SELECT DISTINCT c.country_ID 
	FROM tbl_list_countries c
	INNER JOIN tbl_stateprov s
	ON c.country_ID = s.stprv_Country_ID 
	WHERE s.stprv_Archive = 0 
	AND s.stprv_Code <> 'None'";
	$rsCWCheckActive = $cartweaver->db->executeQuery($query_rsCWCheckActive);	
	$rsCWCheckActive_recordCount = $cartweaver->db->recordCount;
	
	// Put active country ids in a string
	$activeCountries = $cartweaver->db->valueList($rsCWCheckActive, "country_ID");
	/* Unarchive phantom states */
	$query_rsCW = "UPDATE tbl_stateprov SET stprv_Archive = 0
	WHERE stprv_Code = 'None' 
	AND stprv_Country_ID NOT IN ($activeCountries)";
	$rsCW = $cartweaver->db->executeQuery($query_rsCW);	
	
	/* Archive phantom states */
	$query_rsCW = "UPDATE tbl_stateprov SET 
	stprv_Archive = 1
	WHERE stprv_Code = 'None' 
	AND stprv_Country_ID IN ($activeCountries)";
	$rsCW = $cartweaver->db->executeQuery($query_rsCW);	

	header("Location: " . $cartweaver->thisLocation);
	exit();
}

/* List to display all countries and states */
$_GET["CountryView"] = intval($_GET["CountryView"]);
$query_rsCWCountryList = sprintf("SELECT country_ID, 
country_Code, 
country_Name, 
country_DefaultCountry, 
country_Sort 
FROM tbl_list_countries 
WHERE country_Archive = %s
ORDER BY country_sort, country_name",$_GET["CountryView"]);
$rsCWCountryList = $cartweaver->db->executeQuery($query_rsCWCountryList);
$rsCWCountryList_recordCount = $cartweaver->db->recordCount;
$row_rsCWCountryList = $cartweaver->db->db_fetch_assoc($rsCWCountryList);

$query_rsCWStateList ="SELECT stprv_ID, 
stprv_Code, 
stprv_Name, 
stprv_Archive, 
stprv_Country_ID 
FROM tbl_stateprov 
ORDER BY stprv_Name";
$rsCWStateList = $cartweaver->db->executeQuery($query_rsCWStateList);

/* Create a query with all of customer related states to check for deletion
Use ListFind against UseStateList to check for deletion */
$query_rsCWUsedStateList = "SELECT CustSt_StPrv_ID FROM tbl_custstate";
$rsCWUsedStateList = $cartweaver->db->executeQuery($query_rsCWUsedStateList);
$rsCWUsedStateList_recordCount = $cartweaver->db->recordCount;
$row_rsCWUsedStateList = $cartweaver->db->db_fetch_assoc($rsCWUsedStateList);
$usedStateList = explode(",",$cartweaver->db->valueList($rsCWUsedStateList, "CustSt_StPrv_ID"));
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>CW Admin: Countries/Regions</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="assets/admin.css" rel="stylesheet" type="text/css">
</head>
<body>
<?php include("CWIncNav.php");?>
<div id="divMainContent">
  <h1><?php echo($currentStatus);?> Countries/Regions</h1>
  <p><?php 
if($currentStatus == "Active") {
	echo('<a href="' . $cartweaver->thisPage . '?CountryView=1">View Archived</a>');
}else{
	echo('<a href="' . $cartweaver->thisPage . '?CountryView=0">View Active</a>');
}?></p>
  <?php if($currentStatus == "Active") { ?>
  <form name="Add" method="POST" action="<?php echo($cartweaver->thisPageName);?>">
	<table> 		
		 <caption>Add Country / Region / State</caption>
		<tr align="center"> 
			<th>Code</th> 
			<th>Name</th> 
			<th>Sort</th> 
			<th>Location</th> 
			<th>Add</th> 
		</tr>
		<tr align="center" class="altRowEven">
			<td><input name="country_Code" type="text" size="8"></td>
			<td><input name="country_Name" type="text"  size="25"></td>
			<td><input name="country_Sort" type="text" size="3"></td>
			<td><select name="stprv_country" id="stprv_country"> 
			<option value="0">-- New Country --</option> 
			<?php do { // Cartweaver repeat region ?>
			<option value="<?php echo($row_rsCWCountryList["country_ID"]);?>"><?php echo($row_rsCWCountryList["country_Name"]);?></option> 
			<?php } while ($row_rsCWCountryList = $cartweaver->db->db_fetch_assoc($rsCWCountryList)); 
				$cartweaver->db->db_data_seek($rsCWCountryList, 0);
				$row_rsCWCountryList = $cartweaver->db->db_fetch_assoc($rsCWCountryList);?>
			</select></td>
			<td><input name="AddRecord" type="submit" class="formButton" id="AddRecord" value="Add"></td>
		</tr>
	</table>
  </form>
  <?php }/* END IF - CurrentStatus EQ "Active" */
  
/* Only show table if we have records */
if($rsCWCountryList_recordCount != 0) { 
	$query_rsCWCheckStates = "SELECT DISTINCT stprv_Country_ID 
	FROM tbl_stateprov 
	WHERE stprv_Code <> 'None'";
	$rsCWCheckStates = $cartweaver->db->executeQuery($query_rsCWCheckStates);
	$checkStateList = explode(",",$cartweaver->db->valueList($rsCWCheckStates, "stprv_Country_ID"));
?>

<form action="<?php echo($cartweaver->thisPageQS);?>" method="POST" name="Update">
  <table>   
    <tr>
      <th align="center">Code</th>
      <th align="center">Name</th>
      <th align="center">Sort</th>
      <th align="center">Default</th>
      <th align="center">Delete</th>
      <th align="center"><?php echo($currentStatus == "Active") ? "Archive" : "Activate";?></th>
    </tr>
    <?php 
	$recCounter = 1;
	do { // Cartweaver repeat region	
?>
	
	<tr class="<?php echo(cwAltRow($recCounter));?>">
		<td rowspan="2" align="right"><?php echo($recCounter);?>.
		<input name="country_ID[]" type="hidden" size="2" value="<?php echo($row_rsCWCountryList["country_ID"]);?>"> 
		<input type="text" name="country_Code[]" value="<?php echo($row_rsCWCountryList["country_Code"]);?>" size="8"/></td> 
		<td><input type="text" name="country_Name[]" value="<?php echo($row_rsCWCountryList["country_Name"]);?>"  size="25"/></td> 
		<td align="center"><input name="country_Sort[]" type="text" value="<?php echo($row_rsCWCountryList["country_Sort"]);?>" size="3" /> </td> 
		<td align="center"><input name="defCountry" type="radio" class="formRadio" value="<?php echo($row_rsCWCountryList["country_ID"]);?>"<?php if($row_rsCWCountryList["country_DefaultCountry"] == 1){echo(" checked");}?> /></td>
		<td align="center"><input name="country_Delete[]" type="checkbox" value="<?php echo($row_rsCWCountryList["country_ID"]);?>" class="formCheckbox" <?php if(arrayFind($checkStateList,$row_rsCWCountryList["country_ID"]) != -1) { echo(" disabled");}?>/> </td> 
		<td align="center"><input name="country_Archive[]" value="<?php echo($row_rsCWCountryList["country_ID"]);?>" type="checkbox" class="formCheckbox"></td> 
	</tr>
	<tr class="<?php cwAltRow($recCounter++);?>"> 
       <td colspan="5">
		<?php 
			//$haveActiveState = false;			
			// Create state list from filtered state recordset
			$rsCWStateList_filtered = $cartweaver->db->queryOfQuery($rsCWStateList,"*",false,"stprv_Country_ID",$row_rsCWCountryList["country_ID"]);
		 	if ((count($rsCWStateList_filtered) > 1) || (count($rsCWStateList_filtered) == 1 && $rsCWStateList_filtered[0]["stprv_Code"] != "None")) {
		?>
			<table> 
				<tr> 
					<th>Code</th> 
					<th>Name</th> 
					<th>Delete</th> 
					<th>Archive</th> 
				</tr> 				
				<?php $stateCounter = 1;
					foreach ($rsCWStateList_filtered as $key => $row_rsCWStateList){
					//cwDebugger($row_rsCWStateList);					
						if($row_rsCWStateList["stprv_Code"] != "None") {
							//if($row_rsCWStateList["stprv_Archive"] != 1 && $haveActiveState == false){
								//$haveActiveState = true;
							//}/*END if($row_rsCWCountryList["stprv_Archive"] != 1 && $haveActiveState == false)*/
							?>
							<tr> 
								<td align="right"><input type="hidden" name="stprv_ID[]" value="<?php echo($row_rsCWStateList["stprv_ID"]);?>"/>
									<?php echo($stateCounter++);?>.<input type="text" name="stprv_Code[]" value="<?php echo($row_rsCWStateList["stprv_Code"]);?>"  size="3"/></td> 
								<td><input type="text" name="stprv_Name[]" value="<?php echo $row_rsCWStateList["stprv_Name"]; ?>" size="18" /></td> 
								<td align="center"><input type="checkbox" class="formCheckbox" name="stprv_Delete[]" value="<?php echo($row_rsCWStateList["stprv_ID"]);?>"<?php if(arrayFind($usedStateList,$row_rsCWStateList["stprv_ID"]) != -1){echo(" disabled=\"disabled\"");}?> ></td> 
								<td align="center"><input type="checkbox" class="formCheckbox" name="stprv_Archive[]" value="<?php echo($row_rsCWStateList["stprv_ID"]);?>"<?php if($row_rsCWStateList["stprv_Archive"] == 1) {echo(" checked=\"checked\"");}?>></td> 
							</tr> 
						<?php 
						} /*END  if($row_rsCWCountryList["stprv_Code"] != "None") */	
					} /* END foreach ($rsCWStateList_filtered as $key => $row_rsCWStateList) */?>
			 </table>
			 <?php
		}/*END if (count($rsCWStateList_filtered) > 1) || (count($rsCWStateList_filtered) = 1 && rsCWStateList_filtered[0]["stprv_Code"] != "None") */		
?>
		 </td> 
        </tr>
	<?php
} while ($row_rsCWCountryList = $cartweaver->db->db_fetch_assoc($rsCWCountryList)); ?>
  </table>
  <input type="submit" name="UpdateCountries" value="Update Countries" class="formButton"> 
  </form>
  <?php 
}else{
	echo("<p>There are no $currentStatus countries/regions.</p>");
} ?>
</div>
</body>
</html>
<?php
cwDebugger($cartweaver);
?>
