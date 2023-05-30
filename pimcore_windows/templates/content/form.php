<?php
	use Pimcore\Mail;
	use Symfony\Component\HttpFoundation\Request;
	use Pimcore\Model\Table;
	session_start();
	
	$transportFrom = $_POST['transportFrom'];
	
	if ($transportFrom == ""){
		$errorTransportFrom = "Pole ,,Transport od'' nie może być puste!";
		$_SESSION['errorTransportFrom'] = $errorTransportFrom;
		header('Location: index.html');
	}
	
	$transportTo = $_POST['transportTo'];
	
	if ($transportTo == ""){
		$errorTransportTo = "Pole ,,Transport do'' nie może być puste!";
		$_SESSION['errorTransportTo'] = $errorTransportTo;
		header('Location: index.html');
	}
	
	$planeType = $_POST['planeType'];
	$transportDate = $_POST['transportDate'];

	

	//$mail = new \Pimcore\Mail();
	
	/*(if (date("l", $transportDate) == "Saturday" || date("l", $transportDate) == "Sunday")
	{
		$errorTransportDate = "Proszę wybrać dzień od poniedziałku do piątku!";
		$_SESSION['errorTransportDate'] = $errorTransportDate;
		header('Location: index.html');
	}*/
	
	
	$tabelaMail = new Table();
	$cargo = array();
	$cargoCount =0;
	
	for ($i=1; $i<=999; $i++)
	{
		$cargoString = "cargoName" . strval($i); 
		$cargoName = $_POST[$cargoString];
		
		if ($cargoName == "")
		{
		$errorCargoName = "Pole ,,Nazwa ładunku'' nie może być puste!";
		$_SESSION['errorCargoName'] = $errorCargoName;
		header('Location: index.html');
		}
	
		if ($cargoName == NULL) break;
	
		$cargoCiezarString = "cargoCiezar" . strval($i);
		$cargoCiezar = $_POST[$cargoCiezarString];
		
		if ($cargoCiezar == "")
		{
		$errorCargoCiezar = "Pole ,,Ciezar ładunku'' nie może być puste!";
		$_SESSION['errorCargoCiezar'] = $errorCargoCiezar;
		header('Location: index.html');
		}
		
		$cargoTypeString = "cargoType" . strval($i);
		$cargoType = $_POST[$cargoTypeString];
		
	}
		//sprawdzanie ciezaru
		
		if ($planeType == "AirbusA380" && $cargoCiezar > 35000){
		{
			$errorAirbus = "Ładunek nie może przekraczać 35 ton dla samolotu Airbus A380.";
			$_SESSION['errorAirbus'] = $errorAirbus;
			header('Location: index.html');
		}
		
		if ($planeType == "Boeing747" && $cargoCiezar > 38000)
		{
			$errorBoeing = "Ładunek nie może przekraczać 38 ton dla samolotu Boeing 747.";
			$_SESSION['errorBoeing'] = $errorBoeing;
			header('Location: index.html');
		}
		
		$tabela = array();
		array_push($tabela, $cargoName, $cargoCiezar, $cargoType);
		
		array_push($cargo, $tabela);
	}
	
	$tabelaMail->setTable($cargo);

	$shippingDocuments = $_FILES['shippingDocuments'];
	
	foreach($shippingDocuments as $dok)
	{
		$mail->attach($dok->getData(), $dok->getFileName(), $dok->getMimeType());
	}
	
	
	$params = ['firstName' => 'Piotr', 'lastName' => 'Szymański'];
	
	
	/*if ($planeType == "AirbusA380")
	{
		$mail->to('airbus@lemonmind.com');
	}
	else if ($planeType == "Boeing747")
	{
		$mail->to('boeing@lemonmind.com');
	}*/
	
	$mail->setParams($params);
	$mailText = "Transport z: " . strval($transportFrom) . "\nTransport do: " . strval($transportTo) . "\nTyp samolotu: " . strval($planeType) . "\nData transportu: " . strval($transportDate) . "\n";
	
	$mailText = $mailText . strval($tabelaMail);			//chyba
	$mail->text($mailText);
	
	//$mail->send();
	
	
	
	header('Location: index.html');

?>