<?php 
namespace App\Service;

use App\Entity\Player;
use App\Enum\Position;
use Doctrine\ORM\EntityManagerInterface;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class PlayerImporter
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function importFromXlsx(UploadedFile $file): void
    {
        $spreadsheet = IOFactory::load($file->getPathname());
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray();

        //la première ligne est l'en-tête
        foreach (array_slice($rows, 1) as $row) {
            [$firstName, $lastName, $position, $team, $age] = $row;
            //dump($row);
            $player = new Player();
            $player->setFirstName($firstName);
            $player->setLastName($lastName);
            $player->setPosition(Position::from($position));
            $player->setTeam($team);
            $player->setAge((int) $age);

            $this->em->persist($player);
        }

        $this->em->flush();
    }
}
