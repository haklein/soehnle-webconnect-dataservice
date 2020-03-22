# soehnle-webconnect-dataservice
This php script attempts to emulate the terminated Soehnle/Leifheit data service for the Webconnect body scales like the 063340. Unfortunately Leifheit/Soehnle made a business decision to turn of the mysoehnle service on very short notice. A not-so-very-good offer was made to replace the scale with their new module at some discount. I don't want to trash a well functioning scale just because of their planned obsolescence. 

The return strings have been determined via tcpdump and from https://github.com/biggaeh/bathroom_scales

# Installation
## DNS mangling

To redirect the HTTP request from the scale to this script, some DNS mangling is required. I'm using 'unbound' as DNS cache, the following did work for me in the `server:` section:
~~~
        local-zone: "bridge1.soehnle.de" transparent
        local-data: "bridge1.soehnle.de A 192.168.x.y"
~~~

## extensionless php execution

To map the request from the scale (`http://bridge1.soehnle.de/devicedataservice/dataservice?data=...`) to the PHP script (`dataservice.php`), I'm using this rewrite in the nginx configuration:
~~~
    location / {
        if (!-e $request_filename){
            rewrite ^(.*)$ /$1.php;
        }
        try_files $uri $uri/ =404;
    }
~~~
The script resides in `$WEB_ROOT/devicedataservice/dataservice.php`

## log file
create a `log` directory with proper permissions in the `devicedataservice` directory.

# Usage
The php script currently just logs the IDs and the weight (metric) to a log file:
~~~
more log/log_22-Mar-2020.log 
Sun, 22 Mar 20 12:02:28 +0100 e4e4060375b4_05737201-00527260 weight: 71.9
~~~

# TODO

Currently it just parses the MAC of the gateway, the ID of the scale, and the weight. Unfortuntately the service from Leifheit/Soehnle has been suspended on very short notice, and the scale doesn't display any analysis values locally (which makes the statement from Soehnle kind of funny that the scale could still be used offline). So I can't just do a straightforward match of data fields to values..
