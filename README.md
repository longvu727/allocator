# Allocator
Implemented on PHP 7.3.7

`Allocator.php` consist of two services `Allocator->allocate` and `Allocator->orderHistory` and three components: `order, cart, and inventory.`

Allocator is structured in the following description:
```
	Allocator( inventory, orders )
	Order( header, carts )
	Cart( product_name, quantity, type[backorderd, allocated] )
```
`Allocator` has many orders
`Order` has many carts
`Cart` has many products

For the simplicity of implementation, I'm using PHP associated array to full fill the relations.

### Allocator->allocate
Sample Json response:

> {"error":"","success":true,"data":{"order":{"header":1,"cart":[{"product":"A","type":"allocated","quantity":"1"},{"product":"C","type":"allocated","quantity":"1"}]}}}

When `allocate` is requested, `Allocator` `validateAllocateInput, createCart,` add `cart` to new `order` then add `order` to `Allocator->orders` list.

### Allocator->orderHistory
Sample Json response:

> [{"header":1,"order":{"A":"1","B":0,"C":"1","D":0,"E":0},"allocated":{"A":"1","B":0,"C":"1","D":0,"E":0},"backordered":{"A":0,"B":0,"C":0,"D":0,"E":0}},{"header":2,"order":{"A":0,"B":0,"C":0,"D":0,"E":"5"},"allocated":{"A":0,"B":0,"C":0,"D":0,"E":0},"backordered":{"A":0,"B":0,"C":0,"D":0,"E":"5"}},{"header":3,"order":{"A":0,"B":0,"C":0,"D":"4","E":0},"allocated":{"A":0,"B":0,"C":0,"D":0,"E":0},"backordered":{"A":0,"B":0,"C":0,"D":"4","E":0}},{"header":4,"order":{"A":"1","B":0,"C":"1","D":0,"E":0},"allocated":{"A":"1","B":0,"C":0,"D":0,"E":0},"backordered":{"A":0,"B":0,"C":"1","D":0,"E":0}},{"header":5,"order":{"A":0,"B":"3","C":0,"D":0,"E":0},"allocated":{"A":0,"B":"3","C":0,"D":0,"E":0},"backordered":{"A":0,"B":0,"C":0,"D":0,"E":0}}]

Traversing through `Allocator->orderHistory` 

### Inventory
Inventory provide availability of product and quantity.
### order
when a request to `Allocator->allocate`

## Tests
There are two tests:
```
	tests/initial_test.php
	tests/rand_input_test.php
```
A sample test result is `tests/test_result.txt`
### tests/initial_test.php
Test the example input and result from [Shipwire Coding Project-InventoryAllocation.txt]([https://github.com/longvu727/allocator/blob/master/Shipwire%20Coding%20Project-InventoryAllocation.txt](https://github.com/longvu727/allocator/blob/master/Shipwire%20Coding%20Project-InventoryAllocation.txt))

### tests/rand_input_test.php
Generate random input to test all possible inputs
