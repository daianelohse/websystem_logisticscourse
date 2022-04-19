<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['id_usuario']) || empty($_SESSION['id_usuario']) || $_SESSION['tipo_usuario'] == 3 || $_SESSION['tipo_usuario'] == '3') {
    header('Location:login.html');
}

require_once("fpdf/fpdf.php");
require_once('../controle/ControleRelatorio.class.php');
require_once('../controle/ControleProfessorTurma.class.php');

$idAluno = $_GET['id'];
$idTurma = $_GET['turma'];
$dataInicio = $_GET['dataInicio'];
$dataFim = $_GET['dataFim'];

$dataInicioSql = str_replace('/', '-', $dataInicio);
$dataInicioSql = date('Y-m-d', strtotime($dataInicioSql));

$dataFimSql = str_replace('/', '-', $dataFim);
$dataFimSql = date('Y-m-d', strtotime($dataFimSql));

$dataInicio = date('d/m/Y', strtotime($dataInicioSql));
$dataFim = date('d/m/Y', strtotime($dataFimSql));

$pdf = new FPDF("P", "pt", "A4");

$pdf->SetTitle(utf8_decode('Relatório do Aluno'));
$pdf->SetMargins(50, 50);
$pdf->SetAutoPageBreak(50, 50);
$pdf->AddPage();


if ($idTurma !== null) {
    $relatorio = ControleRelatorio::getInstancia();

    //cabeçalho da tabela
    $pdf->SetFont('arial', '', 12);
    $pdf->Cell(80, 20, utf8_decode("SENAI - Serviço Nacional de Aprendizagem Industrial"), 0, 1, 'L');
    $controleProfessorTurma = ControleProfessorTurma::getInstancia();

    $res = $controleProfessorTurma->getProfessoresByIdTurma($idTurma);


    $pdf->Cell(80, 20, utf8_decode("Turma: " . $res[0]['nome_turma']), 0, 1, 'L');


    if (count($res) > 1) {
        $nomeProfessores = "";
        $i = 0;
        while ($i < count($res)) {
            if ((count($res) - 1) == $i) {
                $nomeProfessores .= $res[$i]['nome_usuario'];
            } else {
                $nomeProfessores .= $res[$i]['nome_usuario'] . ", ";
            }
            $i++;
        }
        $pdf->Cell(80, 20, utf8_decode("Professores: " . $nomeProfessores), 0, 1, 'L');
    } else {
        $pdf->Cell(80, 20, utf8_decode("Professor: " . $res[0]['nome_usuario']), 0, 1, 'L');
    }
    $relatorioTurma = $relatorio->getRelatorioByIdAluno($idAluno, $idTurma, $dataInicioSql, $dataFimSql);
    if ($relatorioTurma != null || !empty($relatorioTurma)) {

        $pdf->Cell(80, 20, utf8_decode("Aluno: " . $relatorioTurma[0]['nome_usuario']), 0, 1, 'L');
        $pdf->Cell(80, 20, utf8_decode("Período: " . $dataInicio . " até " . $dataFim), 0, 1, 'L');
        $pdf->Ln(30);
        $pdf->SetFont('arial', 'B', 14);
        $pdf->Cell(0, 5, utf8_decode("Relatório"), 0, 1, 'C');


        $ultimoUser = "";

        //linhas da tabela
        $totalTempoJogado = 0;
        $totalTempoJogado = array();
        $totalTempoJogadoAluno = array();
        $tamanho = count($relatorioTurma);
        for ($i = 0; $i < $tamanho; $i++) {
            if ($relatorioTurma[$i]['nome_usuario'] == $ultimoUser) {
                $totalTempoJogado[$i] = $relatorioTurma[$i]['total_time'];
                $totalTempoJogadoAluno[$i] = $relatorioTurma[$i]['total_time'];
                $pdf->Ln(20);
                $pdf->SetFont('arial', '', 12);
                $pdf->Cell(80, 20, "", 0, 0, "L");
                $pdf->Cell(130, 20, $relatorioTurma[$i]['nome_jogo'], 0, 0, "L");
                $data = date_create($relatorioTurma[$i]['data_jogo']);
                $pdf->Cell(220, 20, date_format($data, 'd/m/Y'), 0, 0, "R");
                $pdf->Cell(60, 20, $relatorioTurma[$i]['total_time'], 0, 0, "R");
                
            } else {
                $totalTempoJogadoAluno = [];
                $totalTempoJogadoAluno[$i] = $relatorioTurma[$i]['total_time'];
                $totalTempoJogado[$i] = $relatorioTurma[$i]['total_time'];
                $ultimoUser = $relatorioTurma[$i]['nome_usuario'];
                $pdf->Ln(40);
                $pdf->SetFont('arial', 'B', 14);
                $pdf->Cell(130, 20, utf8_decode($relatorioTurma[$i]['nome_usuario']), 0, 0, "L");
                $pdf->Ln(20);
                $pdf->SetFont('arial', '', 12);
                $pdf->Cell(80, 20, "", 0, 0, "L");
                $pdf->Cell(130, 20, $relatorioTurma[$i]['nome_jogo'], 0, 0, "L");
                $data = date_create($relatorioTurma[$i]['data_jogo']);
                $pdf->Cell(220, 20, date_format($data, 'd/m/Y'), 0, 0, "R");
                $pdf->Cell(60, 20, $relatorioTurma[$i]['total_time'], 0, 0, "R");

                
            }
        }
        $pdf->Ln(100);
        $pdf->Cell(0, 5, "", "B", 1, 'C');
        $pdf->Cell(430, 20, "Total:", 0, 0, "R");
        $pdf->Cell(60, 20, converterTempo($totalTempoJogado), 0, 0, "R");
    } else {
        $pdf->Ln(20);
        $pdf->SetFont('arial', 'B', 14);
        $pdf->Cell(130, 20, "Nenhum resultado encontrado.", 0, 0, "L");
    }
} else {
    $pdf->Ln(20);
    $pdf->SetFont('arial', 'B', 14);
    $pdf->Cell(130, 20, "Erro ao gerar PDF!", 0, 0, "L");
    $pdf->Ln(20);
    $pdf->Cell(130, 20, "Tente novamente, se o erro persistir consulte o administrador.", 0, 0, "L");
}


$pdf->Output("relatorio_turma" . $idTurma . ".pdf", "I");

function converterTempo($times) {

    $i = 0;
    $s = 0;
    foreach ($times as $time) {
        sscanf($time, '%d:%d:%d', $hour, $min, $seg);
        $i += $hour * 60 + $min;
        $s += $min * 60 + $seg;
    }
    if ($h = floor($i / 60)) {
        $i %= 60;
    }
    if ($i = floor($s / 60)) {
        $s %= 60;
    }
    return sprintf('%02d:%02d:%02d', $h, $i, $s);
}

?>
