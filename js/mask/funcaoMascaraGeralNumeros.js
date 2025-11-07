	function mascaras (evt, campo, padrao) {
		
		//+++++++++++++++++ testando a tecla pressionada pelo usuário 
		
		var charCode = (evt.which)? evt.which : evt.keyCode;
		
		if (charCode == 8) return true; //permitindo backspace
		if (charCode == 9) return true; //permitindo TAB
		// BLOQUEANDO pontos e traços (charCode 46 = ponto, 45 = traço) - apenas números permitidos
		if (charCode <48 || charCode >57) return false; //apenas números de 0-9
		
		campo.maxLength = padrao.length; //tamanho do campo de acordo com fornecido
		
		entrada = campo.value;
		
		if (padrao.length > entrada.length && padrao.charAt (entrada.length) !='#') {
			
			campo.value = entrada+padrao.charAt (entrada.length);	
		}
		
		return true;
			
	} // final da nossa função
