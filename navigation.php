<?php

	require ('functions.php');
	require ('style.php');
	session_start();


?>
<h1>
	<?php
	if(isset($_POST['newJobType'])){
		if($_POST['newJobType']=='newToDo'){
			$_SESSION['page']='ToDo';
			$newJob='toDo';
		}
		if($_POST['newJobType']=='newAwaiting'){
			$_SESSION['page']='Awaiting';
			$newJob='awaiting';
		}
	} else {
		$newJob='none';
	}

		if(!isset($_POST['page']) & !isset($_SESSION['page'])){
			$_SESSION['page']="ToDo";
		} else {
			if (isset($_POST['page'])){
				$_SESSION['page']=$_POST['page'];
			}
		}
		if ($_SESSION['page']=="ToDo"){ 
			$toDo="<button id='ToDoPage' name='page' value='ToDo' class='treeButtonSel'> To Do </button>";
		} else {
			$toDo="<button id='ToDoPage' name='page' value='ToDo' class='treeButton'> To Do </button>";
		}
		if ($_SESSION['page']=="Awaiting"){ 
			$awaiting="<button id='AwaitingPage' name='page' value='Awaiting'  class='treeButtonSel'> Awaiting </button>";
		} else {
			$awaiting="<button id='AwaitingPage' name='page' value='Awaiting' class='treeButton'> Awaiting </button>";
		}
		if ($_SESSION['page']=="Done"){ 
			$done="<button id='DonePage' name='page' value='Done'  class='treeButtonSel'> Done </button>";
		} else {
			$done="<button id='DonePage' class='treeButton' name='page' value='Done'> Done </button>";
		}
		
		$jsonPage=json_encode($_SESSION['page']); // to allow refreshing of page without rePOSTing data;
		
		echo "$toDo$awaiting$done";
	?>
</h1>
<script type="text/javascript" src="http://code.jquery.com/jquery-latest.min.js"></script>

<script>
	var refreshPage=<?=$jsonPage?>;
</script>