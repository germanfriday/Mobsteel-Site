<?php
/* 
================================================================
Application Info: 
Cartweaver© 2002 - 2005, All Rights Reserved.
Developer Info: 
	Application Dynamics Inc.
	1560 NE 10th
	East Wenatchee, WA 98802
	Support Info: http://www.cartweaver.com/go/phphelp

Cartweaver Version: 2.2  -  Date: 09/07/2005
================================================================
Name: CWSearch.php
Description: Provides several choices of search types.

======================================================================================
*/

class CWSearch {
	var $searchtype, $separator, $selectedStart, $selectedEnd, $allcategorieslabel,
					$formid, $keywords, $keywordslabel, $category, $categorylabel, $secondary, 
					$secondarylabel, $buttonlabel, $actionpage, $urlkeywords, $urlcategory, $urlsecondary;
	
	/* constructor function takes url parameters and the Cartweaver object as parameters */
	function CWSearch($cartweaver) {
		$this->urlkeywords = (isset($_GET["keywords"])) ? $_GET["keywords"] : "";
		$this->urlcategory = (isset($_GET["category"])) ? $_GET["category"] : "";
		$this->urlsecondary = (isset($_GET["secondary"])) ? $_GET["secondary"] : "";
		$this->db = $cartweaver->db;
		/* Set the default action page to the defined results page in the cartweaver object */
		$this->actionpage = $cartweaver->settings->targetResults;

		$this->init();
	}

	function setSearchType($searchtype){
		$this->searchtype = strtoupper($searchtype);
	}
	
	function setSeparator($separator) {
		$this->separator = $separator;
	}
	function setSelectedStart($selectedStart){
		$this->selectedStart = $selectedStart;
	}
	
	function setSelectedEnd($selectedEnd) {
		$this->selectedEnd = $selectedEnd;
	}
	function setAllCategoriesLabel($allcategorieslabel){
		$this->allcategorieslabel = $allcategorieslabel;
	}
	
	function setFormid($formid) {
		$this->formid = $formid;
	}
	function setKeywords($keywords){
		$this->keywords = strtoupper($keywords);
	}
	
	function setKeywordsLabel($keywordslabel) {
		$this->keywordslabel = $keywordslabel;
	}
	
	function setCategory($category) {
		$this->category = strtoupper($category);
	}
	
	function setCategoryLabel($categorylabel) {
		$this->categorylabel = $categorylabel;
	}
	
	function setSecondary($secondary) {
		$this->secondary = strtoupper($secondary);
	}
	
	function setSecondaryLabel($secondarylabel) {
		$this->secondarylabel = $secondarylabel;
	}
	
	function setButtonLabel($buttonlabel) {
		$this->buttonlabel = $buttonlabel;
	}
	
	function setActionPage($actionpage) {
		$this->actionpage = $actionpage;
	}
		
	function init(){
		/* SET DEFAULTS */		
		/* Determines the type of search to display. Valid values are "Links" or "Form" */
		$this->searchtype = "Links";		
		/* Variables for Links search types. */
		/* The separator is placed between all cateogry links */
		$this->separator = " | ";
		/* This tag is placed before the currently selected category's link */
		$this->selectedStart = "<strong>";
		/* This tag is placed after the currently selected category's link */
		$this->selectedEnd = "</strong>";
		/* This is the label used for the All Cateogries link */
		$this->allcategorieslabel = "All";
		
		/* Variables for Form searches */
		/* The formid is applied to the actual <form> tag for the search */
		$this->formid = "Search";
		/* "Yes" or "No", determines if a keyword field is displayed */
		$this->keywords = "NO";
		/* This is the default text entered in the keyword search field */
		$this->keywordslabel = $this->urlkeywords;
		/* "Yes" or "No", determines if a category list is shown */
		$this->category = "NO";
		/* This is the text for the first entry in the category list. It will display
			  all categories */
		$this->categorylabel = "All categories";
		/* "Yes" or "No", determines if a secondary category list is shown */
		$this->secondary = "NO";
		/* This is the text for the first entry in the category list. It will display
			  all secondary categories */
		$this->secondarylabel = "All secondary categories";
		/* This is the text to be used for the search button */
		$this->buttonlabel = "Search";
	}
	
