<?php

namespace App\Service;

use App\Exception\DtoException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RequestToDtoConverter
{
    private SerializerInterface $serializer;
    private ValidatorInterface $validator;

    public function __construct(
        SerializerInterface $serializer,
        ValidatorInterface $validator,
    ) {
        $this->serializer = $serializer;
        $this->validator = $validator;
    }

    /**
     * @template T
     * @param Request $request
     * @param class-string<T>  $dtoClass
     *
     * @return T
     * @throws DtoException
     */
    public function createFromContextBasedRequest(Request $request, string $dtoClass): object
    {
        $dto = $this->serializer->deserialize(
            $request->getContent(),
            $dtoClass,
            'json'
        );
        $errors = $this->validator->validate($dto);

        if (count($errors) > 0) {
            /** @var ConstraintViolation $error */
            $error = $errors[0];

            throw new DtoException($error->getPropertyPath() . ': ' . $error->getMessage());
        }

        return $dto;
    }
}
