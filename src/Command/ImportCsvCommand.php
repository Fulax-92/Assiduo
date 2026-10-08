<?php

namespace App\Command;

use App\Entity\AnneeScolaire;
use App\Entity\Classe;
use App\Entity\Etablissement;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Attribute\Option;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

#[AsCommand(name: 'app:import-csv', description: 'Importe les CSV de test (établissements, années scolaires, classes)')]
class ImportCsvCommand
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        #[Autowire('%kernel.project_dir%/data')]
        private readonly string $dataDir,
    ) {
    }

    public function __invoke(
        SymfonyStyle $io,
        #[Option(description: 'Vide les tables concernées avant import')] bool $purge = false,
    ): int {
        $this->em->wrapInTransaction(function () use ($io, $purge): void {
            if ($purge) {
                $this->em->createQuery('DELETE FROM '.Classe::class)->execute();
                $this->em->createQuery('DELETE FROM '.AnneeScolaire::class)->execute();
                $this->em->createQuery('DELETE FROM '.Etablissement::class)->execute();
            }

            /** @var array<string, Etablissement> $etablissements */
            $etablissements = [];
            foreach ($this->read('etablissements.csv') as $row) {
                $e = $this->em->getRepository(Etablissement::class)->findOneBy(['codeUai' => $row['code_uai']])
                    ?? new Etablissement();
                $e->setCodeUai($row['code_uai'])
                    ->setNom($row['nom'])
                    ->setAdresse($row['adresse'])
                    ->setActif((bool) $row['actif']);
                $this->em->persist($e);
                $etablissements[$row['code_uai']] = $e;
            }
            $this->em->flush();
            $io->writeln(sprintf('%d établissement(s)', \count($etablissements)));

            /** @var array<string, AnneeScolaire> $annees */
            $annees = [];
            foreach ($this->read('annees_scolaires.csv') as $row) {
                $etab = $this->etablissement($etablissements, $row['code_uai']);
                $a = $this->em->getRepository(AnneeScolaire::class)
                    ->findOneBy(['libelle' => $row['libelle'], 'etablissement' => $etab]) ?? new AnneeScolaire();
                $a->setEtablissement($etab)
                    ->setLibelle($row['libelle'])
                    ->setDateDebut(new \DateTimeImmutable($row['date_debut']))
                    ->setDateFin(new \DateTimeImmutable($row['date_fin']))
                    ->setCloturee((bool) $row['cloturee']);
                $this->em->persist($a);
                $annees[$row['code_uai'].'|'.$row['libelle']] = $a;
            }
            $this->em->flush();
            $io->writeln(sprintf('%d année(s) scolaire(s)', \count($annees)));

            $count = 0;
            foreach ($this->read('classes.csv') as $row) {
                $annee = $annees[$row['code_uai'].'|'.$row['annee_scolaire']]
                    ?? throw new \RuntimeException(sprintf('Année "%s" inconnue pour %s.', $row['annee_scolaire'], $row['code_uai']));
                $etab = $this->etablissement($etablissements, $row['code_uai']);
                $c = $this->em->getRepository(Classe::class)
                    ->findOneBy(['libelle' => $row['libelle'], 'etablissement' => $etab, 'anneeScolaire' => $annee])
                    ?? new Classe();
                $c->setLibelle($row['libelle'])
                    ->setNiveau($row['niveau'])
                    ->setActive((bool) $row['active'])
                    ->setEtablissement($etab)
                    ->setAnneeScolaire($annee);
                $this->em->persist($c);
                ++$count;
            }
            $this->em->flush();
            $io->writeln(sprintf('%d classe(s)', $count));
        });

        $io->success('Import terminé.');

        return Command::SUCCESS;
    }

    /**
     * @param array<string, Etablissement> $etablissements
     */
    private function etablissement(array $etablissements, string $uai): Etablissement
    {
        return $etablissements[$uai] ?? throw new \RuntimeException(sprintf('Établissement "%s" inconnu.', $uai));
    }

    /**
     * @return \Generator<array<string, string>>
     */
    private function read(string $file): \Generator
    {
        $path = $this->dataDir.'/'.$file;
        $handle = fopen($path, 'r') ?: throw new \RuntimeException(sprintf('Fichier introuvable : %s', $path));

        try {
            $header = fgetcsv($handle, separator: ';', escape: '');
            while (false !== ($line = fgetcsv($handle, separator: ';', escape: '')) ) {
                if ([null] === $line) {
                    continue;
                }
                yield array_combine($header, $line);
            }
        } finally {
            fclose($handle);
        }
    }
}
