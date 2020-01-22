# koning_geo

This extension extends **standard** TYPO3 tables with location / geo data.

# Installation
Install the extension. Configure ``$GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['koning_geo']`` array in the extension manager. There are two fields:

- ``tableList``, a comma separated list of TYPO3 table names that you want to enable geo data for. Example: ``pages, tt_content``.
- ``googleMapsApiKey``, the Google Maps API key for requesting geo data. Read more on [https://developers.google.com/maps/documentation/geocoding/get-api-key](https://developers.google.com/maps/documentation/geocoding/get-api-key)

# Usage in backend
Every table that you enabled this functionality for will have a ``Location data`` tab in the backend. Fill in the name of the location as precise as possible (address, city, country). When found, the geo coordinates will be saved into the ``tx_koninggeo_domain_model_location`` table.

# Usage in code
Use the ``LocationRepository`` to retrieve configured data per record.

# To do
Make extension work for custom tables.
