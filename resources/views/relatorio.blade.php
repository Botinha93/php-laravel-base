<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]>      <html class="no-js"> <!--<![endif]-->
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>{{\Request::get('titulo')}}</title>
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <style>
            #table {
              font-family: Nunito, Helvetica, sans-serif;
              border-collapse: collapse;
              width: 100%;
            }
            
            #table td, #table th {
              border: 1px solid #ddd;
              padding: 8px;
            }
            
            #table tr:nth-child(even){background-color: #f2f2f2;}
            
            #table tr:hover {background-color: #ddd;}
            
            #table th {
              padding-top: 12px;
              padding-bottom: 12px;
              text-align: left;
              background-color: #04AA6D;
              color: white;
            }
            #carregar{
                width: 100%;
                height: 100%;
                float: left;
                position: fixed;
                top: 0;
                left: 0;
                background-color: rgba(75, 75, 75, 0.46);
                text-align:center;
            }
            #popup{
                background-color: #137b85;
                color: #ddd;
                position: relative;
                height: 200px;
                width: 400px;
                top: calc(50% - 110px);
                left: calc(50% - 210px);
                padding: 20px;
                border-radius: 10px;
            }
            h1 {
                font: 2em 'Arial', sans-serif;
                margin-bottom: 40px;
            }

            #loading {
                display: inline-block;
                width: 50px;
                height: 50px;
                border: 3px solid rgba(255,255,255,.3);
                border-radius: 50%;
                border-top-color: #fff;
                animation: spin 1s ease-in-out infinite;
                -webkit-animation: spin 1s ease-in-out infinite;
            }

            @keyframes spin {
                to { -webkit-transform: rotate(360deg); }
                }
            @-webkit-keyframes spin {
                to { -webkit-transform: rotate(360deg); }
                }
            </style>
    </head>
    <body>
        <!--[if lt IE 7]>
            <p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="#">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->
        
        <table id="table">
            <tr>
                <th>Rota</th>
                <th>Data</th>
                <th>Usuario</th>
                <th>unidade</th>
              </tr>
            <tbody></tbody>
          </table>
          <div id="carregar">
            <div id="popup">
                <h1 id="texto">Carregando </h1>
                <div id="loading"></div>
            </div>
          </div>
          <script>
            class Relatorios{
                static requestRelatorio(token, url, elemento, page = 1){
                    var myHeaders = new Headers();
                    myHeaders.append("token", token);
                    var requestOptions = {
                    method: 'GET',
                    headers: myHeaders,
                    redirect: 'follow'
                    };
                    fetch(url+'?page='+page, requestOptions)
                    .then(response => response.json())
                    .then((data) => {
                        for (const d of data['result']['data']) {
                            var relatorio= {
                                "rota" : d['rota'],
                                "created_at" : d['created_at'],
                                "usuario" : d['usuario'],
                                "unidades" : d['unidades'],
                            };
                            const row = elemento.insertRow(-1);
                            for (const c of Object.values(relatorio)) {
                            const cell = row.insertCell(-1);
                            cell.textContent = c;
                            }
                        }
                        if(page < data['result']['to']){
                            document.getElementById('texto').textContent = "Carregando: " + data['result']['to'] + " de " + data['result']['total'];
                            window.scrollTo(0, document.body.scrollHeight);
                            Relatorios.requestRelatorio(token, url, elemento, page + 1);
                        }else{
                            document.getElementById('carregar').style.visibility = "hidden";
                            window.print();
                        }
                    })

                }
            }

            const urlParams = new URLSearchParams(window.location.search);
            Relatorios.requestRelatorio(urlParams.get('token'),urlParams.get('url'),document.querySelector('table'));
          </script>
    </body>
</html>
