<?php

namespace MicroweberPackages\Checkout\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use MicroweberPackages\Notification\Channels\AppMailChannel;
use MicroweberPackages\Option\Facades\Option;


class NewOrder extends Notification
{
    use Queueable;
    use InteractsWithQueue, SerializesModels;

    public $order;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($order = false)
    {
        $this->order = $order;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database', AppMailChannel::class];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {


        $mail = new MailMessage();

        $templateId = Option::getValue('new_order_mail_template', 'orders');
        $template = get_mail_template_by_id($templateId, 'new_order');

        if ($template) {

            $carItems = $this->_getCartItemsTable($this->order->id);

            $loader = new \Twig\Loader\ArrayLoader([
                'twig_mail_template' => $template['message'],
            ]);
            $twig = new \Twig\Environment($loader,[
                'autoescape'=>false
            ]);
            $parsedEmail = $twig->render('twig_mail_template', [
                    'cart_items' => $carItems,
                    'order_status' => $this->order->order_status,
                    'email' => $this->order->email,
                    'first_name' => $this->order->first_name,
                    'last_name' => $this->order->last_name,
                    'phone' => $this->order->phone,
                    'id' => $this->order->id,
                    'order_id' => $this->order->id,
                    'amount' => $this->order->amount,
                    'transaction_id' => $this->order->transaction_id,
                    'shipping' => $this->order->shipping,
                    'shipping_service' => $this->order->shipping_service,
                    'currency' => $this->order->currency,
                    'currency_code' => $this->order->currency_code,
                    'country' => $this->order->country,
                    'city' => $this->order->city,
                    'state' => $this->order->state,
                    'zip' => $this->order->zip,
                    'address' => $this->order->address,
                    'address2' => $this->order->address2,
                    'is_paid' => $this->order->is_paid,
                    'url' => url('/'),
                    'created_at' => date('Y-m-d H:i:s')
                ]
            );

            //cart_items
            $mail->subject($template['subject']);
            $mail->view('app::email.simple', ['content' => $parsedEmail]);
        } else {
            $mail->line('Thank you for your order.');
            $mail->action('Visit our website', url('/'));
        }

        return $mail;
    }

    private function _getCartItemsTable($orderId)
    {
        $cartItemsInfo = array();
        $cartItems = app()->shop_manager->get_cart('order_id=' . $orderId);

        foreach ($cartItems as $cartItem) {

            $item = array();
            if (isset($cartItem['item_image']) and $cartItem['item_image']) {
                $item['item_image'] = $cartItem['item_image'];
                $item['item_image'] = '<img src="' . $item['item_image'] . '" width="100" />';
            }
            if (isset($cartItem['link'])) {
                $item['link'] = $cartItem['link'];
            }
            if (isset($cartItem['title'])) {
                $item['title'] = $cartItem['title'];
            }
            if (isset($cartItem['custom_fields'])) {
                $item['custom_fields'] = $cartItem['custom_fields'];
            }

            $cartItemsInfo[] = $item;
        }

        return app()->format->array_to_table($cartItemsInfo);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return $this->order;
    }


}
