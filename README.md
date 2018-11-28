# plates-http-factory
   Plates adapter for ResponseFactories as defined in [PSR-17](https://www.php-fig.org/psr/psr-17/)
   
   ## Installation
   The (highly) recommended way to install this package is by using [Composer](https://getcomposer.org/)
   
   ```bash
   composer require studiow/plates-http-factory
   ```
   
   You will also need a package that implements PSR-17: [https://packagist.org/providers/psr/http-factory-implementation](https://packagist.org/providers/psr/http-factory-implementation)
   
   ## Usage
   All the examples use [http-interop/http-factory-guzzle](https://packagist.org/packages/http-interop/http-factory-guzzle).
   ### Basic usage
   ```php
   
   $factory = new \Studiow\Plates\ResponseFactory(
       new \League\Plates\Engine('/path/to/templates'), //the plates engine
       new \Http\Factory\Guzzle\ResponseFactory()      //a psr-17 compatible response factory
   );
   
   //rendering the template at /path/to/templates/pages/home with additional data
   $response = $factory->createResponse(
       'pages/home',
       ['title'=>'home']
   );
   ```
   
   ### Using HTTP response codes
   ```php
   $factory = new \Studiow\Plates\ResponseFactory(
       new \League\Plates\Engine('/path/to/templates'),
       new \Http\Factory\Guzzle\ResponseFactory()
   );
   
   $response = $factory->createResponse(
       'error/page-not-found', //name of the template
       ['title'=>'Page not found!'], //data to be passed to the template
       404,     //http status code
       'Not Found' //http reason phrase
   );
   ```