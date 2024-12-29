<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Page;

class LifeCashPage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected ?string $heading = 'منتجات Api';
    protected static string $view = 'filament.pages.life-cash-page';
    protected static ?string $navigationLabel = 'منتجات Api';
    public $search = '';

    public Setting $setting;
    public $items = [];
    public $data = [];
    public $type = [
        'life-cash' => 'منتجات LifeCash ',
        'speed-card' => 'منتجات SpeedCard',
        'eko' => 'منتجات Eko',
        'drd3' => 'منتجات DRD3',
        'cash-mm' => 'منتجات CashSmm',
        'saud' => 'ابو السعود',
        'as7ab'=>'أصحاب',
        'cache-back'=>'كاش باك'
    ];
    public $site;

    protected static function shouldRegisterNavigation(): bool
    {
        return auth()->user()->hasRole('super_admin');
    }

    public function mount(): void
    {

        $this->setting = Setting::first();

    }

    public function updatedSearch()
    {

        $this->items = collect($this->data)->filter(function ($item) {
            if ($this->search) {
                return \Str::contains($item['name'], $this->search, true);
            }
            return true;

        });
    }

    public function getPrinceProduct($type)
    {
        $this->items = [];
        $this->data = [];
        $this->site = $type;
        switch ($type) {
            case 'life-cash':
                $url = 'https://api.life-cash.com/client/api/products';
                $token = $this->setting->apis['life'];

                break;
            case 'speed-card':
                $url = 'https://api.speedcard.vip/client/api/products';
                $token = $this->setting->apis['speed_card'];

                break;
            case 'eko':
                $url = 'https://api.ekostore.co/client/api/products';
                $token = $this->setting->apis['eko'];
                break;
            case 'drd3':
                $url = 'https://drd3m.com/api/v2';
                $token = $this->setting->apis['drd3'];
                break;
            case 'cash-mm':
                $url = 'https://cashsmm.com/api/v2';
                $token = $this->setting->apis['cash-mm'];
                break;
            case 'saud':
                $url = 'https://api.saud-card.com/client/api/products';
                $token = $this->setting->apis['saud'];
                break;
            case 'as7ab':
                $url = 'https://as7abcard.com/api/v1/products';
                $token = $this->setting->apis['as7ab'];
                break;
            case 'cache-back':
                $url = 'https://api.cashback-card.net/client/api/products';
                $token = isset($this->setting->apis['cache-back'])?$this->setting->apis['cache-back']:'';
                break;

        }


        ##################
$array1=['life-cash',
    'speed-card',
'eko',
'saud',
'cache-back',];

        if (in_array($type,$array1)) {
            $response = \Http::withHeaders([
                'api-token' => $token
            ])->get($url);

            if ($response->successful()) {
                $this->items = $response->json();
                $this->data = $response->json();



            }
        }//
        elseif($type==='as7ab'){
            $response = \Http::withToken( $token
            )->get($url);

            $this->items = collect($response->json()['products'])/*->filter(fn($el)=>isset($el['details']['customAmount']['status']) && $el['details']['customAmount']['status']===true)*/;
            $this->data = collect($response->json()['products'])/*->filter(fn($el)=>isset($el['details']['customAmount']['status']) && $el['details']['customAmount']['status']===true)*/;
          //  dd($response->json());

        }
        else{
            $data=[
                'key'=>$token,
                'action'=>'services'
            ];
            $response = \Http::post($url,$data);

            if ($response->successful()) {

                $this->items = $response->json();
                $this->data = $response->json();


            }
        }


    }


}
