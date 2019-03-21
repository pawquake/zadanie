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
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/")
     */
    public function getValue()
    {
        $client = new Client(['base_uri' => 'http://api.nbp.pl/api/exchangerates/']);
        $response = $client->request('GET', 'tables/a/?format=json');

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