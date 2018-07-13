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

if(isset($_POST['bookAdd']) && ($_POST['bookAdd']==1))
{
	$title = $crudCatalog->escapeString($_POST['title']);	
	$isbn = $crudCatalog->escapeString($_POST['isbn']);	
	$author = $crudCatalog->escapeString($_POST['author']);		
	$description = $crudCatalog->escapeString($_POST['description']);
	$coverImage = $_FILES['coverImage']['name'];	
	$personal = $crudCatalog->escapeString($_POST['personal']);
	$addBookResult = $crudCatalog->addBook($title,$isbn,$author,$description,$coverImage,$personal);
}	
?>
<html>
<head>
<title>Add Book</title>
</head>

<body>
<?php include_once("includes/header.php"); ?>

<form action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="post" name="addBokForm" id="addBokForm" enctype="multipart/form-data" autocomplete="off">
<table width="25%" border="0" align="center">
            
			<tr> 
                <td></td>
                <td><strong><?php if(!empty($addBookResult)) { echo $addBookResult['message']; } ?></strong></td>
            </tr>
			<tr> 
                <td></td>
                <td height="20"></td>
            </tr>
			<tr> 
                <td>Title</td>
                <td><input type="text" name="title" id="title"></td>
            </tr>
            <tr> 
                <td>ISBN</td>
                <td><input type="text" name="isbn" id="isbn"></td>
            </tr>
			 <tr> 
                <td>Author</td>
                <td><input type="text" name="author" id="author"></td>
            </tr>
			 <tr> 
                <td>Description</td>
                <td><input type="text" name="description" id="description"></td>
            </tr>
			 <tr> 
                <td>Cover Image</td>
                <td><input type="file" name="coverImage" id="coverImage"></td>
            </tr>
			<tr> 
                <td>Do you need personal list?</td>
                <td><input type="radio" name="personal" id="personalYes" value="1"> Yes <input type="radio" name="personal" id="personalNo" value="0" checked="checked"> No</td>
            </tr>
           <tr>
			<td></td>
			<td><input type="submit" name="bookAdd" id="bookAdd" value="Submit">
			<input type="hidden" name="bookAdd" id="bookAdd" value="1">&nbsp;&nbsp;<a href="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>">Cancel</a></td>
			</tr>
</table>
</form>
</body>
</html>