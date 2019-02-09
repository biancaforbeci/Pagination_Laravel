<html>
<head>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <title>Pagina de Produtos</title>
    <style>
        body {
          padding: 20px;
        }
        .navbar {
          margin-bottom: 20px;
        }
    </style>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>

  <div class="container">
    <div class="card text-center">
      <div class="card-header">
       	Tabela de Clientes
      </div>
      <div class="card-body">
        <h5 class="card-title" id="cardtitle"></h5>

        <table class="table table-hover" id="tabelaClientes">
          <thead>
            <tr>
              <th scope="col">#</th>
              <th scope="col">Nome</th>
              <th scope="col">Sobrenome</th>
              <th scope="col">Email</th>
            </tr>
          </thead>
          <tbody>
          </tbody>
        </table>
      </div>
      <div class="card-footer">

        <nav id="paginationNav">
          <ul class="pagination">
          </ul>
        </nav>

<!--
        <nav id="paginationNav">
          <ul class="pagination">
            <li class="page-item disabled">
              <a class="page-link" href="#">Previous</a>
            </li>
            <li class="page-item"><a class="page-link" href="#">1</a></li>
            <li class="page-item active">
              <a class="page-link" href="#">2</a>
            </li>
            <li class="page-item"><a class="page-link" href="#">3</a></li>
            <li class="page-item">
              <a class="page-link" href="#">Next</a>
            </li>
          </ul>
        </nav>
-->

      </div>
    </div>

  </div>

  <script src="{{ asset('js/app.js') }}" type="text/javascript"></script>

  <script type="text/javascript">

    function getNextItem(data) {
        i = data.current_page+1; // página atual mais um.
        if (data.current_page == data.last_page)
            s = '<li class="page-item disabled">'; //se a última página é igual a página atual é desativado o click do próximo.
        else
            s = '<li class="page-item">';
        s += '<a class="page-link" ' + 'pagina="'+i+'" ' + ' href="javascript:void(0);">Próximo</a></li>'; //coloca o valor da página atual na variável pagina
        return s;
    }

    function getPreviousItem(data) {
        i = data.current_page-1; //página atual menos 1
        if (data.current_page == 1)
            s = '<li class="page-item disabled">'; //se a página atual é igual a 1 é desativado o click da anterior.
        else
            s = '<li class="page-item">';
        s += '<a class="page-link" ' + 'pagina="'+i+'" ' + ' href="javascript:void(0);">Anterior</a></li>'; //coloca o valor da página atual na variável pagina
        return s;
    }

    function getItem(data, i) {
        if (data.current_page == i)
            s = '<li class="page-item active">';  //se a página atual é igual ao inicio (i)
            s = '<li class="page-item">';
        s += '<a class="page-link" ' + 'pagina="'+i+'" ' + ' href="javascript:void(0);">' + i + '</a></li>';  //coloca o valor da página atual na variável pagina
        return s;
    }

    function montarPaginator(data) {

        $("#paginationNav>ul>li").remove();

        $("#paginationNav>ul").append(
            getPreviousItem(data)
        );
        // for (i=1;i<=data.last_page;i++) {
        //     $("#paginationNav>ul").append(
        //         getItem(data,i)
        //     );
        // }

        n = 10;

        if (data.current_page - n/2 <= 1)  // caso em que a página atual for menor ou igual a 6.
            inicio = 1;
        else if (data.last_page - data.current_page < n)  // caso em que a última página menos a página atual for menor que 10.
            inicio = data.last_page - n + 1;
        else
            inicio = data.current_page - n/2;  //página atual - 5.

        fim = inicio + n-1;  //exemplo 91 + 100-1;

        for (i=inicio;i<=fim;i++) {
            $("#paginationNav>ul").append(
                getItem(data,i)
            );
        }
            $("#paginationNav>ul").append(
            getNextItem(data)
            );
        }

    function montarLinha(cliente) {
        return '<tr>' +
            '  <th scope="row">' + cliente.id + '</th>' +
            '  <td>' + cliente.nome + '</td>' +
            '  <td>' + cliente.sobrenome + '</td>' +                  //montando cada linha da tabela
            '  <td>' + cliente.email + '</td>' +
            '</tr>';
    }

    function montarTabela(data) {
        $("#tabelaClientes>tbody>tr").remove();
        for(i=0;i<data.data.length;i++) {
            $("#tabelaClientes>tbody").append(
                montarLinha(data.data[i])  //passando refererência do dado para montar linha da tabela
            );
        }
    }

    function carregarClientes(pagina) {
        $.get('/json',{page: pagina}, function(resp) {
            console.log(resp);
            console.log(resp.data.length);
            montarTabela(resp);
            montarPaginator(resp);
            $("#paginationNav>ul>li>a").click(function(){
                // console.log($(this).attr('pagina') );
                carregarClientes($(this).attr('pagina')); //pega o click do botão do navegador para mudar de página
            })
            $("#cardtitle").html( "Exibindo " + resp.per_page + //mostra quantos clientes por página são mostrados
                " clientes de " + resp.total + //pega o valor total dos dados no banco de dados
                " (" + resp.from + " a " + resp.to +  ")" );  //pega o primeiro valor apresentado até o último da tela
        });
    }

    $(function(){
        carregarClientes(1);
    });

  </script>

</body>
</html>
