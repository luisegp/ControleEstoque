<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Estoque</title>
    <link rel="stylesheet" href="./css/style.css">
    <script>
        // Função para carregar categorias, subcategorias e produtos
        function carregarCategorias() {
            fetch('php/listar_categorias.php')
                .then(response => response.json())
                .then(data => {
                    const categoriaSelects = document.querySelectorAll('#categoria_id');
                    categoriaSelects.forEach(select => {
                        select.innerHTML = '';
                        data.forEach(categoria => {
                            const option = document.createElement('option');
                            option.value = categoria.id;
                            option.textContent = categoria.nome;
                            select.appendChild(option);
                        });
                    });
                    carregarSubcategorias();
                });
        }

        // Função para carregar subcategorias
        function carregarSubcategorias() {
            const categoriaId = document.querySelector('#categoria_id').value;
            fetch(`php/listar_subcategorias.php?categoria_id=${categoriaId}`)
                .then(response => response.json())
                .then(data => {
                    const subcategoriaSelects = document.querySelectorAll('#subcategoria_id');
                    subcategoriaSelects.forEach(select => {
                        select.innerHTML = '';
                        data.forEach(subcategoria => {
                            const option = document.createElement('option');
                            option.value = subcategoria.id;
                            option.textContent = subcategoria.nome;
                            select.appendChild(option);
                        });
                    });
                    carregarProdutos();
                });
        }

        // Função para carregar produtos
        function carregarProdutos() {
            const subcategoriaId = document.querySelector('#subcategoria_id').value;
            fetch(`php/listar_produtos.php?subcategoria_id=${subcategoriaId}`)
                .then(response => response.json())
                .then(data => {
                    const produtoSelect = document.getElementById('produto_id');
                    produtoSelect.innerHTML = '';

                    data.forEach(produto => {
                        const option = document.createElement('option');
                        option.value = produto.id;
                        option.textContent = `${produto.nome} - R$${produto.preco}`;
                        produtoSelect.appendChild(option);
                    });
                });
        }

        // Função para carregar produtos no estoque
        function carregarProdutosEstoque() {
            fetch('php/listar_produtos.php')
                .then(response => response.json())
                .then(data => {
                    const produtosDiv = document.getElementById('produtos');
                    produtosDiv.innerHTML = '';

                    data.forEach(produto => {
                        const produtoDiv = document.createElement('div');
                        produtoDiv.textContent = `Produto: ${produto.nome}, Quantidade: ${produto.quantidade}, Preço: R$${produto.preco} `;

                        // Adiciona um botão de remoção
                        const removerButton = document.createElement('button');
                        removerButton.textContent = 'Remover';
                        removerButton.onclick = () => removerProduto(produto.id);

                        produtoDiv.appendChild(removerButton);
                        produtosDiv.appendChild(produtoDiv);
                    });
                })
                .catch(error => console.error('Erro ao carregar produtos no estoque:', error));
        }

        // Função para remover um produto do estoque
        function removerProduto(produtoId) {
            if (confirm('Tem certeza de que deseja remover este produto?')) {
                fetch('php/remover_produto.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: `produto_id=${encodeURIComponent(produtoId)}`
                })
                .then(response => response.text())
                .then(data => {
                    console.log(data); // Verifique a resposta do servidor
                    if (data.includes('sucesso')) {
                        // Atualize a lista de produtos no estoque
                        carregarProdutosEstoque();
                    } else {
                        alert('Erro ao remover o produto. Por favor, tente novamente.');
                    }
                })
                .catch(error => console.error('Erro ao remover produto:', error));
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            carregarCategorias();
            if (document.getElementById('produto_id')) {
                carregarProdutos();
            }
            if (document.getElementById('produtos')) {
                carregarProdutosEstoque();
            }
        });
    </script>
</head>
<body class="container">

    <header>
        <h1>Gerenciar Estoque</h1>
        <nav class="header_container">
            <ul class="header_content">
                <li><a href="index.html">Home</a></li>
                <li><a href="estoque.php">Gerenciar estoque</a></li>
                <li><a href="comanda.html">Criar comanda</a></li>
            </ul>
        </nav>
    </header>

    <form id="categoriaForm" action="php/adicionar_categoria.php" method="post">
        <label for="nome_categoria">Nome da Categoria:</label>
        <input type="text" id="nome_categoria" name="nome" required>
        <button type="submit">Adicionar Categoria</button>
    </form>

    <form id="subcategoriaForm" action="php/adicionar_subcategoria.php" method="post">
        <label for="nome_subcategoria">Nome da Subcategoria:</label>
        <input type="text" id="nome_subcategoria" name="nome" required>
        <label for="categoria_id">Categoria:</label>
        <select id="categoria_id" name="categoria_id" required>
            <?php include './php/carregar_categorias.php'; ?>
        </select>
        <button type="submit">Adicionar Subcategoria</button>
    </form>

    <form id="estoqueForm" action="php/adicionar_produto.php" method="post">
        <label for="nome">Nome do Produto:</label>
        <input type="text" id="nome" name="nome" required>
        <label for="quantidade">Quantidade:</label>
        <input type="number" id="quantidade" name="quantidade" required>
        <label for="preco">Preço:</label>
        <input type="number" id="preco" name="preco" step="0.01" required>
        <label for="categoria_id_produto">Categoria:</label>
        <select id="categoria_id_produto" name="categoria_id" required>
            <?php include './php/carregar_categorias.php'; ?>
        </select>
        <button type="submit">Adicionar Produto</button>
    </form>

    <h2>Produtos no Estoque</h2>
    <div id="produtos">
        <!-- Lista de produtos será carregada aqui -->
    </div>

</body>
</html>
