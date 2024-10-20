## How To Test
#### run sail
#### run sail artisan migrate
#### run sail artisan db:seed
#### visit http://localhostj

If everything is working you should see a list of fake store names with a list of device names and a column identifying if they are products or peripherlas.

From this point you can see the initial database pull correctly gets the items and identifies them as units or peripherals. however if you do a search it will fail.

the StoreInventory model has relationships as well as methods which will return the type of device. I have experimented with this a lot but cannot seem to figure out how to get the search to work with this setup.

The only thing that makes the search work is directly interfering with the search itself via:

```php
if(filled($this-search)) {
    $query->where('product_catalogs.name, 'like', '%' . $this->search . '%')
        ->orWhere('peripherals.name', 'like', '%' . $this->search . '%');
}
```

and putting that inside the dataSource method in the table.