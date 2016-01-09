<?php

	require ('functions.php');

	session_start();


?>
<style>
	h1{
		margin: auto;
		width: 40%;
		padding: 10px;
	}
	#saveClick{
		float:right;
	}
	body{
		margin: auto;
		width: 80%;
		padding: 10px;		
	}
	.leftCol{
		width:400px;
	}
	.middleCol{
		width:75px;
	}
	.rightCol{
		width:10%;
	}
	.job{
		background-color:#90C3D4;
		width:100%;
	}
	.job:hover{
		background-color:green;
	}
		.person{
		background-color:#90C3D4;
		width:100%;
	}
	.person:hover{
		background-color:green;
	}
	.job:focus{
		background-color:green;
		color:white;
	}
	.date{
		width:100%;
	}
	.date:hover{
		background-color:green;
	}
	.date:focus{
		background-color:green;
	}
	.dateEarly{
		background-color:white;
	}
	.dateDue{
		background-color:#90C3D4;
	}
	.dateLate{
		background-color:red;
	}
	#jobTags{
		absolute:right;
		width
	}
	.treeButton {
		font-family:"Open Sans", "open_sans_local", Helvetica, Arial, sans-serif;
		text-align:center;
		padding:.25em .4em .45em .4em;
		color:#fff;
		background-color:#006600;
		border-radius:4px;
		border:0;
		cursor: pointer;
	}
	.treeButton:hover{
		background-color:green;
		color:black;
	}
	.treeButtonSel {
		font-family:"Open Sans", "open_sans_local", Helvetica, Arial, sans-serif;
		text-align:center;
		padding:.25em .4em .45em .4em;
		color:#fff;
		background-color:#282F9E;
		border-radius:4px;
		border:0;
		cursor: pointer;
	}
	.treeButtonSel:hover{
		background-color:blue;
		color:black;
	}
	textarea {
		resize: none;
	}
	

</style>
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