<table width="50%" border="0" align="center">            
<tr> 
<td><strong><a href="index.php">Home</a></strong></td>
<td><strong><a href="addBook.php">Add Book</a></strong></td>
<td><strong><a href="personal.php">Personal Books List</a></strong></td>
<td><strong><?php if(!empty($_SESSION['name'])) { echo "Welcome ".$_SESSION['name']; } ?></strong> <strong><?php if(!empty($_SESSION['name'])) { ?> <a href="logout.php">Logout</a> <?php } ?></strong></td>
</tr>
</table>
<br><br>