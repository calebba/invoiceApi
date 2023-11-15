Invoice Management API

Welcome to the Invoice Management API! This API allows you to manage invoices and associated items. Follow the steps below to set up and try out the API using Postman.

 Getting Started

Prerequisites

1. Make sure you have [PHP](https://www.php.net/) and [Composer](https://getcomposer.org/) installed on your machine.
2. Install [Postman](https://www.postman.com/) for API testing.

Setup

1. Clone the repository:
   ```bash
   git clone https://github.com/calebba/invoiceApi.git
   cd invoiceApi

If you are running on windows
Add extension=php_sodium.dll to php.ini

Install dependencies:
composer install

 Set up your environment variables by copying the .env.example file to .env:

cp .env.example .env
Update the .env file with your database configuration.

Generate the application key:

php artisan key:generate

Api Auth
Run php artisan passport:install
which will be used to create "personal access" and "password grant" clients which will be used to generate access client for api auth:

Run database migrations:
php artisan migrate

Start the Laravel development server:
php artisan serve


Testing with Postman
1. Open Postman.
2. Set up your environment variables in Postman, including the base URL (e.g., http://localhost:8000).
3. Test the API endpoints by sending requests using the provided examples.
4. Feel free to explore and test other endpoints based on your needs.


Enpoints Examples and process

1. Register user and login
Endpoint: http://localhost:8000/api/vi/register
Method: Post
Data: name, email and password
Try: Register with same email and check reponse

2. Login
Endpoint: http://localhost:8000/api/vi/login
Post: 
Data: email and password
Copy response token and set it as the token under Authorization

       Try: You can test login with wrong credentials to check response

3. Customers:
Get customers
 	Endpoint: http://localhost:8000/api/vi/customers
Method: Get 

Create customers
	Endpoint: http://localhost:8000/api/vi/customers
Method: Post 
Data: 

	{
        "name": "Customer A",
        "email": "customera@gmail.com",
 "phone": 0235205410,
        "address": "Accra",
    	}
      {
        "name": "Customer B",
        "email": "customerb@gmail.com",
 "phone": 0235205411,
        "address": "Kumasi",
    	}

Update Customers
	Endpoint: http://localhost:8000/api/vi/customers/id
Method: Patch 
Data: Get id from previous customers and replace in url/endpoint. From Body in postman, use the x-wwww-form-urlencode format to state the field you want to change value and set the updated value.


Get product
	Endpoint: http://localhost:8000/api/vi/customers/id
Method: Get 
Data: Id from customer creation

Delete product
	Endpoint: http://localhost:8000/api/vi/customers/id
Method: Delete 
Data: Id from customer creation



4. Products:
Get products
 	Endpoint: http://localhost:8000/api/vi/products
Method: Get 

Create Products
	Endpoint: http://localhost:8000/api/vi/products
Method: Post 
Data: 

	{
        "name": "product A",
        "unit_price": "30",
        "quantity": "10",
    	}
{
        "name": "product B",
        "unit_price": "40",
        "quantity": "50",
    	}


Update product
	Endpoint: http://localhost:8000/api/vi/products/id
Method: Patch 
Data: Get id from previous products and replace in url/endpoint. From Body in postman, use the x-wwww-form-urlencode format to state the field you want to change value and set the updated value.


Get product
	Endpoint: http://localhost:8000/api/vi/products/id
Method: Get 
Data: Id from product creation

Delete product
	Endpoint: http://localhost:8000/api/vi/products/id
Method: Delete 
Data: Id from product creation


5. Invoices:
Get invoices
 	Endpoint: http://localhost:8000/api/vi/invoices
Method: Get 

Create invoice
	Endpoint: http://localhost:8000/api/vi/invoices
Method: Post 
Data:  Use the raw under body

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

Update invoice
	Endpoint: http://localhost:8000/api/vi/invoice/id
Method: Patch 
Data: Get id from previous invoice. Use raw again:
change value to see the how updates work.
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



Get invoices
	Endpoint: http://localhost:8000/api/vi/invoices/id
Method: Get 
Data: Id from product invoices

Delete invoices
	Endpoint: http://localhost:8000/api/vi/invoices/id
Method: Delete 
Data: Id from invoices creation



	

Happy testing!
 

