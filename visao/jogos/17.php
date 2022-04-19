<!-- Rachacuca -->
<div id="areaJogoRachacuca">

    <style>
        #areaJogoRachacuca .navios {
            float:right;
        }

        #areaJogoRachacuca .casa {
            background-color: #eeeeee;
            border: 1px solid #ccc;
            padding: 6px;
        }

        #areaJogoRachacuca #descricao {
            float:left;
        }
        #areaJogoRachacuca > #descricao .list-group-item {
            border: 0 !important;
            padding: 8px 25px !important;
            margin-bottom: 0 !important;

        }

        #areaJogoRachacuca .navios {
            margin-right: 50px;
        } 

        #areaJogoRachacuca  #listaDicas > li {
            cursor: pointer;
        }

    


    </style>

    <script>
        var dicasSublinhadas = new Array();
        $("#listaDicas li").click(function () {
            var index = dicasSublinhadas.indexOf(this);
            if (index < 0) {
                $(this).css("text-decoration", "line-through");
                dicasSublinhadas.push(this);
            } else {
                $(this).css("text-decoration", "none");
                dicasSublinhadas.splice(index, 1);
            }
        });

        function mudaCor(id, v) {
            id = id.substring(id.length - 1, id.length)
            switch (v) {
                case 'azul':
                    $('#casa' + id).css('background-color', '#0099FF');
                    break;
                case 'branca':
                    $('#casa' + id).css('background-color', '#FFFFFF');
                    break;
                case 'verde':
                    $('#casa' + id).css('background-color', '#009933');
                    break;
                case 'vermelha':
                    $('#casa' + id).css('background-color', '#FF3333');
                    break;
                case 'preta':
                    $('#casa' + id).css('background-color', '#000000');
                    break;
                default :
                    $('#casa' + id).css('background-color', '#EEEEEE');
                    break;
            }

        }
    </script>


    <ul id="descricao" class="list-group">
        <div style="margin-left:10px">Descrição:</div>
        <li class="list-group-item">Nacionalidade</li>
        <li class="list-group-item">Saída</li>
        <li class="list-group-item">Carregamento</li>
        <li class="list-group-item">Chaminé</li>
        <li class="list-group-item">Destino</li>
    </ul>

    <?php
    $i = 4;
    while ($i > 0) {
        $navio = $i;
        ?>

        <div id="navio<?php echo $navio ?>" class="navios navios col-md-2">
            <span>Navio <?php echo $navio ?></span>
            <div class="casa casa-azul" id="casa<?php echo $navio ?>">

                <select id="selectnacionalidade<?php echo $navio ?>" name="selectnacionalidade<?php echo $navio ?>" class="logicselect form-control">
                    <option value="-1"></option>
                    <option value="brasileiro">Brasileiro</option>
                    <option value="espanhol">Espanhol</option>
                    <option value="frances">Francês</option>
                    <option value="grego">Grego</option>
                    <option value="ingles">Inglês</option>
                </select>


                <select id="selecthorario<?php echo $navio ?>" name="selecthorario<?php echo $navio ?>" class="logicselect form-control">
                    <option value="-1"></option>
                    <option value="h5am">05:00</option>
                    <option value="h6am">06:00</option>
                    <option value="h7am">07:00</option>
                    <option value="h8am">08:00</option>
                    <option value="h9am">09:00</option>
                </select>

                <select id="selectcarregamento<?php echo $navio ?>" name="selectcarregamento<?php echo $navio ?>" class="logicselect form-control">
                    <option value="-1"></option>
                    <option value="arroz">Arroz</option>
                    <option value="cacau">Cacau</option>
                    <option value="cafe">Café</option>
                    <option value="cha">Chá</option>
                    <option value="milho">Milho</option>
                </select>

                <select id="selectcor<?php echo $navio ?>" name="selectcor<?php echo $navio ?>" class="logicselect form-control" onchange="mudaCor(this.id, this.value)">
                    <option value="-1"></option>
                    <option value="azul">Azul</option>
                    <option value="branca">Branca</option>
                    <option value="verde">Verde</option>
                    <option value="vermelha">Vermelha</option>
                    <option value="preta">Preta</option>
                </select>

                <select id="selectcidade<?php echo $navio ?>" name="selectcidade<?php echo $navio ?>" class="logicselect form-control">
                    <option value="-1"></option>
                    <option value="hamburgo">Hamburgo</option>
                    <option value="macau">Macau</option>
                    <option value="manila">Manila</option>
                    <option value="santos">Santos</option>
                    <option value="rotterdam">Rotterdam</option>
                </select>
            </div>

        </div>
        <?php
        $i--;
    }
    ?> 
    <br style="clear:both" />
    <div>

        <p class="observacao">* Clique na dica que você já resolveu para rabiscá-la. </p>

        <ul id="listaDicas">
            <li>O navio Grego sai às 6 da manhã e carrega café.</li>
            <li>O navio do meio tem a chaminé preta.</li>
            <li>O navio Inglês sai às 9 da manhã.</li>
            <li>O navio Francês, que tem a chaminé azul, está a esquerda do navio que carrega café.</li>
            <li>À direita do navio que carrega Cacau está o navio que vai para Macau.</li>
            <li>O navio Brasileiro está indo para Manila.</li>
            <li>O navio que carrega Arroz está ancorado ao lado do navio com chaminé Verde.</li>
            <li>O navio que vai para Santos sai às 5 da manhã.</li>

            <li>O navio Espanhol sai às 7 da manhã e está à direita do navio que vai para Macau.</li>
            <li>O navio com a chaminé vermelha vai para Hamburgo.</li>
            <li>O navio que sai às 7 da manhã está ao lado do navio que tem a chaminé branca.v
            <li>O navio do canto carrega milho.</li>
            <li>O navio com chaminé preta sai às 8 da manhã.</li>
            <li>O navio que que carrega milho está ancorado ao lado do navio que carrega arroz.</li>
            <li>O navio que vai para Hamburgo sai às 6 da manhã.</li>
        </ul>
    </div>
   

</div>