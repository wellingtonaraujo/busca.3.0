// Importa jQuery e registra globalmente
import jQuery from 'jquery';
window.$ = window.jQuery = jQuery;

// -------------------------
// DataTables (Bootstrap 5)
// -------------------------
import 'datatables.net-bs5';
import 'datatables.net-bs5/css/dataTables.bootstrap5.min.css';

// DataTables Responsive (Bootstrap 5)
import 'datatables.net-responsive-bs5';
import 'datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css';

// -------------------------
// Select2 (com tema Bootstrap 5)
// -------------------------
import 'select2/dist/js/select2.js';
import 'select2/dist/css/select2.css';

console.log('CSS select2-bootstrap carregado');

// -------------------------
// Inicialização
// -------------------------
document.addEventListener('DOMContentLoaded', () => {

    // Função para aplicar os estilos Tailwind
    function styleDataTableElements(dtInstance) {
        // dtInstance é a instância do DataTable (objeto retornado por $(table).DataTable())
        const wrapper = $(dtInstance.table().container());
        wrapper.find('select').addClass('border border-gray-600 focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 rounded-md px-2 py-1');
        wrapper.find('input[type="search"]').addClass('border border-gray-600 focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 rounded-md px-2 py-1');
    }

    // Inicializa DataTables
    const tables = document.querySelectorAll('.datatables');
    tables.forEach(tableEl => {
        const dt = $(tableEl).DataTable({
            autoWidth: true,
            responsive: true,
            paging: true,
            searching: true,
            info: true,
            language: {
                url: '/lang/pt_BR.json',
                search: "Buscar:",
                searchPlaceholder: "Digite aqui para pesquisar...",
                lengthMenu: "Mostrar _MENU_ registros por página",
                zeroRecords: "Nenhum registro encontrado",
                info: "Mostrando _START_ a _END_ de _TOTAL_ registros",
                infoEmpty: "Mostrando 0 a 0 de 0 registros",
                infoFiltered: "(filtrado de _MAX_ registros no total)",
                search: "Pesquisar:",
                paginate: {
                    first: "Primeiro",
                    last: "Último",
                    next: "Próximo",
                    previous: "Anterior"
                },
            },

            dom: '<"flex justify-between items-center text-gray-900 flex-wrap gap-2 mb-3"l f>' +
                '<"w-full"tr>' +
                '<"flex justify-between items-center flex-wrap gap-2 mt-3"i p>',


            initComplete: function () {
                // "this" aqui é o elemento HTML da tabela, então pegamos a instância DataTable
                styleDataTableElements($(this).DataTable());
            }
        });

        // Aplica sempre após redraw (para responsivo/paginação)
        dt.on('draw.dt', function () {
            styleDataTableElements(dt); // dt já é a instância correta
        });
    });
});



