<?php
	include(dirname(__FILE__)."/../init.php");
?>
<html>
	<head>
		<style type="text/css">
			
			#searchSelects, #searchSelects ul {
				padding: 0px;
				margin: 0px;
			}
			
			#searchSelects {
				padding-bottom: 30px;
			}
		
			#searchSelects >  li {
				/*display: inline-block;*/
				list-style-type: none;
				padding: 30px 30px 0px 30px;
			}
			
			#searchSelects >  li > ul > li {
				list-style-type: none;
			}
		
		</style>
		<script type="text/javascript" src="../lib/jquery/jquery-1.11.1.min.js"></script>
		<script type="text/javascript">
			$().ready(function() {
				createSelectSubclasses("Soporte técnico", "http://www.owl-ontologies.com/OntologySoporteTecnico.owl#SoporteTecnico");
			});

			function createSelectSubclasses(label, iri)
			{

				$.ajax({
					url: "ajaxTmp.php",
					dataType: "json",
					type: "POST",
					data: {
						accion: "getSubclassesOf",
						iri: iri
					},
					success: function(json){

						if(json.datos.length > 0){
						
							var objLi = document.createElement("li");
							$("#searchSelects").append(objLi);

							var objUl = document.createElement("ul");
							objLi.appendChild(objUl);

							var objLi = document.createElement("li");
							objUl.appendChild(objLi);
	
							var objSpan = document.createElement("span");
							objSpan.appendChild(document.createTextNode(label));
							objLi.appendChild(objSpan);

							var objLi = document.createElement("li");
							objUl.appendChild(objLi);
							
							var objSelect = document.createElement("select");
							objSelect.setAttribute("name", "nivel_0");
							objLi.appendChild(objSelect);
							
							var objOption = document.createElement("option");
							objOption.setAttribute("value", "0");
							objOption.appendChild(document.createTextNode("Selecionar..."));
							objSelect.appendChild(objOption);
						
							$.each(json.datos, function(id, arrDatos){
								var objOption = document.createElement("option");
								objOption.setAttribute('value', arrDatos.iri);
								objOption.appendChild(document.createTextNode(arrDatos.label));
								objSelect.appendChild(objOption);
							});
	
							$(objSelect).bind("change", function(event)
							{
								if(this.value != "0"){
									if(this.value == "http://www.owl-ontologies.com/OntologySoporteTecnico.owl#InstalacionImpresora"){
										createInstancesSTSTEquipoReproduccionImpresoras("Marca", this.value);
									} else {
										createSelectSubclasses($(this.options[this.selectedIndex]).text(), this.value);
									}
								}
							});
						}
					}
				});
			}

			function createInstancesSTSTEquipoReproduccionImpresoras(label, iri)
			{
				$.ajax({
					url: "ajaxTmp.php",
					dataType: "json",
					type: "POST",
					data: {
						accion: "getInstancesSTEquipoReproduccionImpresoras",
						iri: iri
					},
					success: function(json){

						if(json.datos.length > 0)
						{

							var objLi = document.createElement("li");
							$("#searchSelects").append(objLi);

							var objUl = document.createElement("ul");
							objLi.appendChild(objUl);

							var objLi = document.createElement("li");
							objUl.appendChild(objLi);
	
							var objSpan = document.createElement("span");
							objSpan.appendChild(document.createTextNode(label));
							objLi.appendChild(objSpan);

							var objLi = document.createElement("li");
							objUl.appendChild(objLi);

							var objInput = document.createElement("input");
							objInput.setAttribute("name", "iri");
							objInput.setAttribute("type", "hidden");
							objInput.setAttribute("value", iri);
							objLi.appendChild(objInput);
							
							var objSelect = document.createElement("select");
							objSelect.setAttribute("name", "nivel_0");
							objLi.appendChild(objSelect);
							
							var objOption = document.createElement("option");
							objOption.setAttribute("value", "0");
							objOption.appendChild(document.createTextNode("Selecionar..."));
							objSelect.appendChild(objOption);
						
							$.each(json.datos, function(id, arrDatos){
								var objOption = document.createElement("option");
								objOption.setAttribute('value', arrDatos.marcaEquipoReproduccion);
								objOption.appendChild(document.createTextNode(arrDatos.marcaEquipoReproduccion));
								objSelect.appendChild(objOption);
							});
	
							$(objSelect).bind("change", function(event)
							{
								if(this.value != "0"){
									var iri = $(this).parent().find("input").val();
									createInstancesSTSTEquipoReproduccionImpresorasModelo("Modelo", iri, this.value);
								}
							});
						}
					}
				});

			}


			function createInstancesSTSTEquipoReproduccionImpresorasModelo(label, iri, marca)
			{
				$.ajax({
					url: "ajaxTmp.php",
					dataType: "json",
					type: "POST",
					data: {
						accion: "getInstancesSTEquipoReproduccionModelo",
						marca: marca,
						iri: iri
					},
					success: function(json){

						if(json.datos.length > 0)
						{

							var objLi = document.createElement("li");
							$("#searchSelects").append(objLi);

							var objUl = document.createElement("ul");
							objLi.appendChild(objUl);

							var objLi = document.createElement("li");
							objUl.appendChild(objLi);
	
							var objSpan = document.createElement("span");
							objSpan.appendChild(document.createTextNode(label));
							objLi.appendChild(objSpan);

							var objLi = document.createElement("li");
							objUl.appendChild(objLi);

							var objInput = document.createElement("input");
							objInput.setAttribute("name", "iri");
							objInput.setAttribute("type", "hidden");
							objInput.setAttribute("value", iri);
							objLi.appendChild(objInput);

							var objInput = document.createElement("input");
							objInput.setAttribute("name", "marca");
							objInput.setAttribute("type", "hidden");
							objInput.setAttribute("value", marca);
							objLi.appendChild(objInput);
							
							var objSelect = document.createElement("select");
							objSelect.setAttribute("name", "nivel_0");
							objLi.appendChild(objSelect);
							
							var objOption = document.createElement("option");
							objOption.setAttribute("value", "0");
							objOption.appendChild(document.createTextNode("Selecionar..."));
							objSelect.appendChild(objOption);
						
							$.each(json.datos, function(id, arrDatos){
								var objOption = document.createElement("option");
								objOption.setAttribute('value', arrDatos.modeloEquipoReproduccion);
								objOption.appendChild(document.createTextNode(arrDatos.modeloEquipoReproduccion));
								objSelect.appendChild(objOption);
							});
	
							$(objSelect).bind("change", function(event)
							{
								if(this.value != "0"){
									var iri = $(this).parent().find("input[name=\"iri\"]").val();
									var marca = $(this).parent().find("input[name=\"marca\"]").val();
									createInstancesSTSTEquipoReproduccionImpresorasSONombre("Nombre S.O.", iri, marca, this.value);
								}
							});
						}
					}
				});
			}
			
			function createInstancesSTSTEquipoReproduccionImpresorasSONombre(label, iri, marca, modelo)
			{
				$.ajax({
					url: "ajaxTmp.php",
					dataType: "json",
					type: "POST",
					data: {
						accion: "getInstancesSTEquipoReproduccionSONombre",
						marca: marca,
						modelo: modelo,
						iri: iri
					},
					success: function(json){

						if(json.datos.length > 0)
						{

							var objLi = document.createElement("li");
							$("#searchSelects").append(objLi);

							var objUl = document.createElement("ul");
							objLi.appendChild(objUl);

							var objLi = document.createElement("li");
							objUl.appendChild(objLi);
	
							var objSpan = document.createElement("span");
							objSpan.appendChild(document.createTextNode(label));
							objLi.appendChild(objSpan);

							var objLi = document.createElement("li");
							objUl.appendChild(objLi);

							var objInput = document.createElement("input");
							objInput.setAttribute("name", "iri");
							objInput.setAttribute("type", "hidden");
							objInput.setAttribute("value", iri);
							objLi.appendChild(objInput);

							var objInput = document.createElement("input");
							objInput.setAttribute("name", "marca");
							objInput.setAttribute("type", "hidden");
							objInput.setAttribute("value", marca);
							objLi.appendChild(objInput);

							var objInput = document.createElement("input");
							objInput.setAttribute("name", "modelo");
							objInput.setAttribute("type", "hidden");
							objInput.setAttribute("value", modelo);
							objLi.appendChild(objInput);
							
							var objSelect = document.createElement("select");
							objSelect.setAttribute("name", "nivel_0");
							objLi.appendChild(objSelect);
							
							var objOption = document.createElement("option");
							objOption.setAttribute("value", "0");
							objOption.appendChild(document.createTextNode("Selecionar..."));
							objSelect.appendChild(objOption);
						
							$.each(json.datos, function(id, arrDatos){
								var objOption = document.createElement("option");
								objOption.setAttribute('value', arrDatos.nombreSistemaOperativo);
								objOption.appendChild(document.createTextNode(arrDatos.nombreSistemaOperativo));
								objSelect.appendChild(objOption);
							});
	
							$(objSelect).bind("change", function(event)
							{
								if(this.value != "0"){
									var iri = $(this).parent().find("input[name=\"iri\"]").val();
									var marca = $(this).parent().find("input[name=\"marca\"]").val();
									var modelo = $(this).parent().find("input[name=\"modelo\"]").val();
									createInstancesSTSTEquipoReproduccionImpresorasSOVersion("Versión S.O.", iri, marca, modelo, this.value);
								}
							});
						}
					}
				});
			}

			function createInstancesSTSTEquipoReproduccionImpresorasSOVersion(label, iri, marca, modelo, nombreSO)
			{
				$.ajax({
					url: "ajaxTmp.php",
					dataType: "json",
					type: "POST",
					data: {
						accion: "getInstancesSTEquipoReproduccionSOVersion",
						marca: marca,
						modelo: modelo,
						nombreSO: nombreSO,
						iri: iri
					},
					success: function(json){

						if(json.datos.length > 0)
						{

							var objLi = document.createElement("li");
							$("#searchSelects").append(objLi);

							var objUl = document.createElement("ul");
							objLi.appendChild(objUl);

							var objLi = document.createElement("li");
							objUl.appendChild(objLi);
	
							var objSpan = document.createElement("span");
							objSpan.appendChild(document.createTextNode(label));
							objLi.appendChild(objSpan);

							var objLi = document.createElement("li");
							objUl.appendChild(objLi);

							var objInput = document.createElement("input");
							objInput.setAttribute("name", "iri");
							objInput.setAttribute("type", "hidden");
							objInput.setAttribute("value", iri);
							objLi.appendChild(objInput);

							var objInput = document.createElement("input");
							objInput.setAttribute("name", "marca");
							objInput.setAttribute("type", "hidden");
							objInput.setAttribute("value", marca);
							objLi.appendChild(objInput);

							var objInput = document.createElement("input");
							objInput.setAttribute("name", "modelo");
							objInput.setAttribute("type", "hidden");
							objInput.setAttribute("value", modelo);
							objLi.appendChild(objInput);

							var objInput = document.createElement("input");
							objInput.setAttribute("name", "nombreSO");
							objInput.setAttribute("type", "hidden");
							objInput.setAttribute("value", nombreSO);
							objLi.appendChild(objInput);
							
							var objSelect = document.createElement("select");
							objSelect.setAttribute("name", "nivel_0");
							objLi.appendChild(objSelect);
							
							var objOption = document.createElement("option");
							objOption.setAttribute("value", "0");
							objOption.appendChild(document.createTextNode("Selecionar..."));
							objSelect.appendChild(objOption);
						
							$.each(json.datos, function(id, arrDatos){
								var objOption = document.createElement("option");
								objOption.setAttribute('value', arrDatos.versionSistemaOperativo);
								objOption.appendChild(document.createTextNode(arrDatos.versionSistemaOperativo));
								objSelect.appendChild(objOption);
							});
	
							$(objSelect).bind("change", function(event)
							{
								if(this.value != "0"){
									
								}
							});
						}
					}
				});
			}

			
			






			






			

			function createInstancesSTImpresora(iri)
			{
				$.ajax({
					url: "ajaxTmp.php",
					dataType: "json",
					type: "POST",
					data: {
						accion: "getInstancesSTEquipoReproduccion",
						iri: iri
					},
					success: function(json){

						if(json.datos.length > 0)
						{
							var objTable = document.createElement("table");
							objTable.setAttribute("border", "1");

							var objTr = document.createElement("tr");
							objTable.appendChild(objTr);

							var objTd = document.createElement("td");
							objTd.setAttribute("colspan", "2");
							objTd.appendChild(document.createTextNode("Impresora"));
							objTr.appendChild(objTd);

							var objTd = document.createElement("td");
							objTd.setAttribute("colspan", "2");
							objTd.appendChild(document.createTextNode("Sistema Operativo"));
							objTr.appendChild(objTd);

							var objTd = document.createElement("td");
							objTd.appendChild(document.createTextNode("Opciones"));
							objTd.setAttribute("rowspan", "2");
							objTr.appendChild(objTd);

							var objTr = document.createElement("tr");
							objTable.appendChild(objTr);

							var objTd = document.createElement("td");
							objTd.appendChild(document.createTextNode("Marca"));
							objTr.appendChild(objTd);

							var objTd = document.createElement("td");
							objTd.appendChild(document.createTextNode("Modelo"));
							objTr.appendChild(objTd);

							var objTd = document.createElement("td");
							objTd.appendChild(document.createTextNode("Nombre"));
							objTr.appendChild(objTd);

							var objTd = document.createElement("td");
							objTd.appendChild(document.createTextNode("Versión"));
							objTr.appendChild(objTd);

							$.each(json.datos, function(id, arrDatos){
								
								var objTr = document.createElement("tr");
								objTable.appendChild(objTr);

								var objTd = document.createElement("td");
								objTd.appendChild(document.createTextNode(arrDatos.marcaEquipoReproduccion));
								objTr.appendChild(objTd);

								var objTd = document.createElement("td");
								objTd.appendChild(document.createTextNode(arrDatos.modeloEquipoReproduccion));
								objTr.appendChild(objTd);

								var objTd = document.createElement("td");
								objTd.appendChild(document.createTextNode(arrDatos.nombreSistemaOperativo));
								objTr.appendChild(objTd);

								var objTd = document.createElement("td");
								objTd.appendChild(document.createTextNode(arrDatos.versionSistemaOperativo));
								objTr.appendChild(objTd);

								var objTd = document.createElement("td");
								objTd.appendChild(document.createTextNode("\u00A0"));
								objTr.appendChild(objTd);
								
							});

							$("#instances").append(objTable);
						}
					}
				});
			}
		</script>
	</head>
	<body>
		<div>
			<ul id="searchSelects"></ul>
			<div id="instances"></div>
		</div>
	</body>
</html>