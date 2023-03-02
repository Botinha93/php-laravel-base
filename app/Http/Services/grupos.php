<?php

namespace App\Http\Services;

use Illuminate\Support\Facades\DB;

class grupos{
    
    static function pai($id)
    {
        return DB::connection('mysql2')->select('SELECT
        ml.nome_abreviado AS nome, ml.codigo AS numero
        FROM
        tb_unidade ml
        LEFT JOIN tb_unidade cm ON ml.unidade_principal = cm.codigo
        WHERE
        cm.codigo = ' . $id . '
        ORDER BY numero');
    }
    static function filho($id)
    {
        return DB::connection('mysql2')->select('SELECT
        cm.nome_abreviado AS PAI, CONVERT(cm.codigo,char)  AS PAIID,
        ml.nome_abreviado AS nome, CONVERT(ml.codigo,char) AS numero
        FROM
        tb_unidade ml
        LEFT JOIN tb_unidade cm ON ml.unidade_principal = cm.codigo
        WHERE
        ml.unidade_principal = ' . $id . '
        ORDER BY PAIID,numero');
    }
    static function todas()
    {
        return DB::connection('mysql2')->select('SELECT
        cm.codigo AS PAIID, cm.nome_abreviado AS PAI,
        ml.codigo AS FILHOID, ml.nome_abreviado AS FILHO
        FROM
        tb_unidade ml
        LEFT JOIN tb_unidade cm ON ml.unidade_principal = cm.codigo
        ORDER BY PAIID,FILHOID');
    }
    static function raiz()
    {
        return DB::connection('mysql2')->select('SELECT
        ml.nome_abreviado AS nome, ml.codigo AS numero
        FROM
        tb_unidade ml
        LEFT JOIN tb_unidade cm ON ml.unidade_principal = cm.codigo
        WHERE
        ml.unidade_principal = 0
        ORDER BY numero;');
    }
    static function arvore($id){
        $arr = array();
        $grupos = grupos::pai($id);
        (json_encode($grupos) . ' ' . empty($grupos));
        if(!empty($grupos))
        foreach ($grupos as $value) {
            $value = json_decode(json_encode($value), true);
            array_push($arr, $value['numero']);
            ($value['numero'] . ' ' . $id . ' ' . ($value['numero'] != $id));
            if($value['numero'] != $id)
                $arr = array_merge($arr, grupos::arvore($value['numero']));
        }
        (json_encode($arr));
        return $arr;
    }
    static function arvoreRaiz()
    {
        $arr = array();
        $grupos = grupos::raiz();
        foreach ($grupos as $value) {
            $value = json_decode(json_encode($value), true);
            $value['subordinadas'] = grupos::arvore($value['numero']);
            array_push($arr, $value);
        }
        return $arr;
    }
    static function arvoreEscalonada($id, $interno = false)
    {
        $arr = array();
        $grupos = grupos::pai($id);
        if (!empty($grupos))
            foreach ($grupos as $value) {
                $value = json_decode(json_encode($value), true);
                if ($interno) {
                    array_push($arr, $value['numero']);
                    if ($value['numero'] != $id)
                        $arr = array_merge($arr, grupos::arvore($value['numero'], true));
                } else {
                    $value['subordinadas'] = grupos::arvoreEscalonada($value['numero']);
                    array_push($arr, $value);
                }
            }
        return $arr;
    }
}