<?php
namespace FileBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Question\ChoiceQuestion;

class FlieFindMissingCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('file:findMissingOn')
            ->setDescription('find missing files  type: db - db records not on disk; type: disk - files on disk don\'t have db record')
            ->addArgument('type', InputArgument::REQUIRED, ' db/disk')
            //->addOption('no-debug', null, InputOption::VALUE_NONE, 'no-debug')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        $db = $em->getConnection();
        $pPath = $this->getContainer()->get('kernel')->getProjectDir();

        $db->beginTransaction();

        $type = $input->getArgument('type');
        $helper = $this->getHelper('question');


        $rows = $db->fetchAll('SELECT * FROM file');
        if ($type == 'db') {
            $output->writeln("DB files missing from disk:");
            $cnt = 0;
            foreach ($rows as $row) {
                $row['path'] = substr($row['path'], strpos($row['path'],'var/uploads'));
                if (!file_exists($pPath . '/' . $row['path'] . '/' . $row['disk_name'])) {
                    $output->writeln($row['id'] . '|' . $row['name'] . '|' . $pPath . '/' . $row['path'] . '/' . $row['disk_name']);
                    $cnt++;
                }
                //else $output->writeln($row['id'] . '|' . $row['name'] . '|' . $pPath . '/' . $row['path'] . '/' . $row['disk_name']);
            }
            $output->writeln("Missing " . $cnt . " files from disk and only ".(sizeof($rows)-$cnt)." are here.");
        }
        if ($type == 'disk') {
            $question = new ConfirmationQuestion('Do you want to zip and remove extra files on disk (after that a link for downloading will be available)? [yes/no]', false);
            $save = $helper->ask($input, $output, $question);

            $zip = null;
            if(true === $save) {
                $zip = new \ZipArchive();
                $zipfile = $pPath . "/var/".date('Y-m-d_H_i').'_files_missing_in_db_on_disk.zip';
                if ($zip->open($zipfile, \ZipArchive::CREATE)!==TRUE) {
                    exit("cannot open <$filename>\n");
                }
            }

            $diskFiles = $this->dir2Arr($this->getContainer()->getParameter('upload_directory'));
            $dbFiles = [];
            $filesWithNoDb = [];
            foreach($rows as $f) {
                $file = $f['path'] . '/' . $f['disk_name'];
                $file = str_replace('//','/',substr($file, strpos($file,'/var/uploads')));
                $dbFiles[] = $file;
            }

            $delFiles = [];
            foreach($diskFiles as $k=>$dfile) {
                $diskFiles[$k] = substr($dfile,strpos($dfile,'/var/uploads'));

                if (!in_array($diskFiles[$k], $dbFiles)) {
                    //echo 'IS IN: '. $diskFiles[$k]."\n";print_r($dbFiles);exit;
                    $filesWithNoDb[] = $diskFiles[$k];
                    if($zip) {
                        if($zip->addFile($dfile, $diskFiles[$k])) {
                            $delFiles[] = $dfile;
                        }
                    } else {
                        $output->writeln($dfile);
                    }
                }
                //else $output->writeln($dfile);
            }
            if($zip) {
                $output->writeln("Zip archive $zipfile created.\n".$zip->numFiles." files added in zip... deleting them. ZipStatus: ".$zip->status);
                if($zip->close()) {
                    foreach($delFiles as $f) unlink($f);
                }
            }
            //print_r($diskFiles);exit;
            //print_r($filesWithNoDb); exit;
        }

    }


    private function dir2Arr($path, $depth = 0, &$res = []) {
        if($depth>50) return false;
        $dirs = scandir($path);
        foreach ($dirs as $k => $v) {
          if (!in_array($v,array(".",".."))) {
             if (is_dir($path . DIRECTORY_SEPARATOR . $v)) {
                $this->dir2Arr($path . DIRECTORY_SEPARATOR . $v, ++$depth, $res);
             } else {
                $res[] = $path . DIRECTORY_SEPARATOR . $v;
             }
          }
       }
       return $res;
    }
}
