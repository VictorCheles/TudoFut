<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\Time;
use App\Models\Pais;
use App\Models\Competicao;
use Illuminate\Support\Facades\DB;
use App\Services\ApiClientService;

class ImportarDadosFixos extends Command
{
    protected $signature = 'importar:dados-fixos';
    protected $description = 'Importa times, países e campeonatos da API para o banco de dados';

    private $apiUrl;
    private $apiToken;
    private $requisicoesPorMinuto = 10;

    public function __construct()
    {
        parent::__construct();
        $this->apiUrl = config('services.api_cliente.url');
        $this->apiToken = config('services.api_cliente.token');
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔄 Iniciando importação de dados fixos...');

        try {
            DB::beginTransaction(); // 🔹 Garantir consistência dos dados

            // Importar Países
            $this->importarPaises();

            // Importar Competições
            $this->importarCompeticoes();

            // Importar Times
            $this->importarTimes();

            DB::commit();
            $this->info('✅ Importação concluída com sucesso!');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->error('❌ Erro ao importar dados: ' . $e->getMessage());
        }
    }

    private function fazerRequisicao($endpoint)
    {
        static $contador = 0;

        if ($contador >= $this->requisicoesPorMinuto) {
            $this->info('⏳ Atingido o limite de 10 requisições. Aguardando 60 segundos...');
            sleep(60);
            $contador = 0;
        }

        $contador++;
        $response = Http::withHeaders([
            'X-Auth-Token' => $this->apiToken
        ])->get($this->apiUrl . $endpoint);

        if ($response->failed()) {
            throw new \Exception('Erro ao consultar API: ' . $response->body());
        }

        return $response->json();
    }

    private function importarPaises()
    {
        $this->info('🌍 Importando países...');
        $paises = $this->fazerRequisicao('/areas');

        $api = new ApiClientService();

        foreach ($api->filterCountries($paises['areas']) as $pais) {
            Pais::updateOrCreate(
                ['id' => $pais['id']],
                [
                    'name' => $pais['name'],
                    'countryCode' => $pais['countryCode'],
                    'flag' => $pais['flag'] ?? null
                ]
            );
        }

        $this->info('✅ Países importados com sucesso!');
    }

    private function importarCompeticoes()
    {
        $this->info('🏆 Importando competições...');
        $competicoes = $this->fazerRequisicao('/competitions');

        foreach ($competicoes['competitions'] as $competicao) {
            Competicao::updateOrCreate(
                ['id' => $competicao['id']],
                [
                    'name' => $competicao['name'],
                    'code' => $competicao['code'],
                    'emblem' => $competicao['emblem'] ?? null
                ]
            );
        }

        $this->info('✅ Competições importadas com sucesso!');
    }

    private function importarTimes()
    {
        $this->info('⚽ Importando times...');

        $competicoes = Competicao::all();

        foreach ($competicoes as $competicao) {
            $this->info("🔹 Buscando times da competição: {$competicao->name}");

            $times = $this->fazerRequisicao("/competitions/{$competicao->code}/teams");

            foreach ($times['teams'] as $time) {

                if(Time::where('name', $time['name'])->exists())
                {
                    continue;
                }

                Time::updateOrCreate(
                    ['id' => $time['id']],
                    [
                        'name' => $time['name'],
                        'crest' => $time['crest'] ?? null
                    ]
                );
            }
        }

        $this->info('✅ Times importados com sucesso!');
    }
}
