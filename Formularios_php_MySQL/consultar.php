<!doctype html>
<html>
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Cosulta de Viviendas</title>
		<link REL="stylesheet" TYPE="text/css" HREF="Style.css" >
	</head>
	<body>
	<div class="col-md-9 col-md-offset-0"> 
		<?php 
		error_reporting(E_ERROR | E_WARNING | E_PARSE);
		?>
		<H1>Consulta de viviendas</H1>
		
		<br>
		<br>
		<form NAME="selecciona" ACTION="consultar.php" METHOD="POST">
			<div class="col-lg-10" >
				<label for="select" class="col-lg-2 control-label">Mostrar viviendas de  :</label>
				<div class="col-lg-10">
				<select  class="form-control" id="select" name ="tipo">
					<option value="Todos" selected>Todos
					<option value="Casa" >Casa
					<option value="Apartamento" >Apartamento
					<option value="Rancho de Playa">Rancho de Playa
					<option value="Terreno">Terreno


				</select>
				<div class="col-lg-10">
				<br>
				<input class="btn btn-primary " TYPE="submit" NAME="actualizar" VALUE="Actualizar">
				</div>
			</div>
			</div>
			<br>
			<br>
			<br>
		</form>
		<?PHP

		$conexion = mysqli_connect("127.0.0.1", "root", "", "inmobiliaria");
		if (!$conexion)
		{
			echo "Error: No se pudo conectar a MySQL." . PHP_EOL;
			echo "errno de depuración: " . mysqli_connect_errno() . PHP_EOL;
			echo "error de depuración: " . mysqli_connect_error() . PHP_EOL;
			exit;
		}
		//echo "Éxito: Se realizó una conexión apropiada a MySQL! La base de datos inmobiliaria es genial." . PHP_EOL;
		echo "<br>";
		echo "<br>";
		echo "<br>";
		echo "<br>";

		// Enviar consulta
		$instruccion = "select * from viviendas";

		$actualizar = $_REQUEST['actualizar'];
		$tipo = $_REQUEST['tipo'];
		if (isset($actualizar) && $tipo != "Todos")
			$instruccion = $instruccion . " where tipo='$tipo'";

		$instruccion = $instruccion . " order by precio asc";
		$consulta = mysqli_query ($conexion,$instruccion)
			or die ("Fallo en la consulta");

		// Mostrar resultados de la consulta
		$nfilas = mysqli_num_rows ($consulta);
		if ($nfilas > 0)
		{
			print ("<TABLE class='table table-striped table-hover ' WIDTH='650'>\n");
			print ("<TR>\n");
			print ("<TH WIDTH='100'>Tipo</TH>\n");
			print ("<TH WIDTH='100'>Departamento</TH>\n");
			print ("<TH WIDTH='100'>Dormitorios</TH>\n");
			print ("<TH WIDTH='75'>Precio</TH>\n");
			print ("<TH WIDTH='75'>Tamaño</TH>\n");
			print ("<TH WIDTH='150'>Extras</TH>\n");
			print ("<TH WIDTH='50'>Foto</TH>\n");
			print ("</TR>\n");

			for ($i=0; $i<$nfilas; $i++)
			{
				$resultado = mysqli_fetch_array ($consulta);
				print ("<TR>\n");
				print ("<TD>" . $resultado['tipo'] . "</TD>\n");
				print ("<TD>" . $resultado['departamento'] . "</TD>\n");
				print ("<TD>" . $resultado['ndormitorios'] . "</TD>\n");
				print ("<TD>" . $resultado['precio'] . "</TD>\n");
				print ("<TD>" . $resultado['tamano'] . "</TD>\n");
				print ("<TD>" . $resultado['extras'] . "</TD>\n");

				if ($resultado['foto'] != "")
					print ("<TD><A TARGET='_blank' HREF='fotos/" . $resultado['foto'] .
						   "'><IMG BORDER='0' SRC='fotos/ico-fichero.gif' ALT='Foto'></A></TD>\n");
				else
					print ("<TD>&nbsp;</TD>\n");

				print ("</TR>\n");
			}

			print ("</TABLE>\n");
		}
		else
			print ("No hay viviendas disponibles");

		// Cerrar conexión
		mysqli_close ($conexion);

		?>
		<br>
		<br>
		<br>
		<br>
		<P>[ <A HREF='insertar.php'>Volver atras </A> ]</P>
		</div>
		
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
