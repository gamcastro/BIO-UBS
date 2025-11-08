	function alteraNome(){
		var input = document.getElementById("nome");
		if(!input) return;

		var texto = String(input.value || "");

		// Normaliza espaços e aplica Title Case em PT-BR,
		// mantendo preposições/minusculas exceto se no início
		var lowerWords = new Set([
			"da","de","do","das","dos","e","em","na","no","nas","nos",
			"para","por","a","o","as","os","d'","d’"
		]);

		// Não colapsa/trim para não remover espaços enquanto o usuário digita
		texto = texto.toLowerCase();

		function capitalizePiece(w){
			if(!w) return w;
			return w.charAt(0).toUpperCase() + w.slice(1);
		}

		function titleCaseWord(word, isFirst){
			// trata casos com hífen ou apóstrofo preservando separadores
			return word
				.split(/([\-\'’])/)
				.map(function(part, idx){
					// índices pares são palavras, ímpares são separadores
					if(idx % 2 === 1) return part; // separador
					var w = part;
					if(!w) return w;
					// aplica exceções de palavras minúsculas (não na primeira)
					if(!isFirst && lowerWords.has(w)) return w;
					return capitalizePiece(w);
				})
				.join('');
		}

		// Divide preservando sequências de espaços (captura o delimitador)
		var tokens = texto.split(/(\s+)/);
		var foundFirst = false;
		for(var i=0;i<tokens.length;i++){
			var t = tokens[i];
			if(/^[\s]+$/.test(t)) { // é só espaço -> preserva
				tokens[i] = t;
				continue;
			}
			// palavra
			tokens[i] = titleCaseWord(t, !foundFirst);
			if(t.length > 0) foundFirst = true;
		}
		input.value = tokens.join('');
	}

	function alteraSSP(){
		var valor = document.getElementById("ssp");
		if(!valor) return;
		var novoTexto = valor.value.toUpperCase();
		valor.value = novoTexto;
	}

	// Função para aplicar Title Case no nome do profissional
	function alteraNomeProfissional(){
		var input = document.getElementById("NOME_COMPLETO");
		if(!input) return;

		var texto = String(input.value || "");

		// Normaliza espaços e aplica Title Case em PT-BR,
		// mantendo preposições/minusculas exceto se no início
		var lowerWords = new Set([
			"da","de","do","das","dos","e","em","na","no","nas","nos",
			"para","por","a","o","as","os","d'","d'"
		]);

		// Não colapsa/trim para não remover espaços enquanto o usuário digita
		texto = texto.toLowerCase();

		function capitalizePiece(w){
			if(!w) return w;
			return w.charAt(0).toUpperCase() + w.slice(1);
		}

		function titleCaseWord(word, isFirst){
			// trata casos com hífen ou apóstrofo preservando separadores
			return word
				.split(/([\-\''])/)
				.map(function(part, idx){
					// índices pares são palavras, ímpares são separadores
					if(idx % 2 === 1) return part; // separador
					var w = part;
					if(!w) return w;
					// aplica exceções de palavras minúsculas (não na primeira)
					if(!isFirst && lowerWords.has(w)) return w;
					return capitalizePiece(w);
				})
				.join('');
		}

		// Divide preservando sequências de espaços (captura o delimitador)
		var tokens = texto.split(/(\s+)/);
		var foundFirst = false;
		for(var i=0;i<tokens.length;i++){
			var t = tokens[i];
			if(/^[\s]+$/.test(t)) { // é só espaço -> preserva
				tokens[i] = t;
				continue;
			}
			// palavra
			tokens[i] = titleCaseWord(t, !foundFirst);
			if(t.length > 0) foundFirst = true;
		}
		input.value = tokens.join('');
	}
