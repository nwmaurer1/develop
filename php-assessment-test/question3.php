<?php

class Customer
{
    private $customer_information = array(
        'first_name' => 'name',
        'last_name' => 'name',
        'address' => array(
            'address_1' => '',
            'address_2' => '',
            'city' => '',
            'state' => '',
            'zip' => '',
        ),
    );

    public function __construct($first_name, $last_name, $address)
    {
        $this->customer_information['first_name'] = $first_name;
        $this->customer_information['last_name'] = $last_name;
        if (!is_array($address)) {
            $address = array($address);
        }

        foreach ($address as $key => $value) {
            $this->customer_information['address'][$key] = $value;
        }
    }

    public function updateFirstName($first_name)
    {
        $this->customer_information['first_name'] = $first_name;
    }

    public function getFirstName()
    {
        return $this->customer_information['first_name'];
    }

    public function updateLastName($last_name)
    {
        $this->customer_information['last_name'] = $last_name;
    }

    public function getLastName()
    {
        return $this->customer_information['last_name'];
    }

    public function getFullName()
    {
        return $this->getFirstName() . ' ' . $this->getLastName();
    }

    public function updateAddress($address)
    {
        if (!is_array($address)) {
            $address = array($address);
        }

        foreach ($address as $key => $value) {
            $this->customer_information['address'][$key] = $value;
        }
    }

    public function getAddress_1()
    {
        return $this->customer_information['address']['address_1'];
    }

    public function getAddress_2()
    {
        return $this->customer_information['address']['address_2'];
    }

    public function getFullCustomerInformation()
    {
        return  $this->customer_information;
    }

}

class Item
{
    private $id;
    private $name;
    private $quantity;
    private $price;

    public function __construct($id, $name, $quantity, $price)
    {
        $this->id = $id;
        $this->name = $name;
        $this->quantity = $quantity;
        $this->price = $price;
    }

    public function updateItemId($id)
    {
        $this->id = $id;
    }

    public function getItemId()
    {
        return $this->id;
    }

    public function updateItemName($name)
    {
        $this->name = $name;
    }

    public function getItemName()
    {
        return $this->name;
    }

    public function setItemQuantity($quantity)
    {
        $this->quantity = $quantity;
    }

    public function getItemQuantity()
    {
        return $this->quantity;
    }

    public function setItemPrice($price)
    {
        $this->price = $price;
    }

    public function getItemPrice()
    {
        return $this->price;
    }
}

class Cart extends Customer
{
    private $tax_rate;
    private $items;
    private $subtotal;
    private $addressToShipFrom;
    private $total;
    private $itemCount;
    private $shippingCost;

    public function __construct($first_name, $last_name, $address, $subtotal, $total, $addressToShipFrom, $tax_rate = .07)
    {
        parent::__construct($first_name, $last_name, $address);
        $this->subtotal = $subtotal;
        $this->total = $total;
        $this->items = [];
        $this->addressToShipFrom = $addressToShipFrom;
        $this->tax_rate = $tax_rate;
    }

    /**
     *
     * Add Item(s) to the array of items in the cart.
     *
     * @param    integer $id is an id that relates to the item.
     * @param    string $name is the name of the item to be added to the cart
     * @param    integer $quantity is the amount of the same item that is to be added.
     * @param    float   $price is the price of the item added to the cart.
     *
     */
    public function addItemToCart($id, $name, $quantity, $price)
    {
        $item = new Item($id, $name, $quantity, $price);

        $this->items[] = $item;
        $this->updateTotalItemsInCart();
        $this->updateSubtotal();
        $this->updateTotal();
    }

    /**
     *
     * Remove Item(s) from the array of items in the cart.
     *
     * @param    integer $id An id that relates to the item.
     *
     */
    public function removeItemFromCart($id)
    {
        foreach ($this->items as $key => $item) {
            if ($item->getItemID() == $id) {
                unset($this->items[$key]);
            }
        }
        $this->updateTotalItemsInCart();
        $this->updateSubtotal();
        $this->updateTotal();

    }

    public function updateItemQuantity($id, $quantity)
    {
        $desiredItem = $this->getItemInCart($id);
        $desiredItem->setItemQuantity($quantity);
    }

    /**
     *
     * Gets Item Object out of array of Item Objects in cart.
     *
     * @param    integer $id An id that relates to the item.
     * @return      array/object returns an array or object of that item from an array of item objects.
     *
     */
    public function getItemInCart($id)
    {
        foreach ($this->items as $item) {
            if ($item->getItemID() == $id) {
                return $item;
            }
        }
        return [];
    }

