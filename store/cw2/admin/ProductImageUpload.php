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

Cartweaver Version: 2.4  -  Date: 11/27/2005
================================================================
Name: ProductImageUpload.php
Description: Uploads images to the appropriate folders and sets 
the name of the image in the records of the selected product.

Important Note: File permissions need to be enabled for the directories
that you have set up for the thumbnail and full images for your store.
Check the Cartweaver documentation for more details.
================================================================
*/
$imgFolder = "";
$cwError = "";

$imageType = 1;
if(isset($_GET["type"])) {
	$imageType = $_GET["type"];
}
if(isset($_POST["type"])) {
	$imageType = $_POST["type"];
}

$imageThumbPath = $siteRoot . $imageThumbFolder;
$imageLargePath = $siteRoot . $imageLargeFolder;


if(!isset($_GET["result"])) {$_GET["result"] = "";}
if(!isset($_POST["action"])) {$_POST["action"] = "";}

$imageFile = (isset($_GET["file"])) ? $_GET["file"] : "";

/* Set the proper upload folder */
switch($imageType) {
	case 1:
		$folderName = $imageThumbFolder;
		$imageFolderPath = $imageThumbPath;
		$imageTextField = "ImageFileName_T";
		$imageField = "selThumbImage";
		$imageRoot = "../../$imageThumbFolder";
		break;
	case 2:
		$folderName = $imageLargeFolder;
		$imageFolderPath = $imageLargePath;
		$imageTextField = "ImageFileName_L";
		$imageField = "selLargeImage";
		$imageRoot = "../../$imageLargeFolder";
		break;
}

$MaxSizeKB = "50";
$MaxSize = $MaxSizeKB * 1024;
		
switch($_POST["action"]) {

	case "confirmOverwrite":
		if ($_POST["choose"] == "Yes") {
			/* Overwrite the older file */
			/* First, delete the old file */
			unlink($imageFolderPath . $_POST["tempFile"]);
			/* Rename the new file */
			rename($imageFolderPath . $_POST["file"], $imageFolderPath . $_POST["tempFile"]);
			$fileName = $_POST["tempFile"];
			$cwError = "";
			header("Location: " . $cartweaver->thisPage . "?result=The file " . $fileName . " has been successfully uploaded.&file=" . $fileName . "&type=$imageType"); 
			exit();
		}else{
			/* Delete the new file */
			$cwError = "";
			unlink($imageFolderPath . $_POST["file"]);
			header("Location: " . $cartweaver->thisPage); 
			exit();
		}
		break;
		
		
	case "upload":
		/* Upload the image */
		if(isset($_POST["MAX_FILE_SIZE"])) {
			//rejects all non-image files
			if(!preg_match("/.jpg$|.jpeg$|.gif$|.png$/i", $_FILES['file']['name'])){
				$cwError = "You can only upload images.";
				break;
			}
			
			$uploadDirectory = $imageFolderPath . $_FILES['file']["name"];
			$fileName = $_FILES['file']["name"];
			if(file_exists($uploadDirectory)) {
				$tempName = $fileName;			
				$cwError = "Duplicate";
				$fileName = rand(100000,500000) . "_$fileName";
				$uploadDirectory = str_replace($tempName, $fileName, $uploadDirectory);
			}
			if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadDirectory)){
				$_POST["file"] = $imgFolder . $_FILES['file']["name"];
			}else{
				switch($_FILES['file']['error']){
					case 0: //no error; possible file attack!
						$cwError =  "There was a problem with your upload.";
						break;
					case 1: //uploaded file exceeds the upload_max_filesize directive in php.ini
						$cwError =  "The file you are trying to upload is too big.";
						break;
					case 2: //uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the html form
						$cwError =  "The selected file's size is greater than $MaxSizeKB kilobytes which is the maximum size allowed, please select another image and try again.";
						break;
					case 3: //uploaded file was only partially uploaded
						$cwError =  "The file you are trying upload was only partially uploaded.";
						break;
					case 4: //no file was uploaded
						$cwError =  "You must select an image for upload.";
						break;
					default: //a default error, just in case!  :)
						$cwError =  "There was a problem with your upload.";
						break;
				}
			}
		}
		if($cwError == "") {
			header("Location: " . $cartweaver->thisPage . "?result=The+file+$fileName+has+been+successfully+uploaded&file=$fileName&type=$imageType"); 
			exit();
		}

		break;

	case "delete":
		/* User has selected to delete an image */
		$deleteName = "";
		$imageList = "";
		$deleteImage = "";
		if(isset($_POST["confirmdelete"])){
			$deleteImage = true;
			$deleteName = $_POST["image"];
		}else{
			switch ($imageType) {
				case 1:
					$deleteName = $_POST["selThumbImage"];
					break;				
				case 2:
					$deleteName = $_POST["selLargeImage"];
					break;
			}
			/* Check to see if this image is currently associated with any products */
			$query_rsCWCWCheckImage = "SELECT p.product_Name, p.product_MerchantProductID 
			FROM tbl_products p
			INNER JOIN tbl_prdtimages i
			ON p.product_ID = i.prdctImage_ProductID 
			WHERE i.prdctImage_FileName = '$deleteName' 
			AND i.prdctImage_ImgTypeID = $imageType";
			$rsCWCWCheckImage = $cartweaver->db->executeQuery($query_rsCWCWCheckImage);
			$rsCWCWCheckImage_recordCount = $cartweaver->db->recordCount;
			$row_rsCWCWCheckImage = $cartweaver->db->db_fetch_assoc($rsCWCWCheckImage);
			if($rsCWCWCheckImage_recordCount != 0) {
				/* There are products associated with this image */
				$imageList = "<ul>";				
				do{
					$imageList .= "<li>" . $row_rsCWCWCheckImage["product_Name"] . " (" . $row_rsCWCWCheckImage["product_MerchantProductID"] . ")</li>";
				} while ($row_rsCWCWCheckImage = $cartweaver->db->db_fetch_assoc($rsCWCWCheckImage));
				$imageList .=  "</ul>";
				$deleteImage = false;
			}else{
				/* No products, delete the image */
				$deleteImage = true;
			}
		}

		if($deleteImage){
			/* Remove the entry from the database first to prevent a potential broken image */
			$query_deleteImage = "DELETE FROM tbl_prdtimages 
			WHERE prdctImage_FileName = '$deleteName' 
			AND prdctImage_ImgTypeID = $imageType";
			$deleteImage = $cartweaver->db->executeQuery($query_deleteImage);
			/* Delete the file */
			unlink($imageFolderPath . $deleteName);
			header("Location: " . $cartweaver->thisPage . "?type=$imageType&result=The+file+$deleteName+has+been+deleted.&deletedfile=$deleteName"); 
			exit();
		}
		break;
}
/* [ END switch ] */

