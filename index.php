<?php
/**
 * Created by PhpStorm.
 * User: André
 * Date: 30/06/2017
 * Time: 00:14
 *
 * Script que retorna todos os estabelecimentos em um raio de 10 km (de casa)
 * que aceitam vale refeição da ALELO como forma de pagamento
 *
 */


// valor fixo de páginas: 50
// cada uma delas contendo 10 páginas
// 500 estabelecimentos
for ($i = 1; $i <= 50 ; $i++) {

    // parâmetros usados no POST
    $postfields = [
        'page' => $i,
        'ID_CARTAO' => 1,
        'RAIO' => 10,
        'X' => '-49,387934237500005',
        'Y' => '-20,798011525'
    ];

    // curl
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "http://corp.maplink.com.br/AleloStoreLocator/RETORNA_ALELO_PAGING");
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postfields);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    //output do servidor da ALELO
    $server_output = curl_exec($ch);
    $valor = json_decode($server_output);

    // variavel $valor que recebe o objeto data, com as informações das 10 empresas
    $valor = $valor->paging->data;

    // para cada estabelecimento, pega nome e razão social, adiciona no vetor temp
    foreach ($valor as $estabelecimento) {
        $temp = [
            'nome' => $estabelecimento->NOME,
            'razao_social' => $estabelecimento->RAZAO_SOCIAL,
        ];

        $locais[] = $temp;
    }

    // fecha conexão em cada iteração (precisa ser melhorado...)
    curl_close($ch);
}
?>


<html>
<head>Lista de Estabelecimentos</head>
<body>
<p>Segue lista de empresas que aceitam o Ticket Refeição Alelo em São José do Rio Preto</p>
<p>Contagem: <?= sizeof($locais) ?> estabelecimentos</p>
<ul>
    <?php foreach ($locais as $local) { ?>
     <li><?= $local['nome'] ?> - <?= $local['razao_social'] ?></li>
<?php } ?>
</ul>
</body>
</html>
