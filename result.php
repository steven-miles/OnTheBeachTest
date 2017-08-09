<html>
<head>
	<title>Test Results</title>
    <?php 
		// remove the last \n or whitespace character
		$text = trim($_POST['input']); 
		
		//use this text value to find each line and place in an array
		$textarray = explode("\n", $text);
		
		//string to store what we will pass as a final result
		$finalstring = "";
		
		//function to check if text is a single value
		function checkValue($input){
			if(trim(substr($input, -1)) == ">"){
				return true;	
			} else{
				return false;	
			}
		}
		
		//function to check if a value has a dependancy
		function hasDependancy($input){
			if(trim(substr($input, -1)) !== ">" && strpos($input, '=>') !== false){
				return true;
			} else{
				return false;	
			}
		}
	?>
</head>

<body>
	<h1> Results: </h1>
	<?php
		//use this to show if there was an error error
		$error = 0;
        //loop through the array to check
		foreach($textarray as &$value){
			$leftvalue = substr(trim($value), 0, 1);
			$rightvalue = substr(trim($value), -1);
			if( $leftvalue == $rightvalue){
				//if a task depends upon itself
				$error = 1;
			}else if (substr(trim($text), -1) != ">"){
				//if the last value is a dependancy
				$error = 2;
			}else if (checkValue(trim($value)) === true) { //check if this is a single value
				//add the first character to the string if it is not already in the string
				if(strpos($finalstring, $leftvalue) === false){
					$finalstring .= $leftvalue;
				}
			}else if(hasDependancy(trim($value)) === true){
				//first add the left value to the string if it's not already in the string
				if(strpos($finalstring, $leftvalue) === false){
					$finalstring .= $leftvalue;
				}
				
				//check if the second value is already in the string
				if(strpos($finalstring, $rightvalue) !== false){
					//if it is in the string already then delete it
					$finalstring = str_replace($rightvalue, '', $finalstring);
				}
				
				//now place the second value before the first
				$finalstring = substr_replace($finalstring, substr(trim($value), -1), strpos($finalstring, $leftvalue), strpos($finalstring, $rightvalue));
			}else {
				echo "";
			}
		}
		
		if($error == 0){
			//finally display the string
			echo($finalstring);
		} elseif($error == 1){
			//error code for dependancies relying on theirselves
			echo("Dependancies cannot rely on theirselves");	
		} elseif($error == 2){
			//error code for circular dependancies
			echo("Jobs can't have circular dependancies");	
		}
    ?>
</body>
</html>