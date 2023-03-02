

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

