<?php

declare(strict_types=1);

namespace App\Controller\File;

use App\Exception\NotFoundException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/files/{fileName}', name: 'app.file_serve')]
class FileController extends AbstractController
{
    public function __construct(
        private readonly ParameterBagInterface $parameterBag
    ) {
    }

    public function __invoke(string $fileName): Response
    {
        $filePath = $this->parameterBag->get('files_upload_dir') . '/' . $fileName;
        if (!file_exists($filePath)) {
            throw new NotFoundException('The file does not exist');
        }

        $file = new File($filePath);
        return $this->file($file);

    }
}
