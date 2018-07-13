<?php
if (!isset($_SESSION)) session_start();	
if(empty($_SESSION['username']))
{
	header('Location:login.php');
}	

include_once("classes/DBConfig.php");
include_once("classes/CrudCatalog.php");

$crudCatalog = new CrudCatalog();
$addBookResult='';

if($_GET['id'])
{
	$bookId = $_GET['id'];
	$editBookResult = $crudCatalog->editData($bookId);
	$modifyAccess  = $crudCatalog->modifyAccess($bookId);
}

if(isset($_POST['bookUpdate']) && ($_POST['bookUpdate']==1))
{
	$title = $crudCatalog->escapeString($_POST['title']);	
	$isbn = $crudCatalog->escapeString($_POST['isbn']);	
	$author = $crudCatalog->escapeString($_POST['author']);		
	$description = $crudCatalog->escapeString($_POST['description']);
	$coverImage = $_FILES['coverImage']['name'];	
	$personal = $crudCatalog->escapeString($_POST['personal']);
	$coverImageCurrent = $crudCatalog->escapeString($_POST['coverImageCurrent']);
	$editBookResult2 = $crudCatalog->editBook($title,$isbn,$author,$description,$coverImage,$personal,$coverImageCurrent,$bookId);
	$editBookResult = $crudCatalog->editData($bookId);
	$modifyAccess  = $crudCatalog->modifyAccess($bookId);
}	
?>
<html>
<head>
<title>Add Book</title>
</head>

<body>
<a href="index.php">Home</a>

<br><br>
<?php include_once("includes/header.php"); ?>

<form action="editBook.php?id=<?php echo $bookId; ?>" method="post" name="addBokForm" id="addBokForm" enctype="multipart/form-data" autocomplete="off">
<table width="25%" border="0" align="center">
            
			<tr> 
                <td></td>
                <td><strong><?php if(!empty($editBookResult2)) { echo $editBookResult2['message']; } ?></strong></td>
            </tr>
			<tr> 
                <td></td>
                <td height="20"></td>
            </tr>
			<tr> 
                <td>Title</td>
                <td><input type="text" name="title" id="title" value="<?php echo $editBookResult['data']->title; ?>"></td>
            </tr>
            <tr> 
                <td>ISBN</td>
                <td><input type="text" name="isbn" id="isbn" value="<?php echo $editBookResult['data']->isbn; ?>"></td>
            </tr>
			 <tr> 
                <td>Author</td>
                <td><input type="text" name="author" id="author" value="<?php echo $editBookResult['data']->author; ?>"></td>
            </tr>
			 <tr> 
                <td>Description</td>
                <td><input type="text" name="description" id="description" value="<?php echo $editBookResult['data']->description; ?>"></td>
            </tr>
			 <tr> 
                <td>Cover Image</td>
                <td><img src="<?php echo UPLOADS_BOOKSDIR.$editBookResult['data']->coverImage;?>" width="100" height="100">
				<input type="file" name="coverImage" id="coverImage">
				<input type="hidden" name="coverImageCurrent" id="coverImageCurrent" value="<?php echo $editBookResult['data']->coverImage; ?>">
				</td>
            </tr>
			<tr> 
                <td>Do you need personal list?</td>
                <td><input type="radio" name="personal" id="personalYes" value="1" <?php if($modifyAccess==1) { echo 'checked="checked"';} ?>> Yes 
				<input type="radio" name="personal" id="personalNo" value="0" <?php if($modifyAccess==0) { echo 'checked="checked"';} ?>> No</td>
            </tr>
           <tr>
			<td></td>
			<td><input type="submit" name="bookUpdate" id="bookUpdate" value="Submit">
			<input type="hidden" name="bookUpdate" id="bookUpdate" value="1">&nbsp;&nbsp;<a href="index.php">Cancel</a></td>
			</tr>
</table>
</form>
</body>
</html>