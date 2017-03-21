<?php
namespace LicenseLounge\Contract;
require_once __DIR__.'/../vendor/phpwkhtmltopdf/vendor/autoload.php';
use mikehaertl\wkhtmlto\Pdf as Wkhtmlto_PDF;

class PDF {
    public static function generate( $content ) {
        $wp_upload_dir = wp_upload_dir();
        $path_to_save = $wp_upload_dir['basedir']."/tmp/License_Agreement_Contract.pdf";
        $pdf = new Wkhtmlto_PDF( "<html><body style='font-family: serif;'>" . $content . "</body></html>");
        $pdf->setOptions(array(
            "encoding" =>"UTF-8",
            'header-html' => "<!DOCTYPE html><html><p align='center' style='padding-top: 20px;'><img src='http://licenselounge.com/wp-content/uploads/ll-pdf-logo.png' /></p></html>",
            'header-spacing' => 5 
            )
        );
        $pdf->binary = "/usr/local/bin/wkhtmltopdf";
        $pdf->saveAs($path_to_save);
        return $path_to_save;
    }

    public static function remove($path_to_pdf) {
        unlink($path_to_pdf);
    }
}