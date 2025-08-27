// Importa jQuery e registra globalmente
import jQuery from 'jquery';
window.$ = window.jQuery = jQuery;

// Importa DataTables com Bootstrap 5
import 'datatables.net-bs5';
import 'datatables.net-bs5/css/dataTables.bootstrap5.min.css';

// Importa DataTables Responsive com Bootstrap 5
import 'datatables.net-responsive-bs5';
import 'datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css';

// Importa Select2 e seu CSS
import 'select2';
import 'select2/dist/css/select2.css';

// Inicialização após o DOM carregar
document.addEventListener('DOMContentLoaded', () => {
    const tables = document.querySelectorAll('.datatables');

    tables.forEach(table => {
        $(table).DataTable({
            autoWidth: true,
            responsive: true,
            paging: true,
            searching: true,
            info: true,
            language: {
                url: '/lang/pt_BR.json',
                search: "Buscar:",
                searchPlaceholder: "Digite aqui para pesquisar..."
            },
            // Note que estamos adicionando 'f' para busca e 'p' para paginação
            dom: '<"flex justify-between items-center p-2 text-black"fl><"p-2 text-black"rt><"flex justify-between items-center p-2 text-black"ip>'
        });
    });
});
