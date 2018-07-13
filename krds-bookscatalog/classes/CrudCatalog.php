<?php
if (!isset($_SESSION)) session_start();	
define("UPLOADS_BOOKSDIR","uploads/books/");
define("KRDS_BOOKS","krds_books");
define("KRDS_USERS","krds_users");
define("KRDS_BOOKS_USERS","krds_books_users");

class CrudCatalog extends DbConfig
{
	public function __construct()
	{
		parent::__construct();
	}	
	
	public function addBook($title,$isbn,$author,$description,$coverImage,$personal)
	{
		$bookDetailsArr = array();	
		
		if($coverImage!=""){
		
			$bookCoverImage = rand().$coverImage;
			$bookCoverImagePath = UPLOADS_BOOKSDIR.$bookCoverImage;
			copy($_FILES['coverImage']['tmp_name'],$bookCoverImagePath);
		}	
		
		if($title=="" && $isbn=="")
		{
			$bookDetailsArr['data'] = "";
			$bookDetailsArr['status'] = "fail";
			$bookDetailsArr['message'] = "Title and ISBN required";	
		}
		else
        {			
			$bookInsertQuery = "INSERT INTO ".KRDS_BOOKS."(title,author,isbn,description,coverImage) VALUES('".$title."','".$author."','".$isbn."','".$description."','".$bookCoverImage."')";
			$bookInsertResult = $this->connection->query($bookInsertQuery);
			$bookId=$this->connection->insert_id;
			
			$bookUsersInsertQuery = "INSERT INTO ".KRDS_BOOKS_USERS."(userId,bookId,personal) VALUES(".$_SESSION['id'].",'".$bookId."','".$personal."')";
			$bookUsersInsertResult = $this->connection->query($bookUsersInsertQuery);
			
			if($bookInsertResult==true)
			{
				$bookDetailsArr['data'] = "";
				$bookDetailsArr['status'] = "success";
				$bookDetailsArr['message'] = "Book details added successfully.";
			}
			else
			{
				$bookDetailsArr['data'] = "";
				$bookDetailsArr['status'] = "fail";
				$bookDetailsArr['message'] = "Oops something went wrong.";	
			}	
		}
		
		return 	$bookDetailsArr;
	}
	
	public function getData($searchFinalQuery,$action)
	{
		$rowsDataArr = array();
		$bookDetailsArr = array();
		
		if($action=="all")
		{
			$query = "SELECT `id`, `title`, `author`, `isbn`, `description`, `coverImage` FROM ".KRDS_BOOKS." ".$searchFinalQuery."";
			$result = $this->connection->query($query);
			$booksCount = $result->num_rows;
	    }
		if($action=="personal")
		{
			$query = "SELECT b.id, b.title, b.author, b.isbn, b.description, b.coverImage FROM ".KRDS_BOOKS." AS b 
					  INNER JOIN  ".KRDS_BOOKS_USERS." AS bu ON bu.bookId=b.id ".$searchFinalQuery." ";
			$result = $this->connection->query($query);
			$booksCount = $result->num_rows;
	    }
		
		if($booksCount>0){
			while($rows = $result->fetch_assoc())
			{
				$rowsDataArr[] = $rows;
			}
			
			$bookDetailsArr['data'] = $rowsDataArr;
			$bookDetailsArr['status'] = "success";
			$bookDetailsArr['message'] = "";
			
		}else{
			 $bookDetailsArr['data'] = [];
			 $bookDetailsArr['status'] = "fail";
			 $bookDetailsArr['message'] = "No book found.";	
		}
		
		return 	$bookDetailsArr;
	}

	public function usernameExistCheck($username)
	{
		$query = "SELECT username FROM ".KRDS_USERS." WHERE `username`='".$username."'";
		$result = $this->connection->query($query);
		$usernameCount = $result->num_rows;
		return $usernameCount;
    }
	public function loginValid($username,$password)
	{
		$query = "SELECT id,name,username FROM ".KRDS_USERS." WHERE `username`='".$username."' AND `password`='".$password."'";
		$result = $this->connection->query($query);
		$userLoginCount = $result->num_rows;
		if($userLoginCount>0)
		{
			while ($userLoginObj = mysqli_fetch_object($result)){
			$_SESSION['id']=$userLoginObj->id;
			$_SESSION['name']=$userLoginObj->name;
			$_SESSION['username']=$userLoginObj->username;
			}
			
			$bookDetailsArr['data'] =[];
			$bookDetailsArr['status'] = "success";
			$bookDetailsArr['message'] = "Logged in successfully...";
		}
		else
		{
			$bookDetailsArr['data'] =[];
			$bookDetailsArr['status'] = "fail";
			$bookDetailsArr['message'] = "username and password does not match.";
		}
		
		return 	$bookDetailsArr;
    }
	
