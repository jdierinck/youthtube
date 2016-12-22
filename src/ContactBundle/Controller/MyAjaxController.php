<?php
namespace ContactBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class MyAjaxController extends Controller{
	
	public function getCityFromZipAction($zip) {
		$conn = $this->get('database_connection');
		$sql = "SELECT city1 FROM zipcities WHERE zipcode = :zip";
		$query = $conn->prepare($sql);
		$query->bindValue("zip",$zip);
		$query->execute();
		$city = $query->fetch();
		$response = empty($city)? "Unknown zip code": $city["city1"];
		return new Response ($response);
	}
	
}