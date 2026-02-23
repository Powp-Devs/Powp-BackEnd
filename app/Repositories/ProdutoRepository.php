<?php

namespace App\Repositories;

use App\Repositories\Interfaces\ProdutoRepositoryInterface;
use Illuminate\Support\Facades\Http;

class ProdutoRepository implements ProdutoRepositoryInterface
{
    protected $url;
    protected $key;

    public function __construct()
    {
        $this->url = config('app.supabase_url', env('SUPABASE_URL'));
        $this->key = config('app.supabase_key', env('SUPABASE_KEY'));
    }

    protected function request()
    {
        return Http::withHeaders([
            'apikey' => $this->key,
            'Authorization' => 'Bearer ' . $this->key,
            'Accept' => 'application/json',
            'Prefer' => 'return=representation'
        ]);
    }

    public function getAll(int $page, int $perPage)
    {
        $offset = ($page - 1) * $perPage;

        $response = $this->request()->get($this->url . '/rest/v1/pwproduto', [
            'select' => '*, pwestoque(*)',
            'order' => 'codproduto.desc',
            'offset' => $offset,
            'limit' => $perPage,
        ]);
    }
}