<!DOCTYPE html>
<html>
<head>
  <title>Documentação Moviecom API</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <div id="sideMenu"></div>
  <div id="body">
    <img src="logo.png"> <h1>- Documentação Moviecom API</h1>

    <div class="clearAll"></div>

    <section>
      <h2>Introdução</h2>
      <p>
        O objetivo desta documentação é orientar o desenvolvedor sobre a interação com a API Moviecom, descrevendo as etapas de integração, mostrando exemplos de envio e retorno, e orientando a autenticação dos serviços.
      </p>
    </section>

    <section>
      <h2>Token de autenticação</h2>
      <p>
        O Token de autenticação é provido pela Tripé ao desenvolvedor do sistema. Para obte-lo favor entrar em contato via e-mail com contato@tripecriacao.com.br
      </p>
    </section>

    <section>
      <h2>Parâmetros</h2>

      <div class="methodUrl">
        <div>GET</div>
        <div>http://localhost/moviecomAPI/</div>
      </div>

      <div class="clearAll"></div>

      <div class="table">
        <div class="tableHeader">
          <div class="tr">
            <label>Parâmetro</label>
            <label>Descrição</label>
            <label class="center">Obrigatório</label>
          </div>
        </div>
        <div class="tableBody">
          <div class="tr">
            <label><font>token</font></label>
            <label>Código único fornecido para o desenvolvedor que consumirá os serviços da API.</label>
            <label class="center">Sim</label>
          </div>

          <div class="tr">
            <label><font>praca</font></label>
            <label>Código do cinema que deseja verificar as sessões e filmes.</label>
            <label class="center">Sim</label>
          </div>

          <div class="tr">
            <label><font>data_ini</font></label>
            <label>Data inicial do filtro para a listagem de sessões a ser consultada</label>
            <label class="center">Sim</label>
          </div>

          <div class="tr">
            <label><font>data_fim</font></label>
            <label>Data final do filtro para a listagem de sessões a ser consultada</label>
            <label class="center">Sim</label>
          </div>

        </div>
      </div>
    </section>

    <div class="clearAll"></div>

    <section>
      <h2>Exemplo de requisições</h2>
      <div class="code">
        <span class="comment">//get http://localhost/moviecomAPI/</span>
        <span class="choma">{</span>
        <span class="name">token: </span><span class="value">"nrkrA2qSvsCab5oNQdzXviR7ixoLx8Sc",</span>
        <span class="name">praca: </span><span class="value">"TAU",</span>
        <span class="name">data_ini: </span><span class="value">"2017-10-06",</span>
        <span class="name">data_fim: </span><span class="value">"2017-10-07"</span>
        <span class="choma">}</span>
      </div>
    </section>
  </div>
</body>
</html>