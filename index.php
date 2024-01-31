<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="index.css">
	<title>Document</title>
<style>
	.active {
  background-color: #717171;
}
	.slideshow-container {
  max-width: 1000px;
  position: relative;
  margin: auto;
}
.dot {
  height: 15px;
  width: 15px;
  margin: 0 2px;
  background-color: #bbb;
  border-radius: 50%;
  display: inline-block;
  transition: background-color 0.6s ease;
}
.fade {
  animation-name: fade;
  animation-duration: 1.5s;
}

@keyframes fade {
  from {opacity: .4} 
  to {opacity: 1}
}

	</style>
</head>

<body>
	<nav>
		<div class="heading">PRAGATI</div>
		<span class="sideMenuButton"onclick="openNavbar()">☰</span>

		<div class="navbar">
			<ul>
				<li><a href="#Home">Home</a></li>
				<li><a href="#">About</a></li>
				<li><a href="register.php">Sign Up</a></li>
                <li><a href="login.php">Sign In</a></li>
			</ul>
		</div>
	</nav>

	<!-- Side navigation bar for
		responsive website -->
	<div class="sideNavigationBar"id="sideNavigationBar">
		<a href="#" class="closeButton"onclick="closeNavbar()"></a>
		<a href="#">Home</a>
		<a href="#">About</a>
		<a href="register.php">Sign Up</a>
        <li><a href="login.php">Sign In</a></li>
	</div>

	<!-- Content -->
	<div class="line" id="Home">
		<div class="side1">
			<h1>PRAGATI-</h1><h3>Student Performance Portal</h3>
		</div>
		<div class="side2">
			
			<div class="slideshow-container">
				<div class="mySlides fade">
  					
  					<img src="img/11.jpg" style="width:100%">
  				
				</div>

				<div class="mySlides fade">
  					
  					<img src="img/2.jpg" style="width:100%">
  					
				</div>

				<div class="mySlides fade">
  				
  					<img src="img/3.jpg" style="width:100%">
				</div>

			</div>
	</div>
</div>
<div style="text-align:center">
  <span class="dot"></span> 
  <span class="dot"></span> 
  <span class="dot"></span> 
</div>

	<section class="about" id="My Projects">
		<div class="content">
			<div class="title">
				<span>Better You!</span>
			</div>
			<div class="boxes">
				<div class="box">
					<div class="topic">
						<a href="" target="_blank">
							Set Goals
						</a>
					</div>
					<p>
					Goals, both short and long-term, are a great way to measure your success. If you don’t have goals in sight, you have nothing to achieve or strive for in your courses.
					If you set concrete goals for yourself, it’s easier to become motivated and measure your success in those goals.Make sure your goals are realistic! While you should 
					challenge yourself, you shouldn’t set yourself up for failure, either.Remember, you can always set higher goals once you’ve achieved your first set
					</p>
				</div>
				<div class="box">

					<div class="topic">
						<a href="" target="_blank">
							Attendance
						</a>
					</div>
					<p>
					This should be common sense – if students go to class, they will likely become more successful in the course.
					Obviously, the course material is presented during class periods and students that are paying attention tend 
					to learn while in class and, thus, are more likely to perform well on exams.
					</p>
				</div>

				<div class="box">
					<div class="topic">
						<a href="" target="_blank">
							Participation
						</a>
					</div>
					<p>
					Going to class is one thing but paying attention and participating in class is another. If you listen to the lessons, questions are likely to arise. If they come up in class, ask!
					If you’re too shy in a large class, wait and ask the professor after class or during office hours. It’s important to know, however, that if you’ve got a question, it’s likely that other students have the same question as well.
					</p>
				</div>
			</div>
		</div>
	</section>

	<section class="contact" id="contact">
		<div class="content">
			
			<div class="contactMenu">
				<div class="input1">
				<div class="title1">
							<span>
								Get in Touch
							</span>
						</div>
          <ul class="list-unstyled">
            
              <a href="https://www.facebook.com/"><img src="img/f.png" alt="" srcset=""width="18%" height="20%"></a>
            
           
              <a href="https://www.instagram.com/"><img src="img/i.png" alt="" srcset=""width="18%" height="20%"></a>
           
           
              <a href="https://twitter.com/?lang=en"><img src="img/t.png" alt="" srcset=""width="18%" height="20%"></a>
                  
         
              <a href="https://www.pinterest.com/"><img src="img/p.png" alt="" srcset=""width="18%" height="20%"></a>
            
          </ul>

      
					
				
					
					
				</div>
				<div class="input3">
					<div class="rightside1">
						<div class="title1">
							<span>
								Contact Us
							</span>
						</div>
						<div class="content1">
							Sinamangal,Kathmandu,Nepal
						</div>
						<div class="title1">
							<span>More Information</span>
						</div>
						<div class="content1">
							Greetings to all the students and teachers out there!
							We welcome you to the platform where we
							consistently strive to offer the best
							of education. This platform has been
							designed<br> for those wishing to expand their knowledge, share their
							knowledge and is ready to grab their dream job. We are here for keeping records of students during their academic session and analyzing their performance.
							Thank you for choosing and supporting us!We hope for the betterment in your academic journey.
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
	<!-- Footer section -->
	<footer>
		<div class="footer">
			<span>
			© 2023 Copyright:
    <a href="index.php"> Pragati.com</a>
			</span>
		</div>
	</footer>
	<script>
function openNavbar() {
	document.getElementById("sideNavigationBar")
		.style.width = "50%";
}
function closeNavbar() {
	document.getElementById("sideNavigationBar")
		.style.width = "0%";
}
let slideIndex = 0;
showSlides();

function showSlides() {
  let i;
  let slides = document.getElementsByClassName("mySlides");
  let dots = document.getElementsByClassName("dot");
  for (i = 0; i < slides.length; i++) {
    slides[i].style.display = "none";  
  }
  slideIndex++;
  if (slideIndex > slides.length) {slideIndex = 1}    
  for (i = 0; i < dots.length; i++) {
    dots[i].className = dots[i].className.replace(" active", "");
  }
  slides[slideIndex-1].style.display = "block";  
  dots[slideIndex-1].className += " active";
  setTimeout(showSlides, 2000); // Change image every 2 seconds
}
	</script>
</body>

</html>