	function display() {
	/* Determine the type of search to display. Valid values are "Links" or "Form" */
	/* If we're displaying links or the category search field, then get a category list */
		if($this->searchtype == "LINKS" || $this->category == "YES") {
			$query_rsCWGetCategories = "SELECT category_ID, category_Name 
			FROM tbl_prdtcategories 
			WHERE category_archive = 0 
			ORDER BY category_sortorder, category_Name";
			$rsCWGetCategories = $this->db->executeQuery($query_rsCWGetCategories);
		}
		
		/* If we're displaying secondary categories, then get a secondary category list */
		if($this->secondary == "YES") {
			$query_rsCWSecondaryCategories = "SELECT scndctgry_ID, scndctgry_Name 
			FROM tbl_prdtscndcats 
			WHERE scndctgry_Archive = 0 
			ORDER BY scndctgry_Sort, scndctgry_Name";
			$rsCWSecondaryCategories = $this->db->executeQuery($query_rsCWSecondaryCategories);
		}
		$separator = "";
		/* ///   BEGIN SEARCH TYPE SELECTION  ///  */
		switch ($this->searchtype) {
			/* ///   SEARCH BY CATEGORY - TEXT LINKS  ///  */ 
			case "LINKS":
				if($this->allcategorieslabel != "") {
					$separator = $this->separator;
					if ($this->urlcategory == "0") { 
						echo($this->selectedStart);
					}
					echo("<a href=\"" . $this->actionpage . "?category=0\">" . $this->allcategorieslabel . "</a>");
					if ($this->urlcategory == "0") {
						echo($this->selectedEnd); 
					}
				}
				while ($row_rsCWGetCategories = $this->db->db_fetch_assoc($rsCWGetCategories)) { 
					 if($row_rsCWGetCategories['category_ID'] != 1) {
						echo($separator);
						$separator = $this->separator;
						if($this->urlcategory == $row_rsCWGetCategories['category_ID']) {
							 echo($this->selectedStart);
						}
						echo("<a href=\"" . $this->actionpage . "?category=" . $row_rsCWGetCategories['category_ID'] . "\">" . $row_rsCWGetCategories['category_Name'] . "</a>");
						if($this->urlcategory == $row_rsCWGetCategories['category_ID']) {
							 echo($this->selectedEnd);
						}
					}
				}	
				break; 	
			/* SEARCH BY FORM */ 
			case "FORM":		
				echo("<form name=\"" . $this->formid . "\" id=\"" . $this->formid . "\" method=\"get\" action=\"" . $this->actionpage . "\">\n");
				if($this->keywords == "YES") {
					 echo("<input name=\"keywords\" id=\"keywords\" type=\"text\" value=\"" . $this->keywordslabel . "\" onFocus=\"if(this.value == '" . $this->keywordslabel . "'){this.value=''}\">\n");
				}
				if($this->category == "YES") {
					 echo("<select name=\"category\" id=\"category\">"); 
						if($this->categorylabel != "") {
							echo("<option value=\"0\">" . $this->categorylabel . "</option>");
						}
						/* //Populate Dropdown with results of rsCWGetCategories query // */ 
						while ($row_rsCWGetCategories = $this->db->db_fetch_assoc($rsCWGetCategories)) { 
							if($row_rsCWGetCategories['category_ID'] != 1) {
								$selected = ($this->urlcategory == $row_rsCWGetCategories['category_ID']) ? "selected=\"selected\"" : "";
								echo("<option value=\"" . $row_rsCWGetCategories['category_ID'] . "\" $selected>" . $row_rsCWGetCategories['category_Name'] . "</option>");
							}
						}
					echo("</select>");
				}
				if($this->secondary == "YES") {
					 echo("<select name=\"secondary\" id=\"secondary\">");
						if($this->secondarylabel != "") {
							 echo("<option value=\"0\" selected>" . $this->secondarylabel ."</option>");
						}
						/* //Populate Dropdown with results of rsCWSecondaryCategories query // */ 
						while ($row_rsCWSecondaryCategories = $this->db->db_fetch_assoc($rsCWSecondaryCategories)) { 
							if ($row_rsCWSecondaryCategories['scndctgry_ID'] != 1) {
								$selected = ($this->urlsecondary == $row_rsCWSecondaryCategories['scndctgry_ID']) ? "selected=\"selected\"" : "";
								echo("<option value=\"" . $row_rsCWSecondaryCategories['scndctgry_ID'] . "\" $selected>" . $row_rsCWSecondaryCategories['scndctgry_Name'] . "</option>");
							}
						}
					echo("</select>");
				}
				echo("<input name=\"Submit\" type=\"submit\" class=\"formButton\" value=\"" . $this->buttonlabel . "\">");
				echo("</form>");
				break; 
		}// End switch
	}// End function
}// end constructor
?>