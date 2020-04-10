<?php declare(strict_types=1);

namespace App\Controller;

use App\Service\InnServiceInterface;
use devsergeev\validators\InnValidator;
use InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Throwable;
use function mb_strlen;

class IndexController extends AbstractController
{
    private const INN_LENGTH = 12;

    /**
     * @Route("/", name="main")
     * @param Request $request
     * @param InnServiceInterface $innService
     * @return Response
     */
    public function main(Request $request, InnServiceInterface $innService): Response
    {
        $inn = $request->get('inn', '');
        $messages = $this->prepareResponse($request, $innService);

        return $this->render(
            'main.html.twig',
            [
                'inn' => $inn,
                'messages' => $messages
            ]
        );
    }

    private function prepareResponse(Request $request, InnServiceInterface $innService): array
    {
        $inn = $request->get('inn', '');
        $messages = $this->innValidator($inn);
        if (count($messages) > 0) {
            return $messages;
        }

        $inn = (int)$inn;
        try {
            $isTaxPayer = $innService->isTaxPayer($inn);
            $messages[] = $isTaxPayer ? 'ИНН является самозанятым' : 'ИНН не является самозанятым';
        } catch (Throwable $e) {
            $message = sprintf('Возникла ошибка, попробуйте повторить попытку, код: %s', $e->getCode());
            $messages[] = $message;
        }

        return $messages;
    }

    private function innValidator(string $inn): array
    {
        if (mb_strlen($inn) !== self::INN_LENGTH) {
            return [sprintf('ИНН физического лица должен состоять из %s цифр', self::INN_LENGTH)];
        }

        $messages = [];
        try {
            InnValidator::check($inn);
        } catch (InvalidArgumentException $e) {
            $messages[] = $e->getMessage();
        }

        return $messages;
    }
}
