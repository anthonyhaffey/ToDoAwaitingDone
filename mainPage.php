<?php

	/* To-Do, Awaiting, Done
	
	A To-Do list by Anthony Haffey
	
	*/
	
	
?>
<form action="mainPage.php" method="post">
	<input type='button' class='treeButton' value="Save" id="saveClick" onclick='saveFunc()'>
	<button id="saveButton" name='saveButton' style="display:none">Save</button>
<?php
	require('navigation.php');
?>	
	<body>
		<input name="newJob" id="newJob" placeholder="new job here">
		<input type ="date" name="newJobDate" id="newJobDate">
		<button name="newJobType" class="treeButton" value="newToDo">To Do</button>
		<input name="newPerson" id="newPerson" placeholder="Awaiting Person's name here">
		<button name="newJobType" class="treeButton" value="newAwaiting">Awaiting</button>
		<div style="clear:both"></div>
		<br>
		<br>
		Tag Filter:<input name="tagFilter" id="tagFilter" placeholder="which tags do you want?">
		<button name="filterButton" class="treeButton">Apply filter!</button>
		<button class="treeButton">Remove filter!</button>
		<br>
		<br>		
		<table style="float:left">
		<?php
			
		switch($_SESSION['page']){
			case 'ToDo':
				$thisArray='ToDo.csv';
				break;
			case 'Awaiting':
				$thisArray='Awaiting.csv';
				break;				
			case 'Done':
				$thisArray='Done.csv';
				break;
		}
		
		{// creating backups - do after log in sorted
			//if(file_exists()
			
		}
		
			$pageArray=csv_to_array($thisArray);
			
			{//check if anything's been posted
				if(isset($_POST['saveButton'])){
					for($i=0;$i<count($pageArray);$i++){
						$pageArray[$i]['Job']=$_POST['job'.$i];
						$pageArray[$i]['Tags']=$_POST['tag'.$i];
						if(isset($_POST['date'.$i])){
							$pageArray[$i]['Due']=$_POST['date'.$i];
						}
						if(isset($_POST['person'.$i])){
							$pageArray[$i]['Person']=$_POST['person'.$i];
						}
					}
				}
				array_to_csv($pageArray,$thisArray);//save edited array
				$pageArray=csv_to_array($thisArray);//just to make sure we're all up to date
				
				if(isset($_POST['jobNo'])){// means that a job has been selected as done
					$doneArray=csv_to_array('Done.csv');
					$thisRow=count($doneArray);
					$doneArray[$thisRow]['Job']=$pageArray[$_POST['jobNo']]['Job'];
					$doneArray[$thisRow]['Date']=date("Y-m-d");
					$doneArray[$thisRow]['Tags']=$pageArray[$_POST['jobNo']]['Tags'];
					$doneArray[$thisRow]['CreatedDate']=$pageArray[$_POST['jobNo']]['CreatedDate'];
					if(isset($pageArray[0]['Person'])){
						$doneArray[$thisRow]['Person']=$pageArray[$_POST['jobNo']]['Person'];
					} else {
						$doneArray[$thisRow]['Person']="blank"; // need to do this
					}
					unset($pageArray[$_POST['jobNo']]);
					array_to_csv($doneArray,'Done.csv');//save done array
					array_to_csv($pageArray,$thisArray);//save edited array
				}
				if(isset($_POST['toDoNo'])){ //means that a job is being transferred from this array to the To Do array
					$switchTo='ToDo';
					$switchJobNo=$_POST['toDoNo'];
				}
				if(isset($_POST['awaitingNo'])){ //means that a job is being transferred from this array to the To Do array
					$switchTo='Awaiting';
					$switchJobNo=$_POST['awaitingNo'];
				}
				
				if (isset($switchTo)){				
				
//					print_r($_POST);
					// add to toDo
					$receiveArray=csv_to_array("$switchTo.csv");
//					echo "<br>";
					$thisRow=count($receiveArray);
					$receiveArray[$thisRow]['Job']=$pageArray[$switchJobNo]['Job'];
					$receiveArray[$thisRow]['Due']=$pageArray[$switchJobNo]['Due'];
					$receiveArray[$thisRow]['Tags']=$pageArray[$switchJobNo]['Tags'];
					$receiveArray[$thisRow]['CreatedDate']=$pageArray[$switchJobNo]['CreatedDate'];
					$receiveArray[$thisRow]['Person']=$pageArray[$switchJobNo]['Person'];
					$receiveArray[$thisRow]['CompletedDate']=$pageArray[$switchJobNo]['CompletedDate'];
//					print_r($toDoArray);
					// unset from awaiting (thisPage)
					unset($pageArray[$switchJobNo]);
//					echo "<br>";
//					print_r($pageArray);
					array_to_csv($receiveArray,"$switchTo.csv");//save to recipient array
					array_to_csv($pageArray,$thisArray);//save edited array					// save both
				}
				
				/* Do the same for the awaiting button!!!
				if(isset($_POST['toDoNo'])){ //means that a job is being transferred from this array to the To Do array
					print_r($_POST);
				}
				*/
				
				if(isset($_POST['delete'])){
					unset($pageArray[$_POST['delete']]);
					array_to_csv($pageArray,$thisArray);//save edited array
				}
				if(isset($_POST['newJobType'])){
					if(strcmp($_POST['newJobType'],'newToDo')==0){
						$newRow=count($pageArray);
						$pageArray[$newRow]['Job']=$_POST['newJob'];
						$pageArray[$newRow]['Due']=$_POST['newJobDate'];
						$pageArray[$newRow]['CreatedDate']=date("Y-m-d");
						$pageArray[$newRow]['Tags']="blank";						
					} else { //must be an awaiting job
						$newRow=count($pageArray);
						$pageArray[$newRow]['Job']=$_POST['newJob'];
						$pageArray[$newRow]['Due']=$_POST['newJobDate'];
						$pageArray[$newRow]['CreatedDate']=date("Y-m-d");
						$pageArray[$newRow]['Tags']="blank";
						$pageArray[$newRow]['Person']=$_POST['newPerson'];
					}
					$pageArray[$newRow]['CompletedDate']="blank";
					array_to_csv($pageArray,$thisArray);//save edited array
				}
				$pageArray=csv_to_array($thisArray);//to avoid indexing issues
			}
			
			{// identify the order of jobs
				$orderArray=array();
				foreach($pageArray as $toDoItem){
					if(isset($toDoItem['Due'])){
						$slashCheck=explode('/',$toDoItem['Due']);
						if(count($slashCheck)>1){
							$toDoItem['Due']=$slashCheck[2].'-'.$slashCheck[1].'-'.$slashCheck[0];
						}
						$toDoItem['Due']=str_ireplace('-','',$toDoItem['Due']);
						$toDoItem['Due']=intval($toDoItem['Due']);
						array_push($orderArray,$toDoItem['Due']);
					} else {
						array_push($orderArray,$toDoItem['CompletedDate']);
					}
				}
				
				uasort($orderArray, 'cmp');
				
				//save in this order - then resume;
				ordered_array_to_csv($pageArray,$thisArray,array_keys($orderArray));
			}
			
			$pageArray=csv_to_array($thisArray);//to avoid indexing issues
			
			$jsonPage=json_encode($pageArray);
			
			{// Number of Jobs done today		
				$doneArray=csv_to_array('Done.csv');
				$jobsDoneToday=0;
				$today=date("Y-m-d");
				foreach($doneArray as $doneItem){
					//echo $today.'-vs-'.$doneItem['CompletedDate'];
					if(strcmp($today,$doneItem['CompletedDate'])==0){
						$jobsDoneToday++;
					}
				}
				?>
				<h1>Jobs Done Today:<?=$jobsDoneToday?></h1>
			<?php }
			{//	Listing jobs
				$jobNo=-1;
				$displaySetting='';
				foreach($pageArray as $toDoItem){
					$jobNo++;
					if(isset($_POST['filterButton'])){
						$haystack=$toDoItem['Tags'];
						$needle=$_POST['tagFilter'];
						if(stripos($haystack,$needle)!==false){
							$displaySetting='style="display:show"';
						} else {
							$displaySetting='style="display:none"';
						}
					}
					echo "<tr $displaySetting>";
						echo "<td class='leftCol'><textarea onclick='resizeTextarea(job$jobNo); editTags($jobNo)' onkeydown='resizeTextarea(job$jobNo)' name='job$jobNo' class='job' id='job$jobNo'>".$toDoItem['Job']."</textarea>";
						
						echo "<textarea name='tag$jobNo' id='tag$jobNo' style='display:none'>".$toDoItem['Tags']."</textarea>";
						
						echo "<td>";
					//identify date here
						if(isset($toDoItem['Due'])){
							$testDateFormat=explode('-',$toDoItem['Due']);
							if (count($testDateFormat)<3){
								$toDoDate=explode('/',$toDoItem['Due']);
								$toDoMonth=$toDoDate[1];
								$toDoDay=$toDoDate[0];
								$toDoYear=$toDoDate[2];
								$toDoDate[0]=$toDoYear;
								$toDoDate[1]=$toDoMonth;
								$toDoDate[2]=$toDoDay;
								$toDoItem['Due']=implode($toDoDate,'-');
							}
	//						$diffDates=$today-$toDoItem['Due'];
						
							$relativeDate=(strtotime(date("m/d/Y"))-strtotime($toDoItem['Due']))/(86400);

							if ($relativeDate<0){
								$dateClass='Early';
							}
							if ($relativeDate==0){
								$dateClass='Due';
							}
							if ($relativeDate>0){
								$dateClass='Late';
							}		
							
							echo "<td class='middleCol'><input type='date' class='date date$dateClass' id='date$jobNo' name='date$jobNo' value='".$toDoItem['Due']."'><td>";
							
							
							if(strcmp($_SESSION['page'],'ToDo')==0){ // add awaiting button
								echo "<td><button name='awaitingNo' value='$jobNo' class='treeButton' id='Awaiting$jobNo'>Awaiting</button></td>";
							} else {
								
								echo "<td><input id='person$jobNo' name='person$jobNo' class='person' value=".$toDoItem['Person']."></td>";
								echo "<td><button name='toDoNo' value='$jobNo' class='treeButton' id='ToDo$jobNo'>To DO</button></td>";
								
							}
							echo "<td><button name='jobNo' value='$jobNo' class='treeButton' id='Done$jobNo'>Done</button></td>";
													
						} 
						if(strcmp($_SESSION['page'],'Done')==0){ //i.e. is a done job
							echo "<td>".$toDoItem['CompletedDate']."</td>";
							echo "<td>".$toDoItem['Person']."</td>";
							echo "<td><button class='treeButton' name='undoneToDo' value=$jobNo>To Do</button></td>";
							echo "<td><button class='treeButton' name='undoneAwaiting' value=$jobNo>Awaiting</button></td>";
							
							
						}
						echo "<td><input type='button' class='treeButton' id value=Delete onclick='deleteFunc($jobNo)'></td>";
							
						echo "<td><button class='treeButton' id='delete$jobNo' name='delete' value=$jobNo style='display:none'>Delete</button></td>";
					
					echo "<tr>";				
				}
					
			}
		
		?>
		</table>		
		<div id="jobTags">
			<div id='displayJobText'></div>
			<textarea id="editTagsTextarea" name="editTagsTextarea" style="display:none"></textarea>
		</div>
	<body>
