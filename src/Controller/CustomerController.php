<?php declare(strict_types=1);

namespace App\Controller;

use App\EntityRepository\CustomerRepository;
use App\Model\Message;
use App\Service\Messenger;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CustomerController extends AbstractController
{
    private CustomerRepository $customerRepository;
    private Messenger $messenger;

    public function __construct(
        CustomerRepository $customerRepository,
        Messenger $messenger,
    ) {
        $this->customerRepository = $customerRepository;
        $this->messenger = $messenger;
    }

    /**
     * @Route("/customer/{code}/notifications", name="customer_notifications", methods={"GET"})
     */
    public function notifyCustomer(string $code, Request $request): Response
    {
        $requestData = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);

        $customer = $this->customerRepository->findOneBy(['code' => $code]);
        if (null === $customer) {
            return new Response('User not found', Response::HTTP_NOT_FOUND);
        }

        $message = new Message();
        $message->setBody($requestData['body']);
        $message->setType($customer->getNotificationType());

        $this->messenger->send($message);

        return new Response("OK");
    }
}
