# A CLI tool for decoding lightning network payment requests as defined in BOLT #11
![Docker Pulls](https://img.shields.io/docker/pulls/jorijn/bitcoin-bolt11)

This tool allows for easy decoding and converting of bech32 encoded payment requests as defined in BOLT #11. It enables you to convert payment requests to the following formats:

* JSON
* XML
* YAML
* CSV

## Installation

### Docker (preferred)
The Docker image is already set up and configured with all the required dependencies. You can pull the image from Docker Hub like this;

```shell
$ docker pull jorijn/bitcoin-bolt11:latest
```

There are also tagged versions available for each release. You can inspect which ones through this repository or at https://hub.docker.com/r/jorijn/bitcoin-bolt11/tags.

#### Architectures other than amd64
Currently, only automated `amd64` builds are produced. If you require other architectures you can clone this repository and run;

```shell
$ docker build . --tag jorijn/bitcoin-bolt11:latest
```

### Manually
Alternatively, you can install and build the CLI tool yourself. First, clone this repository and make sure your PHP installation is set up. The minimum version is PHP 7.4, and the program requires extensions `json`, `bcmath` and `gmp`.

For setting up all the required dependencies and configure autoloading, run the following command;

```shell
$ composer install --no-dev
```

Now you can run the program from the `bin` directory;

```shell
$ bin/bolt11 --help
```

## Usage
```
Usage:
  decode [options] [--] <payment-request>

Arguments:
  payment-request                    The payment request that should be decoded.

Options:
  -o, --output-format=OUTPUT-FORMAT  The format the payment request should be output as. Default: json. Supported: xml, yaml, csv, json
  -f, --formatted                    If supplied, will format the result.
  -h, --help                         Display help for the given command. When no command is given display help for the list command
  -q, --quiet                        Do not output any message
  -V, --version                      Display this application version
      --ansi                         Force ANSI output
      --no-ansi                      Disable ANSI output
  -n, --no-interaction               Do not ask any interactive question
  -v|vv|vvv, --verbose               Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug
  ```

## Examples
### Convert to JSON, formatted
```shell
$ docker run --rm jorijn/bitcoin-bolt11 decode --output-format=json --formatted lnbc9678785340p1pwmna7lpp5gc3xfm08u9qy06djf8dfflhugl6p7lgza6dsjxq454gxhj9t7a0sd8dgfkx7cmtwd68yetpd5s9xar0wfjn5gpc8qhrsdfq24f5ggrxdaezqsnvda3kkum5wfjkzmfqf3jkgem9wgsyuctwdus9xgrcyqcjcgpzgfskx6eqf9hzqnteypzxz7fzypfhg6trddjhygrcyqezcgpzfysywmm5ypxxjemgw3hxjmn8yptk7untd9hxwg3q2d6xjcmtv4ezq7pqxgsxzmnyyqcjqmt0wfjjq6t5v4khxxqyjw5qcqp2rzjq0gxwkzc8w6323m55m4jyxcjwmy7stt9hwkwe2qxmy8zpsgg7jcuwz87fcqqeuqqqyqqqqlgqqqqn3qq9qn07ytgrxxzad9hc4xt3mawjjt8znfv8xzscs7007v9gh9j569lencxa8xeujzkxs0uamak9aln6ez02uunw6rd2ht2sqe4hz8thcdagpleym0j
{
    "prefix": "lnbc9678785340p",
    "satoshis": 967878,
    "milli_satoshis": 967878534,
    "timestamp": 1572468703,
    [...]
}
```

### Convert to XML, formatted
```shell
$ docker run --rm jorijn/bitcoin-bolt11 decode --output-format=xml --formatted lnbc9678785340p1pwmna7lpp5gc3xfm08u9qy06djf8dfflhugl6p7lgza6dsjxq454gxhj9t7a0sd8dgfkx7cmtwd68yetpd5s9xar0wfjn5gpc8qhrsdfq24f5ggrxdaezqsnvda3kkum5wfjkzmfqf3jkgem9wgsyuctwdus9xgrcyqcjcgpzgfskx6eqf9hzqnteypzxz7fzypfhg6trddjhygrcyqezcgpzfysywmm5ypxxjemgw3hxjmn8yptk7untd9hxwg3q2d6xjcmtv4ezq7pqxgsxzmnyyqcjqmt0wfjjq6t5v4khxxqyjw5qcqp2rzjq0gxwkzc8w6323m55m4jyxcjwmy7stt9hwkwe2qxmy8zpsgg7jcuwz87fcqqeuqqqyqqqqlgqqqqn3qq9qn07ytgrxxzad9hc4xt3mawjjt8znfv8xzscs7007v9gh9j569lencxa8xeujzkxs0uamak9aln6ez02uunw6rd2ht2sqe4hz8thcdagpleym0j
<?xml version="1.0"?>
<response>
  <prefix>lnbc9678785340p</prefix>
  <satoshis>967878</satoshis>
  <milli_satoshis>967878534</milli_satoshis>
  <timestamp>1572468703</timestamp>
  [...]
</response>
```

### Convert to YAML, formatted
```shell
$ docker run --rm jorijn/bitcoin-bolt11 decode --output-format=yaml --formatted lnbc9678785340p1pwmna7lpp5gc3xfm08u9qy06djf8dfflhugl6p7lgza6dsjxq454gxhj9t7a0sd8dgfkx7cmtwd68yetpd5s9xar0wfjn5gpc8qhrsdfq24f5ggrxdaezqsnvda3kkum5wfjkzmfqf3jkgem9wgsyuctwdus9xgrcyqcjcgpzgfskx6eqf9hzqnteypzxz7fzypfhg6trddjhygrcyqezcgpzfysywmm5ypxxjemgw3hxjmn8yptk7untd9hxwg3q2d6xjcmtv4ezq7pqxgsxzmnyyqcjqmt0wfjjq6t5v4khxxqyjw5qcqp2rzjq0gxwkzc8w6323m55m4jyxcjwmy7stt9hwkwe2qxmy8zpsgg7jcuwz87fcqqeuqqqyqqqqlgqqqqn3qq9qn07ytgrxxzad9hc4xt3mawjjt8znfv8xzscs7007v9gh9j569lencxa8xeujzkxs0uamak9aln6ez02uunw6rd2ht2sqe4hz8thcdagpleym0j
 prefix: lnbc9678785340p
 satoshis: 967878
 milli_satoshis: 967878534
 timestamp: 1572468703
 [...]
```

### Linux-fu with `jq`
```shell
docker run --rm jorijn/bitcoin-bolt11 decode --output-format=json lnbc9678785340p1pwmna7lpp5gc3xfm08u9qy06djf8dfflhugl6p7lgza6dsjxq454gxhj9t7a0sd8dgfkx7cmtwd68yetpd5s9xar0wfjn5gpc8qhrsdfq24f5ggrxdaezqsnvda3kkum5wfjkzmfqf3jkgem9wgsyuctwdus9xgrcyqcjcgpzgfskx6eqf9hzqnteypzxz7fzypfhg6trddjhygrcyqezcgpzfysywmm5ypxxjemgw3hxjmn8yptk7untd9hxwg3q2d6xjcmtv4ezq7pqxgsxzmnyyqcjqmt0wfjjq6t5v4khxxqyjw5qcqp2rzjq0gxwkzc8w6323m55m4jyxcjwmy7stt9hwkwe2qxmy8zpsgg7jcuwz87fcqqeuqqqyqqqqlgqqqqn3qq9qn07ytgrxxzad9hc4xt3mawjjt8znfv8xzscs7007v9gh9j569lencxa8xeujzkxs0uamak9aln6ez02uunw6rd2ht2sqe4hz8thcdagpleym0j | jq -r .payee_node_key
030e0aad879f25cb8627bc68bf57cd567da5130cfee70c8c940a43e1ba8e430300
```

### Shell output redirection
```shell
$ docker run --rm jorijn/bitcoin-bolt11 decode --output-format=csv lnbc9678785340p1pwmna7lpp5gc3xfm08u9qy06djf8dfflhugl6p7lgza6dsjxq454gxhj9t7a0sd8dgfkx7cmtwd68yetpd5s9xar0wfjn5gpc8qhrsdfq24f5ggrxdaezqsnvda3kkum5wfjkzmfqf3jkgem9wgsyuctwdus9xgrcyqcjcgpzgfskx6eqf9hzqnteypzxz7fzypfhg6trddjhygrcyqezcgpzfysywmm5ypxxjemgw3hxjmn8yptk7untd9hxwg3q2d6xjcmtv4ezq7pqxgsxzmnyyqcjqmt0wfjjq6t5v4khxxqyjw5qcqp2rzjq0gxwkzc8w6323m55m4jyxcjwmy7stt9hwkwe2qxmy8zpsgg7jcuwz87fcqqeuqqqyqqqqlgqqqqn3qq9qn07ytgrxxzad9hc4xt3mawjjt8znfv8xzscs7007v9gh9j569lencxa8xeujzkxs0uamak9aln6ez02uunw6rd2ht2sqe4hz8thcdagpleym0j > payment_request.csv
```

## PHP Library
The code is available for use within other PHP applications as a composer library. View the repository here: https://github.com/Jorijn/bitcoin-bolt11
