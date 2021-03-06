<?php
if (!isset($_SESSION)) session_start();	
if(empty($_SESSION['username']))
{
	header('Location:login.php');
}
include_once("classes/DBConfig.php");
include_once("classes/CrudCatalog.php");

$crudCatalog = new CrudCatalog();
$searchBookResult='';
$searchFinalQuery='';
$searchTitlQuery='';
$searchIsbnQuery='';
$bookResult='';

if(isset($_POST['bookSearch']) && ($_POST['bookSearch']==1))
{
	$searchQuery='';
	$title = $crudCatalog->escapeString($_POST['title']);	
	$isbn = $crudCatalog->escapeString($_POST['isbn']);	
	
	if($title!="" && $isbn=="")
	{
		$searchTitlQuery = "title LIKE '%$title%'";
		$searchQuery = $searchTitlQuery;
	}
	if($isbn!="" && $title=="")
	{
		$searchIsbnQuery.= "isbn LIKE '%$isbn%'";
		$searchQuery = $searchIsbnQuery;
	}
	if($title!="" && $isbn!="")
	{
		$searchQuery = $searchTitlQuery." OR ".$searchIsbnQuery;
	}
	
	$searchFinalQuery=($searchQuery=='') ? " WHERE bu.personal=1" : "WHERE ".$searchQuery;
	$bookResult = $crudCatalog->getData($searchFinalQuery,'personal');
}	
else
{
	$searchFinalQuery=" WHERE bu.personal=1";
	$bookResult = $crudCatalog->getData($searchFinalQuery,'personal');
}
?>
<html>
<head>
<title>Books</title>
</head>

<body>

<?php include_once("includes/header.php"); ?>

<form action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="post" name="searchBookForm" id="searchBookForm" autocomplete="off">
<table width="25%" border="0" align="center">
            
			<tr> 
                <td colspan="2"><h3>Search Books</h3></td>
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
			<td></td>
			<td><input type="submit" name="bookSearch" id="bookSearch" value="Search">
			<input type="hidden" name="bookSearch" id="bookSearch" value="1">&nbsp;&nbsp;<a href="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>">Cancel</a></td>
			</tr>
</table>
</form>

<table width="50%" align="center">		
	<tr> 
		<?php 
		if(!empty($bookResult) && $bookResult['status']="success") 
		{
			$inc=0;
			foreach ($bookResult['data'] as $key => $res) { 
			
			if(!empty($_SESSION['name'])) {
			$modifyAccess  = $crudCatalog->modifyAccess($res['id']);
			}
			else{
				$modifyAccess  = 0;
			}	
		?>
		<td><table width="100%" align="center">
			<tr>
				<td><img src="<?php echo UPLOADS_BOOKSDIR.$res['coverImage'];?>" width="200" height="300"></td>
			</tr>
			<tr> 
               <td height="10"></td>
            </tr>
			<tr>
				<td><strong><h2><?php echo $res['title']; ?></h2></strong></td>
			</tr>
			<tr>
				<td><?php echo $res['description']; ?></td>
			</tr>
			<tr>
				<td><?php if($modifyAccess>0) { ?><a href="editBook.php?id=<?php echo $res['id']; ?>">Edit Book</a>  &nbsp;&nbsp; <a href="javascript:delBook('<?php echo $res['id']; ?>');">Delete</a> <?php } ?></td>
			</tr>
			</table>
		</td>
		<?php 
			if($inc==3){ 
				echo "</tr><tr>"; 
				$inc=0; 
				} 
			$inc++; 
			}  
		}  
		if(!empty($bookResult) && $bookResult['status']="fail"){ ?>
		<tr> 
            <td align="center"><strong><?php if(!empty($bookResult)) { echo $bookResult['message']; } ?></strong></td>
        </tr>
		<?php } ?>	
	</tr>
</table>

</body>
</html>