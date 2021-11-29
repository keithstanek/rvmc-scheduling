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
	
    $teacherDao = new TeacherDao();
    $teacher = $teacherDao->getTeacherByid($_POST["instructor_id"]);
    //$to = $teacher->email;
	$to = "mwilliams105@atu.edu";
	$subject = "$checkin->first_name $checkin->last_name has arrived for their $checkin->instrument lesson.";
	$message = "Hello, $checkin->instructor <br>$checkin->first_name $checkin->last_name has arrived for their $checkin->instrument lesson.";
	
    mail($to,$subject,$message);
	
	$checkin->first_name = "";
	$checkin->last_name = "";
	$checkin->instructor = "";
	$checkin->Instrument = "";
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
            <option>Banjo</option>
            <option>Bass</option>
            <option>Bassoon</option>
            <option>Clarinet</option>
            <option>Dobro</option>
            <option>Drum</option>
            <option>Flute</option>
            <option>Guitar</option>
            <option>Mandolin</option>
            <option>Music Theory</option>
            <option>Oboe</option>
            <option>Piano</option>
            <option>Recorder</option>
            <option>Saxophone</option>
            <option>Slide</option>
            <option>Ukulele</option>
            <option>Violin</option>
            <option>Vocal</option>
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
							  <img class="card-img-top" src="img/1.jpeg" alt="Card image cap">
							  <div class="card-body">
								<input type="text" id="input1" value="Victoria Greenawalt" readonly>
								<p>Victoria Greenawalt is our manager here at River Valley Music Center. She teaches beginner through advanced voice, beginnner piano, and beginner ukulele. She also teaches concepts such as music theory, ear training, and sight reading. 
									Victoria has lived in the River Valley most of her life, went to grade school in Russellville, and graduated from Arkansas Tech University with her Bachelor's of Music Education. She is currently teaching elementary music, and is the choir director at Atkins School district. She has also been singing on church worship teams since she was 13.
									Music had been such a big part of Victoria's life, she knew at a very young age that teaching others music was what she wanted to do. "It's a dream come true to be able to teach students to love music, and to appreciate all that it has to offer. It's such a magical moment to see someone get a concept, or sing something in a way they haven't before, and it just clicks for them. You can see the almost surprised look on their face, and they know they've created something special. That's why I do what I do!" says Greenawalt.
									In her free time, Victoria loves to spend time with her family and friends. She also loves to spend time  outdoors, watch her favorite shows with her husband, or make crafting projects.</p>
								<button type="button" onclick="getInputValue1();" class="btn btn-primary" id="btn1">Pick Me!</button>
							  </div>
							</div>
                        
                    <div class="card" style="width: 18rem; margin: 1.5rem;">
							  <img class="card-img-top" src="img/guitar.jpeg" alt="Card image cap">
							  <div class="card-body">
								<input type="text" id="input2" value="Carolyn Mortenson" readonly>
								<p>No Bio Available.</p>
								<button type="button" onclick="getInputValue2();" class="btn btn-primary" id="btn2">Pick Me!</button>
							  </div>
							</div>
				</div>
				<div class="row gx-5 justify-content-center">
                   
                            <div class="card" style="width: 18rem; margin: 1.5rem;">
							  <img class="card-img-top" src="img/3.jpeg" alt="Card image cap">
							  <div class="card-body">
								<input type="text" id="input3" value="Emily McFadin" readonly>
								<p>Emily McFadin teaches woodwind instruments and beginning piano. Originally from Springdale, AR, she moved to Russellville to attend Arkansas Tech University where she graduated with Cum Laude honors and earned her Bachelors in Instrumental Music Education.
								During her time at ATU she was actively involved in the Baptist Collegiate Ministry (BCM) along with several music ensembles and organizations. She served two years as drum major for the Arkansas Tech Band of Distinction and played both clarinet and low clarinets for ATU's Symphonic Wind Ensemble. Emily enjoys spending time at home with her three cats (Susan, Poof, and Squish), doing puzzles, reading Wonder Woman comic books, and eating Chick-fil-A.</p>
								<button type="button" onclick="getInputValue3();" class="btn btn-primary" id="btn3">Pick Me!</button>
							  </div>
							</div>
                        
                    <div class="card" style="width: 18rem; margin: 1.5rem;">
							  <img class="card-img-top" src="img/guitar.jpeg" alt="Card image cap">
							  <div class="card-body">
								<input type="text" id="input4" value="Joseph Freeman" readonly>
								<p>Joseph Freeman teaches Guitar, Bass, and Ukulele. He has been playing guitar for over eight years, and has picked up the bass and ukulele within the last few years. When he was 17, he was a student at RVMC until COVID hit and he was unable to take lessons anymore. About a year later, he was offered to come and teach and he accepted in December of 2020. 

								Joseph has interests in a wide variety of music. He loves all sorts of technical playing, but still wants to hear great melodies as well. He is into the genres metal, classical, jazz, and some pop, hip hop, and country. He has a deep interest in music theory and loves to shred when he gets the chance. Strong technique points include- alternate picking, economy picking, sweep picking, string skipping, timing/rhythm. Strong theory points include- scales, modes, improvisation, chord progressions. Can teach complete beginners to advanced players.</p>
								<button type="button" onclick="getInputValue4();" class="btn btn-primary" id="btn4">Pick Me!</button>
							  </div>
							</div>
				</div>
				<div class="row gx-5 justify-content-center">
                   
                            <div class="card" style="width: 18rem; margin: 1.5rem;">
							  <img class="card-img-top" src="img/5.jpeg" alt="Card image cap">
							  <div class="card-body">
								<input type="text" id="input5" value="Matthew Stone" readonly>
								<p>Mr. Matthew Stone is a vocal, piano, and music theory instructor in his final semester of pursuing his Bachelor's in Vocal Music Education through Arkansas Tech University. He plans to teach high school choir after he graduates and hopes to compose for choirs across the world. Stone's dream is to teach the language of music to anyone who wishes to learn it and to inspire creativity through the art of song.</p>
								<button type="button" onclick="getInputValue5();" class="btn btn-primary" id="btn5">Pick Me!</button>
							  </div>
							</div>
                        
                    <div class="card" style="width: 18rem; margin: 1.5rem;">
							  <img class="card-img-top" src="img/6.jpeg" alt="Card image cap">
							  <div class="card-body">
								<input type="text" id="input6" value="Garrett Snavely" readonly>
								<p>Hello! My name is Garrett Snavely, and I have been a music teacher at River Valley Music Center since July of 2021. I teach piano and voice lessons, and am comfortable with all ages of learners. Having started learning piano at the very late age of 16, I am firm believer that you are able to learn to jump into piano at any point in life. I enjoy working one-on-one with others, and especially so when there is music involved.

								I am a graduate of Arkansas Tech University, where I earned a Bachelor’s of Music Education. I was also actively involved in two choirs while studying for my degree. When I’m not giving lessons, I am preparing music for different church services in the area.</p>
								<button type="button" onclick="getInputValue6();" class="btn btn-primary" id="btn6">Pick Me!</button>
							  </div>
							</div>
                   
				</div>
				<div class="row gx-5 justify-content-center">
                   
                            <div class="card" style="width: 18rem; margin: 1.5rem;">
							  <img class="card-img-top" src="img/guitar.jpeg" alt="Card image cap">
							  <div class="card-body">
								<input type="text" id="input7" value="Ben Smith" readonly>
								<p>No Bio Available.</p>
								<button type="button" onclick="getInputValue7();" class="btn btn-primary" id="btn7">Pick Me!</button>
							  </div>
							</div>
                    <div class="card" style="width: 18rem; margin: 1.5rem;">
							  <img class="card-img-top" src="img/8.jpeg" alt="Card image cap">
							  <div class="card-body">
								<input type="text" id="input8" value="Nathan Bain" readonly>
								<p>No Bio Available.</p>
								<button type="button" onclick="getInputValue8();" class="btn btn-primary" id="btn8">Pick Me!</button>
							  </div>
							</div>
                </div> 
				<div class="row gx-5 justify-content-center">
                   
                            <div class="card" style="width: 18rem; margin: 1.5rem;">
							  <img class="card-img-top" src="img/guitar.jpeg" alt="Card image cap">
							  <div class="card-body">
								<input type="text" id="input9" value="Josh Cannon" readonly>
								<p>No Bio Available.</p>
								<button type="button" onclick="getInputValue9();" class="btn btn-primary" id="btn9">Pick Me!</button>
							  </div>
							</div>
                    <div class="card" style="width: 18rem; margin: 1.5rem;">
							  <img class="card-img-top" src="img/guitar.jpeg" alt="Card image cap">
							  <div class="card-body">
								<input type="text" id="input10" value="Isabella Palmer" readonly>
								<p>No Bio Available.</p>
								<button type="button" onclick="getInputValue10();" class="btn btn-primary" id="btn10">Pick Me!</button>
							  </div>
							</div>
                </div> 
				<div class="row gx-5 justify-content-center">
                   
                            <div class="card" style="width: 18rem; margin: 1.5rem;">
							  <img class="card-img-top" src="img/guitar.jpeg" alt="Card image cap">
							  <div class="card-body">
								<input type="text" id="input11" value="Jason Waterson" readonly>
								<p>No Bio Available.</p>
								<button type="button" onclick="getInputValue11();" class="btn btn-primary" id="btn11">Pick Me!</button>
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
            document.getElementById("instructor_id").value = 2;
            getInstruments(2);
			$("#myModal").hide();
		};
		function getInputValue3(){  
			document.getElementById("instructor").value = document.getElementById("input3").value
            document.getElementById("instructor_id").value = 3;
            getInstruments(3);
			$("#myModal").hide();
		};
		function getInputValue4(){  
			document.getElementById("instructor").value = document.getElementById("input4").value
			document.getElementById("instructor_id").value = 4;
            getInstruments(4);
			$("#myModal").hide();
		};
		function getInputValue5(){  
			document.getElementById("instructor").value = document.getElementById("input5").value
			document.getElementById("instructor_id").value = 5;
            getInstruments(5);
			$("#myModal").hide();
		};
		function getInputValue6(){  
			document.getElementById("instructor").value = document.getElementById("input6").value
			document.getElementById("instructor_id").value = 6;
            getInstruments(6);
			$("#myModal").hide();
		};
		function getInputValue7(){  
			document.getElementById("instructor").value = document.getElementById("input7").value
			document.getElementById("instructor_id").value = 7;
            getInstruments(7);
			$("#myModal").hide();
		};
		function getInputValue8(){  
			document.getElementById("instructor").value = document.getElementById("input8").value
			document.getElementById("instructor_id").value = 8;
            getInstruments(8);
			$("#myModal").hide();
		};
		function getInputValue9(){  
			document.getElementById("instructor").value = document.getElementById("input9").value
			document.getElementById("instructor_id").value = 9;
            getInstruments(9);
			$("#myModal").hide();
		};
		function getInputValue10(){  
			document.getElementById("instructor").value = document.getElementById("input10").value
			document.getElementById("instructor_id").value = 10;
            getInstruments(10);
			$("#myModal").hide();
		};
		function getInputValue11(){  
			document.getElementById("instructor").value = document.getElementById("input11").value
			document.getElementById("instructor_id").value = 11;
            getInstruments(11);
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
