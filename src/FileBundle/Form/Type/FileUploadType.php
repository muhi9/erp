<?php

namespace FileBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;

use Symfony\Component\Form\Extension\Core\Type\FileType;

class FileUploadType extends AbstractType {
    static $mimeTypes = [
            /////////////////////////////////////////// expansion types
            'image-all' => [
                'image',
                'image-other',
            ],
            'text' => [
                'text-plain',
                'text-document',
                'text-office'
            ],

            /////////////////////////////////////////// plain types
            'video' => [
                'video/x-msvideo', // .avi
                'video/mpeg', // .mpeg
                'video/ogg', // .ogv
                'video/webm', // .webm
                'video/mp2t', // .ts
                'video/webm', // webm
                'video/3gpp', // .3gp
                'video/3gpp2', // .3g2
            ],
            'audio' => [
                'audio/aac', // .aac
                'audio/midi', // .mid .midi
                'audio/x-midi', // .mid .midi
                'audio/mpeg', // .mp3
                'audio/ogg', // .oga
                'audio/opus', // .opus
                'audio/wav', // .wav
                'audio/webm', // .weba
                'audio/3gpp', // .3gp
                'audio/3gpp2', // .3g2
            ],
            'archive' => [
                'application/x-freearc', // .arc
                'application/x-bzip', // .bz
                'application/x-bzip2', // .bz2
                'application/gzip', // .gz
                'application/java-archive', // .jar
                'application/vnd.rar', // .rar
                'application/x-tar', // .tar
                'application/zip', // .zip
                'application/x-7z-compressed', // .7z
            ],
            'e-book' => [
                'application/vnd.amazon.ebook', // .azw
                'application/epub+zip', // .epub
            ],
            'image' => [
                'image/gif', // .gif
                'image/jpeg', // .jpg .jpeg
                'image/png', // .png
            ],
            'image-other' => [
                'image/bmp', // .bmp
                'image/vnd.microsoft.icon', // .ico
                'image/svg+xml', // .svg
                'image/tiff', // .tif .tiff
                'image/webp', // webp
            ],
            'font' => [
                'application/vnd.ms-fontobject', // .eot
                'font/otf', // .otf
                'font/ttf', // ttf
                'font/woff', // .woff
                'font/woff2', // .woff2
            ],
            'text-plain' => [
                'text/plain', // .txt
            ],
            'text-web-document' => [
                'text/css', // .css
                'text/html', // .html
                //'application/xhtml+xml', // .xhtml
                'text/javascript', // .js .mjs
                'application/json', // .json
                'application/ld+json', // .jsonld
            ],
            'text-document' => [
                'application/pdf', // .pdf
                'application/rtf', // .rtf
            ],
            'text-office' => [
                'application/x-abiword', // .abv
                'text/csv', // .csv
                'application/msword',// .doc .dot
                'application/vnd.openxmlformats-officedocument.wordprocessingml.document', // .docx
                'text/calendar', // .ics
                'application/vnd.oasis.opendocument.presentation', // .odp
                'application/vnd.oasis.opendocument.spreadsheet', // .ods
                'application/vnd.oasis.opendocument.text', // .odt
                'application/vnd.ms-powerpoint', // .ppt .pot .pps .ppa
                'application/vnd.openxmlformats-officedocument.presentationml.presentation', // .pptx
                'application/vnd.ms-excel', // .xsl .xlt .xla
                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', // .xlxs
                'application/xml', // .xml
                'text/xml', // .xml
                'application/vnd.openxmlformats-officedocument.presentationml.template', // .potx
                'application/vnd.ms-access', // .mdb
                'application/vnd.visio', // .vsd
            ],
        ];

    public function buildForm(FormBuilderInterface $builder, array $options) {
    }



    public function buildView(FormView $view, FormInterface $form, array $options) {
        $options['mime'] = is_array($options['mime']) ? $options['mime'] : (is_string($options['mime']) ? [$options['mime']] : NULL);
        if (empty($options['mime'])) {
            throw new \Exception("Mime type is missing (".implode(", ", array_keys(self::$mimeTypes)).")", 1);
        }
        $mime = self::mime($options['mime']);
        $view->vars['mime'] = $options['mime'];
        $view->vars['maxSize'] = $options['maxSize'];
        $view->vars['attr']['accept'] = implode(",", $mime);
    }



    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults([
            'compound' => false,
            'inherit_data' => true,
            'by_reference' => true,
            'mapped' => false,
            'required' => false,
            'maxSize' => '30M',
            'mime' => ['image', 'text'],
            'resize_image' => ['max_width'=>false, 'max_height'=>false, 'keep_ratio'=>true],
        ]);
    }



    public function getParent() {
        return FileType::class;
    }


    public static function mime($options) {
        $output = [];
        $found = [];
        while ($option = array_pop($options)) {
            if (array_key_exists($option, self::$mimeTypes)) {
                $options = array_merge($options, self::$mimeTypes[$option]);
                $found = array_merge($found, self::$mimeTypes[$option]);
            } else if (!in_array($option, $found)) {
                throw new \Exception("Unknown file mime type \"$option\" (".implode(", ", array_keys(self::$mimeTypes)).")", 1);
            } else {
                $output[] = $option;
            }
        }
        return array_values(array_unique($output));
    }

}
