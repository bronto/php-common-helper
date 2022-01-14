# Bronto PHP Common Library

**This package is archived and no longer maintained.**

This package contains a number of common scripts and utilities used
throughout the PHP packages provided by Bronto.

Provided is a set of functional utilties and a chainable object that
wraps an associative array.

## Transfer

The library ships with a pretty nice cURL transfer impl

```
<?php

$json = new \Bronto\Serialize\Json\Standard();
$curls = new \Bronto\Transfer\Curl\Adapter();
$request = $curls->createRequest('GET', 'http://some-resource.com');
$response = $request->respond();

$data = $json->decode($response->body());

$multi = new \Bronto\Transfer\Curl\Multi();
// Below are the defaults
$multi
    ->setMaxConnections(10)
    ->setPipeLining(true)
    ->setExecuteEagerly(true);
foreach ($data as $customer) {
    $request = $curls->createRequest('POST', 'http://some-resource.com')
        ->header('Authorization', 'Bearer: abc123')
        ->header('Content-Type', $json->getMimeType())
        ->body($json->encode($customer))
        ->on('complete', function($response) {
            var_dump($response);
        });
    $multi->add($request);
}
$multi->complete();
```
