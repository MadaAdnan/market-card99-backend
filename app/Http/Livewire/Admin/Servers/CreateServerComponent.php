<?php

namespace App\Http\Livewire\Admin\Servers;

use App\Models\Server;
use Livewire\Component;
use Livewire\WithFileUploads;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use RegexIterator;

class CreateServerComponent extends Component
{

    use WithFileUploads;

    public $name;
    public $username;
    public $password;
    public $code;
    public $api;
    public $img;
    public $servers;
    public $network;

    public $is_active='active';

    public function mount()
    {
        $this->getServers();
    }

    public function render()
    {
        return view('livewire.admin.servers.create-server-component');
    }

    private function getServers()
    {
        $path = app_path('Helpers/');
        $fqcns = array();

        $allFiles = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path));
        $phpFiles = new RegexIterator($allFiles, '/\.php$/');
        foreach ($phpFiles as $phpFile) {
            $content = file_get_contents($phpFile->getRealPath());
            $tokens = token_get_all($content);
            $namespace = 'App\Helpers\\';
            for ($index = 0; isset($tokens[$index]); $index++) {
                if (!isset($tokens[$index][0])) {
                    continue;
                }
                if (
                    T_NAMESPACE === $tokens[$index][0]
                    && T_WHITESPACE === $tokens[$index + 1][0]
                    && T_STRING === $tokens[$index + 2][0]
                ) {
                    //$namespace = $tokens[$index + 2][1];
                    // Skip "namespace" keyword, whitespaces, and actual namespace
                    $index += 2;
                }
                if (
                    T_CLASS === $tokens[$index][0]
                    && T_WHITESPACE === $tokens[$index + 1][0]
                    && T_STRING === $tokens[$index + 2][0]
                ) {
                    $fqcns[] = $namespace . '' . $tokens[$index + 2][1];
                    // Skip "class" keyword, whitespaces, and actual classname
                    $index += 2;

                    # break if you have one class per file (psr-4 compliant)
                    # otherwise you'll need to handle class constants (Foo::class)
                    break;
                }
            }
        }
        $this->servers = $fqcns;
    }



    public function submit()
    {
        $this->validate([
            'name' => 'required|unique:servers,name',
            'img' => 'required|image',
            'api' => 'required',
            'code' => 'required|unique:servers,code'
        ]);

        Server::create([
            'name' => $this->name,
            'img' => \Storage::disk('public')->put('servers', $this->img),
            'code' => $this->code,
            'api' => $this->api,
            'username'=>$this->username,
            'password'=>$this->password,
            'is_active'=>$this->is_active,
            'network'=>$this->network
        ]);
        $this->reset(['name','img','code','api','password','username','network']);
        $this->dispatchBrowserEvent('success', [
            'msg' => 'تم إضافة السيرفر بنجاح'
        ]);
    }
}
