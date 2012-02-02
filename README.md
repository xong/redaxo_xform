XForm for REDAXO Version 5
=============

AddOn to manage tables and forms for REDAXO CMS Version 5

	Take care - this is for REDAXO Version 5. If you want the Xform for REDAXO Version 4 you must go to https://github.com/dergel/redaxo4_xform/


Installation
-------

* Download and unzip
* Rename the unzipped folder from redaxo_xform to xform
* Move the folder to your REDAXO 5 System /redaxo/include/addons/
* Install and activate the addon xform and the plugins setup, manager, email in the REDAXO 5 backend


Development
-------

To register your own xform classes in your addon/plugin please use

	rex_xform::addPath($addonname, $type, $path);