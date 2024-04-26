```php
private function sendConfirmationEmailToClient($subject, $data, $name, $email) {
    $to = $email;
    $from = 'rebates@conservationpays.com';

    $template = getViewsPath() . 'mailer/template/template.php';

    if (file_exists($template)) {
        extract($data);
        ob_start();
        include $template;
        $message = ob_get_clean();
    } else {
        $this->log('The email template file was not found at: ' . $template);
        $message = $data['html_message'];
    }

    @$headers = 'MIME-Version: 1.0' . "\r\n";
    $headers .= 'Content-type: text/html; charset=UTF-8' . "\r\n";
    $headers .= 'From: ' . $from . "\r\n";
    $headers .= 'X-Mailer: PHP/' . phpversion();
    return mail($to, $subject, $message, $headers);
}

private function getWaitListMailMessage($name, $email, $confirmation) {
    $data = [
        'title' => "Thank you",
        'html_message' => htmlspecialchars($name) . ',  thank you for completing the rebate '
        . 'application. Unfortunately, there are no rebates available at the moment, but we '
        . 'have added you to our waiting list and will notify you when one '
        . 'becomes available in your area.<br /><br />Below you will find your '
        . 'confirmation code. Please save it for your records. If you need to '
        . 'contact us about your status on the waiting list, please provide us '
        . 'with your confirmation code so that we can easily retrieve your records.'
        . '<br /><br />'
        . '<b>Confirmation code: ' . htmlspecialchars($confirmation) . '</b><br />'
        . '<b>Email address: ' . htmlspecialchars($email) . '</b><br /><br /><em>** Do not purchase '
        . 'your toilet unless you have received your Approval Confirmation '
        . 'Notification and Number. Toilets purchased before you have been '
        . 'approved may not be eligible for a rebate.**</em>'
    ];
    $this->message = trim($data['html_message']);
    return $data;
}

private function getMailMessage($name, $email, $applicationid) {
    $rebateslink = 'http://rebates2.conservationpays.com';

    $data = [
        'title' => 'Thank You ',
        'html_message' => htmlspecialchars($name) . ',  thank you for completing the rebate '
        . 'application. We have received your request and it\'s pending review. '
        . 'Once your application has been reviewed we will notify you whether '
        . 'it\'s been approved or denied.'
        . '<br /><br />'
        . 'Please allow up to five (5) business days to receive a email confirmation '
        . 'of "Approval or Denial" from the Rebate Administrator regarding your '
        . 'application. If approved, you will receive an email with all of the '
        . 'instructions on how to receive your rebate. '
        . '<span style="text-decoration:underline;">Please read that email carefully</span>.'
        . '<br /><br />'
        . 'Below you will find your Application Number. Please save it for '
        . 'your records. If you need to contact us about your rebate application, '
        . 'please provide us with your Application Number so that we can easily '
        . 'retrieve your records. You will also be able to use your Application '
        . 'Number to access our online Rebate Center to check on the status of your application.'
        . '<br /><br />'
        . '<b>Application Number: ' . htmlspecialchars($applicationid) . '</b><br />'
        . '<b>Email address: ' . htmlspecialchars($email) . '</b><br /><br /><em>** Do not purchase '
        . 'your toilet unless you have received your Approval Confirmation '
        . 'Notification and Number. Toilets purchased before you have been '
        . 'approved may not be eligible for a rebate.**</em><br /><br />'
        . '<span style="color:#7fa500">While you’re waiting for your approval, take '
        . 'advantage of free water-saving '
        . 'showerheads and faucet aerators. <a style="color:#0995d0" href="http://conservationpays.com/partners/">'
        . 'See if your community is participating</a>!</span>',
        'button_text' => 'Rebate Center',
        'button_url' => $rebateslink
    ];
    $this->message = trim($data['html_message']);
    return $data;
}
```