<?php

namespace App\Controller;

use Pimcore\Bundle\AdminBundle\Controller\Admin\LoginController;
use Pimcore\Controller\FrontendController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Carbon\Carbon;

use Pimcore\Mail;
use Pimcore\Model\Table;
use Pimcore\Model\DataObject;

class DefaultController extends FrontendController
{
    /**
     * @param Request $request
     * @return Response
     */
    public function defaultAction(Request $request): Response
    {
        return $this->render('default/default.html.twig');
    }    
    
	public function formAction(): Response
	{
		session_unset();
		session_start();

	
	$transportFrom = $_SESSION['transportFrom'];
	
	if ($transportFrom == ""){
		$errorTransportFrom = "Pole ,,Transport od'' nie może być puste!";
		$_SESSION['errorTransportFrom'] = $errorTransportFrom;
	}
	$transportTo = $_SESSION['transportTo'];
	
	if ($transportTo == ""){
		$errorTransportTo = "Pole ,,Transport do'' nie może być puste!";
		$_SESSION['errorTransportTo'] = $errorTransportTo;
	}
	
	$planeType = $_SESSION['planeType'];
	$transportDate = $_SESSION['transportDate'];

	$mail = new \Pimcore\Mail();

	$params = ['transportFrom' => $transportFrom, 'transportTo' => $transportTo, 'planeType' => $planeType, 'transportDate' => $transportDate];
	$mail->setParams($params);
	
	$stringHtml = "Transport z: {{ transportFrom }} <br>Transport do: {{ transportTo }} <br>Typ samolotu: {{ planeType }} <br>Data transportu: {{ transportDate }} <br> Ładunek: <br> <table> <tr> <th> Nazwa </th> <th> Ciężar </th> <th> Typ </th> </tr>";
	

	
	
	
	$cargo = array();
	$cargoCount =0;
	
	for ($i=1; $i<=999; $i++)
	{
		
		$cargoString = "cargoName" . strval($i); 
		
		if (!isset($_SESSION[$cargoString])) {
			break;
		}
		
		$cargoName = $_SESSION[$cargoString];
		
		if ($cargoName == "")
		{
		$errorCargoName = "Pole ,,Nazwa ładunku'' nie może być puste!";
		$_SESSION['errorCargoName'] = $errorCargoName;
		}
	
		
	
		$cargoCiezarString = "cargoCiezar" . strval($i);
		$cargoCiezar = $_SESSION[$cargoCiezarString];
		
		if ($cargoCiezar == "")
		{
		$errorCargoCiezar = "Pole ,,Ciezar ładunku'' nie może być puste!";
		$_SESSION['errorCargoCiezar'] = $errorCargoCiezar;
		}
		
		$cargoTypeString = "cargoType" . strval($i);
		$cargoType = $_SESSION[$cargoTypeString];
		
	
		//sprawdzanie ciezaru
		
		if ($planeType == "AirbusA380" && $cargoCiezar > 35000)
		{
			$errorAirbus = "Ładunek nie może przekraczać 35 ton dla samolotu Airbus A380.";
			$_SESSION['errorAirbus'] = $errorAirbus;
		}
		
		if ($planeType == "Boeing747" && $cargoCiezar > 38000)
		{
			$errorBoeing = "Ładunek nie może przekraczać 38 ton dla samolotu Boeing 747.";
			$_SESSION['errorBoeing'] = $errorBoeing;
		}
		
		
		
		$tabela = array();
		array_push($tabela, $cargoName, $cargoCiezar, $cargoType);
		
		array_push($cargo, $tabela);
		
		$stringHtml = $stringHtml . "<tr> <th>" . $cargoName . "</th> <th>" . $cargoCiezar . "</th> <th>" . $cargoType . "</th> </tr>";
	}
		

	$stringHtml = $stringHtml . "</table>";

	$shippingDocuments = $_SESSION['shippingDocuments'];
	
	foreach($shippingDocuments as $dok)
	{
		$newAsset = new \Pimcore\Model\Asset();
		$newAsset->setFilename($dok['name']);
		$newAsset->setData(file_get_contents('C:/xampp/htdocs/pimcore_windows/files/' . $dok['name']));
		$mail->attach($newAsset->getData(), $newAsset->getFileName());
	}
	
	
	
	
	if ($planeType == "AirbusA380")
	{
		$mail->to('airbus@lemonmind.com');
	}
	else if ($planeType == "Boeing747")
	{
		$mail->to('boeing@lemonmind.com');
	}
	
	
	$newObject = new DataObject\Transport(); 
	$newObject->setKey(\Pimcore\Model\Element\Service::getValidKey('objekt1', 'object'));
	$newObject->setParentId(1);
	$newObject->setPlaneType($planeType);
	$newObject->setTransportFrom($transportFrom);
	$newObject->setTransportTo($transportTo);
	$newObject->setTransportDate(Carbon::parse($transportDate));
	$newObject->setCargo($cargo);
	$newObject->save();
	
	$mail->html($stringHtml);
	
	
	
	$mail->send();
	
	return $this->render('done.php.twig');
	}
    /**
     * Forwards the request to admin login
     */
	 
	
	 
	 
	 
    public function loginAction(): Response
    {
        return $this->forward(LoginController::class.'::loginCheckAction');
    }
}
