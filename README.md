## Epay for Laravel 4

Epay is payment gateway adapters.

### Installation

- [Epay on Packagist](https://packagist.org/packages/teepluss/epay)
- [Epay on GitHub](https://github.com/teepluss/laravel-epay)

To get the lastest version of Theme simply require it in your `composer.json` file.

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

API also ships with a facade which provides the static syntax for creating collections. You can register the facade in the `aliases` key of your `app/config/app.php` file.

~~~
'aliases' => array(

    'Epay' => 'Teepluss\Epay\Facades\Epay'

)
~~~

## Usage

...... is comming soon ......

## Support or Contact

If you have some problem, Contact teepluss@gmail.com

<a href='http://www.pledgie.com/campaigns/22201'><img alt='Click here to lend your support to: Donation and make a donation at www.pledgie.com !' src='http://www.pledgie.com/campaigns/22201.png?skin_name=chrome' border='0' /></a>
