<?php

namespace App\Http\Livewire;

use App\Enums\BillStatusEnum;
use App\Enums\OrderStatusEnum;
use App\Models\Bill;
use App\Models\Order;
use Livewire\Component;

class OrderWedgitComponent extends Component
{

    public $start;
    public $end;
    public function mount(){
        $this->start=now()->startOfMonth()->format('Y-m-d');
        $this->end=now()->format('Y-m-d');
    }
    public function render()
    {
        return view('livewire.order-wedgit-component',[
            'bills'=>Bill::orWhere('status',[BillStatusEnum::COMPLETE->value,BillStatusEnum::SUCCESS->value])->whereNull('api_id')->whereBetween('created_at',[$this->start,$this->end])->sum('price'),
            'orders'=>Order::where('status',OrderStatusEnum::COMPLETE->value)->whereBetween('created_at',[$this->start,$this->end])->sum('price'),
            'life'=>Bill::orWhere('status',[BillStatusEnum::COMPLETE->value,BillStatusEnum::SUCCESS->value])->whereNotNull('api_id')->where(function($query){
                return $query->whereNull('api')->orWhere('api','life-cash');
            })->whereBetween('created_at',[$this->start,$this->end])->sum('price'),
            'eko'=>Bill::orWhere('status',[BillStatusEnum::COMPLETE->value,BillStatusEnum::SUCCESS->value])->where('api','eko')->whereBetween('created_at',[$this->start,$this->end])->sum('price'),
            'speed'=>Bill::orWhere('status',[BillStatusEnum::COMPLETE->value,BillStatusEnum::SUCCESS->value])->where('api','speed-card')->whereBetween('created_at',[$this->start,$this->end])->sum('price'),

        ]);
    }
}
