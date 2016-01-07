<?php

	/* To-Do, Awaiting, Done
	
	A To-Do list by Anthony Haffey
	
	*/
	
	
?>
<body>
<?php
	require('navigation.php');
?>
	<form action="index.php">
		<input name="newJob" id="newJob" placeholder="new job here">
		<input type ="date" name="newJobDate" id="newJobDate">
		<button class="treeButton" value="newToDo">To Do</button>
		<button class="treeButton" value="newAwaiting">Awaiting</button>
		<button class="treeButton" value="newDone">Done</button>
		<div style="clear:both"></div>
		
		<table style="float:left">
		<?php
		
		

			$toDoArray=csv_to_array('ToDo.csv');
			$jobNo=0;
			$today=strtotime(date('j F Y'));
			$today=($today-.1);

			foreach($toDoArray as $toDoItem){
				$jobNo++;
				echo "<tr>";
					echo "<td class='leftCol'><textarea onkeydown='resizeTextarea(job$jobNo)' class='job' id='job$jobNo'>".$toDoItem['Job']."</textarea><td>";
					
				//identify date here
					$toDoDate=explode('/',$toDoItem['Due']);
					$toDoMonth=$toDoDate[1];
					$toDoDay=$toDoDate[0];
					$toDoDate[0]=$toDoMonth;
					$toDoDate[1]=$toDoDay;
					$toDoItem['Due']=implode($toDoDate,'/');
					$diffDates=$today-$toDoItem['Due'];
								
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
					
					
					echo "<td class='middleCol'><textarea class='date date$dateClass' id='date$jobNo'>".$toDoItem['Due']."</textarea><td>";
					
					echo "<td><button value='Done!' class='treeButton' id='Done$jobNo'>Done</button></td>";
				echo "<tr>";
				
			}		
		?>
		</table>
	</form>
	<div id="jobGroups">
		List of types of jobs here
	</div>
<body>
<script>
// adjust the height for all of the jobs???



function resizeTextarea(o) {
    o.style.height = "1px";
    o.style.height = (o.scrollHeight+5)+"px";
} // solution provided by Alsciende on http://stackoverflow.com/questions/995168/textarea-to-resize-based-on-content-length

</script>