/* Get a list of files from the image folders */
$dir = opendir($imageThumbPath);
while (false !== ($filename = readdir($dir))) {
  $files[] = $filename;
}
$thumbOptions = "";
sort($files);
foreach($files as $key=>$val) {
	if(is_file($imageThumbPath.$val)) $thumbOptions .= "<option value=\"$val\"" . (($val==$imageFile) ? "selected=\"selected\"" : "") . ">$val</option>\n";
}

$files = array();

$dir = opendir($imageLargePath);
while (false !== ($filename = readdir($dir))) {
  $files[] = $filename;
}
$largeOptions = "";
sort($files);
foreach($files as $key=>$val) {
	if(is_file($imageLargePath.$val)) $largeOptions .= "<option value=\"$val\"" . (($val==$imageFile) ? "selected=\"selected\"" : "") . ">$val</option>\n";
}


/* Set necessary onload events */
$onloadEvents = "";

if(!isset($_POST["fieldnames"])){
	if($cwError != "Duplicate"){
		if(!isset($_GET['file'])) $_GET['file'] = '';
		$onloadEvents = "showImageSelection();updatePreview('','$imageRoot','$imageType','$imageField');";
	}
}

if(isset($_GET["file"])){
	$onloadEvents .= "updateImages('$imageType','$imageRoot" . $_GET["file"] . "','$imageTextField','" . $_GET["file"] . "');";
}

