# snAPI-File

A simple PHP REST API to access data stored in the filesystem

## Purpose

This is an extremely lightweight REST API that utilizes (JSON) files as data stores. Just name your stores and point them to a file, and snAPI-File will handle the rest.

## Support

If you run into any problems, please let us know by opening a new issue.

## Store File

You must point snAPI-File to the stores you want to use. By default, snAPI-File loads those from a `stores.json` file, however you may change that through the constructor (see below).

The store file should be in the following format.

```json
{
  "store1": "store1.json",
  "store2": "store2.json"
}
```

The store being used is sent in each request. Only stores referenced in the store file are supported. Any other referenced store will result in an HTTP 404 error.

## Usage

### Setup

Setting up the API is simple. Just create your store file and add the following lines to your PHP file.

```php
use \SnapiFile\Api;

$api = new Api();
$api->go();
```

That's it. snAPI-File will handle the requests from there.

### API

#### `__construct($storeFile)`

##### Parameters

| Parameter | Type | Required | Default | Notes |
| --- | --- | :---: | --- | --- |
| `$storeFile` | string |  | `'stores.json'` | The default file is created in the same directory as your PHP file. |

##### Returns

| Type | Notes |
| --- | --- |
| `Api` | The `Api` object |

[:tophat:](#api)

#### `go()`

##### Parameters

| Parameter | Type | Required | Default | Notes |
| --- | --- | :---: | --- | --- |
|  | void |  |  |  |

##### Returns

| Type | Notes |
| --- | --- |
| void |  |

[:tophat:](#api)

### Client Requests

Requests to snAPI-File must be either `GET` or `POST` and include a specific set of parameters.

| Parameter | Required | Notes |
| --- | --- | --- |
| store | :heavy_check_mark: | The store to use
| action | :heavy_check_mark: | The action to perform (i.e., `create`, `retrieve`, `update`, `delete`)
| key | | The key in the store to affect <br><br> Only required for `create`, `update`, and `delete` actions. Use with `retrieve` to get a specific record from the store. Omit from `retrieve` to get all records from the store. |
| value | :heavy_check_mark: | The value to use to affect `key`
