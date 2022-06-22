# Cruz Roja Ecuatoriana

## Azure CDN Assets
### Commands
Sync assets that have been defined in the config to the CDN. Only pushes changes/new assets. Deletes locally removed files on CDN

     `php artisan asset-cdn:sync`

Pushes assets that have been defined in the config to the CDN. Pushes all assets. Does not delete files on CDN.

     `php artisan asset-cdn:push`

Deletes all assets from CDN, independent from config file.

     `php artisan asset-cdn:empty`

### Serving Assets
     Replace mix() with mix_cdn().
     Replace asset() with asset_cdn().
