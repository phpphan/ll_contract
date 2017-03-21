<?php
namespace LicenseLounge\Contract;
require_once __DIR__ . "/models/contract.php";
require_once __DIR__ . "/contract_template.php";
require_once __DIR__ . "/mailer.php";
require_once __DIR__ . "/pdf.php";
require __DIR__ . "/../../../../wp-load.php";
require __DIR__ . "/../../../../wp-cron.php";

use LicenseLounge\Contract\Model\Contract;
class Cron {
    public static function process() {
        $contract = Contract::find_one_undelivered();

        if( !$contract ) {
            return;
        }

        $soundkit = ($contract->soundkit)?true:false;
        $contract_template = new \LicenseLounge\Contract\Contract_Template($contract->price_id,$soundkit);
 
        $contract_template->process_content($contract);

        $path_to_pdf = \LicenseLounge\Contract\PDF::generate($contract_template->processed_content);
        $email_address = get_post_meta($contract->payment_id, "_edd_payment_user_email", true);

        Mailer::send($email_address, $path_to_pdf);        
        Contract::update($contract->id, array(
                "content" => $contract_template->processed_content,
                "data" => serialize($contract_template->replacement_data),
                "delivered_on" => current_time( "mysql" ),
                "email_address" => $email_address
            )
        );
        \LicenseLounge\Contract\PDF::remove($path_to_pdf);
    }
}