<?php
/* This will give an error. Note the output
 * above, which is before the header() call */
session_unset();
session_start();
 
$_SESSION['transportFrom'] = $_POST['transportFrom'];
$_SESSION['transportTo'] = $_POST['transportTo'];
$_SESSION['planeType'] = $_POST['planeType'];
$_SESSION['transportDate'] = $_POST['transportDate'];
$transportDate = $_SESSION['transportDate'];
$_SESSION['shippingDocuments'] = $_FILES['shippingDocuments'];

function diverse_array($vector) {

    $result = array();

    foreach($vector as $key1 => $value1)

        foreach($value1 as $key2 => $value2)

            $result[$key2][$key1] = $value2;

    return $result;

}

$shippingDocuments = diverse_array($_SESSION['shippingDocuments']);
$_SESSION['shippingDocuments'] = $shippingDocuments;


$blad=0;

if ($_SESSION['transportFrom'] == ""){
		$errorTransportFrom = "Pole ,,Transport od'' nie może być puste!";
		$_SESSION['errorTransportFrom'] = $errorTransportFrom;
		$blad=1;
	}
	
if ($_SESSION['transportTo'] == ""){
		$errorTransportTo = "Pole ,,Transport do'' nie może być puste!";
		$_SESSION['errorTransportTo'] = $errorTransportTo;
		$blad=1;
	}

if (date("l", strtotime($transportDate)) == "Saturday" || date("l", strtotime($transportDate)) == "Sunday")
	{
		$errorTransportDate = "Proszę wybrać dzień od poniedziałku do piątku!";
		$_SESSION['errorTransportDate'] = $errorTransportDate;
		$blad=1;
	}
	
for ($i=1; $i<=10; $i++)
{
	$cargoString = "cargoName" . strval($i); 
	$_SESSION[$cargoString] = NULL;
	
	$cargoCiezarString = "cargoCiezar" . strval($i);
	$_SESSION[$cargoCiezarString] = NULL;
	
	$cargoTypeString = "cargoType" . strval($i);
	$_SESSION[$cargoTypeString] = NULL;
}


for ($i=1; $i<=999; $i++)	
	{
		$cargoString = "cargoName" . strval($i); 
		$cargoName = $_POST[$cargoString];
	
	
	
		if ($cargoName == NULL){
			$_SESSION['cargoNum'] = $i-1;
			break;
		} 
		
		if ($cargoName == "")
		{
		$errorCargoName = "Pole ,,Nazwa ładunku'' nie może być puste!";
		$_SESSION['errorCargoName'] = $errorCargoName;
		$blad=1;
		}
		
		$_SESSION[$cargoString] = $cargoName;
	
		$cargoCiezarString = "cargoCiezar" . strval($i);
		$_SESSION[$cargoCiezarString] = $_POST[$cargoCiezarString];
		
		if ($_SESSION[$cargoCiezarString] == "")
		{
		$errorCargoCiezar = "Pole ,,Ciezar ładunku'' nie może być puste!";
		$_SESSION['errorCargoCiezar'] = $errorCargoCiezar;
		$blad=1;
		}
		
		$cargoTypeString = "cargoType" . strval($i);
		$_SESSION[$cargoTypeString] = $_POST[$cargoTypeString];
		
		
		if ($_SESSION['planeType'] == "AirbusA380" && $_SESSION[$cargoCiezarString] > 35000)
		{
			$errorAirbus = "Ładunek nie może przekraczać 35 ton dla samolotu Airbus A380.";
			$_SESSION['errorAirbus'] = $errorAirbus;
			$blad=1;
		}
		
		if ($_SESSION['planeType'] == "Boeing747" && $_SESSION[$cargoCiezarString] > 38000)
		{
			$errorBoeing = "Ładunek nie może przekraczać 38 ton dla samolotu Boeing 747.";
			$_SESSION['errorBoeing'] = $errorBoeing;
			$blad=1;
		}
		
	}
	
	if ($blad==0)
	{
		foreach($shippingDocuments as $dok)
		{
			//print_r($dok['name']);
			//print_r($dok['tmp_name']);
			move_uploaded_file($dok['tmp_name'], 'C:/xampp/htdocs/pimcore_windows/files/' . $dok['name']);
		}
	}

if ($blad == 0) {
	header('Location: http://localhost/formUpload');
}

if ($blad == 1){
	echo $_SESSION['errorTransportFrom'];
	header('Location: http://localhost/mainpage');
}

exit;
?>