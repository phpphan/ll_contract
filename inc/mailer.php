<?php
namespace LicenseLounge\Contract;

class Mailer {
    public static function send($email_address, $contract) {
        $headers = 'From: License Lounge <sales@licenselounge.com>' . "\r\n";
        wp_mail($email_address, "License Lounge - License Agreement Contract", "Hello,\n\nAttached, find a copy of the License Lounge Agreement for your recent purchase.\n\nThanks,\nLicense Lounge", $headers, $contract);
    }
}