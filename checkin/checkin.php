<?php
require dirname(__FILE__) . "/../classes/all_classes_include.php";

$action = "insert"; // the default action for the page load
$CheckinDao = new CheckinDao();

$checkin = new Checkin();

isset($_POST["first_name"]) ? $checkin->first_name = $_POST["first_name"] : $checkin->first_name = "";
isset($_POST["last_name"]) ? $checkin->last_name = $_POST["last_name"] : $checkin->last_name = "";
isset($_POST["instructor"]) ? $checkin->instructor = $_POST["instructor"] : $checkin->instructor = "";
isset($_POST["instrument"]) ? $checkin->instrument = $_POST["instrument"] : $checkin->instrument = "";
if (isset($_POST["btnsubmit"]) ) {
	$CheckinDao->insert($checkin);
	$checkin->first_name = "";
	$checkin->last_name = "";
	$checkin->instructor = "";
	$checkin->Instrument = "";
    $message = "Something cool";
    $teacherDao = new TeacherDao();
    $teacher = $teacherDao->getTeacherByid($_POST["instructor_id"]);
    $email = $teacher->email;
    mail($email, 'My Subject', $message);
}

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>RVMC-CheckIn</title>

    <!-- Bootstrap -->
    <link href="css/bootstrap-4.4.1.css" rel="stylesheet">
	<link href="css/styles.css" rel="stylesheet">
  </head>
	
  <style>
		
	html, body { 
		  background: url("img/piano.jpeg") no-repeat center center fixed; 
		  -webkit-background-size: cover;
		  -moz-background-size: cover;
		  -o-background-size: cover;
		  background-size: cover;
		  height: 90%;
	}
	 
	.modal-backdrop {
		/* bug fix - no overlay */    
		display: none;    
	}
	 

	* {
	  box-sizing: border-box;
	}

	/* Add styles to the form container */

	/* Full-width input fields */
	  input[type=text], input[type=password] {
	  width: 100%;
	  padding: 15px;
	  margin: 5px 0 22px 0;
	  border: none;
	  background: #C6C6C6;
	}

	input[type=text]:focus, input[type=password]:focus {
	  background-color: #ddd;
	  outline: none;
	}

	  .selector{
		  width: 100%;
		  padding: 15px;
		  margin: 5px 0 35px 0;
		  background: #C6C6C6;
		  text-align: center;
		  border: none;
		  border-radius: 4px;
	  }
	
	  
	/* Set a style for the submit button */
	.btn {
	  background-color: #04AA6D;
	  color: white;
	  padding: 16px 20px;
	  border: none;
	  cursor: pointer;
	  width: 100%;
	  opacity: 0.9;
	}

	.btn:hover {
	  opacity: 1;
	}
  </style>
	
	
  <body>
	  <div class="container" style="height: 100%; max-height: 100%;">
		<form name="frmCheckin" id="myForm" class="stu_form" method="post" action="checkin.php" style="opacity: .9;" >
		 <h1>Check-In</h1>
		 <label for="first_name"><b>First Name</b></label>
		 <input type="text"  id="first_name" name="first_name" style=" margin: 5px 0 10px 0; border-radius: 4px;" required value="<?=$checkin->first_name?>">
		 <label for="last_name"><b>Last Name</b></label><br>
		 <input type="text"  id="last_name" name="last_name" style=" margin: 5px 0 35px 0; border-radius: 4px;" required value="<?=$checkin->last_name?>">
			
		<!-- Button trigger modal -->
		<div class="row" style="align-content: center; width: 100%; margin: auto;">
		  <div class="column" style="width:50%;"><button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal" style="background-color: #C6C6C6; color: black; margin: 5px 0 35px 0; width: 95%;">
		  Select Instructor
		</button></div>
		  <div class="column"  style="width: 50%;"><input id="instructor" name="instructor" type="text" class="field left" style="text-align: center; border-radius: 4px; background-color: #C6C6C6" readonly value="<?=$checkin->instructor?>"></div>
		</div>
            <input type="hidden" name="instructor_id" id="instructor_id" value="">
			
		<select id="instrument" name="instrument" class="selectpicker selector">
		  <optgroup label="Instrument">
			<option selected="selected">Select Instrument</option>
			<option>Guitar</option>
			<option>Drums</option>
			<option>Piano</option>
			<option>Violen</option>
			<option>Vocals</option>
		  </optgroup>
		</select>

		<?php
		if (isset($_POST["btnsubmit"]) ) {
		?>

		<div class="alert alert-warning alert-dismissible fade show" id="alert" role="alert">
		  <strong>You've checked in!</strong>
		  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
			<span aria-hidden="true">&times;</span>
		  </button>
		</div>				

		<?php
         }
         ?>

		<input type="submit" name="btnsubmit" class="btn btn-success" value="Submit">

		<!-- Modal -->
		<div class="modal fade" id="myModal" style="height: 90%; opacity: 1;">
  			<div class="modal-dialog modal-dialog-scrollable modal-xl" role="document">
   			 <div class="modal-content">
				 <!-- Modal Header -->
    		      <div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">Our Instructors</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					  <span aria-hidden="true">&times;</span>
					</button>
				  </div>
			  <div class="modal-body">
                <div class="row gx-5 justify-content-center">
                   
                            <div class="card" style="width: 18rem; margin: 1.5rem;">
							  <img class="card-img-top" src="img/guitar.jpeg" alt="Card image cap">
							  <div class="card-body">
								<input type="text" id="input1" value="Instructor 1" readonly>
								<p>This is sample text that means absolutely nothing.</p>
								<button type="button" onclick="getInputValue1();" class="btn btn-primary" id="btn1">Pick Me!</button>
							  </div>
							</div>
                        
                    <div class="card" style="width: 18rem; margin: 1.5rem;">
							  <img class="card-img-top" src="img/guitar.jpeg" alt="Card image cap">
							  <div class="card-body">
								<input type="text" id="input2" value="Instructor 2" readonly>
								<p>This is sample text that means absolutely nothing.</p>
								<button type="button" onclick="getInputValue2();" class="btn btn-primary" id="btn2">Pick Me!</button>
							  </div>
							</div>
				</div>
				<div class="row gx-5 justify-content-center">
                   
                            <div class="card" style="width: 18rem; margin: 1.5rem;">
							  <img class="card-img-top" src="img/guitar.jpeg" alt="Card image cap">
							  <div class="card-body">
								<input type="text" id="input3" value="Instructor 3" readonly>
								<p>This is sample text that means absolutely nothing.</p>
								<button type="button" onclick="getInputValue3();" class="btn btn-primary" id="btn3">Pick Me!</button>
							  </div>
							</div>
                        
                    <div class="card" style="width: 18rem; margin: 1.5rem;">
							  <img class="card-img-top" src="img/guitar.jpeg" alt="Card image cap">
							  <div class="card-body">
								<input type="text" id="input4" value="Instructor 4" readonly>
								<p>This is sample text that means absolutely nothing.</p>
								<button type="button" onclick="getInputValue4();" class="btn btn-primary" id="btn4">Pick Me!</button>
							  </div>
							</div>
				</div>
				<div class="row gx-5 justify-content-center">
                   
                            <div class="card" style="width: 18rem; margin: 1.5rem;">
							  <img class="card-img-top" src="img/guitar.jpeg" alt="Card image cap">
							  <div class="card-body">
								<input type="text" id="input5" value="Instructor 5" readonly>
								<p>This is sample text that means absolutely nothing.</p>
								<button type="button" onclick="getInputValue5();" class="btn btn-primary" id="btn5">Pick Me!</button>
							  </div>
							</div>
                        
                    <div class="card" style="width: 18rem; margin: 1.5rem;">
							  <img class="card-img-top" src="img/guitar.jpeg" alt="Card image cap">
							  <div class="card-body">
								<input type="text" id="input6" value="Instructor 6" readonly>
								<p>This is sample text that means absolutely nothing.</p>
								<button type="button" onclick="getInputValue6();" class="btn btn-primary" id="btn6">Pick Me!</button>
							  </div>
							</div>
                   
				</div>
				<div class="row gx-5 justify-content-center">
                   
                            <div class="card" style="width: 18rem; margin: 1.5rem;">
							  <img class="card-img-top" src="img/guitar.jpeg" alt="Card image cap">
							  <div class="card-body">
								<input type="text" id="input7" value="Instructor 7" readonly>
								<p>This is sample text that means absolutely nothing.</p>
								<button type="button" onclick="getInputValue7();" class="btn btn-primary" id="btn7">Pick Me!</button>
							  </div>
							</div>
                    <div class="card" style="width: 18rem; margin: 1.5rem;">
							  <img class="card-img-top" src="img/guitar.jpeg" alt="Card image cap">
							  <div class="card-body">
								<input type="text" id="input8" value="Instructor 8" readonly>
								<p>This is sample text that means absolutely nothing.</p>
								<button type="button" onclick="getInputValue8();" class="btn btn-primary" id="btn8">Pick Me!</button>
							  </div>
							</div>
                </div> 
			</div> 
          </div>
		</div>			
	  </div>
	</form><br>
    </div>
	  
	<!-- Footer -->  
	<footer>
	   <p>River Valley Music Center</p> 
	</footer>
    
	
	<script>
		<?php 
        if (isset($_POST["btnsubmit"])) {
        ?>
        $(document).ready(function() {
            myClick();
        });
        function myClick() {
            setTimeout( function() { $('.alert').alert('close'); }, 3000 );
        }
        <?php
        }
        ?>

		function getInputValue1(){  
			document.getElementById("instructor").value = document.getElementById("input1").value
            document.getElementById("instructor_id").value = 1;
            getInstruments(1);
			$("#myModal").hide();
		};
		function getInputValue2(){  
			document.getElementById("instructor").value = document.getElementById("input2").value
            document.getElementById("instructor_id").value = 3;
            getInstruments(3);
			$("#myModal").hide();
		};
		function getInputValue3(){  
			document.getElementById("instructor").value = document.getElementById("input3").value
            document.getElementById("instructor_id").value = 4;
            getInstruments(4);
			$("#myModal").hide();
		};
		function getInputValue4(){  
			document.getElementById("instructor").value = document.getElementById("input4").value
			$("#myModal").hide();
		};
		function getInputValue5(){  
			document.getElementById("instructor").value = document.getElementById("input5").value
			$("#myModal").hide();
		};
		function getInputValue6(){  
			document.getElementById("instructor").value = document.getElementById("input6").value
			$("#myModal").hide();
		};
		function getInputValue7(){  
			document.getElementById("instructor").value = document.getElementById("input7").value
			$("#myModal").hide();
		};
		function getInputValue8(){  
			document.getElementById("instructor").value = document.getElementById("input8").value
			$("#myModal").hide();
		};

        function getInstruments(id) {
            $(document).ready(function() {
                $.ajax({
                    url: "./data.php?id=" + id
                }).then(function(data) {
                    var i, L = document.getElementById("instrument").options.length - 1;
                    for(i = L; i >= 0; i--) {
                        document.getElementById("instrument").remove(i);
                    }

                    var options = data.split(",");
                    for (i = 0; i < options.length; i++) {
                        console.log(JSON.stringify(options[i]));
                        var opt = document.createElement('option');
                        opt.value = options[i];
                        opt.innerHTML = options[i];
                        document.getElementById("instrument").appendChild(opt);
                    }
                });
            });
        }

		function confirmation(){
			alert("Form successfully submitted!")
			document.getElementById("instructor").value = ""
			document.getElementById("first_name").value = ""
			document.getElementById("last_name").value = ""
			$('#instrument').get(0).selectedIndex = 0;
		}
		
		
	</script>
	  
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) --> 
    <script src="js/jquery-3.4.1.min.js"></script>

    <!-- Include all compiled plugins (below), or include individual files as needed --> 
    <script src="js/popper.min.js"></script>
  	<script src="js/bootstrap-4.4.1.js"></script>
	<script src="js/plugin.js"></script>
	<?php
     
    ?>
  </body>
</html>
