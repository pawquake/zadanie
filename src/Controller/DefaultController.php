<?php
/**
 * Created by PhpStorm.
 * User: pgrzadko
 * Date: 13.03.2019
 * Time: 11:54
 */

namespace App\Controller;

use GuzzleHttp\Client;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/")
     */
    public function getValue(Request $request)
    {
        $client = new Client(['base_uri' => 'http://api.nbp.pl/api/exchangerates/']);
        if ($request->get('trip-start')!== null) {
            $date = $request->get('trip-start');
            try {
                $response = $client->request('GET', 'tables/a/' . $date . '/?format=json');
            } catch (\Exception $e) {
                return $this->render("error.html.twig", [
                    "error" => ("Nie poprawny paramter   ". $e->getMessage())
                ]);
            }
        } else {
            try {
                $response = $client->request('GET', 'tables/a/?format=json');
            } catch (\Exception $e) {
                return $this->render("error.html.twig", [
                    "error" => ("Nie poprawny paramter   ". $e->getMessage())
                ]);
            }
        }

        $data = $response->getBody();
        $data = json_decode($data, true);

        $date = $data[0]['effectiveDate'];
        $rate = $data[0]['rates'];

        return $this->render("exchange.html.twig", [
            "rates" => $rate,
            "date" => $date
        ]);
    }
}