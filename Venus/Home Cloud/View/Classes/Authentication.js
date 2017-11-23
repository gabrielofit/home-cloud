function OnReady(){
	
	Setup();
	
}

function OnResize(){
	
	Setup();
	
}

function Setup(){
		
	
}

$(document).ready(function(){
	
	// ----- STARTS AREA FOR DEBUG -----
	
	$('#body-userToken').html(localStorage.getItem('USER_TOKEN'));
	
	// ----- ENDS AREA FOR DEBUG -----
	
	$('#body-signInForm').submit(function(e){
		
		$('#body-signInForm-submit').attr('disabled', 'disabled');
		
		$.ajax({
			
			type: $('#body-signInForm').attr('method'),
			url: $('#body-signInForm').attr('action'),
			data: $('#body-signInForm').serialize(),
			
			success: function(data){
				
				if(data['header']['code'] != 'TOKPOS0'){
					
					alert(data['header']['description']);
					
					return false;
					
				}
				
				localStorage.setItem('USER_TOKEN', data['payload']['token']);
				
				// ----- STARTS AREA FOR DEBUG -----
				
				$('#body-storedToken').html(localStorage.getItem('USER_TOKEN'));
				
				// ----- ENDS AREA FOR DEBUG -----
				
			},
			
			error: function(data){
				
				alert('Erro to request server');
				
			}
			
		});
		
		$('#body-signInForm-submit').removeAttr('disabled');

		e.preventDefault();
		
	});
	
	$('#body-signUpForm').submit(function(e){
		
		$('#body-signUpForm-submit').attr('disabled', 'disabled');
		
		$.ajax({
			
			type: $('#body-signUpForm').attr('method'),
			url: $('#body-signUpForm').attr('action'),
			data: $('#body-signUpForm').serialize(),
			
			success: function(data){
				
				if(data['header']['code'] != 'USEPOS0'){
					
					alert(data['header']['description']);
					
					return false;
					
				}
	
				alert('User registred, try login now.');
				
			},
			
			error: function(data){
				
				console.log(data.responseJSON);
				
			}
			
		});
		
		$('#body-signUpForm-submit').removeAttr('disabled');
		
		e.preventDefault();
		
	});
	
});