    public function updateTotalItemsInCart()
    {
        foreach ($this->items as $item) {
            $this->itemCount = $this->itemCount + $item->getItemQuantity();
        }
    }

    public function getTotalItemsInCart()
    {
        return $this->itemCount;
    }

    public function updateAddressToShip($addressToShipFrom)
    {
        $this->addressToShipFrom = $addressToShipFrom;
    }

    public function getAddressToShipFrom()
    {
        return $this->addressToShipFrom;
    }

    /**
     *
     * Overrides or sets the value of the shipping cost with the Shipping rate API.
     *
     * @param    bool $override flags whether the shipping rate cost can be overridden
     * @param    integer $price sets the value of the shipping cost rate
     *
     *
     */
    public function setShippingRateCost($override = false, $price = 0)
    {
        if ($override) {
            $this->shippingCost = $price;
        } else {
            $Cost = new ShippingRateApi($this->getAddressToShipFrom(), $this->getFullAddressInformation());
            $this->shippingCost = $Cost;
        }
    }

    public function getShippingRateCost()
    {
        return $this->shippingCost;
    }

    public function setTaxRate($rate)
    {
        $this->tax_rate = $rate;
    }

    public function getTaxRate()
    {
        return $this->tax_rate;
    }

    /**
     *
     * Gets the total cost of one item without quantity included in the cart.
     *
     * @param    integer $id An id that relates to the item.
     * @return      float The Price of the single item without quantity
     *
     */
    public function getTotalCostOfSingleItem($id)
    {
        $desiredItem = $this->getItemInCart($id);
        //Shipping Cost is zero until override price is entered.
        $shippingCost = $this->getShippingRateCost();

        return ($desiredItem->getItemPrice() + $shippingCost + ($desiredItem->getItemPrice() * $this->getTaxRate()));
    }

    public function getTotalCostOfSingleItemWithQuantity($id)
    {

       $desiredItem = $this->getItemInCart($id);
       $cost = $this->getTotalCostOfSingleItem($id) * $desiredItem->getItemQuantity();
       return $cost;
    }

    /**
     *
     * Set the cost of an item regardless of quantity.
     *
     * @param    integer $id is an id that relates to the item.
     * @param    float $price overrides the price of a single item (regardless of quantity).
     *
     */
    public function setCostOfSingleItem($id, $price = 0.00)
    {
        $desiredItem = $this->getItemInCart($id);
        $desiredItem->setItemPrice($price);
    }

    /**
     *
     * Helper Function: Updates Subtotal value when adding or removing items in cart.
     *
     */
    public function updateSubtotal()
    {
        $cost = 0;
        foreach ($this->items as $item) {
            $cost = $cost + ($item->getItemPrice() * $item->getItemQuantity());
        }

        $this->subtotal = $cost;
    }


    public function getSubtotal()
    {
        return $this->subtotal;
    }

    /**
     *
     * Helper Function: Updates Total value when adding or removing items in cart.
     *
     */
    public function updateTotal()
    {
        $shippingCost = $this->getShippingRateCost();
        $this->total = ($this->getSubtotal() + $shippingCost + ($this->getSubtotal() * $this->getTaxRate()));
    }

    public function getTotal()
    {
        return $this->total;
    }
}

function testBench()
{
    $first_name = 'John';
    $last_name = 'Smith';
    $address = [
        'address_1' => '1600 Pennsylvania Ave',
        'address_2' => '',
        'city' => 'District of Columbia',
        'state' => 'NW',
        'zip' => '20500',
    ];
    $subtotal = 0;
    $total = 0;
    $addressToShipFrom = 'Amazon Headquarters 410 Terry Ave. N Seattle, WA 98109';
    $cart = new Cart($first_name, $last_name, $address, $subtotal, $total, $addressToShipFrom);

    print_r($cart->getTotalItemsInCart() . '<br>');

    $cart->addItemToCart(17, 'Beef Steak', 4, 12.18);

    print_r($cart->getTotalItemsInCart() . '<br>');

    print_r($cart->getSubtotal() . '<br>');

    print_r(var_export($cart->getItemInCart(17)) . '<br>');

    $cart->addItemToCart(12, 'HorseRadish', 2, 2.50);

    print_r($cart->getFullName() . '<br>');

    print_r($cart->getTaxRate() . '<br>');

    $cart->updateFirstName('Jerry');

    $cart->updateLastName('Seinfeld');

    print_r(var_export($cart->getFullCustomerInformation()) . '<br>');

    print_r($cart->getTotal() . '<br>');

    print_r($cart->getTotalCostOfSingleItem(12) . '<br>');

    print_r($cart->getSubtotal());
}

testBench();