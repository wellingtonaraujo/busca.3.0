<?php

namespace App\Http\Controllers;

use App\Models\Custodiado\Custodiado;
use App\Models\Custodiado\CustodiadoAntigo;
use App\Models\Custodiado\PessoaAntiga;
use App\Models\Custodiado\Regime;
use App\Models\Custodiado\Vinculado;
use App\Traits\PageHeaderTrait;
use App\Traits\SearchTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

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

        // retorna para index caso a request esteja vazia
        if ($this->requestEmpty($request)) {
            return redirect()->route('search.index');
        }

        if ($request->origem === 'antiga') {
            // Carrega tudo que vamos precisar de uma vez (evita N+1)
            $custodiado = CustodiadoAntigo::with([
                'pessoa',
                'pessoa.vinculadosComoVisitante',
                'pessoa.vinculadosComoInterno',
                'pessoa.documentosAntigos',
                'pessoa.contatosAntigos',
                'regime',
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

            $titulo = "Consulta Prisional";
            $breadcrumbs = $this->breadcrumbs;
            $otherButtons = $this->buttons;
            $foto = $custodiado->pessoa->foto;
            return view('search.consulta-prisional', compact('custodiado', 'titulo', 'breadcrumbs', 'otherButtons', 'foto', 'routeSearch'));
        } else {
            // TODO: implementar ramo da base nova

            $custodiado = Custodiado::query()
                ->with([
                    'pessoa:id,nome,alcunha',
                    'pessoa.documentosSlim', // USA A RELAÇÃO LEVE
                    'pessoa.contatos:id,pessoa_id,contato,vinculado_tipo_id',
                    'regime:id,descricao',
                    'situacaoAtual:id,descricao',
                    'fotoRel:id,pessoa_id,img,img_type,foto_tipo_id,foto_posicao_id', // <- relação com outro nome
                ])
                ->where('id', $request->custodiado_id)
                ->first();

            if (!$custodiado) {
                Alert::info('Atenção', "Registro não encontrado na base de dados");
                return redirect()->back()->withInput();
            }

            $titulo = "Consulta Prisional";
            $breadcrumbs = $this->breadcrumbs;
            $otherButtons = $this->buttons;
            $foto = $custodiado->foto;
            return view('search.consulta-prisional', compact('custodiado', 'titulo', 'breadcrumbs', 'otherButtons', 'foto', 'routeSearch'));
        }
    }
}
