
# Project Title

Using the PHP laravel framework, design an invoicing REST Backend
API.

# Getting Started

## Prerequisites
1. Make sure you have [PHP](https://www.php.net/) and [Composer](https://getcomposer.org/) installed on your machine.
2. Have a considerable undersatnding of laravel framework
3. Install [Postman](https://www.postman.com/) for API testing.
4. If you are on windows make sure you have the php extension php_sodium.dll. If not add extension=php_sodium.dll to your php.ini file
## Installation

Clone the repository

```bash
    git clone https://github.com/calebba/invoiceApi.git
    cd invoiceApi
```

Install dependencies

```bash
    composer install
```

 Environment Variables

```bash
    cp .env.example .env
    Update the .env file with your database configuration.
```

Generate the application key

```bash
    php artisan key:generate.
```

Run database migration

```bash
    php artisan migrate
```

create "personal access" and "password grant" clients which will be used to generate access client for api auth:

```bash
    php artisan passport:install
```

Start Laravel development server

```bash
    php artisan serve
```


## API Reference

#### Register User

```http
  POST /api/v1/register
```
#### Body
| Field | Type     | Description                |
| :-------- | :------- | :------------------------- |
| `name` | `string` | **Required**. name |
| `email` | `string` | **Required**.**Unique**. Email |
| `password` | `string` | **Required**.**Min=6(words)**. password |

#### Login User

```http
  POST /api/v1/login
```
#### Body
| Field | Type     | Description                       |
| :-------- | :------- | :-------------------------------- |
| `email`      | `string` | **Required**. email used for registration |
| `password`      | `string` | **Required**. password from registration |

#### Logout User

#### Authorization
| Type | Description                       |
| :-------- |  :-------------------------------- |
| `token`   | **Required**. Bearer token from login |

```http
  POST /api/v1/logout/{id}
```


## Users
### To use the users API, the current user mut be of user_type 2 which is the superAdmin
#### Authorization
| Type | Description                       |
| :-------- |  :-------------------------------- |
| `token`   | **Required**. Bearer token from Login|

#### Get Users

```http
  GET /api/v1/Users
```


#### Get User

```http
  GET /api/v1/users/{id}
```



#### Update User

```http
  PATCH /api/v1/users/{id}
```
#### Body
| Field | Type     | Description                       |
| :-------- | :------- | :-------------------------------- |
| `name` | `string` | name |
| `email` | `string` | **Unique**. Email |
| `password` | `string` | **Min=6(words)**. password |


#### Delete User

```http
  Delete /api/v1/users/{id}
```



## Products
#### Authorization
| Type | Description                       |
| :-------- |  :-------------------------------- |
| `token`   | **Required**. Bearer token from login |

#### Get Products

```http
  GET /api/v1/products
```

#### Create Products

```http
  POST /api/v1/products
```
#### Body
| Field | Type     | Description                       |
| :-------- | :------- | :-------------------------------- |
| `name`      | `string` | **Required**. Product name |
| `unit_price`      | `decimal` | **Required**. Product unit price |
| `quantity`      | `integer` | **Required**. Product initial quantity |


#### Get Product

```http
  GET /api/v1/products/{id}
```


#### Update Product

```http
  PATCH /api/v1/products/{id}
```
#### Body
| Field | Type     | Description                       |
| :-------- | :------- | :-------------------------------- |
| `name`      | `string` | Product name |
| `unit_price`      | `decimal` | Product unit price |
| `quantity`      | `integer` | Product initial quantity |


#### Delete Product

```http
  Delete /api/v1/products/{id}
```


## Customers
#### Authorization
| Type | Description                       |
| :-------- |  :-------------------------------- |
| `token`   | **Required**. Bearer token from Login |

#### Get Customers

```http
  GET /api/v1/customers
```

#### Create Customer

```http
  POST /api/v1/customers
```
#### Body
| Field | Type     | Description                       |
| :-------- | :------- | :-------------------------------- |
| `name`      | `string` | **Required**. Customer name |
| `email`      | `string` | **Required**. Customer email |
| `address`      | `string` | **Required**. Customer address |
| `phone`      | `integer` | **Required**.**Min = 10**. Customer contact|


#### Get Customer

```http
  GET /api/v1/customers/{id}
```


#### Update customer

```http
  PATCH /api/v1/customer/{id}
```
#### Body
| Field | Type     | Description                       |
| :-------- | :------- | :-------------------------------- |
| `name`      | `string` | Customer name |
| `email`      | `string` | Customer email |
| `address`      | `string` | Customer address |
| `phone`      | `integer` | Customer contact|



#### Delete Customer

```http
  Delete /api/v1/customers/{id}
```



## Invoices
#### Authorization
| Type | Description                       |
| :-------- |  :-------------------------------- |
| `token`   | **Required**. Bearer token from login |

#### Get Invoices

```http
  GET /api/v1/invoices
```

#### Create Invoice

```http
  POST /api/v1/invoices
```
#### Body
| Field | Type     | Description                       |
| :-------- | :------- | :-------------------------------- |
| `user_id`      | `bigint` | **Required**. Existing user Id. |
| `invoice_code`      | `string` | **Required**.**Unique**. invoice code |
| `due_date`      | `date` | **Required**. Date invoice was issued |
| `customer_id`      | `date` | **Required**. Date invoice needs to be fullfilled|
| `tax_percentage`      | `integer` | **Required** on invoice|
| `items`      | `arary` | **Required**.**Min = 1**. Array of products seleted {}|

## Item Array (All required and must be at least 1)
{
            "product_id": 1,
            "item_quantity": 2,
            "item_unit_price": 30,
            "description": "Description for Item 1"
        }


#### Get Invoice

```http
  GET /api/v1/invoices/{id}
```


#### Update Invoice

```http
  PATCH /api/v1/customer/{id}
```
#### Body
| Field | Type     | Description                       |
| :-------- | :------- | :-------------------------------- |
| `user_id`      | `bigint` | **Required**. Existing user Id. |
| `invoice_code`      | `string` | **Required**.**Unique**. invoice code |
| `due_date`      | `date` | **Required**. Date invoice was issued |
| `customer_id`      | `date` | **Required**. Date invoice needs to be fullfilled|
| `tax_percentage`      | `integer` | **Required** on invoice|
| `items`      | `arary` | **Required**.**Min = 1**. Array of products seleted {}|

## Item Array (All required and must be at least 1)
{
            "product_id": 1,
            "item_quantity": 2,
            "item_unit_price": 30,
            "description": "Description for Item 1"
        }


#### Delete Invoice

```http
  Delete /api/v1/invoices/{id}
```


## Sample Data / Examples

```json
    ### Customers

    #### Customer A 
    {
        "name": "Customer A",
        "email": "customera@gmail.com",
        "phone": 0235205410,
        "address": "Accra",
    	}

    #### Customer B
      {
        "name": "Customer B",
        "email": "customerb@gmail.com",
        "phone": 0235205411,
        "address": "Kumasi",
    }
```


```json
    ### Products

    #### Product A   
    {
        "name": "product A",
        "unit_price": "30",
        "quantity": "10",
    	}

    #### Product B
      {
        "name": "product B",
        "unit_price": "40",
        "quantity": "50",
    }
```


```json
    ### Invoice

    #### Create Invoice : Use  Raw under Body 
    {
        "user_id": 1,
        "invoice_code": "INV123",
        "issue_date": "2023-01-01",
        "due_date": "2023-01-15",
        "customer_id": 1,
        "tax_percentage": 10,
        "items": [
            {
                "product_id": 1,
                "item_quantity": 2,
                "item_unit_price": 30,
                "description": "Description for Item 1"
            },
            {
                "product_id": 2,
                "item_quantity": 3,
                "item_unit_price": 40,
                "description": "Description for Item 2"
            }
        ]
    }

    #### Invoice created: Put this in Raw under body and update values to check updates
   {
        "user_id": 1,
        "invoice_code": "INV123",
        "issue_date": "2023-01-01",
        "due_date": "2023-01-15",
        "customer_id": 1,
        "tax_percentage": 10,
        "updated_at": "2023-11-14T23:11:11.000000Z",
        "created_at": "2023-11-14T23:11:11.000000Z",
        "id": 5,
        "items": [
            {
                "id": 3,
                "invoice_id": 1,
                "product_id": 1,
                "item_name": "Product B",
                "item_quantity": 2,
                "item_unit_price": "30.00",
                "item_cost": "60.00",
                "description": "Description for Item 1",
                "created_at": "2023-11-14T23:11:11.000000Z",
                "updated_at": "2023-11-14T23:11:11.000000Z"
            },
            {
                "id": 4,
                "invoice_id": 1,
                "product_id": 2,
                "item_name": "Product B",
                "item_quantity": 3,
                "item_unit_price": "40.00",
                "item_cost": "120.00",
                "description": "Description for Item 2",
                "created_at": "2023-11-14T23:11:11.000000Z",
                "updated_at": "2023-11-14T23:11:11.000000Z"
            }
        ]
    
    }
```

