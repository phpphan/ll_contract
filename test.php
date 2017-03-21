<?php

require "../../../wp-load.php";

$original_contract_content = get_page_by_path("contract-gold")->post_content;
$data = array(
    "date" => date("M d, Y"),
    "string_to_replace" => "Testing 123"
);
$patterns = array();
$replacements = array();
foreach($data as $k => $v) {
    $patterns[] = "/\[\[" . $k . "\]\]/";
    $replacements[] = $v;
}
$contract_content = preg_replace($patterns, $replacements, $original_contract_content);
$contract_content = wpautop($contract_content);

require_once __DIR__.'/inc/mailer.php';
use LicenseLounge\Contract\Mailer;
require_once __DIR__.'/vendor/phpwkhtmltopdf/vendor/autoload.php';
use mikehaertl\wkhtmlto\Pdf;

$wp_upload_dir = wp_upload_dir();
// $path_to_save = $wp_upload_dir["basedir"] . "/" . time() . ".pdf";
$path_to_save = "/tmp/" . time() . ".pdf";
$pdf = new Pdf( "<html><body style='font-family: serif;'>" . $contract_content . "</body></html>");
$pdf->binary = "wkhtmltopdf-amd64";
$pdf->setOptions(array(
        "encoding", "UTF-8",
        'header-html' => "<!DOCTYPE html><html><p align='center' style='padding-top: 20px 0 0;'><img src='http://licenselounge.com/wp-content/uploads/ll-pdf-logo.png' /></p></html>",
        'header-spacing' => 50
    )
);
$pdf->saveAs($path_to_save);
//Mailer::send("lakatos.frank@gmail.com", $path_to_save);
//Mailer::send("ngocson092@gmail.com", $path_to_save);
Mailer::send("lmarchie@gmail.com", $path_to_save);

unlink($path_to_save);

function save_contract_to_db() {
    LicenseLounge\Contract\Model\Contract::save( array(
            "content" => $contract_content,
            "data" => serialize(array(
                "original_content" => $original_contract_content
            ))
        )
    );    
}
