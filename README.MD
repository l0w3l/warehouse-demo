# API Documentation (Test-Based)

## Table of Contents
1. [Introduction](#introduction)
2. [Base URL](#base-url)
3. [Endpoints](#endpoints)
    - [Products](#products)
    - [Warehouses](#warehouses)
    - [Orders](#orders)
4. [Pagination](#pagination)
5. [Error Handling](#error-handling)

## Introduction

This documentation provides exact response structures based on the test cases for the API endpoints.

## Base URL

All API endpoints are prefixed with `/api/v1/`.

## Endpoints

### Products

#### List all products
- **Endpoint**: `GET /api/v1/products`
- **Response Structure**:
  ```json
  {
    "count": 100,
    "data": [
      {
        "id": 1,
        "name": "Product A",
        "price": 19.99,
        "warehouses": [
          {
            "id": 1,
            "name": "Main Warehouse",
            "stock": 100
          }
        ]
      }
    ]
  }
  ```

### Warehouses

#### List all warehouses
- **Endpoint**: `GET /api/v1/warehouses`
- **Response Structure**:
  ```json
  {
    "count": 50,
    "data": [
      {
        "id": 1,
        "name": "Main Warehouse"
      },
      {
        "id": 2,
        "name": "Secondary Warehouse"
      }
    ]
  }
  ```

#### Get warehouse products
- **Endpoint**: `GET /api/v1/warehouses/{warehouse}/products`
- **Response Structure**:
  ```json
  {
    "count": 50,
    "data": {
      "id": 1,
      "name": "Main Warehouse",
      "products": [
        {
          "id": 1,
          "name": "Product A",
          "price": 19.99,
          "stock": 100
        },
        {
          "id": 2,
          "name": "Product B",
          "price": 29.99,
          "stock": 50
        }
      ],
      "created_at": "2023-01-01T00:00:00.000000Z",
      "updated_at": "2023-01-01T00:00:00.000000Z"
    }
  }
  ```

### Orders

#### List all orders
- **Endpoint**: `GET /api/v1/orders`
- **Response Structure**:
- **Support filter and sort query params (see [OrderSortEnum](./app/Enums/Repositories/Order/OrderSortEnum.php) and [OrderStatusEnum](./app/Enums/Models/Order/OrderStatusEnum.php))**
  ```json
  {
    "count": 100,
    "data": [
      {
        "id": 1,
        "customer": "John Doe",
        "total_amount": 99.97,
        "total_quantity": 3,
        "products": [
          {
            "id": 1,
            "name": "Product A",
            "price": 19.99,
            "quantity": 2,
            "created_at": "2023-01-01T00:00:00.000000Z",
            "updated_at": "2023-01-01T00:00:00.000000Z"
          },
          {
            "id": 2,
            "name": "Product B",
            "price": 29.99,
            "quantity": 1,
            "created_at": "2023-01-01T00:00:00.000000Z",
            "updated_at": "2023-01-01T00:00:00.000000Z"
          }
        ],
        "completed_at": null,
        "created_at": "2023-01-01T00:00:00.000000Z",
        "updated_at": "2023-01-01T00:00:00.000000Z"
      }
    ]
  }
  ```

#### Create an order
- **Endpoint**: `POST /api/v1/orders`
- **Request Body**:
  ```json
  {
    "customer": "Test Customer",
    "warehouse_id": 1,
    "products": [
      {
        "id": 1,
        "quantity": 2
      },
      {
        "id": 2,
        "quantity": 1
      }
    ]
  }
  ```
- **Response Structure**:
  ```json
  {
    "data": {
      "id": 2,
      "customer": "Test Customer",
      "total_amount": 69.97,
      "total_quantity": 3,
      "products": [
        {
          "id": 1,
          "name": "Product A",
          "price": 19.99,
          "quantity": 2,
          "created_at": "2023-01-01T00:00:00.000000Z",
          "updated_at": "2023-01-01T00:00:00.000000Z"
        },
        {
          "id": 2,
          "name": "Product B",
          "price": 29.99,
          "quantity": 1,
          "created_at": "2023-01-01T00:00:00.000000Z",
          "updated_at": "2023-01-01T00:00:00.000000Z"
        }
      ],
      "completed_at": null,
      "created_at": "2023-01-02T00:00:00.000000Z",
      "updated_at": "2023-01-02T00:00:00.000000Z"
    }
  }
  ```

#### Update an order
- **Endpoint**: `PUT|PATCH /api/v1/orders/{order}`
- **Response Structure**: Same as create order, with updated fields

#### Cancel an order
- **Endpoint**: `POST /api/v1/orders/{order}/cancel`
- **Response Structure**:
  ```json
  {
    "data": {
      "id": 2,
      "customer": "Test Customer",
      "total_amount": 69.97,
      "total_quantity": 3,
      "products": [
        // ... same product structure ...
      ],
      "completed_at": null,
      "created_at": "2023-01-02T00:00:00.000000Z",
      "updated_at": "2023-01-02T00:00:00.000000Z",
      "status": "cancelled"
    }
  }
  ```

#### Complete an order
- **Endpoint**: `POST /api/v1/orders/{order}/complete`
- **Response Structure**:
  ```json
  {
    "data": {
      "id": 2,
      "customer": "Test Customer",
      "total_amount": 69.97,
      "total_quantity": 3,
      "products": [
        // ... same product structure ...
      ],
      "completed_at": "2023-01-02T01:00:00.000000Z",
      "created_at": "2023-01-02T00:00:00.000000Z",
      "updated_at": "2023-01-02T01:00:00.000000Z",
      "status": "completed"
    }
  }
  ```

#### Restore an order
- **Endpoint**: `POST /api/v1/orders/{order}/restore`
- **Response Structure**:
  ```json
  {
    "data": {
      "id": 2,
      "customer": "Test Customer",
      "total_amount": 69.97,
      "total_quantity": 3,
      "products": [
        // ... same product structure ...
      ],
      "completed_at": null,
      "created_at": "2023-01-02T00:00:00.000000Z",
      "updated_at": "2023-01-02T02:00:00.000000Z",
      "status": "active"
    }
  }
  ```

## Pagination

All list endpoints support pagination through query parameters:
- `offset`: Starting record (default: 0)
- `limit`: Number of records per page (default: 10)

Responses include a `count` field with total available items.

## Error Handling

Errors return JSON responses with:
- HTTP status code
- Error message
- (Optional) Validation errors

Example:
```json
{
  "message": "The given data was invalid.",
  "errors": {
    "customer": ["The customer field is required."]
  }
}
```
