<?php

namespace App\Http\Livewire;

use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;

class OrderList extends Component
{

    use WithPagination;

    public function render()
    {
        $products = Product::paginate(5);
        return view('livewire.order-list',['orders'=>$products]);
    }

}
