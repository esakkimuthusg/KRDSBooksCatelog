<?php
if (!isset($_SESSION)) session_start();	
if(!empty($_SESSION['username']))
{
	header('Location:index.php');
}
include_once("classes/DBConfig.php");
include_once("classes/CrudCatalog.php");
$formMssage='';

$crudCatalog = new CrudCatalog();

if(isset($_POST['userLogin']) && ($_POST['userLogin']==1))
{
	$username = $_POST['username'];
	$password = $_POST['password'];

	//Verify form
	$formMssage = "";
	$captchaMssage = true;
	if(trim($username)=="") {
		$formMssage = "Your email/mobile is required.";
	} elseif(trim($password)=="") {
		$formMssage = "Your password is required.";
	} else {
		
		$password = md5($password);
		
		$userNameExistCheckCount = $crudCatalog->usernameExistCheck($username);
		
		if($userNameExistCheckCount>0)
		{
			$userLoginValid = $crudCatalog->loginValid($username,$password);
			$formMssage = $userLoginValid['message'];
			header("Location:addBook.php");
		}
		else
		{
			$formMssage = "username does not exist.";
		}	
	}
}

?>
<html>
<head>
<title>Books</title>
</head>

<body>

<form action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="post" name="userLoginForm" id="userLoginForm" autocomplete="off">
<table width="25%" border="0" align="center">
            
			<tr> 
                <td colspan="2"><h3>Login</h3></td>
            </tr>
			<tr> 
                <td colspan="2"><strong><?php echo $formMssage; ?></strong></td>
            </tr>
			<tr> 
                <td>Username</td>
                <td><input type="text" name="username" id="username"></td>
            </tr>
            <tr> 
                <td>Password</td>
                <td><input type="text" name="password" id="password"></td>
            </tr>
           <tr>
			<td></td>
			<td><input type="submit" name="userLogin" id="userLogin" value="Search">
			<input type="hidden" name="userLogin" id="userLogin" value="1">&nbsp;&nbsp;<a href="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>">Cancel</a></td>
			</tr>
</table>
</form>

</body>
</html>