</form>
<script>
	function resizeTextarea(o) {
			o.style.height = "1px";
			o.style.height = (o.scrollHeight+5)+"px";
	} // solution provided by Alsciende on http://stackoverflow.com/questions/995168/textarea-to-resize-based-on-content-length
	function deleteFunc(x){
		delConf=confirm("Are you SURE you want to delete this job?");
		if (delConf== true){
			document.getElementById('delete'+x).click();
		}	
	}
	function saveFunc(x){
		saveConf=alert("Saving To Do List");
		document.getElementById('saveButton').click();	
	}
	$(document).on("keydown", disableF5);
	function disableF5(e) { 
		if ((e.which || e.keyCode) == 116) {
			e.preventDefault();
			var refCheck = confirm("do you want to save changes?");
			if (refCheck==true){
						alert ('save not yet installed. Sorry :-(');
			} else {
				refCheck2=confirm("Do you want to refresh the page anyway (lose changes) or continue with current work?");
				if (refCheck2==true){
					document.getElementById(refreshPage+'Page').click();
				}
			}
		}
	};
	$(window).bind('keydown', function(event) {
			if (event.ctrlKey || event.metaKey) {
					switch (String.fromCharCode(event.which).toLowerCase()) {
					case 's':
							event.preventDefault();
							alert('Saving');
							$("#saveButton").click();
							break;
					}
			}
	});
	var thisPage = <?=$jsonPage?>;
	var tagsInProgress;
	function editTags(x){
		//alert(x);
		var theseTags=document.getElementById('tag'+x).value;
		theseTags=theseTags.split(';');
		document.getElementById('editTagsTextarea').value=theseTags;
		$('#editTagsTextarea').show();
		document.getElementById('displayJobText').innerHTML=document.getElementById('job'+x).value;
		tagsInProgress = 'tag'+x;
	}
	$('#editTagsTextarea').on("keyup", function(){
		//alert("editing this area");
		document.getElementById(tagsInProgress).value=document.getElementById('editTagsTextarea').value;
	});

</script>