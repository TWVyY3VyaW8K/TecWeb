<!-- The Modal -->
<div id="SignUpModal" class="Modal">
	<!-- Modal Content -->
	<form class="modal-content animate container1024" method="post" action="#" onsubmit="return doSignUp(event)">
		<div class="modalHead">
			<span onclick="closeModal('SignUpModal')" class="close" title="Close Modal">&times;</span>
			<h1>SIGN UP FORM</h1>
		</div>
		<div class="container">
			<label for="usrSignUp">Username</label>
			<input id="usrSignUp" type="text" placeholder="Enter Username" name="usrSignUp" maxlength="20" />

			<label for="pwdSignUp">Password</label>
			<input id="pwdSignUp" type="password" placeholder="Enter Password" name="pwdSignUp" maxlength="30"/>

			<label for="name">Name</label>
			<input id="name" type="text" placeholder="Enter Name" name="name" />

			<label for="surname">Surname</label>
			<input id="surname" type="text" placeholder="Enter Surname" name="surname" />

		</div>
		<div class="container" id="SignUpMessage">
			<!--container for invalid login message-->

		</div>

		<div class="container modalFotter" >
			<button type="submit">Register</button>
			<button type="button" onclick="closeModal('SignUpModal')" class="cancelbtn">Cancel</button>
		</div>

	</form>
</div>
