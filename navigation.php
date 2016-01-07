<?php

	require ('functions.php');



?>
<style>
	h1{
		margin: auto;
		width: 30%;
		padding: 10px;
	}
	body{
		margin: auto;
		width: 60%;
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
	.job:focus{
		background-color:green;
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
	#jobGroups{
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
	textarea {
		resize: none;
	}
	

</style>

	<h1>
		- Todo -  
		<button class="treeButton"> Awaiting </button>
		<button class="treeButton"> Done </button>
	</h1>
