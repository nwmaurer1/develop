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

    public function addItemToCart($id, $name, $quantity, $price)
    {
        $item = new Item($id, $name, $quantity, $price);

        $this->items[] = $item;
        $this->updateTotalItemsInCart();
        $this->updateSubtotal();
        $this->updateTotal();
    }

    public function removeItemFromCart($id)
    {
        foreach ($this->items as $key => $item) {
            if ($item->id == $id) {
                unset($this->items[$key]);
            }
        }
        $this->updateTotalItemsInCart();
        $this->updateSubtotal();
        $this->updateTotal();

    }

    public function getItemInCart($id)
    {
        foreach ($this->items as $item) {
            if ($item->id == $id) {
                return $item;
            }
        }
        return [];
    }

    public function updateTotalItemsInCart()
    {
        foreach ($this->items as $item) {
            $this->itemCount = $this->itemCount + $item->quantity;
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

    public function getTotalCostOfSingleItem($id)
    {
        $desiredItem = $this->getItemInCart($id);
        $shippingCost = $this->getShippingRateCost();

        return (($desiredItem->price + $shippingCost + ($desiredItem->price * $this->getTaxRate())) * $desiredItem->quantity);
    }

    public function setCostOfSingleItem($id, $price = 0)
    {
        $desiredItem = $this->getItemInCart($id);
        $desiredItem->setItemPrice($price);
    }

    public function updateSubtotal()
    {
        $cost = 0;
        foreach ($this->items as $item) {
            $cost = $cost + ($item->price * $item->quantity);
        }

        $this->subtotal = $cost;
    }

    public function getSubtotal()
    {
        return $this->subtotal;
    }

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