const first = document.getElementById("first");
const second = document.getElementById("second");
const signup = document.getElementById("signup");
const signin = document.getElementById("signin");

signup.addEventListener("click", show_signup);

signin.addEventListener("click", show_signin);

function show_signup(){
	first.style.display = 'none';
	second.style.display = 'block';
}

function show_signin(){
	second.style.display = 'none';
	first.style.display = 'block';	
}