<?php

namespace MicroweberPackages\Notification\Channels;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Channels\MailChannel;
use MicroweberPackages\Utils\Mail\MailSender;
use Symfony\Component\Mailer\Exception\TransportException;

class AppMailChannel extends MailChannel
{
    /**
     * Send the given notification.
     *
     * @param  mixed $notifiable
     * @param  \Illuminate\Notifications\Notification $notification
     * @return void
     */
    public function send($notifiable, Notification $notification)
    {


         $mailSender = new MailSender();
         $mailSender->configMailDriver();


        if (!\Config::get('mail.driver')) {
            return;
        }

        // Swift_RfcComplianceException: Address in mailbox given [Admin] does not comply with RFC 2822, 3.6.2. in /var/www/html/vendor/swiftmailer/swiftmailer/lib/classes/Swift/Mime/Headers/MailboxHeader.php:355

        try {
            return parent::send($notifiable, $notification);
//        } catch (Swift_AddressEncoderException $e) {
//             \Log::error($e);
//        } catch (Swift_DependencyException $e) {
//            \Log::error($e);
//        } catch (Swift_RfcComplianceException $e) {
//             \Log::error($e);
//        } catch (Swift_TransportException $e) {
//             \Log::error($e);
        } catch (TransportException $e) {
         //    \Log::error($e);
        } catch (\Exception $e) {
         //   \Log::error($e);
        }
    }
}
