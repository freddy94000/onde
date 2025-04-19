<?php

namespace App\Command;

use App\Entity\Category;
use App\Entity\Country;
use App\Entity\Island;
use App\Entity\Menu;
use App\Entity\Page;
use App\Enum\TypeMenu;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;

#[AsCommand(
    name: 'app:import:data',
    description: 'Init Data of Project Onde',
)]
class ImportDataCommand extends Command
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
        parent::__construct('app:import:data');
    }


    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln([
            'Add Countries',
            '============',
            '',
        ]);

        $arrayCountries = $this->transformFileToArray('data/country.csv');
        $progressBar = new ProgressBar($output, count($arrayCountries));
        $progressBar->start();
        $countries = [];
        foreach ($arrayCountries as $arrayCountry) {
            $country = new Country();
            $country->setName($arrayCountry['name']);
            $this->entityManager->persist($country);
            $progressBar->advance();
            $countries[$arrayCountry['id']] = $country;
        }
        $this->entityManager->flush();
        $progressBar->finish();

        $output->writeln([
            'Add Islands',
            '============',
            '',
        ]);

        $arrayIslands = $this->transformFileToArray('data/island.csv');
        $progressBar = new ProgressBar($output, count($arrayIslands));
        $progressBar->start();
        foreach ($arrayIslands as $arrayIsland) {
            $island = new Island();
            $island
                ->setName($arrayIsland['name'])
                ->setCountry($countries[$arrayIsland['pays']])
            ;
            $this->entityManager->persist($island);
            $progressBar->advance();
        }
        $this->entityManager->flush();
        $progressBar->finish();

        $output->writeln([
            'Add Pages',
            '============',
            '',
        ]);

        $arrayPages = $this->transformFileToArray('data/page.csv');
        $progressBar = new ProgressBar($output, count($arrayPages));
        $progressBar->start();
        $pages = [];
        foreach ($arrayPages as $arrayPage) {
            $page = new Page();
            $page
                ->setName($arrayPage['name'])
                ->setContent($arrayPage['content'])
            ;
            $this->entityManager->persist($page);
            $progressBar->advance();
            $pages[$arrayPage['id']] = $page;
        }
        $this->entityManager->flush();
        $progressBar->finish();

        $output->writeln([
            '',
            'Add Categories',
            '============',
            '',
        ]);

        $arrayMenus = $this->transformFileToArray('data/menu.csv');
        $progressBar = new ProgressBar($output, count($arrayMenus));
        $progressBar->start();
        $menus = [];
        foreach ($arrayMenus as $arrayMenu) {
            $menu = new Menu();
            $menu
                ->setName($arrayMenu['name'])
                ->setType(TypeMenu::from($arrayMenu['type']))
                ->setMenuOrder($arrayMenu['order'])
            ;

            if ($arrayMenu['parent']) {
                $menu->setParent($menus[$arrayMenu['parent']]);
            }

            if ($arrayMenu['page']) {
                $menu->setPage($pages[$arrayMenu['page']]);
            }

            if ($arrayMenu['category']) {
                $category = new Category();
                $category->setName($arrayMenu['name']);
                $this->entityManager->persist($category);
                $menu->setCategory($category);
            }

            if ($arrayMenu['path']) {
                $menu->setPath($arrayMenu['path']);
            }

            if ($arrayMenu['url']) {
                $menu->setUrl($arrayMenu['url']);
            }

            if ($arrayMenu['icon']) {
                $menu->setIcon($arrayMenu['icon']);
            }


            $this->entityManager->persist($menu);
            $progressBar->advance();
            $menus[$arrayMenu['id']] = $menu;
        }
        $this->entityManager->flush();
        $progressBar->finish();

        return Command::SUCCESS;
    }

    private function transformFileToArray(string $path): array
    {
        $array = [];
        $filesystem = new Filesystem();
        if ($filesystem->exists($path)) {
            $contents = $filesystem->readFile($path);
            $lines = explode("\n", $contents);
            $head = str_getcsv(array_shift($lines), ";");
            foreach ($lines as $line) {
                if (empty($line)) {
                    continue;
                }

                $csv = str_getcsv($line,";");
                $array[] = array_combine($head, $csv);
            }
        }

        return $array;
    }
}
