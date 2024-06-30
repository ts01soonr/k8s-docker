### Get a Free SSL Certificate With Let’s Encrypt

_Let’s Encrypt is a free, automated, and open Certificate Authority._

1. Install tools for using the Let's Encrypt certificates using Certbot

```bash

  sudo apt-get update
  sudo apt-get install software-properties-common
  sudo add-apt-repository ppa:certbot/certbot
  sudo apt-get update
  sudo apt-get install python-certbot-nginx

```

2. Configure your domain DNS to point to your droplet's IP

3. Check if your domain is pointing correctly

   ```bash

   $ dig +short aws.35cloud.com
   > 3.67.161.133

   ```

4. Run Certbot to create the SSL certificate

   ```bash

   sudo certbot --nginx certonly

   ```

### Setup Nginx with SSL

1.  Install Nginx

    ```bash

    sudo apt-get install nginx

    ```

2.  Redirect all traffic traffic to SSL

    ```bash

    # Open the following file
    sudo vim /etc/nginx/sites-enabled/default

    # Delete everything and add the following
    server {
        listen 80;
        listen [::]:80 default_server ipv6only=on;
        return 301 https://$host$request_uri;
    }

    ```

3.  Create a secure Diffie-Hellman group (takes a few minutes)

    ```bash

    sudo openssl dhparam -out /etc/ssl/certs/dhparam.pem 2048

    ```

4.  Create a configuration file for SSL

    ```bash

    # Open the following file
    sudo vim /etc/nginx/snippets/ssl-params.conf

    # Paste the following from https://cipherli.st/ (follow the link for more info)
    ssl_protocols TLSv1.3 TLSv1.2 TLSv1.1 TLSv1;
    ssl_prefer_server_ciphers on;
    ssl_ciphers ECDHE-RSA-AES256-GCM-SHA512:DHE-RSA-AES256-GCM-SHA512:ECDHE-RSA-AES256-GCM-SHA384:DHE-RSA-AES256-GCM-SHA384:ECDHE-RSA-AES256-SHA384;
    ssl_ecdh_curve secp384r1; # Requires nginx >= 1.1.0
    ssl_session_timeout  10m;
    ssl_session_cache shared:SSL:10m;
    ssl_session_tickets off; # Requires nginx >= 1.5.9
    ssl_stapling on; # Requires nginx >= 1.3.7
    ssl_stapling_verify on; # Requires nginx => 1.3.7
    resolver 208.67.222.222 208.67.220.220 valid=300s;
    resolver_timeout 5s;
    add_header Strict-Transport-Security "max-age=63072000; includeSubDomains; preload";
    add_header X-Frame-Options DENY;
    add_header X-Content-Type-Options nosniff;
    add_header X-XSS-Protection "1; mode=block";
    
    
    # Paste this at the bottom of the file
    ssl_dhparam /etc/ssl/certs/dhparam.pem;

    ```

5.  Configure the server to use SSL

    **ATTENTION:** Replace all the `aws.35cloud.com` with your domain

    ```bash

    # Open the following file
    sudo vim /etc/nginx/sites-enabled/default

    # Paste the following bellow the existing config
    server {
        listen 443 ssl http2;
        listen [::]:443 ssl http2;
        server_name aws.35cloud.com; # REPLACE HERE

        ssl_certificate /etc/letsencrypt/live/aws.35cloud.com/fullchain.pem; # REPLACE HERE
        ssl_certificate_key /etc/letsencrypt/live/aws.35cloud.com/privkey.pem; # REPLACE HERE

        include snippets/ssl-params.conf;

        location / {
            proxy_set_header X-Real-IP $remote_addr;
            proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
            proxy_set_header X-NginX-Proxy true;
            proxy_set_header Upgrade $http_upgrade;
            proxy_set_header Connection 'upgrade';
            proxy_pass http://localhost:9000/;
            proxy_ssl_session_reuse off;
            proxy_set_header Host $http_host;
            proxy_pass_header Server;
            proxy_cache_bypass $http_upgrade;
            proxy_redirect off;
        }
    }

    ```

6.  Test the Nginx config

    ```bash

    $ sudo nginx -t
    > nginx: the configuration file /etc/nginx/nginx.conf syntax is ok
    > nginx: configuration file /etc/nginx/nginx.conf test is successful

    ```

7.  Start Nginx

    ```bash

    sudo systemctl start nginx

    ```

8.  Finally, test your app by visiting your domain on your browser!

9.  Setup the same different local VM by reuse the same [ssl_certificate]

    edit C:\Windows\System32\drivers\etc\hosts
    
    add local VM's IP mapping into aws.35cloud.com
    
    192.168.33.12   aws.35cloud.com


![App Screenshot](/k8s/img/ssl.png)