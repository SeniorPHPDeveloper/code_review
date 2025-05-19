<?php declare(strict_types=1);

namespace App\Controller;

use App\Dto\NotificationDto;
use App\EntityRepository\CustomerRepository;
use App\Exception\DtoException;
use App\Exception\MessengerException;
use App\Service\MessageFactory;
use App\Service\Messenger;
use App\Service\RequestToDtoConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CustomerController extends AbstractController
{
    private CustomerRepository $customerRepository;
    private MessageFactory $messageFactory;
    private Messenger $messenger;
    private RequestToDtoConverter $requestToDtoConverter;

    public function __construct(
        CustomerRepository $customerRepository,
        MessageFactory $messageFactory,
        Messenger $messenger,
        RequestToDtoConverter $requestToDtoConverter,
    ) {
        $this->customerRepository = $customerRepository;
        $this->messageFactory = $messageFactory;
        $this->messenger = $messenger;
        $this->requestToDtoConverter = $requestToDtoConverter;
    }

    /**
     * @Route("/customer/{code}/notifications", name="customer_notifications", methods={"POST"})
     */
    public function notifyCustomer(string $code, Request $request): JsonResponse
    {
        try {
            $dto = $this->requestToDtoConverter->createFromContextBasedRequest($request, NotificationDto::class);
        } catch (DtoException $e) {
            return new JsonResponse(['error' => 'Invalid request', 'status' => 'error'], Response::HTTP_BAD_REQUEST);
        }

        $customer = $this->customerRepository->findOneBy(['code' => $code]);
        if (null === $customer) {
            return new JsonResponse(
                ['error' => 'User could not be found', 'status' => 'error'],
                Response::HTTP_BAD_REQUEST
            );
        }

        $message = $this->messageFactory->create($dto, $customer->getNotificationType());

        try {
            $this->messenger->send($message);
        } catch (MessengerException $e) {
            return new JsonResponse(
                ['error' => 'Unsupported messenger type', 'status' => 'error'],
                Response::HTTP_BAD_REQUEST
            );
        }

        return new JsonResponse(['status' => "OK"]);
    }
}
