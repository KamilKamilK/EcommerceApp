## Spis treści
* [Project description](#project-description)
* [Used technogies](#used-technologies)
* [ENV Laravel](#env-laravel)
* [Project start](#project-start)
* [Postman urls](#postman-urls)

## Project description
Procject shows e-commerce API . As Admin you can easily create,edit,update and delete users, products and orders. As a client you can list all available products and place and order. In project you can notice observer pattern and task scheduling wchich delete orders older than 3 days. All required actions in this aplication are register in specially created for this logs.  
To test this application use POSTMAN application.

Project was created for recruitment requests.

### Used technogies 

- Php v. 7.4.9
- Laravel v. 8.0+
- MySQL

#### ENV Laravelowy
    It is necessary to connect to basic database.
    Standard env file looks like:
   
    DB_HOST=localhost
    DB_DATABASE=laravel
    DB_USERNAME=root
    DB_PASSWORD=
    
#### Project start

After you open project enter to POSTMAN program and use it by typing correct POSTMAN collection url.

```
Remember about migration
php artisan migrate
```

```
You can seed database typing laravel cli command
php artisan db:seed
```

#### POSTMAN collection urls

#USER
POST api/admin/users - create new user
GET api/admin/users - get all users
GET api/admin/users/{1} - show single user with id 1
PUT api/admin/users/{1} -modify user with id 1
DELETE api/admin/users/{1} - delete user with id 1

#PRODUCT
POST api/admin/products - create new products
GET api/admin/products - get all products
GET api/admin/products/{1} - show single products with id 1
PUT api/admin/products/{1} -modify products with id 1
DELETE api/admin/products/{1} - delete products with id 1

GET api/public/products - get all products
GET api/public/products/{1} - show single products with id 1

#ORDER
POST api/admin/orders - create new orders
GET api/admin/orders - get all orders
GET api/admin/orders/{1} - show single orders with id 1
PUT api/admin/orders/{1} -modify orders with id 1
DELETE api/admin/orders/{1} - delete orders with id 1

POST api/public/orders - create new orders as client


    Readme created 2021-11-18
