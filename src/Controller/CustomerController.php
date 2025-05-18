<?php declare(strict_types=1);

namespace App\Controller;

use App\EntityRepository\CustomerRepository;
use App\Model\Message;
use App\Service\EmailSender;
use App\Service\Messenger;
use App\Service\SMSSender;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CustomerController extends AbstractController
{
    private CustomerRepository $customerRepository;

    public function __construct(
        CustomerRepository $customerRepository
    ) {
        $this->customerRepository = $customerRepository;
    }

    /**
     * @Route("/customer/{code}/notifications", name="customer_notifications", methods={"GET"})
     */
    public function notifyCustomer(string $code, Request $request): Response
    {
        $requestData = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);

        $customer = $this->customerRepository->find($code);

        $message = new Message();
        $message->setBody($requestData['body']);
        $message->setType($customer->getNotificationType());

        $messenger = new Messenger([new EmailSender(), new SMSSender()]);
        $messenger->send($message);

        return new Response("OK");
    }
}
