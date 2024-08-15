function abrirMenu(){
    document.getElementById("menu-Oculto").style.width="400px";
    document.getElementById("principal").style.marginLeft="0px";
}
function fecharMenu(){
    document.getElementById("menu-Oculto").style.width="0vw";
    document.getElementById("principal").style.marginLeft="0vw";
}

function buscarLivro(numeroRegistro) {
    if (!numeroRegistro) return;

    fetch(`buscar_livro.php?numero_registro=${numeroRegistro}`)
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                alert(data.error);
                return;
            }

            // Preencher o formulário com os dados do livro
            document.querySelector('input[placeholder="NOME DO LIVRO"]').value = data.titulo || '';
            document.querySelector('input[placeholder="AUTOR"]').value = data.autor || '';
            document.querySelector('input[placeholder="CDU"]').value = data.cdu || '';
            document.querySelector('input[placeholder="CDD"]').value = data.cdd || '';
            document.querySelector('input[placeholder="ORIGEM"]').value = data.origem || '';
            document.querySelector('input[placeholder="EDITORA"]').value = data.editora || '';
            document.querySelector('input[placeholder="LOCAL"]').value = data.local || '';
            document.querySelector('input[placeholder="GÊNERO"]').value = data.genero || '';
            document.querySelector('input[placeholder="ANO DE AQUISIÇÃO"]').value = data.ano_aquisicao || '';
            document.querySelector('input[placeholder="QUANTIDADE EM ESTOQUE"]').value = data.quantidade_estoque || '';
            // Assumindo que o selo é um booleano
            document.querySelector(`select.selec option[value="${data.selo ? 'SIM' : 'NÃO'}"]`).selected = true;
        })
        .catch(error => console.error('Erro:', error));
}

