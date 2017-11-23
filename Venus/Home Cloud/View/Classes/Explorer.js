var protocol = 'https';					// http  or https
var host = 'localhost';					// localhost, 127.0.0.1, 192.168.0.100 etc
var version = '1.0';					// api version to use

$(document).ready(function(){
	
	// -----------------------------------
	//
	// Request the user's folder hierarchy
	//
	// -----------------------------------
	$.ajax({
		
		type: 'GET',
		url: protocol + '://' + host + '/Venus/Home Cloud/Rest/' + version + '/Controllers/Folders.php',
		data: "{0}={1}".format("token", localStorage.getItem('USER_TOKEN')),
		
		success: function(data){
			
			// -----------------------------------
			//
			// Do something about the error
			//
			// -----------------------------------
			if(data['header']['code'] != 'FOLGET0'){
				
				console.log('error to get the user\'s folder hierarchy');
				return false;
				
			}
			
			var rootName = data['payload']['root']['name'];
			var rootIdentifier = data['payload']['root']['identifier'];
			
			var rootUl = document.createElement('ul');
			var rootLi = document.createElement('li');
			var rootInput = document.createElement('input');
			var rootLabel = document.createElement('label');
			var rootDiv = document.createElement('div');
			var rootImg = document.createElement('img');
			var rootSamp = document.createElement('samp');
			
			
			$(rootLi).attr('id', rootIdentifier);
			$(rootInput).attr('type', 'checkbox');
			$(rootInput).attr('id', 'FOR:' + rootIdentifier);
			$(rootLabel).attr('for', 'FOR:' + rootIdentifier);
			$(rootLabel).attr('class', 'body-aside-hierarchy-folder');
			$(rootImg).attr('src', protocol + '://' + host + '/Venus/Home Cloud/Data/Images/folder-icon.png');
			$(rootImg).attr('class', 'body-aside-hierarchy-folder-icon');
			$(rootSamp).attr('class', 'body-aside-hierarchy-folder-name');
			$(rootSamp).html(rootName);
			
			
			$(rootLabel).append(rootImg);
			$(rootLabel).append(rootSamp);
			$(rootLi).append(rootInput);
			$(rootLi).append(rootLabel);
			$(rootUl).append(rootLi);
			
			
			for(var i = 0; i < data['payload']['folders'].length; i++){
				
				var folderName = data['payload']['folders'][i]['name'];
				var folderIdentifier = data['payload']['folders'][i]['identifier'];
				var folderDirectory = data['payload']['folders'][i]['directory'];
				
				var folderUl = document.createElement('ul');
				var folderLi = document.createElement('li');
				var folderInput = document.createElement('input');
				var folderLabel = document.createElement('label');
				var folderDiv = document.createElement('div');
				var folderImg = document.createElement('img');
				var folderSamp = document.createElement('samp');
				
				
				$(folderLi).attr('id', folderIdentifier);
				$(folderInput).attr('type', 'checkbox');
				$(folderInput).attr('id', 'FOR:' + folderIdentifier);
				$(folderLabel).attr('for', 'FOR:' + folderIdentifier);
				$(folderLabel).attr('class', 'body-aside-hierarchy-folder');
				$(folderImg).attr('src', protocol + '://' + host + '/Venus/Home Cloud/Data/Images/folder-icon.png');
				$(folderImg).attr('class', 'body-aside-hierarchy-folder-icon');
				$(folderSamp).attr('class', 'body-aside-hierarchy-folder-name');
				$(folderSamp).html(folderName);
				
				
				$(folderLabel).append(folderImg);
				$(folderLabel).append(folderSamp);
				$(folderLi).append(folderInput);
				$(folderLi).append(folderLabel);
				$(folderUl).append(folderLi);
				
				
				var folderDirectoryAsMD5 = md5(folderDirectory);
				
				$('#' + folderDirectoryAsMD5).append(folderUl);
				console.log($('#' + folderDirectoryAsMD5));
			}
			
			
			$('#body-aside-hierarchy').append(rootUl);
			
		},
		error: function(data){
			
			
			
		}
		
	});
	
});



/*
$(document).ready(function(){
	
	$('#body-aside').css('height', window.innerHeight - parseInt($('#body-tools').css('height')) - 1 - parseInt($('#body-aside-hierarchy').css('padding-top')) - parseInt($('#body-aside-hierarchy').css('padding-bottom')));
	$('#body-aside').css('width', parseInt($('#body-aside-hierarchy').css('width')) + parseInt($('#body-aside-resizer').css('width')));
	
	if(localStorage.getItem('USER_TOKEN') == null){
		
		//window.location = "https://127.0.0.1/Venus/Cloud/View/1.0/Structures/Authentication.html";
		
		return false;
		
	}

	$.ajax({
		
		type: 'GET',
		url: 'https://127.0.0.1/Venus/Cloud/Rest/1.0/Controllers/Tokens.php',
		data: "{0}={1}".format("token", localStorage.getItem('USER_TOKEN')),
		
		success: function(data){
			
			if(data['header']['code'] != 'TOKGET0'){
				
				alert(data['header']['description']);
				
				return false;
				
			}
			
			$.ajax({
				
				type: 'GET',
				url: 'https://127.0.0.1/Venus/Cloud/Rest/1.0/Controllers/Folders.php',
				data: "{0}={1}&{2}={3}".format("token", localStorage.getItem('USER_TOKEN'), "directory", "/"),
				
				success: function(data){
					
					if(data['header']['code'] != 'FOLGET0'){
						
						alert(data['header']['description']);
						
						return false;
						
					}
					
					var folderRoot = document.createElement('ul');
					
					$(folderRoot).attr('id', 'root');
					
					for(var i = 0; i < data['payload'].length; i++){
						
						var itemType = data['payload'][i]['type'];
						var itemDirectory = data['payload'][i]['directory'];
						var itemName = data['payload'][i]['name'];
						var itemIdentifier = data['payload'][i]['identifier'];
						
						
						if(itemType == 'FOLDER'){
							
							var folderUl = document.createElement('ul');
							var folderLi = document.createElement('li');
							var folderInput = document.createElement('input');
							var folderLabel = document.createElement('label');
							var folderDiv = document.createElement('div');
							var folderImg = document.createElement('img');
							var folderSamp = document.createElement('samp');
							
							
							$(folderUl).attr('id', itemIdentifier);
							
							$(folderInput).attr('type', 'checkbox');
							$(folderInput).attr('id', itemDirectory);
							
							$(folderLabel).attr('for', itemDirectory);
							$(folderLabel).attr('class', 'body-aside-hierarchy-folder');
							
							$(folderImg).attr('src', 'https://127.0.0.1/Venus/Cloud/Data/Images/folder-icon.png');
							$(folderImg).attr('class', 'body-aside-hierarchy-folder-icon');
							
							$(folderSamp).attr('class', 'body-aside-hierarchy-folder-name');
							$(folderSamp).html(itemName);
							
							
							$(folderLabel).append(folderImg);
							$(folderLabel).append(folderSamp);
							
							$(folderLi).append(folderInput);
							$(folderLi).append(folderLabel);
							
							$('#body-aside-hierarchy').append(folderUl);
						}
					}
					
				},
				
				error: function(data){
					
					alert('Erro to request server');
					
				}
				
			});
			
		},
		
		error: function(data){
			
			alert('Erro to request server');
			
		}
		
	});
	
});

*/