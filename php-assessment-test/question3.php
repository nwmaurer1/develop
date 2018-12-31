<?php

class Customer
{
    private $first_name;
    private $last_name;
    private $address = array(
        array(
            'address_1' => '',
            'address_2' => '',
            'city' => '',
            'state' => '',
            'zip' => '',
        ),
    );

    public function __construct($first_name, $last_name, $address)
    {
        $this->first_name = $first_name;
        $this->last_name = $last_name;
        if (!is_array($address)) $address = array($address);

        foreach ($address as $key => $value) {
            $this->address[$key] = $value;
        }
    }

    public function updateFirstName($first_name)
    {
        $this->first_name = $first_name;
    }

    public function getFirstName()
    {
        return $this->first_name;
    }

    public function updateLastName($last_name)
    {
        $this->last_name = $last_name;
    }

    public function getLastName()
    {
        return $this->last_name;
    }

    public function getFullName()
    {
        return $this->getFirstName() . ' ' . $this->getLastName();
    }

    public function updateAddress($address)
    {
        if (!is_array($address)) $address = array($address);

        foreach ($address as $key => $value) {
            $this->address[$key] = $value;
        }
    }

    public function getAddress_1()
    {
        return $this->address['address_1'];
    }

    public function getAddress_2()
    {
        return $this->address['address_2'];
    }

    public function getFullAddressInformation()
    {
        return $this->address;
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
    private $tax_rate = .07;
    private $items;
    private $subtotal;
    private $addressToShipFrom;
    private $total;
    private $itemCount;
    private $shippingCost;

    public function __construct($first_name, $last_name, $address, $subtotal, $total, $addressToShipFrom)
    {
        parent::__construct($first_name, $last_name, $address);
        $this->subtotal = $subtotal;
        $this->total = $total;
        $this->items = [];
        $this->addressToShipFrom = $addressToShipFrom;
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

    public function getShippingRateCost()
    {
        $Cost = new ShippingRateApi($this->getAddressToShipFrom(), $this->getFullAddressInformation());
        return $Cost->ShippingRateCost();
    }

    public function getTotalCostOfSingleItem($id)
    {
        $desiredItem = [];
        foreach ($this->items as $item) {
            if ($item->id == $id) {
                $desiredItem = $item;
                break;
            }
        }
        $shippingCost = $this->getShippingRateCost();

        return (($desiredItem->price + $shippingCost + ($desiredItem * $this->tax_rate)) * $desiredItem->quantity);
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
        $this->total = ($this->getSubtotal() + $shippingCost + ($this->getSubtotal() * $this->tax_rate));
    }

    public function getTotal()
    {
        return $this->total;
    }
}