	public function editData($bookId)
	{
		$query = "SELECT `id`, `title`, `author`, `isbn`, `description`, `coverImage` FROM ".KRDS_BOOKS." WHERE id=".$bookId."";
		$result = $this->connection->query($query);
		$booksCount = $result->num_rows;
		if($booksCount>0)
		{
			$userLoginObj = mysqli_fetch_object($result);
			$bookDetailsArr['data'] =$userLoginObj;
			$bookDetailsArr['status'] = "success";
			$bookDetailsArr['message'] = "";
		}
		else
		{
			$bookDetailsArr['data'] =[];
			$bookDetailsArr['status'] = "fail";
			$bookDetailsArr['message'] = "";
		}
	
		return 	$bookDetailsArr;
    }
	
	public function modifyAccess($bookId)
	{
		$query = "SELECT bookId FROM ".KRDS_BOOKS_USERS." WHERE `userId`=".$_SESSION['id']." AND bookId=".$bookId."";
		$result = $this->connection->query($query);
		$bookCount = $result->num_rows;
		return $bookCount;
	}	
	
	
	public function editBook($title,$isbn,$author,$description,$coverImage,$personal,$coverImageCurrent,$bookId)
	{
		$bookDetailsArr = array();	
		
		if($coverImage!=""){
			
			if(is_file(UPLOADS_BOOKSDIR.$coverImageCurrent))
			{
				unlink(UPLOADS_BOOKSDIR.$coverImageCurrent);
			}	
			
			$bookCoverImage = rand().$coverImage;
			$bookCoverImagePath = UPLOADS_BOOKSDIR.$bookCoverImage;
			copy($_FILES['coverImage']['tmp_name'],$bookCoverImagePath);
		}	
		else
		{
			$bookCoverImage = $coverImageCurrent;
		}	
		
		if($title=="" && $isbn=="")
		{
			$bookDetailsArr['data'] = "";
			$bookDetailsArr['status'] = "fail";
			$bookDetailsArr['message'] = "Title and ISBN required";	
		}
		else
        {			
			$bookUpdateQuery = "UPDATE ".KRDS_BOOKS." SET title='".$title."', author='".$author."', isbn='".$isbn."', description='".$description."',coverImage='".$bookCoverImage."' WHERE id=".$bookId."";
			$bookUpdateResult = $this->connection->query($bookUpdateQuery);
			
			$bookUsersUpdateQuery = "UPDATE ".KRDS_BOOKS_USERS." SET personal='".$personal."' WHERE userId=".$_SESSION['id']." AND bookId=".$bookId."";
			$bookUsersUpdateResult = $this->connection->query($bookUsersUpdateQuery);
			
			if($bookUpdateResult==true)
			{
				$bookDetailsArr['data'] = "";
				$bookDetailsArr['status'] = "success";
				$bookDetailsArr['message'] = "Book details updated successfully.";
			}
			else
			{
				$bookDetailsArr['data'] = "";
				$bookDetailsArr['status'] = "fail";
				$bookDetailsArr['message'] = "Oops something went wrong.";	
			}	
		}
		
		return 	$bookDetailsArr;
	}
	
	public function delData($bookId)
	{
		$bookDetailsArr = array();	
		
		$query = "SELECT `id`, `title`, `author`, `isbn`, `description`, `coverImage` FROM ".KRDS_BOOKS." WHERE id=".$bookId."";
		$result = $this->connection->query($query);
		$booksCount = $result->num_rows;
		if($booksCount>0)
		{
			$editBookResult = mysqli_fetch_object($result);
			$coverImage = $editBookResult->coverImage;
		}	
		
		if($coverImage!=""){
			
			if(is_file(UPLOADS_BOOKSDIR.$coverImage))
			{
				unlink(UPLOADS_BOOKSDIR.$coverImage);
			}	
		}	
			
					
		$bookDelQuery = "DELETE FROM ".KRDS_BOOKS." WHERE id=".$bookId."";
		$bookDelResult = $this->connection->query($bookDelQuery);
			
		if($bookDelResult==true)
		{
			$bookDetailsArr['data'] = "";
			$bookDetailsArr['status'] = "success";
			$bookDetailsArr['message'] = "Book details deleted successfully.";
		}
		else
		{
			$bookDetailsArr['data'] = "";
			$bookDetailsArr['status'] = "fail";
			$bookDetailsArr['message'] = "Oops something went wrong.";	
		}	
		
		return $bookDetailsArr;
	}
	
    public function escapeString($input)
    {
		return $this->connection->real_escape_string($input);
	}
}	

?>