if(isset($_GET["deletedfile"])){
	$onloadEvents .= "checkDeletedImage('$imageType','$folderName','$imageTextField','" . $_GET["deletedfile"] . "');";
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>CW Admin: Product Image Upload</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="assets/admin.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="assets/global.js"></script>
<script language="JavaScript" type="text/JavaScript">
<!--
function updateImages(imgType,imageSRC,txtField,imageName) {
  eval("self.opener.document.productform." + txtField + ".value='"+imageName+"'");
	var obj = eval("self.opener.document.productform.image"+imgType);
	obj.src = imageSRC;
	obj.alt = 'Image path: '+imageSRC;
	obj.style.display = 'inline';
}

function updateExisting(imgType, imgSelect, imageSRC, txtField){
	var sel = MM_findObj(imgSelect);
	if(sel.selectedIndex!=-1){
		var imageName = sel.options[sel.selectedIndex].value;
		updatePreview(imageName, imageSRC, imgType, imgSelect);
		updateImages(imgType,imageSRC+imageName,txtField,imageName);
	}else{
		alert("Please select an image.")
	}
}

function updatePreview(imageName,imageSRC,imgType,imageField){
	MM_findObj('imagePreview'+imgType).src=imageSRC+"/"+imageName;
	MM_findObj('butSel'+imgType).value="Select Image";
	pickValue(imageName, imageField);
}

function showImageSelection(){
	var radImage = MM_findObj('type');
	var toggleID;
	for(i=0;i<radImage.length;i++){
		toggleID = "divImages" + radImage[i].value;
		if(radImage[i].type == 'radio'){
			MM_findObj(toggleID).style.display = (radImage[i].checked)? "block" : "none";
		}
	}
}

function tmt_confirm(msg){
	document.MM_returnValue=(confirm(unescape(msg)));
}

function checkDeletedImage(imgType,imageSRC,txtField,imageName){
	var parentImage = eval("self.opener.document.productform." + txtField + ".value");
	if(parentImage == imageName){
		updateImages(imgType,imageSRC,txtField,'');
	}
}

function pickValue(value, field) {
	try {
	var theField = MM_findObj(field);
	for(var i=0; i<theField.length; i++) {
		if(theField.options[i].value == value) {
			theField.options[i].selected = true;
			break;
		}
	}
	}catch(e){
	  // noop
	}
}
//-->
</script>
</head>
<body onLoad="<?php echo($onloadEvents);?>">
<div id="divMainContent" style="margin-left: 10px;">
	<h1>Product Image Upload</h1>
	<?php if($cwError == "Duplicate") { ?>
		<form action="<?php echo($cartweaver->thisPageQS);?>" method="post" name="confirm">
			<p>Do you want to overwrite this file?</p>
			<input name="choose" type="submit" class="formButton" id="choose" value="Yes" />
			<input name="choose" type="submit" class="formButton" id="choose" value="No" />
			<input type="hidden" name="file" value="<?php echo($fileName);?>" />
			<input type="hidden" name="tempFile" value="<?php echo($tempName);?>" />
			<input type="hidden" name="type" value="<?php echo($_POST["type"]);?>" />
			<input type="hidden" name="action" value="confirmOverwrite">
		</form>
	<?php } else { // if($cwError == "Duplicate")
		if($_POST["action"] != "confirmOverwrite") {
			if($_POST["action"] != "delete") {
				if($cwError != ""){
					echo("<p><strong>$cwError</strong></p>");
				}
				if($_GET["result"] != ""){
					echo("<p>" . $_GET["result"] . "</p>");
				}
			?>
				<form action="<?php echo($cartweaver->thisPage);?>" method="post" enctype="multipart/form-data" name="FileUploader" id="FileUploader" onSubmit="YY_checkform('FileUploader','type[0]','#q','2','Choose the type of image you\'re uploading.');return document.MM_returnValue">
					<table style="width: 406px;">
						<tr>
							<th>Choose Image Type </th>
							<th>Upload Image </th>
						</tr>
						<tr>
							<td width="100%"><label>
								<input<?php if($imageType == 1) {echo(' checked="checked"');}?> type="radio" name="type" value="1" onClick="showImageSelection();" />
								Thumbnail</label>
								<br />
								<label>
								<input<?php if($imageType == 2) {echo(' checked="checked"');}?> type="radio" name="type" value="2" onClick="showImageSelection();" />
								Large Image</label></td>
							<td align="right"><input name="file" type="file">
								<br />
								<input name="Submit" type="submit" class="formButton" value="Upload Image" style="margin-top: 3px;" />
								<input type="hidden" name="action" value="upload" />
								<input type="hidden" name="MAX_FILE_SIZE" value="<?php echo($MaxSize);?>"></td>
						</tr>
					</table>
				</form>
				<div id="divImages1">
					<form action="<?php echo($cartweaver->thisPage);?>" method="post" name="frmThumbnails" id="frmThumbnails" onSubmit="YY_checkform('frmThumbnails');return document.MM_returnValue">
						<table>
							<caption>
							Select Existing Image
							</caption>
							<tr>
								<th scope="col">Thumbnail Images </th>
							</tr>
							<tr>
								<td align="right" scope="col"><?php if($largeOptions == "") {
										echo("<p>There are currently no thumbnail images uploaded.</p>");
										}else{ ?>
										<select style="width: 400px; margin-bottom: 3px;" name="selThumbImage" size="10" id="selThumbImage" onChange="updatePreview(this.value,'<?php echo($imageRoot);?>','1','<?php echo($imageField);?>');">
											<?php echo($thumbOptions);?>
										</select>
										<br />
										<input name="butSel1" type="button" class="formButton" id="butSel1" onClick="updateExisting('1', 'selThumbImage', '<?php echo($imageRoot);?>', 'ImageFileName_T');this.value='Image Set';" value="Select Image" />
										<input name="delete" type="submit" class="formButton" id="delete" onClick="tmt_confirm('Are%20you%20sure%20you%20want%20to%20delete%20this%20image?');return document.MM_returnValue" value="Delete Image" />
										<input name="action" type="hidden" id="action" value="delete">
										<input name="type" type="hidden" id="type" value="1" />
									<?php } ?></td>
						</table>
						<p>Image Preview:<br />
							<image src="" alt="Choose an image to preview" id="imagePreview1" /></p>
					</form>
				</div>
				<div id="divImages2" style="display: none;">
					<form action="<?php echo($cartweaver->thisPage);?>" method="post" name="frmLarge" id="frmLarge">
						<table>
							<caption>
							Select Existing Image
							</caption>
							<tr>
								<th scope="col">Large Images </th>
							</tr>
							<tr>
								<td align="right" scope="col"><?php if($largeOptions == "") {
										echo("<p>There are currently no large images uploaded.</p>");
										}else{ ?>
										<select name="selLargeImage" size="10" id="selLargeImage" style="width: 400px; margin-bottom: 3px;" onChange="updatePreview(this.value,'<?php echo($imageRoot);?>','2','<?php echo($imageField);?>');">
											<?php echo($largeOptions);?>
										</select>
										<br />
										<input name="butSel2" type="button" class="formButton" id="butSel2" onClick="updateExisting('2', 'selLargeImage', '<?php echo($imageRoot);?>', 'ImageFileName_L');this.value='Image Set';" value="Select Image" />
										<input name="delete" type="submit" class="formButton" id="delete" onClick="tmt_confirm('Are%20you%20sure%20you%20want%20to%20delete%20this%20image?');return document.MM_returnValue" value="Delete Image" />
										<input name="action" type="hidden" id="action" value="delete">
										<input name="type" type="hidden" id="type" value="2" />
									<?php } ?></td>
							</tr>
						</table>
						<p>Image Preview:<br />
							<img src="" alt="Choose an image to preview" id="imagePreview2" /></p>
					</form>
				</div>
			<?php }else{ 
					if($imageList != "") {
					?>
				<p>This image is associated with the following products:</p>
				<?php echo($imageList);?>
				<p>Do you still want to delete this image?</p>
				<?php
				}else{
					echo("Are you sure?");
				}?>
				<form action="<?php echo($cartweaver->thisPage);?>" method="post" name="frmDelete" id="frmDelete">
					<input name="confirmdelete" type="submit" class="formButton" id="confirmdelete" value="Yes" />
					<input type="button" value="No" class="formButton" onClick="javascript:history.go(-1);" />
					<input name="type" type="hidden" id="type" value="<?php echo($imageType);?>" />
					<input type="hidden" name="image" value="<?php echo($deleteName);?>" />
					<input name="action" type="hidden" id="action" value="delete" />
				</form>
			<?php } 
			}//if($cwError == "Duplicate") 
		?>
		<p style="text-align: right;"><a href="javascript:window.close();">Close Window</a></p>
	<?php } //if($cwError == "Duplicate") ?>
</div>
</body>
</html>
<?php
cwDebugger($cartweaver);
?>