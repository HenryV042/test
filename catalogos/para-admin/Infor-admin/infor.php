<?php
require_once '../dependencies/config.php';

// Recuperar o título do livro da URL
$titulo = isset($_GET['titulo_livro']) ? $_GET['titulo_livro'] : '';
echo $titulo;
$matricula = 3528107;

// Função para buscar a URL da capa do livro usando a Google Books API com cURL
function getBookCover($titulo)
{
    $titulo = urlencode($titulo);
    $url = "https://www.googleapis.com/books/v1/volumes?q=intitle:" . $titulo;

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        curl_close($ch);
        return '../img/sem-foto.png'; // Retorna uma imagem padrão em caso de erro
    }

    curl_close($ch);

    $data = json_decode($response, true);

    if (isset($data['items'][0]['volumeInfo']['imageLinks']['thumbnail'])) {
        return $data['items'][0]['volumeInfo']['imageLinks']['thumbnail'];
    }

    return '../img/sem-foto.png'; // Retorna uma imagem padrão se não encontrar a capa
}

// Buscar a capa do livro
$imagem = getBookCover($titulo);

// Buscar números de registro disponíveis
try {
    $sql = "SELECT DISTINCT numero_registro FROM livros";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $numeros_registro = $stmt->fetchAll(PDO::FETCH_COLUMN);
} catch (PDOException $e) {
    echo "Erro na consulta: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Biblioteca</title>
    <link rel="stylesheet" href="Css/style.css">
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body>
    <header>
        <nav>
            <aside id="menu-Oculto" class="menu-Oculto">
                <div class="imagemMenu">
                    <img src="<?php echo $imagem; ?>" alt="Capa do Livro" class="logoMenu">
                    <button class="fechar" href="" onclick="fecharMenu()"><i class="fa-solid fa-circle-arrow-left"></i></button>
                </div>

                <div class="linha"></div>
                <div class="opcoes">
                    <a href=""><i class="fa-solid fa-file"></i> Cadastrar Livro</a>
                    <a href=""><i class="fa-solid fa-book-open-reader"></i> Cadastrar Empréstimo</a>
                    <a href=""><i class="fa-solid fa-book-bookmark"></i> Banco de Livros</a>
                    <a href=""><i class="fa-brands fa-leanpub"></i> Empréstimos</a>
                    <a href=""><i class="fa-solid fa-user-plus"></i> Adicionar Turma</a>
                    <a href=""><i class="fa-solid fa-address-book"></i> Pedidos</a>
                    <a href=""><i class="fa-solid fa-file-import"></i> Relatório</a>
                    <a href="" class="sair"><i class="fa-solid fa-circle-xmark"></i> Sair</a>
                </div>
            </aside>
            <section id="principal">
                <span style="font-size:30px;cursor:pointer" onclick="abrirMenu()">&#9776;</span>
                <div class="nav-logo">
                    <img src="img/logoEEEP.png" alt="logo" class="logo_eeep" />
                    <div class="ret"></div>
                    <img src="img/logoNav.png" alt="logo" class="library" />
                </div>
            </section>
        </nav>
    </header>
    <div class="container">
        <div class="img-livro">
            <img src="img/pequenoprincipe.jpg" alt="Livro">
        </div>
        <div class="formulario">
            <form>
                <h2>MAIS INFORMAÇÕES</h2>
                <label for="">NOME DO LIVRO</label>
                <input type="text" placeholder="NOME DO LIVRO" required>
                <label for="">AUTOR</label>
                <input type="text" placeholder="AUTOR" required>
                <div class="input-group">
                    <input type="text" placeholder="CDU">
                    <input type="text" placeholder="CDD">
                </div>
                <div class="input-group">
                    <input type="text" placeholder="ORIGEM">
                    <input type="text" placeholder="EDITORA">
                </div>
                <div class="input-group">
                    <input type="text" placeholder="LOCAL">
                    <input type="text" placeholder="GÊNERO">
                </div>
                <div class="input-group">
                    <input type="date" placeholder="ANO DE AQUISIÇÃO">
                    <select id="numero_registro" onchange="buscarLivro(this.value)">
                        <option value="">NÚMERO DE REGISTRO</option>
                        <?php foreach ($numeros_registro as $registro): ?>
                            <option value="<?php echo htmlspecialchars($registro); ?>"><?php echo htmlspecialchars($registro); ?></option>
                        <?php endforeach; ?>
                    </select>

                </div>
                <div class="input-group">
                    <input type="text" placeholder="QUANTIDADE EM ESTOQUE" required>
                    <select name="" id="" class="selec">
                        <option value="">SELO</option>
                        <option value="">SIM</option>
                        <option value="">NÃO</option>
                    </select>
                </div>
                <div class="button-group">
                    <button type="submit" class="btn-enviar"><i class="fa-solid fa-check"></i> Enviar</button>
                    <button type="button" class="btn-voltar"><i class="fa-solid fa-arrow-left"></i> Voltar</button>
                </div>
            </form>
        </div>
    </div>
</body>
<script type="text/javascript" src="scripts.js"></script>

</html>