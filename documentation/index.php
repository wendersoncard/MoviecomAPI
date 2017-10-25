<!DOCTYPE html>
<html>
<head>
  <title>Documentação Moviecom API</title>
  <link rel="stylesheet" href="style.css">

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script type="text/javascript">
    $(function(){
      $(window).scroll(function(e){
        var windowPos = $(this).scrollTop();

        $('h2').each(function(i, el){
          var elPos = $(el).offset().top;

          if(windowPos > elPos-50){
            $('#sideMenu ul li a').removeClass('active');
            $('#sideMenu ul li a[h2="'+$(el).attr('id')+'"]').addClass('active');
          }
        })
      });

      $(window).trigger('scroll');

      $('#sideMenu a').click(function(e){
        dis = this
        $('html, body').animate({
          scrollTop: $("#"+$(dis).attr('h2')).offset().top
        }, 200);
      })
    })
  </script>
</head>
<body>
  
  <div id="sideMenu">
      <div id="logoMenu"><img src="logo.png"></div>
      <ul>
        <li><a h2="introducao">Introdução</a></li>
        <li><a h2="token">Token de autenticação</a></li>
        <li><a h2="params">Parâmetros</a></li>
        <li><a h2="req">Requisição</a></li>
        <li><a h2="resp">Resposta</a></li>
        <li><a h2="erros">Erros</a></li>
        <li><a h2="code">Exemplo de código</a></li>
      </ul>
  </div>
  
  <div id="body">
    <img src="logo.png" style="margin-top: -10px;"> <h1>Documentação Moviecom API</h1>

    <div class="clearAll"></div>

    <section>
      <h2 id="introducao">Introdução</h2>
      <p>
        O objetivo desta documentação é orientar o desenvolvedor sobre a interação com a API Moviecom, descrevendo as etapas de integração, mostrando exemplos de envio e retorno, e orientando a autenticação dos serviços.
      </p>
    </section>

    <section>
      <h2 id="token">Token de autenticação</h2>
      <p>
        O Token de autenticação é provido pela Tripé Criação para o desenvolvedor do sistema. Para obte-lo favor entrar em contato via e-mail com contato@tripecriacao.com.br
      </p>
    </section>

    <section>
      <h2 id="params">Parâmetros</h2>

      <div class="methodUrl">
        <div>GET</div>
        <div>http://Moviecom.com.br/moviecomAPI/</div>
      </div>

      <div class="clearAll"></div>

      <div class="table" style="margin-bottom: 30px;">
        <div class="tableHeader">
          <div class="tr">
            <label>Header</label>
            <label>Descrição</label>
            <label class="center">Obrigatório</label>
          </div>
        </div>
        <div class="tableBody">
          <div class="tr">
            <label><font>user_token</font></label>
            <label>Código único fornecido para o desenvolvedor que consumirá os serviços da API.</label>
            <label class="center">Sim</label>
          </div>
        </div>
      </div>

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
      <h2 id="req">Exemplo de requisições</h2>
      <div class="code">
        <span class="comment">//GET http://Moviecom.com.br/moviecomAPI/</span>
        <span class="choma">{</span>
        <span class="name">praca: </span><span class="value">"TAU"<font class="white">,</font></span>
        <span class="name">data_ini: </span><span class="value">"2017-10-06"<font class="white">,</font></span>
        <span class="name">data_fim: </span><span class="value">"2017-10-07"</span>
        <span class="choma">}</span>
      </div>
    </section>

    <section>
      <h2 id="resp">Exemplo de resposta</h2>
      <div class="code">
        <span class="comment">//success</span>
        <span class="choma">{</span>
        <span class="name status">"status":</span><span class="value">"Success"<font class="white">,</font></span>
        <span class="name status">"data": </span><span class="value white">[ {</span>
            <span class="name">"praca": </span><span class="value"> "TAU"<font class="white">,</font></span>
            <span class="name">"filmes": </span><span class="value"> <font class="white">[</font></span>
                <span class="choma sec">{</span>
                    <span class="name sec">"filme": </span><span class="value"> <font class="white">{</font></span>
                        <span class="name thir">"titulo": </span><span class="value"> "Blade Runner 2049"<font class="white">,</font></span>
                        <span class="name thir">"cartaz": </span><span class="value"> "http://moviecom.com.br/site/wp-content/uploads/2017/08/blade-runner-ok.jpg"<font class="white">,</font></span>
                        <span class="name thir">"sinopse": </span><span class="value"> "Após trinta anos dos eventos do primeiro filme, a humanidade está ameaçada novamente e o perigo pode ser ainda maior. O novato oficial K (Ryan Gosling), remexe um segredo enterrado há tempos e que tem o potencial de mergulhar a sociedade em completo caos. Essa descoberta leva o policial a uma frenética busca por um ex-blade runner da polícia de Los Angeles, Rick Deckard (Harrison Ford), que está desaparecido há 30 anos.\r\n<div class=\"fichaFilme\"></div>\r\n&nbsp;"<font class="white">,</font></span>
                        <span class="name thir">"censura": </span><span class="value"> "14"<font class="white">,</font></span>
                        <span class="name thir">"trailer": </span><span class="value"> "g-LzzkTi6hk"<font class="white">,</font></span>
                        <span class="name thir">"ticket_filme": </span><span class="value"> "http://moviecom.com.br/vendafilme/?praca=TAU&idfilme=4502"<font class="white">,</font></span>
                        <span class="name thir">"horarios":  </span><span class="value"><font class="white">[</font></span>
                            <span class="choma four">{</span>
                                <span class="name fif">"sessao": </span><span class="value minor"> <font class="white">{</font></span>
                                    <span class="name six">"tipo": </span><span class="value minor"> "DUB"<font class="white">,</font></span>
                                    <span class="name six">"dia": </span><span class="value minor"> "2017-10-06"<font class="white">,</font></span>
                                    <span class="name six">"hora": </span><span class="value minor"> "16:15:00"<font class="white">,</font></span>
                                    <span class="name six">"sala": </span><span class="value minor"> "03"<font class="white">,</font></span>
                                    <span class="name six">"ticket_sessao": </span><span class="value minor"> "http://moviecom.com.br/vendasessao/?idprog=11e7a87469b29cc480ce84349711a651&praca=TAU"</span>
                                <span class="choma fif">}</span>
                            <span class="choma four">},</span>
                            <span class="choma four">{</span>
                                <span class="name fif">"sessao": </span><span class="value minor"> <font class="white">{</font></span>
                                    <span class="name six">"tipo": </span><span class="value minor"> "DUB"<font class="white">,</font></span>
                                    <span class="name six">"dia": </span><span class="value minor"> "2017-10-06"<font class="white">,</font></span>
                                    <span class="name six">"hora": </span><span class="value minor"> "19:30:00"<font class="white">,</font></span>
                                    <span class="name six">"sala": </span><span class="value minor"> "03"<font class="white">,</font></span>
                                    <span class="name six">"ticket_sessao": </span><span class="value minor"> "http://moviecom.com.br/vendasessao/?idprog=11e7a87469b29d3480ce84349711a651&praca=TAU"</span>
                                <span class="choma fif">}</span>
                            <span class="choma four">},</span>
                            <span class="choma four">{</span>
                                <span class="name fif">"sessao": </span><span class="value minor"> <font class="white">{</font></span>
                                    <span class="name six">"tipo": </span><span class="value minor"> "LEG 3D"<font class="white">,</font></span>
                                    <span class="name six">"dia": </span><span class="value minor"> "2017-10-06"<font class="white">,</font></span>
                                    <span class="name six">"hora": </span><span class="value minor"> "21:10:01"<font class="white">,</font></span>
                                    <span class="name six">"sala": </span><span class="value minor"> "04"<font class="white">,</font></span>
                                    <span class="name six">"ticket_sessao": </span><span class="value minor"> "http://moviecom.com.br/vendasessao/?idprog=11e7a87469b29d5080ce84349711a651&praca=TAU"</span>
                                <span class="choma fif">}</span>
                            <span class="choma four">}</span>
                        <span class="choma thir">]</span>
                    <span class="choma sec">}</span>
                <span class="choma name">}</span>
            <span class="choma name">]</span>
            <span class="choma status">} ]</span>
        <span class="choma">}</span>
      </div>
    </section>
    <section>
      <h2 id="erros">Erros</h2>

      <p>Quando não for enviado um dos três parâmetros obrigatórios, a API retornará o erro a seguir:</p>
      <div class="code">
        <span class="comment">//error</span>
        <span class="choma">{</span>
        <span class="name status">"status":</span><span class="value">"Error"<font class="white">,</font></span>
        <span class="name status">"data": </span><span class="value"><font class="white">[</font></span>
        <span class="name"></span><span class="value">"É necessário enviar os parâmetros de data início, data fim e código da praça."</span>
        <span class="choma status">]</span>
        <span class="choma">}</span>
      </div>

      <p>Quando não for enviado o <strong>token de autenticação</strong> (ou estiver em branco), a API retornará o erro a seguir:</p>
      <div class="code">
        <span class="comment">//error</span>
        <span class="choma">{</span>
        <span class="name status">"status":</span><span class="value">"Error"<font class="white">,</font></span>
        <span class="name status">"data": </span><span class="value"><font class="white">[</font></span>
        <span class="name"></span><span class="value">"É necessário enviar o token de autenticação."</span>
        <span class="choma status">]</span>
        <span class="choma">}</span>
      </div>

      <p>Quando o <strong>token de autenticação</strong> tiver sido enviado mas não foi localizado pelo sistema, a API detornará o erro a seguir:</p>
      <div class="code">
        <span class="comment">//error</span>
        <span class="choma">{</span>
        <span class="name status">"status":</span><span class="value">"Error"<font class="white">,</font></span>
        <span class="name status">"data": </span><span class="value"><font class="white">[</font></span>
        <span class="name"></span><span class="value">"Token de autenticação inválido."</span>
        <span class="choma status">]</span>
        <span class="choma">}</span>
      </div>

      <p>Quando as <strong>datas de início e fim</strong> tiverem uma diferença maior do que 2 meses (60 dias), a API retornará o erro a seguir:</p>
      <div class="code">
        <span class="comment">//error</span>
        <span class="choma">{</span>
        <span class="name status">"status":</span><span class="value">"Error"<font class="white">,</font></span>
        <span class="name status">"data": </span><span class="value"><font class="white">[</font></span>
        <span class="name"></span><span class="value">"Número máximo de dias disponíveis para consulta é de 60 dias."</span>
        <span class="choma status">]</span>
        <span class="choma">}</span>
      </div>

      <p>Caso o sistema da API estiver com algum erro interno temporariamente, a seguinte mensagem será retornada:</p>
      <div class="code">
        <span class="comment">//error</span>
        <span class="choma">{</span>
        <span class="name status">"status":</span><span class="value">"Error"<font class="white">,</font></span>
        <span class="name status">"data": </span><span class="value"><font class="white">[</font></span>
        <span class="name"></span><span class="value">"Erro interno."</span>
        <span class="choma status">]</span>
        <span class="choma">}</span>
      </div>

    </section>

    <section>
      <h2 id="code">Exemplo de requisição via Jquery Ajax:</h2>
      <img src="exemplo.png" style="width: 100%;">
    </section>
  </div>
</body>
</html>