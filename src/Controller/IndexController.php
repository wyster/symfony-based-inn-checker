<?php declare(strict_types=1);

namespace App\Controller;

use App\InnNumber\InnNumber;
use App\Service\InnServiceInterface;
use App\Validator\InnValidator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Throwable;

class IndexController extends AbstractController
{
    /**
     * @Route("/", name="main")
     * @param Request $request
     * @param InnServiceInterface $innService
     * @return Response
     */
    public function main(Request $request, InnServiceInterface $innService): Response
    {
        $inn = $request->get('inn', '');

        $messages = [];
        $validator = new InnValidator();

        if ($validator->isValid((int)$inn)) {
            try {
                $isTaxPayer = $innService->isTaxPayer(new InnNumber((int)$inn));
                $messages[] = $isTaxPayer ? 'ИНН является самозанятым' : 'ИНН не является самозанятым';
            } catch (Throwable $e) {
                $message = sprintf('Возникла ошибка, попробуйте повторить попытку, код: %s', $e->getCode());
                $messages[] = $message;
            }
        } else {
            $messages = $validator->getMessages();
        }

        return $this->render(
            'main.html.twig',
            [
                'inn' => $inn,
                'messages' => $messages
            ]
        );
    }
}
