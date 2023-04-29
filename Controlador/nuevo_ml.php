<?php
define("DB_SERVER", "SERVIDOR");
define("DB_USERNAME", "sa");
define("DB_PASSWORD", "Axoft1988");
define("DB_PORT", "1433");
define("DB_DATABASE", "LAKER_SA");
function new_ml(){
	try
	{
		$conn = new PDO("sqlsrv:Server=" . DB_SERVER . "," . DB_PORT . ";Database=" . DB_DATABASE, DB_USERNAME, DB_PASSWORD);
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	
		
		$sth = $conn->query("SELECT * FROM RO_VIEW_INSERT_ML_SOF_AUDITORIA");


		if($sth->fetch())
		{


			$sth = $conn->prepare("SET NOCOUNT ON; EXEC RO_SP_INSERT_ML_SOF_AUDITORIA");
			/* $sth->bindParam(1, $name);
			$sth->bindParam(2, $lastname);
			$sth->bindParam(3, $age); */
			$sth->execute();
			$sth->nextRowset();
		
			$conn = null;
		}else
		


	}
	catch(PDOException $e)
	{
		echo $e->getMessage();
	}

	}

