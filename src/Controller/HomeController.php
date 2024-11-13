<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController
{
    #[Route('/number/{id}', name: 'number')]
    public function number(int $id): Response
    {
        $number = random_int(0, 100);

        return new Response(
            '<html>
                <body>
                    <p>
                        Random Number: '.$number.'
                    </p>
                    <p>
                        Number ID : '.$id.'
                    </p>
                </body>
            </html>'
        );
    }
}