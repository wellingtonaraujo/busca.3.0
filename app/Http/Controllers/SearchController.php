<?php

namespace App\Http\Controllers;

use App\Models\Custodiado\Custodiado;
use App\Models\Custodiado\CustodiadoAntigo;
use App\Models\Custodiado\PessoaAntiga;
use App\Models\Custodiado\PessoaEnderecoAntigo;
use App\Models\Custodiado\Regime;
use App\Models\Custodiado\Vinculado;
use App\Traits\PageHeaderTrait;
use App\Traits\SearchTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Cache;

class SearchController extends Controller
{
    use SearchTrait;
    use PageHeaderTrait;

    public function __construct()
    {
        $this->initPageHeader();
        $prev = url()->previous();
        $fallback = route('search'); // ajuste para a rota que você quiser como padrão
        if (isset(request()->routeSearch))
            $this->breadcrumbs[] = ['label' => 'Pesquisar pessoas', 'icon' => 'ti ti-search', 'link' => url(request()->routeSearch)];

        $this->titulo = 'Consulta Prisional';

        // acrescentando botões
        $this->buttons[] = ['icon' => 'ti ti-printer', 'link' => route('acl.create'), 'bg' => 'bg-gray-600', 'text' => 'text-white', 'hover' => 'bg-gray-900', 'title' => 'Imprimir consulta prisional'];
        $this->buttons[] = ['icon' => 'ti ti-file', 'link' => route('acl.create'), 'bg' => 'bg-gray-600', 'text' => 'text-white', 'hover' => 'bg-gray-900', 'title' => 'Imprimir relatório geral'];
    }


    //meto do index
    public function index()
    {
        $pessoas = $this->search(request());
        $tempo_execucao = $this->tempoExecucao;
        $parametros = request()->all();
        $routeSearch = route('search', $parametros);

        return view('search.index', compact('pessoas', 'parametros', 'tempo_execucao', 'routeSearch'));
    }

    public function consultaPrisional(Request $request)
    {

        // rota de retorno para a search.index
        $routeSearch = $request->routeSearch;
        $start = microtime(true);
        // retorna para index caso a request esteja vazia
        if ($this->requestEmpty($request)) {
            return redirect()->route('search.index');
        }

        if ($request->origem === 'antiga') {
            // Carrega tudo que vamos precisar de uma vez (evita N+1)
            $custodiado = CustodiadoAntigo::with([
                'pessoa' => function ($q) {
                    $q->select('id', 'nome', 'alcunha', 'nascimento')
                        ->with([
                            'vinculadosComoVisitante',
                            'vinculadosComoInterno',
                            'documentosAntigos',
                            'contatosAntigos',
                            // 'endereco:idpessoa_endereco,logradouro,numero_complemento,cep,idbairro,referencia',
                        ]);
                },
                'regime:id,descricao',
                'situacaoAtual',
                'vinculadoAntigo',
            ])->where('idinterno', $request->custodiado_id)->first();

            if (!$custodiado) {
                Alert::warning('Atenção', 'Registro (antigo) não encontrado.');
                return back()->withInput();
            }

            // Mapeia/alias tudo que termina em "Antigo" para nomes sem o sufixo
            $this->aliasLegacyOnCustodiado($custodiado);

            // a partir daqui, na view você pode usar:
            // $custodiado->vinculado, $custodiado->regime, $custodiado->situacaoAtual,
            // $custodiado->pessoa->documentos, $custodiado->pessoa->contatos,
            // $custodiado->pessoa->vinculados (merge visitante+interno), etc.

            $p = $custodiado->pessoa;
            $tem = PessoaEnderecoAntigo::where('idpessoa', $p->id)->orderBy('id', 'desc')->first();

            if ($endereco = $custodiado->pessoa->endereco) {
                dd($endereco);
                dd($endereco->endereco, $endereco->numero, $endereco->complemento, $endereco->bairro->nome, $endereco->cidade->nome, $endereco->estado->nome, $endereco->pais->nome);
            }

            $titulo = "Consulta Prisional";
            $breadcrumbs = $this->breadcrumbs;
            $otherButtons = $this->buttons;
            $foto = $custodiado->pessoa->foto;
            $end = microtime(true);
            $this->tempoExecucao = round($end - $start, 4) . 's';
            $tempo_execucao = $this->tempoExecucao;
            return view('search.consulta-prisional', compact('custodiado', 'titulo', 'breadcrumbs', 'otherButtons', 'foto', 'routeSearch', 'tempo_execucao'));
        } else {
            // TODO: Consulta em custodiado na base de dados nova
            $id = (int) $request->custodiado_id;

            $custodiado = Cache::remember("consulta_prisional:v2:custodiado:$id", 60, function () use ($id) {
                return Custodiado::query()
                    ->from('custodiados as c')
                    ->select(['c.id', 'c.pessoa_id', 'c.regime_id', 'c.custodiado_situacao_atual_id'])
                    ->with([
                        'regime:id,descricao',
                        'situacaoAtual:id,descricao',

                        'pessoa:id,nome,alcunha,nascimento',
                        'pessoa.endereco:id,endereco,numero,complemento,bairro_id,cidade_id,uf_id,cep',
                        'pessoa.endereco.bairro:id,nome',
                        'pessoa.endereco.cidade:id,nome',
                        'pessoa.endereco.estado:id,sigla',

                        'pessoa.contatos:id,pessoa_id,contato_tipo_id,vinculado_tipo_id,contato,observacao,nome',
                        'pessoa.contatos.contatoTipo:id,descricao',
                        'pessoa.contatos.vinculadoTipo:id,descricao',

                        'pessoa.documentos:id,documento_tipo_id,expedicao_estado_id,expedicao_pais_id,documento_numero,data_expedicao,orgao_expedicao,descricao',
                        'pessoa.documentos.documentoTipo:id,abreviatura',
                        'pessoa.documentos.estado:id,sigla',
                        'pessoa.documentos.pais:id,sigla',

                        'pessoa.fotos:id,img,img_type,foto_tipo_id,foto_posicao_id',
                    ])
                    ->where('c.id', $id)
                    ->first();
            });

            if (!$custodiado) {
                Alert::info('Atenção', "Registro não encontrado na base de dados");
                return redirect()->back()->withInput();
            }

            $titulo       = "Consulta Prisional";
            $breadcrumbs  = $this->breadcrumbs;
            $otherButtons = $this->buttons;
            $routeSearch  = route('search');

            // Foto: usa a base64 gerada pelos accessors da Pessoa; se não houver, a Blade cai no fallback
            $foto = optional($custodiado->pessoa)->foto_base64;

            $end = microtime(true);
            $this->tempoExecucao = round($end - $start, 4) . 's';
            $tempo_execucao = $this->tempoExecucao;

            return view('search.consulta-prisional', compact(
                'custodiado',
                'titulo',
                'breadcrumbs',
                'otherButtons',
                'foto',
                'routeSearch',
                'tempo_execucao'
            ));
        }
    }
}
