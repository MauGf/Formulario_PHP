<!doctype html>
<html>
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Inserción de vivienda</title>
		<link rel ="stylesheet" type="text/css" href="Style.css">
		<script type="text/javascript">
		</script>
	</head>
	<body>
		<div class="col-md-9 col-md-offset-0"> 
			<?php 
			error_reporting(E_ERROR | E_WARNING | E_PARSE);
			?>
			<?php
			// Obtener valores introducidos en el formulario
			if ($_POST)
			{ 
				echo("<BR>");
				echo("<BR>");
				echo("<BR>");
				echo ("<bold>Bienbenidos :</bold>");
				echo("<BR>");
				echo("<BR>");
				$insertar = $_REQUEST['insertar'];
				$tipo = $_REQUEST['tipo'];
				$departamento = $_REQUEST['departamento'];
				$direccion = $_REQUEST['direccion'];
				$ndormitorios = $_REQUEST['ndormitorios'];
				$precio = $_REQUEST['precio'];
				$tamano = $_REQUEST['tamano'];
				$extras = $_REQUEST['extras'];
				$observaciones = $_REQUEST['observaciones'];
				//<--------------------------------------------------------------------------------->
				$error = false;
				if (isset($insertar))
				{

					// Comprobar errores
					// Dirección
					if (trim($direccion) == "")
					{
						$errores["direccion"] = "¡Se requiere la dirección de la vivienda!";
						$error = true;
					}
					else
						$errores["direccion"] = "";
					// Precio
					if (!is_numeric($precio))
					{
						$errores["precio"] = "¡El precio debe ser un valor numérico!";
						$error = true;
					}
					else
						$errores["precio"] = "";
					// Tamaño
					if (!is_numeric($tamano))
					{
						$errores["tamano"] = "¡El tamaño debe ser un valor numérico!";
						$error = true;
					}
					else
						$errores["tamano"] = "";

					// Subir fichero con la foto de la vivienda
					$copiarFichero="true";
					$uploadedfile_size=$_FILES['foto'][size];
					echo $_FILES[foto][name];
					if ($_FILES[foto][size]>1000000)
					{$msg=$msg."El archivo es mayor que 1MB, debes reduzcirlo antes de subirlo<BR>";
					 $copiarFichero="false";}
					// Si el archivo no es JPG o PNG el archivo no sera guardado
					if (!($_FILES[foto][type] =="image/jpeg" OR $_FILES[foto][type] =="image/png"))
					{

						$msg=$msg." No es permitido tu archivo tiene que ser JPG o png. Otros archivos no son permitidos<BR>";
						$copiarFichero="false";
					}

					if($copiarFichero=="true"){
						// Mover foto a su ubicación definitiva
						$nombreFichero=$_FILES[foto][name];
						$add="fotos/$nombreFichero";
						// Si ya existe un fichero con el mismo nombre, renombrarlo
						$nombreCompleto = $add;
						if (is_file($nombreCompleto))
						{
							$idUnico = time();
							$nombreFichero = $idUnico . "-" . $nombreFichero;
						}


						if(move_uploaded_file ($_FILES[foto][tmp_name], $add)){
							echo " Ha sido subido satisfactoriamente";
						}else{echo "Error al subir el archivo";}

					}else{echo $msg;}
				}

				// Si los datos son correctos, procesar formulario
				if (isset($insertar) && $error==false)
				{
					// Insertar la vivienda en la Base de Datos
					$conexion = mysqli_connect("127.0.0.1", "root", "", "inmobiliaria");
					if (!$conexion)
					{
						echo "Error: No se pudo conectar a MySQL." . PHP_EOL;
						echo "errno de depuración: " . mysqli_connect_errno() . PHP_EOL;
						echo "error de depuración: " . mysqli_connect_error() . PHP_EOL;
						exit;
					}
					//echo "Éxito: Se realizó una conexión apropiada a MySQL! La base de datos inmobiliaria es genial." . PHP_EOL;
					//echo "Información del host: " . mysqli_get_host_info($conexion) . PHP_EOL;

					$n = count ($extras);
					if ($n > 0)
					{
						$ex = $extras[0];
						for ($i=1; $i<$n; $i++)
							$ex = $ex . "," . $extras[$i];
					}
					else
						$ex = "";

					$instruccion = "insert into viviendas (tipo, departamento, direccion, ndormitorios, " .
						"precio, tamano, extras, foto, observaciones) values " .
						"('$tipo', '$departamento', '$direccion', '$ndormitorios', " .
						"'$precio', '$tamano', '$ex', '$nombreFichero', '$observaciones')";

					$consulta = mysqli_query ($conexion,$instruccion)
						or die ("Fallo en la inserción");
					mysqli_close ($conexion);


					print ("<H1>Inserción de vivienda</H1>\n");
					print ("<P>Estos son los datos introducidos:</P>\n");
					print ("<UL>\n");
					print ("   <LI>Tipo: $tipo\n");
					print ("   <LI>Departamento: $departamento\n");
					print ("   <LI>Dirección: $direccion\n");
					print ("   <LI>Número de dormitorios: $ndormitorios\n");
					print ("   <LI>Precio: $precio $\n");
					print ("   <LI>Tamaño: $tamano metros cuadrados\n");
					print ("   <LI>Extras: ");

					foreach ($extras as $extra)
						print ($extra . " ");
					print ("\n");

					if ($copiarFichero == true)
						print ("   <LI>Foto: <A TARGET='_blank' HREF='$add'>$nombreFichero</A>\n");
					else
						print ("   <LI>Foto: (no hay)\n");

					print ("   <LI>Observaciones: $observaciones\n");
					print ("</UL>\n");
					echo("<BR>");
					echo ("<P>[ <A HREF='javascript:history.back()'>Volver</A> ]</P>\n");
					print ("<P>[ <A HREF='insertar.php'>Insertar otra vivienda</A> ]</P>\n");
				}

			}
			else
			{
			?>
			
			
			<!-- Captura de datos  -->
			
			<H1>Inserción de vivienda</H1>

			<P>Introduzca los datos de la vivienda:</P>

			<form  CLASS="borde" ACTION="insertar.php" METHOD="POST" ENCTYPE="multipart/form-data">
				<div class="col-lg-10" >
				<label for="select" class="col-lg-2 control-label">Tipo de Vivienda :</label>
				<div class="col-lg-10">
					<select class="form-control" id="select" name="tipo">
						<option></option>
						<option SELECTED>Casa</option>
						<option>Apartamento</option>
						<option>Rancho de Playa</option>
						<option>Terreno</option>
					</select>
					</div>
					</div>
					<br>
				
				<div class="col-lg-10" >
				<br>
				<label for="select" class="col-lg-2 control-label">Departamento :</label>
				<div class="col-lg-10">
					<select class="form-control" id="select" name="departamento">
						<option></option>
						<option SELECTED>San Salvador</option>
						<option>Santa Ana</option>
						<option>San Miguel</option>
						<option>Usulutan</option>
						<option>Sonsonate</option>
					</select>
					</div>
					</div>
					<br>
				<div class="col-lg-10" >
				<br>
				<label for="select" class="col-lg-2 control-label">Direccion :</label>
				<div class="col-lg-10">
					<input class="form-control"  type="text" maxlength="30" size="20" name="direccion">
					<?PHP
				if (isset($insertar))
					print (" VALUE='$direccion'>\n");
				else
					print ("\n");
				if ($errores["direccion"] != "")
					print ("<BR><SPAN CLASS='error'>" . $errores["direccion"] . "</SPAN>");
					?>
					</div>
				</div>
				<div class="col-lg-10" >
				<br>
				<label for="select" class="col-lg-2 control-label">Numero de dormitorios :</label>
				
				<div class="col-lg-10">
					<input type="radio" name="ndormitorios" value="1">1
					<input type="radio" name="ndormitorios" value="2">2
					<input type="radio" name="ndormitorios" value="3">3
					<input type="radio" name="ndormitorios" value="4">4
					<input type="radio" name="ndormitorios" value="5">5
					</div>
					<br>
					</div>
					
			
				<div class="col-lg-10" >
				<br>
				<label for="select" class="col-lg-2 control-label">Precio :</label>
				<div class="col-lg-10">
					<input class="form-control" type="text" maxlength="9" size="20" name="precio">
					<?PHP
				if (isset($insertar))
					print (" VALUE='$precio' $;\n");
				else
					print ("  $;\n");
				if ($errores["precio"] != "")
					print ("<BR><SPAN CLASS='error'>" . $errores["precio"] . "</SPAN>");
					?>
				</div>
				</div>
				<div class="col-lg-10" >
				<br>
				<label for="select" class="col-lg-2 control-label">Tamaño :</label>
				<div class="col-lg-10"> 
					<input type="text" maxlength="9" size="20" name="tamano">
					<?PHP
				if (isset($insertar))
					print (" VALUE='$tamano'> metros cuadrados\n");
				else
					print (" metros cuadrados\n");
				if ($errores["tamano"] != "")
					print ("<BR><SPAN CLASS='error'>" . $errores["tamano"] . "</SPAN>");
					?>
				</div>
				</div>
				<div class="col-lg-10" >
				<br>
				<label for="select" class="col-lg-2 control-label">Extras(marque los que procedan):</label>
				<div class="col-lg-10"> 
					<input type="checkbox" name="extras[]" value="Piscina">Piscina
					<input type="checkbox" name="extras[]" value="Jardin" >Jardín
					<input type="checkbox" name="extras[]" value="Garage" >Garage
				</div>
				</div>
				
				<div class="col-lg-10" >
				<br>
				<label for="select" class="col-lg-2 control-label">Foto :</label>
				<div class="col-lg-10"> 
					<input TYPE="HIDDEN" NAME="MAX_FILE_SIZE" VALUE="1002400">
					<input type="file" name="foto"><br><br>
					<?PHP
				if ($errores["foto"] != "")
					print ("<BR><SPAN CLASS='error'>" . $errores["foto"] . "</SPAN>");
					?>
				</div>
				</div>
				<br>
				<div class="col-lg-10" >
				<br>
				<label for="textArea" class="col-lg-2 control-label">Observaciones :</label>
				<div class="col-lg-10"> 
					<textarea class="form-control" rows="6" cols="60" id="observaciones" name="observaciones" ></textarea>

				</div>
				
				</div>
				<div class="col-lg-10" >
				<br>
				<label for="select" class="col-lg-2 control-label">Inserción:</label>
				<div class="col-lg-10"> 
				<p><em><input class="btn btn-primary " type="submit" name="insertar" value="Insertar Vivienda"/></em></p> 
				<br>
				</div>
				</div>
			</form>
			<?php

			}

			?>
			<br>
			<br>
			<br>
			<div class="col-lg-10" >
			<P>[ <A HREF='consultar.php'>Consultar Viviendas </A> ]</P>
			</div>
		
		</div>
		<!-- Fin Captura de datos  -->
		
		<div class="col-lg-12">
		<hr>
		<div align ="center"class="panel-footer">
		<footer >  
		
			copyright @Mauricio Garcia 2017
		</footer> 
		</div>
		</div>
	</body>
</html>
