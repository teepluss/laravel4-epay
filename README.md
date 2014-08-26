## Epay for Laravel 4

Epay is payment gateway adapters.

### Installation

- [Epay on Packagist](https://packagist.org/packages/teepluss/epay)
- [Epay on GitHub](https://github.com/teepluss/laravel4-epay)

To get the lastest version of Epay simply require it in your `composer.json` file.

~~~
"teepluss/epay": "dev-master"
~~~

You'll then need to run `composer install` to download it and have the autoloader updated.

Once Theme is installed you need to register the service provider with the application. Open up `app/config/app.php` and find the `providers` key.

~~~
'providers' => array(

    'Teepluss\Epay\EpayServiceProvider'

)
~~~

Epay also ships with a facade which provides the static syntax for creating collections. You can register the facade in the `aliases` key of your `app/config/app.php` file.

~~~
'aliases' => array(

    'Epay' => 'Teepluss\Epay\Facades\Epay'

)
~~~

## Usage

Generate payment form.
~~~php
$adapter = Epay::factory('paypal');

$adapter->setSandboxMode(true);

$adapter->setSuccessUrl('http://www.domain/foreground/success')
        ->setCancelUrl('http://www.domain/foreground/cancel')
        ->setBackendUrl('http://www.domain/background/invoice/00001');


$adapter->setMerchantAccount('demo@gmail.com');

$adapter->setLanguage('TH')
        ->setCurrency('THB');

$adapter->setInvoice(00001)
        ->setPurpose('Buy a beer.')
        ->setAmount(100);

$adapter->setRemark('Short note');

$generated = $adapter->render();

var_dump($generated);
~~~

Checking foregound process.
~~~php
$adapter = Epay::factory('paypal');

$adapter->setSandboxMode(true);

$adapter->setMerchantAccount('demo@gmail.com');

$adapter->setInvoice(00001);

$result = $adapter->getFrontendResult();

var_dump($result);
~~~

Checking background process (IPN)
~~~php
$adapter = Epay::factory('paypal');

$adapter->setSandboxMode(true);

$adapter->setMerchantAccount('demo@gmail.com');

$adapter->setInvoice(00001);

$result = $adapter->getBackendResult();

var_dump($result);
~~~

## Support or Contact

If you have some problem, Contact teepluss@gmail.com


[![Support via PayPal](https://rawgithub.com/chris---/Donation-Badges/master/paypal.jpeg)](https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=9GEC8J7FAG6JA)
