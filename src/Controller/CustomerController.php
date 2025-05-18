<?php declare(strict_types=1);

namespace App\Controller;

use App\Dto\NotificationDto;
use App\EntityRepository\CustomerRepository;
use App\Exception\DtoException;
use App\Model\Message;
use App\Service\Messenger;
use App\Service\RequestToDtoConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class CustomerController extends AbstractController
{
    private CustomerRepository $customerRepository;
    private Messenger $messenger;
    private RequestToDtoConverter $requestToDtoConverter;

    public function __construct(
        CustomerRepository $customerRepository,
        Messenger $messenger,
        RequestToDtoConverter $requestToDtoConverter,
    ) {
        $this->customerRepository = $customerRepository;
        $this->messenger = $messenger;
        $this->requestToDtoConverter = $requestToDtoConverter;
    }

    /**
     * @Route("/customer/{code}/notifications", name="customer_notifications", methods={"GET"})
     */
    public function notifyCustomer(string $code, Request $request): Response
    {
        try {
            $dto = $this->requestToDtoConverter->createFromContextBasedRequest($request, NotificationDto::class);
        } catch (DtoException $e) {
            return new Response('Invalid request', Response::HTTP_BAD_REQUEST);
        }

        $customer = $this->customerRepository->findOneBy(['code' => $code]);
        if (null === $customer) {
            return new Response('User not found', Response::HTTP_NOT_FOUND);
        }

        $message = new Message();
        $message->setBody($dto->body);
        $message->setType($customer->getNotificationType());

        $this->messenger->send($message);

        return new Response("OK");
    }
}
