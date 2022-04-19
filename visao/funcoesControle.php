<?php

$acao = $_GET['url'];
include_once '../controle/ControleProfessor.class.php';
include_once '../controle/ControleTurma.class.php';
include_once '../controle/ControleAluno.class.php';
include_once '../controle/ControleUsuario.class.php';
include_once '../controle/ControleRelatorio.class.php';
include_once '../controle/ControleProfessorTurma.class.php';
include_once '../controle/ControleJogo.class.php';
include_once '../controle/ControleJogosLiberadosTurma.class.php';
include_once '../controle/ControleAlunoTurma.class.php';

switch ($acao) {
    case 'pegarNumeroSolicitacoesAcesso':
        $controleUsuario = ControleUsuario::getInstancia();

        if (!isset($_GET['pesquisa']) || empty($_GET['pesquisa']) || !$_GET['pesquisa'] == 'undefined') {
            $pesquisa = $_GET['pesquisa'];
        } else {
            $pesquisa = null;
        }

        $res = $controleUsuario->getUsuariosSemPermissao($pesquisa);

        echo count($res);

        break;
    case 'cadastrarPermissaoUsuario':

        $id = $_POST['id_usuario'];
        $tipo = $_POST['tipo_usuario'];
        $permissaoAntiga = $_POST['permissao_antiga'];

        if ($permissaoAntiga != -1) {
            if ($permissaoAntiga == 2) {
                /* Era um professor que se torna aluno. 
                 * Sua conta como professor fica inativa, 
                 * é criada uma referência para o usuário dele na tabela de aluno 
                 */
                $controleProfessor = ControleProfessor::getInstancia();

                //Atualiza a situação da conta do professor como desativada
                $res2 = $controleProfessor->updateProfessor($id, 0);
                if ($res2) {

                    //É criada a conta na tabela de "Alunos" 

                    $controleAluno = ControleAluno::getInstancia();

                    $res2 = $controleAluno->cadastrarAluno($controleAluno->novoAluno(0, $id, 1));

                    if ($res2) {
                        /*
                         * É atualizado o tipo de permissão na conta do usuário em "Usuários" 
                         */
                        $controleUsuario = ControleUsuario::getInstancia();
                        $res3 = $controleUsuario->setPermissaoUsuario($id, $tipo);
                        if ($res3) {
                            echo 'sucesso';
                        } else {
                            echo 'erro';
                        }
                    } else {
                        echo 'erro';
                    }
                } else {
                    echo 'erro';
                }
            } else if ($permissaoAntiga == 3) {
                /* Era um aluno que se torna professor. 
                 * Sua conta como aluno fica inativa, 
                 * é criada uma referência para o usuário dele na tabela de professor 
                 */


                //Atualiza a situação da conta do aluno como desativada
                $controleAluno = ControleAluno::getInstancia();
                $res1 = $controleAluno->updateAluno($id, 0);


                if ($res1) {
                    //É criada a conta na tabela de "Professor" 
                    $controleProfessor = ControleProfessor::getInstancia();
                    $res2 = $controleProfessor->cadastrarProfessor($controleProfessor->novoProfessor(0, $id, 1));

                    if ($res2) {
                        //Atualiza a permissão do usuário para professor
                        $controleUsuario = ControleUsuario::getInstancia();
                        $res3 = $controleUsuario->setPermissaoUsuario($id, $tipo);

                        if ($res3) {
                            echo 'sucesso';
                        } else {
                            echo 'erro';
                        }
                    } else {
                        echo 'erro';
                    }
                } else {
                    echo 'erro';
                }
            }
        } else {
            $controleUsuario = ControleUsuario::getInstancia();

            $res = $controleUsuario->setPermissaoUsuario($id, $tipo);


            if ($res) {
                if ($tipo == 2) {
                    $controleProfessor = ControleProfessor::getInstancia();
                    $res1 = $controleProfessor->cadastrarProfessor($controleProfessor->novoProfessor(0, $id, 1));
                    if ($res1) {
                        echo 'sucesso';
                    } else {
                        echo 'erro';
                    }
                } else if ($tipo == 3) {
                    //Cadastrar aluno na tabela "Alunos"
                    $controleAluno = ControleAluno::getInstancia();

                    $res2 = $controleAluno->cadastrarAluno($controleAluno->novoAluno(0, $id, 1));

                    if ($res2) {
                        echo 'sucesso';
                    } else {
                        echo 'erro';
                    }
                }
            } else {
                echo 'erro';
            }
        }

        break;
    case 'desativarUsuario':
        $id = $_POST['id_usuario'];
        $permissaoAntiga = $_POST['permissao_antiga'];

        //Tornar conta do professor inativa
        if ($permissaoAntiga == 2) {
            $controleProfessor = ControleProfessor::getInstancia();
            $res1 = $controleProfessor->updateProfessor($id, 0);
            if ($res1) {
                echo 'sucesso';
            } else {
                echo 'erro';
            }
        }

        //Tornar conta do aluno inativa
        if ($permissaoAntiga == 3) {
            $controleAluno = ControleAluno::getInstancia();
            $res1 = $controleAluno->updateAluno($id, 0);
            if ($res1) {
                echo 'sucesso';
            } else {
                echo 'erro';
            }
        }

        break;
    case 'excluirUsuario':
        $id = $_POST['id_usuario'];
        $controleUsuario = ControleUsuario::getInstancia();
        $res = $controleUsuario->deleteUsuario($id);
        if ($res) {
            echo 'sucesso';
        } else {
            echo 'erro';
        }
        break;
    case 'pegarUsuariosSemPermissao':
        $controleUsuario = ControleUsuario::getInstancia();


        $res = $controleUsuario->getUsuariosSemPermissao($_GET['pesquisa']);

        if (count($res) > 0) {
            $i = 0;
            echo '<div class="list-group">';
            while ($i < count($res)) {
                if ($res[$i]['tipo_usuario'] == 2) {
                    $tipo = "professor";
                    $segundaOpcao = "Aluno";
                    $class = "iconBlack";
                } else if ($res[$i]['tipo_usuario'] == 3) {
                    $tipo = "aluno";
                    $segundaOpcao = "Professor";
                    $class = "iconBlue";
                }
                echo '<li id="user' . $res[$i]['id_usuario'] . '" class="list-group-item clearfix">
					<span  class="' . $class . ' glyphicon glyphicon-user"></span> 
					<span class="nomeUser userDetalhe' . $res[$i]['id_usuario'] . '"> ' . $res[$i]['nome_usuario'] . ' </span>
					<span class="emailUser userDetalhe' . $res[$i]['id_usuario'] . '">(' . $res[$i]['email_usuario'] . ') </span> 
                                            
                                        <span class="confirmarPermissaoUser confirmarPermissaoUser' . $res[$i]['id_usuario'] . '" style="display:none">
                                            <span class="confirmarPrimeiraOpcao' . $res[$i]['id_usuario'] . '">Tem certeza que deseja liberar o acesso do tipo <strong>' . $tipo . '</strong> para ' . $res[$i]['nome_usuario'] . '?</span>
                                            <span id="confirmarSegundaOpcao' . $res[$i]['id_usuario'] . '" style="display:none">as</span>
                                            <span class="pull-right">
                                            <button onclick="confirmarAprovacao(' . $res[$i]['id_usuario'] . ',' . $res[$i]['tipo_usuario'] . ')" class="btn btn-xs btn-success">
                                                CONFIRMAR
                                           </button>
                                           <button onclick="cancelarAprovacao(' . $res[$i]['id_usuario'] . ')" class="btn btn-xs btn-danger">
                                                CANCELAR
                                           </button>
                                          </span>
                                          
                                        
                                        </span>
                                        <span id="feedbackCadastro' . $res[$i]['id_usuario'] . '" class="feedBackCadPermissao">
                                            
                                        </span>   
                                       
                                            
                                       <span class="pull-right">
                                           <span class="liberacaoRapida' . $res[$i]['id_usuario'] . '">
                                       <span class="liberarAcesso">Liberar permissão de <strong>' . $tipo . '</strong>? </span>
                                           
                                         
                                               
                                          <button onclick="aprovarPermissao(' . $res[$i]['id_usuario'] . ')" class="btn btn-xs btn-success">
                                                <span class="iconeClose glyphicon glyphicon-thumbs-up"> Sim</span>
                                           </button>
                                          

                                       <button onclick="abrirPermissaoUsuario(' . $res[$i]['id_usuario'] . ')" class="btn btn-xs btn-danger">
                                            <span class="iconeClose glyphicon glyphicon-thumbs-down"> Não</span>
                                        </button>
                                         </span>
                            
                            
                            <button class="botao' . $res[$i]['id_usuario'] . ' btn btn-xs" style="display:none" onclick="fecharPermissaoUsuario()">
                                 <span class="iconeClose"></span>
                            </button>
                        </span>
                            <div class="checkUserPermissao" id="permissaoUser' . $res[$i]['id_usuario'] . '" style="display:none">
                                <div class="radio">
                                 <label>
                                    <input name="optradio" type="radio" id="permissao' . $segundaOpcao . '" value="3" onclick="mostrarBotoes()">
                                    ' . $segundaOpcao . '
                                </label>
                                </div>
                                <div class="radio">
                                 <label>
                                    <input name="optradio" type="radio" id="permissaoExcluir" value="excluir" onclick="mostrarBotoes()">
                                    Excluir
                                </label>
                                </div>
                                
                                <div class="botoesGerenciarPermissao">
                                    <button class="btn btn-success" type="button" onclick="aprovarPermissaoSegundaOpcao(' . $res[$i]['id_usuario'] . ',\'' . $res[$i]['nome_usuario'] . '\')">SALVAR</button>
                                    <button class="btn btn-danger" type="button" onclick="fecharPermissaoUsuario()">CANCELAR</button>
                                </div>
                            </div>
                    </li>';
                $i++;
            }
            echo '</div>';
        } else {
            if ($_GET['pesquisa'] == null) {
                echo '<p class="mensagemUsuarioPermissao">Nenhuma solicitação de acesso.</p>';
            } else {
                echo '<p class="mensagemUsuarioPermissao">Nenhum usuário esperando liberação de acesso com o nome/usuário "<strong>' . $_GET['pesquisa'] . '</strong>" foi encontrado.</p>';
            }
        }


        break;
    case 'pegarUsuariosInativos':
        $controleUsuario = ControleUsuario::getInstancia();


        $res = $controleUsuario->getUsuariosInativos($_GET['pesquisa']);

        if (count($res) > 0) {
            $i = 0;
            echo '<div class="list-group">';
            while ($i < count($res)) {
                if ($res[$i]['tipo_usuario'] == 2) {
                    $tipo = "professor";
                    $segundaOpcao = "Aluno";
                    $class = "iconBlack";
                } else if ($res[$i]['tipo_usuario'] == 3) {
                    $tipo = "aluno";
                    $segundaOpcao = "Professor";
                    $class = "iconBlue";
                }
                echo '<li id="user' . $res[$i]['id_usuario'] . '" class="list-group-item clearfix">
                                    <span  class="' . $class . ' glyphicon glyphicon-user"></span> 
                                    <span class="nomeUser userDetalhe' . $res[$i]['id_usuario'] . '"> ' . $res[$i]['nome_usuario'] . ' </span>
                                    <span class="emailUser userDetalhe' . $res[$i]['id_usuario'] . '">(' . $res[$i]['email_usuario'] . ') </span> 

                                    <span class="confirmarPermissaoUser confirmarPermissaoUser' . $res[$i]['id_usuario'] . '" style="display:none">
                                        <span class="confirmarPrimeiraOpcao' . $res[$i]['id_usuario'] . '">Tem certeza que deseja reativar o acesso do tipo <strong>' . $tipo . '</strong> para ' . $res[$i]['nome_usuario'] . '?</span>

                                        <span class="pull-right">
                                        <button onclick="confirmarAtivarUsuario(' . $res[$i]['id_usuario'] . ',' . $res[$i]['tipo_usuario'] . ')" class="btn btn-xs btn-success">
                                            CONFIRMAR
                                       </button>
                                       <button onclick="cancelarAprovacao(' . $res[$i]['id_usuario'] . ')" class="btn btn-xs btn-danger">
                                            CANCELAR
                                       </button>
                                      </span>


                                    </span>
                                    <span id="feedbackCadastro' . $res[$i]['id_usuario'] . '" class="feedBackCadPermissao">

                                    </span>   


                                   <span class="pull-right">
                                       <span class="liberacaoRapida' . $res[$i]['id_usuario'] . '">
                                   <span class="liberarAcesso">Reativar conta do <strong>' . $tipo . '</strong>? </span>



                                      <button onclick="aprovarPermissao(' . $res[$i]['id_usuario'] . ')" class="btn btn-xs btn-success">
                                            <span> Ativar</span>
                                       </button>



                                     </span>


                        <button class="botao' . $res[$i]['id_usuario'] . ' btn btn-xs" style="display:none" onclick="fecharPermissaoUsuario()">
                             <span class="iconeClose"></span>
                        </button>
                    </span>
                        <div class="checkUserPermissao" id="permissaoUser' . $res[$i]['id_usuario'] . '" style="display:none">
                            <div class="radio">
                             <label>
                                <input name="optradio" type="radio" id="permissao' . $segundaOpcao . '" value="3" onclick="mostrarBotoes()">
                                ' . $segundaOpcao . '
                            </label>
                            </div>
                            <div class="radio">
                             <label>
                                <input name="optradio" type="radio" id="permissaoExcluir" value="excluir" onclick="mostrarBotoes()">
                                Excluir
                            </label>
                            </div>

                            <div class="botoesGerenciarPermissao">
                                <button class="btn btn-success" type="button" onclick="aprovarPermissaoSegundaOpcao(' . $res[$i]['id_usuario'] . ',\'' . $res[$i]['nome_usuario'] . '\')">SALVAR</button>
                                <button class="btn btn-danger" type="button" onclick="fecharPermissaoUsuario()">CANCELAR</button>
                            </div>
                        </div>
                </li>';
                $i++;
            }
            echo '</div>';
        } else {

            if ($_GET['pesquisa'] == null) {
                echo '<p class="mensagemUsuarioPermissao">Nenhum usuário inativo.</p>';
            } else {
                echo '<p class="mensagemUsuarioPermissao">Nenhum usuário inativo com o nome/usuário "<strong>' . $_GET['pesquisa'] . '</strong>" foi encontrado.</p>';
            }
        }


        break;
    case 'pegarTodosUsuariosComPermissao':
        $controleUsuario = ControleUsuario::getInstancia();

        $res = $controleUsuario->getUsuariosComPermissao($_GET['pesquisa']);
        if (count($res) > 0) {
            $i = 0;
            echo '<div class="list-group">';
            $tipo = "";
            $segundaOpcao = "";
            $class = "";


            while ($i < count($res)) {
                if ($res[$i]['tipo_usuario'] == 2) {
                    $tipo = "Professor";
                    $segundaOpcao = "Aluno";
                    $class = "iconBlack";
                } else if ($res[$i]['tipo_usuario'] == 3) {
                    $tipo = "Aluno";
                    $segundaOpcao = "Professor";
                    $class = "iconBlue";
                }
                echo '<li id="user' . $res[$i]['id_usuario'] . '" class="list-group-item clearfix">
					<span  class="' . $class . ' glyphicon glyphicon-user"></span> 
					<span class="nomeUser userDetalhe' . $res[$i]['id_usuario'] . '"> ' . $res[$i]['nome_usuario'] . ' </span>
					<span class="emailUser userDetalhe' . $res[$i]['id_usuario'] . '">(' . $res[$i]['email_usuario'] . ') </span> 
                                            
                                        <span class="confirmarPermissaoUser confirmarPermissaoUser' . $res[$i]['id_usuario'] . '" style="display:none">
                                            
                                            <span class="confirmarPrimeiraOpcao' . $res[$i]['id_usuario'] . '">Tem certeza que deseja liberar o acesso do tipo <strong>' . $tipo . '</strong> para ' . $res[$i]['nome_usuario'] . '?</span>
                                            <span id="confirmarSegundaOpcao' . $res[$i]['id_usuario'] . '" style="display:none">as</span>
                                            <span class="pull-right">
                                            <button onclick="confirmarAprovacao(' . $res[$i]['id_usuario'] . ',' . $res[$i]['tipo_usuario'] . ')" class="btn btn-xs btn-success">
                                                CONFIRMAR
                                           </button>
                                           <button onclick="cancelarAprovacao(' . $res[$i]['id_usuario'] . ')" class="btn btn-xs btn-danger">
                                                CANCELAR
                                           </button>
                                          </span>
                                          
                                        
                                        </span>
                                        <span id="feedbackCadastro' . $res[$i]['id_usuario'] . '" class="feedBackCadPermissao">
                                            
                                        </span>   
                                       
                                            
                                       <span class="pull-right">
                                           <span class="liberacaoRapida' . $res[$i]['id_usuario'] . '">
                                       <span class="liberarAcesso"> <strong>' . $tipo . '</strong> </span>
                                           
                                         

                                       <button title="Editar permissão"  onclick="abrirPermissaoUsuario(' . $res[$i]['id_usuario'] . ')" class="btn btn-xs btn-warning">
                                            <span class="iconeClose glyphicon glyphicon glyphicon-pencil"></span>
                                        </button>
                                         </span>
                            
                            
                            <button class="botao' . $res[$i]['id_usuario'] . ' btn btn-xs" style="display:none" onclick="fecharPermissaoUsuario()">
                                 <span class="iconeClose"></span>
                            </button>
                        </span>
                            <div class="checkUserPermissao" id="permissaoUser' . $res[$i]['id_usuario'] . '" style="display:none">
                                <div class="radio">
                                 <label>
                                    <input name="optradio" type="radio" id="permissao' . $segundaOpcao . '" value="3" onclick="mostrarBotoes()">
                                    ' . $segundaOpcao . '
                                </label>
                                </div>
                                <div class="radio">
                                 <label>
                                    <input name="optradio" type="radio" id="permissaoExcluir" value="excluir" onclick="mostrarBotoes()">
                                    Excluir
                                </label>
                                </div>
                                
                                <div class="botoesGerenciarPermissao">
                                    <button class="btn btn-success" type="button" onclick="aprovarPermissaoSegundaOpcao(' . $res[$i]['id_usuario'] . ',\'' . $res[$i]['nome_usuario'] . '\')">SALVAR</button>
                                    <button class="btn btn-danger" type="button" onclick="fecharPermissaoUsuario()">CANCELAR</button>
                                </div>
                            </div>
                    </li>';
                $i++;
            }
            echo "</div>";
        } else {
            if ($_GET['pesquisa'] == null) {
                echo '<p class="mensagemUsuarioPermissao">Nenhum usuário com acesso liberado.</p>';
            } else {
                echo '<p class="mensagemUsuarioPermissao">Nenhum usuário liberado com o nome/usuário "<strong>' . $_GET['pesquisa'] . '</strong>" foi encontrado.</p>';
            }
        }

        break;
    case 'pegarProfessores':
        $controleProfessor = ControleProfessor::getInstancia();
        $res = $controleProfessor->getProfessores();
        $i = 0;
        while ($i < count($res)) {
            echo '  <div class="checkbox">
                                <label>
                                    <input name="usuarios" id="prof' . $i . '" type="checkbox" value="' . $res[$i]['id_professor'] . '">
                                    ' . $res[$i]['nome_usuario'] . '
                                </label>
                            </div>';
            $i++;
        }
        break;
    case 'validarNomeTurma':
        $controleTurma = ControleTurma::getInstancia();
        $nomeTurma = $_GET['nomeTurma'];

        $res = $controleTurma->getTurmaByNome($nomeTurma);
        if (count($res) > 0) {
            echo 'Turma já cadastrada!';
        }
        break;
    case 'cadastrarTurma':

        $controleTurma = ControleTurma::getInstancia();
        $res = $controleTurma->cadastrarTurma($controleTurma->novaTurma(0, $_POST['turma']));
        if ($res) {
            $idTurma = $controleTurma->getUltimoId();
        }



        $controleProfessorTurma = ControleProfessorTurma::getInstancia();

        $res1 = $controleProfessorTurma->cadastrarProfessorTurma($controleProfessorTurma->novoProfessorTurma($_POST['professor'], $idTurma));
        if ($res1) {
            echo '<div id="alerta" class="alert col-sm-12 alert-success">
					<i class="glyphicon glyphicon-ok"></i>
			
					<span style="font-size:15pt">Turma cadastrada com sucesso!</span></div>';
        } else {
            echo '<div id="alerta" class="alert col-sm-12 alert-danger">
				<i class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></i>
				<span style="font-size:15pt">Erro ao realizar o cadastro da turma!</span> <br />
				Se o erro persistir, entre em contato com o administrador do sistema.</div>';
        }



        break;
    case 'editarTurma':
        $idTurma = $_POST['idTurma'];
        $nomeTurma = $_POST['nomeTurma'];
        $professores = $_POST['idProfessor'];

        //UPDATE NA TABELA TURMAS
        $controleTurma = ControleTurma::getInstancia();
        $res = $controleTurma->editarTurma($idTurma, $nomeTurma);
        if ($res) {
            //EXCLUIR TODAS AS REFERÊNCIAS DA TURMA TABELA DE 'PROFESSORESTURMAS'
            $controleProfessorTurma = ControleProfessorTurma::getInstancia();
            $res1 = $controleProfessorTurma->deleteByIdTurma($idTurma);

            if ($res1) {
                //CADASTRAR PROFESSORES E TURMAS NA TABELA 'PROFESSORESTURMAS'
                $res2 = $controleProfessorTurma->cadastrarProfessorTurma($controleProfessorTurma->novoProfessorTurma($professores, $idTurma));
                if ($res2) {
                    echo '<div id="alerta" class="alert col-sm-12 alert-success">
					<i class="glyphicon glyphicon-ok"></i>
			
					<span style="font-size:15pt">Turma cadastrada com sucesso!</span></div>';
                } else {
                    echo '<div id="alerta" class="alert col-sm-12 alert-danger">
				<i class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></i>
				<span style="font-size:15pt">Erro ao realizar o cadastro da turma!</span> <br />
				Se o erro persistir, entre em contato com o administrador do sistema.</div>';
                }
            } else {
                echo '<div id="alerta" class="alert col-sm-12 alert-danger">
				<i class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></i>
				<span style="font-size:15pt">Erro ao realizar o cadastro da turma!</span> <br />
				Se o erro persistir, entre em contato com o administrador do sistema.</div>';
            }
        } else {
            echo '<div id="alerta" class="alert col-sm-12 alert-danger">
				<i class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></i>
				<span style="font-size:15pt">Erro ao realizar o cadastro da turma!</span> <br />
				Se o erro persistir, entre em contato com o administrador do sistema.</div>';
        }
        break;
    case 'cadastrarTurmaProfessor':
        $idTurma = $_POST['idTurma'];
        $nomeTurma = $_POST['nomeTurma'];
        $professores = $_POST['idProfessor'];

        $controleProfessorTurma = ControleProfessorTurma::getInstancia();

        //CADASTRAR PROFESSORES E TURMAS NA TABELA 'PROFESSORESTURMAS'
        $res2 = $controleProfessorTurma->cadastrarProfessorTurma($controleProfessorTurma->novoProfessorTurma($professores, $idTurma));
        if ($res2) {
            echo '<div id="alerta" class="alert col-sm-12 alert-success">
					<i class="glyphicon glyphicon-ok"></i>
			
					<span style="font-size:15pt">Turma cadastrada com sucesso!</span></div>';
        } else {
            echo '<div id="alerta" class="alert col-sm-12 alert-danger">
				<i class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></i>
				<span style="font-size:15pt">Erro ao realizar o cadastro da turma!</span> <br />
				Se o erro persistir, entre em contato com o administrador do sistema.</div>';
        }

        break;
    case 'pegarTurmas':
        $controleTurma = ControleTurma::getInstancia();
        $res = $controleTurma->getTurmas();

        if (count($res) > 0) {
            $i = 0;
            echo '<div class="list-group">';
            while ($i < count($res)) {
                if ($res[$i]['numero_alunos'] > 1 || $res[$i]['numero_alunos'] == 0) {
                    $alunos = " alunos";
                } else {
                    $alunos = " aluno";
                }
                echo '<li href="#" class="list-group-item clearfix" ">
                       <span onclick="abrirAlunosTurma(' . $res[$i]['id_turma'] . ',\'' . $res[$i]['nome_turma'] . '\',null)">
						<span  class="glyphicon glyphicon-folder-close"></span> 
						<span class="nomeTurma"> ' . $res[$i]['nome_turma'] . ' </span>
								<span class="nAlunos">' . $res[$i]['numero_alunos'] . $alunos . ' </span> 
                                                                    
                            
                         </span>
                            <span class="pull-right">
                            <button title="Editar" class="btn btn-xs btn-warning" onclick="editarTurma(' . $res[$i]['id_turma'] . ',\'' . $res[$i]['nome_turma'] . '\')">
                                <span class="glyphicon glyphicon-pencil"></span>
                            </button>
                            <button title="Arquivar" class="btn btn-xs btn-danger" data-target=".modalArquivarTurma"  data-toggle="modal" onclick="arquivarTurma(' . $res[$i]['id_turma'] . ',\'' . $res[$i]['nome_turma'] . '\')">
                                <span class="glyphicon glyphicon-remove"></span>
                            </button>
                        </span>
                      
                    </li>';
                $i++;
            }
            echo '</div>';
        } else {
            echo 'Nenhum turma cadastrada.';
        }
        break;
    case 'pegarTurmasSelect':
        $controleTurma = ControleTurma::getInstancia();
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }



        $res = $controleTurma->getTurmasByIdSemEsteProfessor($_SESSION['id_professor']);

        if (count($res) > 0) {
            $i = 0;
            echo '<option></option>';
            while ($i < count($res)) {
                echo ' <option value="' . $res[$i]['id_turma'] . '"> ' . $res[$i]['nome_turma'] . ' </option>';
                $i++;
            }
        } else {
            echo '';
        }
        break;
    case 'pegarTurmasCombo':
        $controleTurma = ControleTurma::getInstancia();
        $res = $controleTurma->getTurmas();

        if (count($res) > 0) {
            $i = 0;
            echo '<option value=""></option>';
            while ($i < count($res)) {
                echo '<option value="' . $res[$i]['id_turma'] . '">' . $res[$i]['nome_turma'] . '</option>';
                $i++;
            }
        }
        break;
    case 'pegarTurmasDoProfessor':
        $professor = $_GET['id'];


        $controleTurma = ControleTurma::getInstancia();

        $res = $controleTurma->getTurmasByIdProfessor($professor);
        if (count($res) > 0) {
            $i = 0;
            echo '<div class="list-group">';
            while ($i < count($res)) {
                if ($res[$i]['numero_alunos'] > 1 || $res[$i]['numero_alunos'] == 0) {
                    $alunos = " alunos";
                } else {
                    $alunos = " aluno";
                }
                echo '<li href="#" class="list-group-item clearfix" onclick="abrirAlunosTurma(' . $res[$i]['id_turma'] . ',\'' . $res[$i]['nome_turma'] . '\',null)"><span  class="glyphicon glyphicon-folder-close"></span> <span
                            class="nomeTurma"> ' . $res[$i]['nome_turma'] . ' </span>
                            <span class="nAlunos"> ' . $res[$i]['numero_alunos'] . $alunos . ' </span>
                    </li> ';
                $i++;
            }
            echo '</div>';
        } else {
            echo 'Nenhuma turma cadastrada.';
        }
        break;
    case "pegarAlunosTurma":
        $id_turma = $_GET['turma'];
        //pegar professores
        $controleProfessorTurma = ControleProfessorTurma::getInstancia();
        $res = $controleProfessorTurma->getProfessoresByIdTurma($id_turma);
        if (count($res) > 0 && $res != false) {
            $nomeProfessores = "";
            if (count($res) > 1) {
                $nomeProfessores .= "<strong>es:</strong> ";
            } else {
                $nomeProfessores .= "<strong>:</strong> ";
            }

            $i = 0;
            while ($i < count($res)) {

                $nome = $res[$i]['nome_usuario'];

                if ($res[$i]['estaAtivo_professor'] == 0) {
                    $nome = "<span title='Usuário Invativo' class='usuarioInativo'>" . $nome . " <i title='Usuário Invativo' class='glyphicon glyphicon-exclamation-sign'></i> </span>";
                }

                if ($i == (count($res) - 1)) {
                    $nomeProfessores .= $nome . "<br />";
                } else {
                    $nomeProfessores .= $nome . ", ";
                }
                $i++;
            }
            echo '<strong>Professor</strong>' . $nomeProfessores;
        } else {
            echo '<p>Nenhum resultado encontrado.</p>';
        }
        $controleAlunoTurma = ControleAlunoTurma::getInstancia();

        $res = null;
        $res = $controleAlunoTurma->getAlunoTurmaByIdTurma($id_turma);

        if ($res) {
            if (count($res) > 0) {
                echo '<div class="linkAbreModal"><a id="abreModalRelatorio" data-toggle="modal" data-target=".modalRelatorioTurma" onclick="abrirModalRelatorioTurma(' . $res[0]['id_turma'] . ',\'' . $res[0]['nome_turma'] . '\')">Relatório</a></div>';

                echo '<div class="list-group">';
                $i = 0;
                while ($i < count($res)) {
                    echo '<li class="list-group-item clearfix"> 
                    <span onclick="abrirModalRelatorioAluno(' . $res[$i]['id_aluno'] . ',\'' . $id_turma . '\',\'' . $res[$i]['nome_usuario'] . '\')" data-toggle="modal" data-target=".modalRelatorioAluno">
                        <span class="glyphicon glyphicon-user"></span>
                            <span class="nomeAluno"> ' . $res[$i]['nome_usuario'] . ' </span>
                                <span class="emailUser"> (' . $res[$i]['email_usuario'] . ') </span>
                                    </span>';
                    if (session_status() == PHP_SESSION_NONE) {
                        session_start();
                    }
                    if ($_SESSION['tipo_usuario'] == 1) {
                        echo ' <span class="pull-right"><button title="Editar" onclick="abrirModalEditarPerfil(' . $res[$i]['id_usuario'] . ',\'controle\',' . $id_turma . ',\'' . $res[$i]['nome_turma'] . '\')" data-toggle="modal" data-target=".modalEditarPerfil" class="btn btn-xs btn-warning">
                                <span class="glyphicon glyphicon-pencil"></span>
                            </button>
                     
                        <button onclick="removerAlunoTurma(' . $res[$i]['id_turma'] . ',' . $res[$i]['id_aluno'] . ',\'' . $res[$i]['nome_usuario'] . '\')" data-toggle="modal" data-target=".modalRemoverAluno" title="Remover da Turma" class="btn btn-xs btn-danger">
                               <span class="glyphicon glyphicon-trash"></span>
                        </button></span>';
                    } else {
                        echo '';
                    }

                    echo '</li> ';
                    $i++;
                }


                echo '</div>';
            } else {
                echo 'Nenhum aluno cadastrado nessa turma';
            }
        } else {
            echo '<p style="margin-top:2em">Nenhum resultado encontrado.</p>';
        }
        break;
    case "pegarAlunosSemTurmaPesquisa":
        $pesquisa = $_GET['q'];
        $id = $_GET['idTurma'];

        $controleUsuario = ControleAluno::getInstancia();

        $res = $controleUsuario->getAlunosSemTurmaByNome($pesquisa, $id);

        if (count($res) > 0) {

            echo json_encode($res);
        }
        break;
    case "salvarAlunos":

        $controleAlunoTurma = ControleAlunoTurma::getInstancia();


        $res = $controleAlunoTurma->cadastrarAlunoTurma($controleAlunoTurma->novoAlunoTurma($_POST['alunos_id_aluno'], $_POST['turmas_id_turma']));

        if (count($_POST['alunos_id_aluno']) > 1) {
            $plural = "s ";
        } else {
            $plural = " ";
        }

        if ($res) {
            echo '<div id="alerta" class="alert alert-success">
					<i class="glyphicon glyphicon-ok"></i>
			
					<span style="font-size:15pt">Aluno' . $plural . 'cadastrado' . $plural . 'com sucesso!</span></div>';
        } else {
            echo '<div id="alerta" class="alert alert-danger">
				<i class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></i>
				<span style="font-size:15pt">Erro ao realizar o cadastro do' . $plural . 'aluno' . $plural . '!</span> <br />
				Se o erro persistir, entre em contato com o administrador do sistema.</div>';
        }

        break;
    case "salvarRelatorio":

        $idJogo = $_POST["id_jogo"];
        $idUsuario = $_POST["id_usuario"];
        $idAluno = $_POST["id_aluno"];
        $tempo = $_POST["tempo"];
        $data = $_POST['data'];


        $controleAluno = ControleAluno::getInstancia();

        $res = $controleAluno->getAlunosByIdUser($idUsuario);

        if (count($res) > 0) {
            $idAluno = $res[0]['id_aluno'];

            $controleRelatorio = ControleRelatorio::getInstancia();

            $res = $controleRelatorio->insert($controleRelatorio->novoRelatorio(0, $idJogo, $idAluno, $idUsuario, $tempo, $data));

            if (!$res) {
                echo 'erro';
            }
        }
        break;
    case 'pesquisarJogos':


        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        $tipoUsera = $_SESSION['tipo_usuario'];
        $idUser = $_SESSION['id_usuario'];

        if ($tipoUsera == 3 || $tipoUsera == '3') {
            $jogos = ControleJogosLiberadosTurma::getInstancia();

            $res = $jogos->getJogoLiberadoByUser($idUser);
        } else {
            $controleJogo = ControleJogo::getInstancia();
            $res = $controleJogo->pesquisarJogos(trim(utf8_decode($_GET['pesquisa'])));
        }
        echo '<div id="thumbs"><div class="row">';

        if (count($res) > 0) {
            for ($i = 0; $i < count($res); $i++) {
                echo '<div class="col-lg-3 col-md-4 col-xs-6 thumb">
                <div class="thumbnail" data-toggle="modal" data-target=".bs-example-modal-lg" onclick="abrirJogo(' . $res[$i]['id_jogo'] . ')">
                    <img src="dist/img/jogos/' . $res[$i]["nome_imagem_jogo"] . '" class="responsive"  alt="' . $res[$i]["nome_jogo"] . '"/>
                   <span id="hoverJogos"><i class="playJogo glyphicon glyphicon-play-circle"></i> <br/>' . utf8_encode($res[$i]['nome_jogo']) . '</span>
                </div>
               </div>';
            }
            echo '</div></div>';
        } else {
            echo 'Nenhum jogo encontrado.';
        }
        break;
    case 'pegarProfessoresByIdTurma':
        $controleProfessorTurma = ControleProfessorTurma::getInstancia();
        $res = $controleProfessorTurma->getProfessoresByIdTurmaEdicao($_GET["id"]);

        $lista = "";

        for ($i = 0; $i < count($res); $i++) {
            if ($i !== (count($res) - 1)) {
                $lista .= '{"id_professor": "' . $res[$i]['id_professor'] . '", "nome_professor": "' . $res[$i]['nome_professor'] . '"},';
            } else {
                $lista .= '{"id_professor": "' . $res[$i]['id_professor'] . '", "nome_professor": "' . $res[$i]['nome_professor'] . '"}';
            }
        }

        print_r($lista);

        break;
    case 'pegarprofessoresPesquisa':
        $pesquisa = $_GET['q'];
        $controleProfessor = ControleProfessor::getInstancia();
        $r = $controleProfessor->getProfessorByNome($pesquisa);

        echo json_encode($r);

        break;
    case 'arquivarTurma':
        $controleTurma = ControleTurma::getInstancia();
        $res = $controleTurma->arquivarTurma($_POST['id']);
        if ($res) {
            echo '<div id="alerta" class="alert alert-success">
					<i class="glyphicon glyphicon-ok"></i>
					<span style="font-size:15pt">Arquivada com sucesso!</span></div>';
        } else {
            echo '<div id="alerta" class="alert alert-danger">
				<i class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></i>
				<span style="font-size:15pt">Erro ao realizar ao arquivar turma. Tente novamente, se o erro persistir, consulte o administrador do sistema.</div>';
        }

        break;
    case 'numeroTurmasArquivadas':
        $controleTurma = ControleTurma::getInstancia();
        $res = $controleTurma->getNumeroTurmasArquivadas();
        if ($res[0]['numeroTurmasArquivadas'] == 0) {
            echo 'Nenhuma turma arquivada';
        }
        if ($res[0]['numeroTurmasArquivadas'] == 1) {
            echo '1 turma arquivada';
        }
        if ($res[0]['numeroTurmasArquivadas'] > 1) {
            echo $res[0]['numeroTurmasArquivadas'] . ' turmas arquivadas';
        }

        break;
    case 'numeroTurmasArquivadasByIdProfessor':
        $id = $_GET['id'];
        $controleTurma = ControleTurma::getInstancia();
        $res = $controleTurma->getNumeroTurmasArquivadasByProfessor($id);
        if ($res[0]['numeroTurmasArquivadas'] == 0) {
            echo 'Nenhuma turma arquivada';
        }
        if ($res[0]['numeroTurmasArquivadas'] == 1) {
            echo '1 turma arquivada';
        }
        if ($res[0]['numeroTurmasArquivadas'] > 1) {
            echo $res[0]['numeroTurmasArquivadas'] . ' turmas arquivadas';
        }

        break;
    case 'pegarTurmasArquivadas':
        $controleTurma = ControleTurma::getInstancia();
        $res = $controleTurma->getTurmasArquivadas();

        if (count($res) > 0) {

            $i = 0;
            echo '<div class="list-group">';
            while ($i < count($res)) {

                if ($res[$i]['numero_alunos'] > 1 || $res[$i]['numero_alunos'] == 0) {
                    $alunos = " alunos";
                } else {
                    $alunos = " aluno";
                }
                echo '<li href="#" class="list-group-item clearfix" ">
                       <span onclick="abrirAlunosTurma(' . $res[$i]['id_turma'] . ',\'' . $res[$i]['nome_turma'] . '\',\'arquivada\')">
						<span  class="glyphicon glyphicon-folder-close"></span> 
						<span class="nomeTurma"> ' . $res[$i]['nome_turma'] . ' </span>
								<span class="nAlunos">' . $res[$i]['numero_alunos'] . $alunos . ' </span> 
                                                                    
                            
                         </span>
                            <span class="pull-right">
                            <button title="Abrir Turma" class="btn btn-xs btn-warning" data-target=".modalAbrirTurma"  data-toggle="modal" onclick="abrirTurmaFechada(' . $res[$i]['id_turma'] . ',\'' . $res[$i]['nome_turma'] . '\')">
                                <span class="glyphicon glyphicon-folder-open"></span>
                            </button>
                            <button title="Excluir" class="btn btn-xs btn-danger" data-target=".modalExcluirTurma"  data-toggle="modal" onclick="excluirTurma(' . $res[$i]['id_turma'] . ',\'' . $res[$i]['nome_turma'] . '\')">
                                <span class="glyphicon glyphicon-trash"></span>
                            </button>
                        </span>
                      
                    </li>';
                $i++;
            }
            echo '</div>';
        }

        break;
    case 'pegarTurmasArquivadasByIdProfessor':
        $id = $_GET['id'];
        $controleTurma = ControleTurma::getInstancia();
        $res = $controleTurma->getTurmasArquivadasByIdProfessor($id);

        if (count($res) > 0) {
            $i = 0;
            echo '<div class="list-group">';
            while ($i < count($res)) {
                echo '<li href="#" class="list-group-item clearfix" ">
                       <span onclick="abrirAlunosTurma(' . $res[$i]['id_turma'] . ',\'' . $res[$i]['nome_turma'] . '\',\'arquivada\')">
						<span  class="glyphicon glyphicon-folder-close"></span> 
						<span class="nomeTurma"> ' . $res[$i]['nome_turma'] . ' </span>
								<span class="nAlunos">' . $res[$i]['numero_alunos'] . ' alunos</span> 
                                                                    
                            
                         </span>
                           
                    </li>';
                $i++;
            }
            echo '</div>';
        }

        break;
    case 'removerAlunoTurma':

        $controleAlunoTurma = ControleAlunoTurma::getInstancia();
        $res = $controleAlunoTurma->deleteByIdTurma($_POST['id_aluno'], $_POST['id_turma']);

        if ($res) {
            echo '<div id="alerta" class="alert alert-success">
                    <i class="glyphicon glyphicon-ok"></i>
                    <span style="font-size:15pt">O aluno foi excluído da turma com sucesso!</span></div>';
        } else {
            echo '<div id="alerta" class="alert alert-danger">
                    <i class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></i>
                    <span style="font-size:15pt">Erro ao excluir aluno da turma. Tente novamente, se o erro persistir, consulte o administrador do sistema.</div>';
        }

        break;
    case 'abrirTurmaArquivada':
        $controleTurma = ControleTurma::getInstancia();
        $res = $controleTurma->abrirTurmaArquivada($_POST['id']);

        if ($res) {
            echo '<div id="alerta" class="alert alert-success">
                    <i class="glyphicon glyphicon-ok"></i>
                    <span style="font-size:15pt">A situação da turma foi atualizada com sucesso!</span></div>';
        } else {
            echo '<div id="alerta" class="alert alert-danger">
                    <i class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></i>
                    <span style="font-size:15pt">Erro ao abrir a turma. Tente novamente, se o erro persistir, consulte o administrador do sistema.</div>';
        }

        break;
    case 'editarCadastroUsuario':
        $controleUsuario = ControleUsuario::getInstancia();

        if (!isset($_POST['senha_usuario']) || empty('senha_usuario') || $_POST['senha_usuario'] == '') {
            $senha = null;
        } else {
            $senha = $_POST['senha_usuario'];
        }

        $res = $controleUsuario->editarUsuario($controleUsuario->novoUsuario($_POST['id_usuario'],trim( $_POST ['nome_usuario']), trim($_POST ['user_usuario']), trim($_POST ['email_usuario']), trim($senha), null, true));

        if ($res) {
            if ($_POST['autor_edicao'] == 'user') {
                session_start();
                $_SESSION['id_usuario'] = $_POST['id_usuario'];
                $_SESSION['nome_usuario'] = $_POST['nome_usuario'];
            }
            echo '<div id="alerta" class="alert col-sm-12 alert-success">
                    <i class="glyphicon glyphicon-ok"></i>
                    <span style="font-size:15pt">Edição realizada com sucesso!</span> 
		 </div>';
        } else {
            echo '<div id="alerta" class="alert col-sm-12 alert-danger">
                    <i class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></i>
                    <span style="font-size:15pt">Erro ao realizar a edição do cadastro!</span> <br /> 
                    Se o erro persistir, entre em contato com o administrador do sistema.
                  </div>';
        }
        break;
    case 'excluirTurma':
        $id = $_POST['id'];
        $controleRelatorio = ControleRelatorio::getInstancia();

        //Excluir referência da turma na tabela de alunos
        $controleAlunoTurma = ControleAlunoTurma::getInstancia();

        $res1 = $controleAlunoTurma->deleteByIdTurma(null, $id);


        if ($res1) {
            //Excluir referência da tabela 'professores_turmas'
            $controleProfessorTurma = ControleProfessorTurma::getInstancia();

            $res2 = $controleProfessorTurma->deleteByIdTurma($id);


            if ($res2) {
                $controleJogosLiberadosTurma = ControleJogosLiberadosTurma::getInstancia();
                $res3 = $controleJogosLiberadosTurma->deleteByTurma($id);


                if ($res3) {

                    //Excluir turma da tabela 'turmas'
                    $controleTurma = ControleTurma::getInstancia();

                    $res4 = $controleTurma->delete($id);


                    if ($res4) {
                        echo '<div id="alerta" class="alert col-sm-12 alert-success">
                    <i class="glyphicon glyphicon-ok"></i>
                    <span style="font-size:15pt">Turma excluída com sucesso!</span> 
		 </div>';
                    } else {
                        echo '<div id="alerta" class="alert col-sm-12 alert-danger">
                    <i class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></i>
                    <span style="font-size:15pt">Erro ao realizar a edição do cadastro!</span> <br /> 
                    Se o erro persistir, entre em contato com o administrador do sistema.
                  </div>';
                    }
                } else {
                    echo '<div id="alerta" class="alert col-sm-12 alert-danger">
                    <i class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></i>
                    <span style="font-size:15pt">Erro ao realizar a edição do cadastro!</span> <br /> 
                    Se o erro persistir, entre em contato com o administrador do sistema.
                  </div>';
                }
            } else {
                echo '<div id="alerta" class="alert col-sm-12 alert-danger">
                    <i class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></i>
                    <span style="font-size:15pt">Erro ao realizar a edição do cadastro!</span> <br /> 
                    Se o erro persistir, entre em contato com o administrador do sistema.
                  </div>';
            }
        } else {
            echo '<div id="alerta" class="alert col-sm-12 alert-danger">
                    <i class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></i>
                    <span style="font-size:15pt">Erro ao realizar a edição do cadastro!</span> <br /> 
                    Se o erro persistir, entre em contato com o administrador do sistema.
                  </div>';
        }

        break;
    case 'redefinirSenha':

        $controleUser = ControleUsuario::getInstancia();
        $res = $controleUser->getUsuarioByUserEmail($_GET['userEmail']);

        if (count($res) > 0) {

            $mail = $res[0]['email_usuario'];
            $mail = explode("@", $mail);

            $tamanho = strlen($mail[0]);
            $pontos = "";

            $i = 0;
            $tamanho--;
            while ($tamanho > $i) {
                $pontos .= "*";
                $i++;
            }
            $mail[0] = substr($mail[0], 0, 1) . ($pontos);



            error_reporting(-1);
            ini_set('display_errors', 'On');

            //emails para quem será enviado o formulário
            $emailenviar = "daianelohse@gmail.com";
            $destino = $mail;
            $assunto = "Redefinição de Senha - Curso Técnico em Logística";

            // É necessário indicar que o formato do e-mail é html
            $headers = 'MIME-Version: 1.0' . "\r\n";
            $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
            $headers .= 'From: $nome <$email>';
            //$headers .= "Bcc: $EmailPadrao\r\n";

            $arquivo = "senha nova";

            $enviaremail = mail("daianelohse@gmail.com", "ex mail", "ex content", "From: daianelohse@gmail.com");
            if ($enviaremail) {
                echo '<div class="alert alert-success">Um email será enviado para o endereço de e-mail que você cadastrou no seu perfil (' . $mail[0] . '@' . $mail[1] . ')</div>';
            } else {
                var_dump($enviaremail);
                echo '<div class="alert alert-danger">Ocorreu um erro ao enviar a nova senha para o seu e-mail. Se o erro persistir, consulte o administrador</div>';
            }
        } else {
            echo '<div class="alert alert-danger">Usuario ou e-mail inválido.</div>';
        }

        break;
    case 'pegarJogosLiberados':
        $jogos = ControleJogo::getInstancia();

        //pegar os jogos Liberados
        $turma = $_GET['turma'];
        $res = $jogos->getJogosLiberados($turma);


        if (empty($res)) {
            echo 'Nenhum jogo liberado nesta turma.';
        } else {
            for ($i = 0; $i < count($res); $i++) {
                echo '<div class="jogosPlanoDeAula col-lg-3 col-md-4 col-xs-6 thumb">
                    <div class="jogoLiberado thumbnail">
                        <span class="iconeLiberado glyphicon glyphicon-ok"></span>
                        <div class="bloquearJogo glyphicon glyphicon-lock" title="Bloquear" onclick="bloquearJogo(' . $res[$i]['id_jogo'] . ',' . $turma . ')"></div>
                        <img src="dist/img/jogos/' . $res[$i]["nome_imagem_jogo"] . '" class="img-responsive" alt="' . utf8_encode($res[$i]['nome_jogo']) . '"/>
                        <div class="verJogo" data-toggle="modal" data-target=".bs-example-modal-lg"  onclick="abrirJogo(' . $res[$i]["id_jogo"] . ')">Ver jogo</div>
                    </div>
                </div>';
            }
        }

        break;
    case 'pegarJogosBloqueados':
        $jogos = ControleJogo::getInstancia();

        //pegar os jogos Liberados
        $turma = $_GET['turma'];
        $res = $jogos->getJogosBloqueados($turma);


        if (empty($res)) {
            echo 'Nenhum jogo blqueado nesta turma.';
        } else {
            for ($i = 0; $i < count($res); $i++) {
                echo '<div class="jogosPlanoDeAula col-lg-3 col-md-4 col-xs-6 thumb">
                    <div class="jogoBloqueado thumbnail">
                        <span class="iconeBloqueado glyphicon glyphicon-lock"></span>
                        <div class="liberarJogo glyphicon glyphicon-ok" title="Liberar" onclick="liberarJogo(' . $res[$i]['id_jogo'] . ',' . $turma . ')"></div>
                        <img src="dist/img/jogos/' . $res[$i]["nome_imagem_jogo"] . '" class="img-responsive" alt="' . utf8_encode($res[$i]['nome_jogo']) . '"/>
                        <div class="verJogo" data-toggle="modal" data-target=".bs-example-modal-lg"  onclick="abrirJogo(' . $res[$i]["id_jogo"] . ')">Ver jogo</div>
                    </div>
                </div>';
            }
        }
        break;
    case 'bloquearJogo':
        $jogosTurmas = ControleJogosLiberadosTurma::getInstancia();

        $turma = $_POST['turma'];
        $jogo = $_POST['jogo'];


        $res = $jogosTurmas->updateJogoLiberadoTurma($jogo, $turma, 0);

        if (!$res) {
            echo 'false';
        }

        break;

    case 'liberarJogo':
        $jogosTurmas = ControleJogosLiberadosTurma::getInstancia();


        $turma = $_POST['turma'];
        $jogo = $_POST['jogo'];

        //Verificar se já está cadastrado

        $res = $jogosTurmas->getJogosLiberadosByIdTurmaJogo($jogo, $turma);

        if (count($res) <= 0) {

            $res1 = $jogosTurmas->cadastrarJogoLiberadoTurma($jogosTurmas->novoJogosLiberadosTurma($jogo, $turma));
        } else if (count($res) > 0) {
            $res1 = $jogosTurmas->updateJogoLiberadoTurma($jogo, $turma, 1);
            if (!$res1) {
                echo 'false';
            }
        } else {
            echo 'false';
        }

        break;

    case 'verificarResultadosRelatorioTurma':

        $controleRelatorio = ControleRelatorio::getInstancia();


        $dataInicioSql = str_replace('/', '-', $_GET['data_inicio']);
        $dataInicioSql = date('Y-m-d', strtotime($dataInicioSql));

        $dataFimSql = str_replace('/', '-', $_GET['data_fim']);
        $dataFimSql = date('Y-m-d', strtotime($dataFimSql));



        $res = $controleRelatorio->getVerificarRelatorioByIdTurma($_GET['id'], $dataInicioSql, $dataFimSql);

        if (count($res) > 0) {
            echo 'sucesso';
        } else {
            echo 'erro';
        }

        break;
    case 'verificarResultadosRelatorioAluno':
        $controleRelatorio = ControleRelatorio::getInstancia();

        $dataInicioSql = str_replace('/', '-', $_GET['data_inicio']);
        $dataInicioSql = date('Y-m-d', strtotime($dataInicioSql));

        $dataFimSql = str_replace('/', '-', $_GET['data_fim']);
        $dataFimSql = date('Y-m-d', strtotime($dataFimSql));


        $res = $controleRelatorio->getVerificarRelatorioByIdAluno($_GET['id'], $_GET['turma'], $dataInicioSql, $dataFimSql);

        if ($res) {
            echo 'sucesso';
        } else {
            echo 'erro';
        }
        break;
    case 'ativarUsuario':

        $id = $_POST['id_usuario'];
        $tipo = $_POST['tipo_usuario'];

        if ($tipo == 2) {
            //Professor
            $controleProfessor = ControleProfessor::getInstancia();

            $res = $controleProfessor->updateProfessor($id, 1);

            if ($res) {
                echo 'sucesso';
            } else {
                echo 'erro';
            }
        } else if ($tipo == 3) {
            //Aluno
            $controleAluno = ControleAluno::getInstancia();

            $res = $controleAluno->updateAluno($id, 1);
            if ($res) {
                echo 'sucesso';
            } else {
                echo 'erro';
            }
        }

        break;
}

    