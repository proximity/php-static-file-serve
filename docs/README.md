# PHP Static File Serve
This tool is for securely serving static files, and is built using PHP.
An .htaccess file is included for easy configuration with Apache, but
you should be able to configure other webservers easily as well.

## Installation
First, set up a folder that you want to store your files in. This
folder does not need to be directly accessible by web, but the web user
should have read permissions.

Then, set up this app in a folder that is accessible by web. Ideally
the php folder should be configured as the 'public_html' directory.

### Apache < 2.4
If you use AllowOverride, everything should work automatically. If you
have turned AllowOverride off or would like to for security reasons,
follow the instructions in the Apache 2.4 section below.

### Apache 2.4+
AllowOverride is disabled by default, this is good. Just copy the
contents of .htaccess into your Apache vhost, inside a <Directory>
block.

### Other web servers
Other web servers may work, but have not been tested and will likely
have different configuration instructions.
