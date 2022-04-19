<?php
include 'cabecalho.php';
echo $_SESSION['tipo_usuario'];
if (($_SESSION['tipo_usuario']) == 3 || ($_SESSION['tipo_usuario']) == '3') {
    include 'jogos.php';
} else {
    ?>
    <div class="centralizar">

        <input type="hidden" name="tipo_user" id="tipoUser" value="<?php echo $tipoUser ?>" />
        <input type="hidden" name="id_user" id="idUser" value="<?php echo $idUser ?>" />
        <input type="hidden" name="id_professor" id="idProf" value="<?php echo $idProfessor ?>" />

        <div id="controle">
            <?php
            if ($tipoUser == 1) {

                include 'gerenciarPermissoes.html';
            }
            ?>
        </div>

        <ol id="bread" class="breadcrumb">
            <li><a href="#" class="active" onclick="atualizarTurmas()">Turmas</a></li>
        </ol>

        <div class="pagina">
            <section id="turmas">
                <div class="container">
                    <div class="botoesControle">
                        <span class="pull-right">
                            <button type="button" id="novaTurma" class="btn" onclick="abrirCadastroTurmas('<?php echo $tipoUser ?>')">Nova Turma</button>
                        </span>
                    </div>
                </div>



                <div id="adicionarTurma"  class="container">


                    <div class="panel panel-default">

                        <div class="panel-heading"><i class="glyphicon glyphicon-plus-sign"></i> Adicionar Turma<button type="button" class="close" onclick="$('#adicionarTurma').hide();" aria-hidden="true">&times;</button></div>
                        <div class="panel-body">
                            <div id="addTurma-alert"></div>

                            <form id="formCadastro" class="form-horizontal" role="form">

                                <?php
                                if ($_SESSION['tipo_usuario'] == 1) {
                                    ?>

                                    <div class="form-group">
                                        <label class="col-md-1 control-label">Professor</label>.
                                        <div class="col-md-10">

                                            <div id="erroselectProfessores" class="erros"></div>

                                            <div class="col-lg-14" id="inputProfessor">

                                                <input type="text" id="cadastrarTurma" value="" />

                                            </div> 


                                            <div id="selectProfessores" class="">

                                            </div>
                                        </div>
                                    </div>



                                    <?php
                                }
                                if ($_SESSION['tipo_usuario'] == 2) {
                                    ?>


                                    <div class="form-group">
                                        <label id="labelTurmasSemEsteProfessor" class="col-md-1 control-label">Turmas</label>.
                                        <div class="col-md-10">

                                            <div id="erroselectTurmas" class="erros"></div>

                                            <div class="col-lg-14" id="inputTurmas">

                                                <select class="form-control" id="selectTurmas">

                                                </select>
                                            </div> 



                                        </div>
                                    </div>

                                    <?php
                                }
                                ?>




                                <div class="form-group">
                                    <label for="turma" id="labelTurma" class="col-md-1 control-label">Nova turma</label>
                                    <div class="col-md-10">
                                        <div id="erroturma" class="erros"></div>
                                        <input type="text" id="turma" class="form-control" name="turma" placeholder="Nome da Turma">
                                        <p class='help-block'> Exemplo: Tec Log Vesp 2014/1 </p>
                                    </div>
                                </div>

                                <div class="pull-right">
                                    <div id="col-md-10">
                                        <button type="button" class="btn btn-success" onclick="cadastrarNovaTurma()" id="salvarTurma">Cadastrar</button>
                                        <button type="button" class="btn btn-success" onclick="salvarEdicaoTurma()" id="editarTurma" style="display: none">Editar</button>
                                    </div>
                                </div>


                            </form>	
                        </div>
                    </div>

                </div>

                <div id="listaTurmas">
                    Carregando...
                </div>


                <br style="clear: both"/>
                <div id="listaTurmasArquivadas">

                    <p>
                        <span id="nTurmasArquivadas" style="display: inline;" onclick="abrirTurmasArquivadas()">Nenhuma turma arquivada</span>

                        <span class="pull-right" id="ocultarTurmasArquivadas" onclick="collapseTurmas()"><span id="iconCollapseTurmasArquivadas" class="glyphicon glyphicon-collapse-down"></span></span>
                    </p>

                    <div style="clear: both"></div>

                    <div id="lstTurmasArquivadas">
                    </div>
                </div>





                <div id="responsive" class="modal fade modalArquivarTurma" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content" id="modalArquivarTurma">
                            Carregando...
                        </div>
                    </div>
                </div>


                <div id="responsive" class="modal fade modalAbrirTurma" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content" id="modalAbrirTurma">
                            Carregando...
                        </div>
                    </div>
                </div>

                <div id="responsive" class="modal fade modalExcluirTurma" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content" id="modalExcluirTurma">
                            Carregando...
                        </div>
                    </div>
                </div>







            </section>

            <section id="alunos">

                <div class="container">

                    <div class="botoesControle">
                        <span class="pull-right">
                            <button type="button" id="inserirAluno" class="btn" onclick="inserirAlunos()">Inserir Aluno</button>
                            <button type="button" id="btPlanodeAula" class="btn" onclick="planoDeAula(idTurma)">Plano de Aula</button>
                        </span>
                    </div>

                    <div id="adicionarAluno"  class="container">
                        <div class="panel panel-default">
                            <div class="panel-heading"><i class="glyphicon glyphicon-plus-sign"></i> Adicionar Aluno - Turma <span id="nomeTurma"></span>
                                <button type="button" class="close" onclick="$('#adicionarAluno').hide();" aria-hidden="true">&times;</button></div>
                            <div class="panel-body">
                                <div id="addAluno-alert"></div>
                                <form role='form'>
                                    <div class="form-group">
                                        <label>Alunos</label>


                                        <div id="erroselectUsuarios" class="erros"></div>

                                        <div class="col-lg-14">
                                            <div id="adicionarAlunoCheck">
                                                <input type="text" id="cadastrarAluno" value="" style="display: none"/>
                                            </div>
                                        </div>

                                        <div class="pull-right">
                                            <button type="button" class="btn btn-success" onclick="salvarAlunos()" id="cadAluno">Salvar</button>
                                        </div>
                                    </div>
                                </form>	
                            </div>
                        </div>
                    </div>




                    <div id="listaAlunos">
                        Carregando...
                    </div>
                </div>

                <div id="responsive" class="modal fade modalRelatorioTurma" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content" id="modalRelatorioTurma">
                            Carregando...
                        </div>
                    </div>
                </div>

                <div id="responsive" class="modal fade modalRelatorioAluno" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content" id="modalRelatorioAluno">
                            Carregando...
                        </div>
                    </div>
                </div>

                <div id="responsive" class="modal fade modalRemoverAluno" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content" id="modalRemoverAluno">
                            Carregando...
                        </div>
                    </div>
                </div>

            </section>

            <section id="planoDeAula">

                <div id="containerPlanoDeAula">
                    <div class="titulosPlanoDeAula">
                        <span><i class="glyphicon glyphicon-ok"></i> Jogos Liberados</span>
                    </div>

                    <div id="jogosLiberados"  class="container">

                        Carregando...
                    </div>

                    <div class="titulosPlanoDeAula">
                        <span><i class="glyphicon glyphicon-lock"></i> Jogos Bloqueados</span>
                    </div>

                    <div id="jogosBloqueados"  class="container">

                        Carregando...
                    </div>

                </div>
                <div id="responsive" class="modal fade planoDeAula-alert" tabindex="-1"
                     role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                    <div class="modal-dialog">

                        <div class="modal-content" id="planoDeAula-alert">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                <h3 class="modal-title"><i class="erro glyphicon glyphicon-remove-circle"></i> Erro</h3>
                            </div>
                            <div class="modal-body">
                                <span style="font-size: 15px" id="erroPlanodeAula"><span>
                                        <span style="font-size: 12px">Tente novamente mais tarde. Se o erro persistir, entre em contato com o admnistrador do sistema.</span>

                                        </div>
                                        <div class="modal-footer">
                                            <div class="botoesModalConfirmacao">
                                                <button type="button" class="btn btn-success" data-dismiss="modal">OK</button>
                                            </div>

                                        </div>
                                        </div>
                                        </div>
                                        </div>

                                        </section>

                                        </div>




                                        </div>
                                        </div>
                                        <div id="responsive" class="modal fade bs-example-modal-lg" tabindex="-1"
                                             role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                                            <div class="modal-lg">
                                                <div class="modal-content" id="modalJogo">

                                                </div>
                                            </div>
                                        </div>

                                        <?php
                                    }
                                    include 'rodapeControle.html';
                                    ?>
