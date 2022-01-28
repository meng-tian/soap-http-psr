# soap-http-psr

This library is a further step from [meng-tian/async-soap-guzzle](https://github.com/meng-tian/async-soap-guzzle). Instead of depending on a specific HTTP client, this library builds a SOAP client on the top of [PHP Standards Recommendataions (PSR)](https://www.php-fig.org/) where different implementations of [HTTP client](https://www.php-fig.org/psr/psr-18/) and [HTTP messages](https://www.php-fig.org/psr/psr-7/) can be used.
