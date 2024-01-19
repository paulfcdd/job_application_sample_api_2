<?php

declare(strict_types=1);

namespace App\Query\GetFileByName;

use App\Entity\File;
use App\Exception\NotFoundException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
readonly class GetFileByNameDispatcher
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ){

    }

    public function __invoke(GetFileByNameQuery $query): ?File
    {
        $cvFile = $this->entityManager->getRepository(File::class)->findOneBy(['name' => $query->fileName]);

        if (!$cvFile) {
            throw new NotFoundException('File not found');
        }
        return $cvFile;
    }
}
