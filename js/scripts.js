// Função para carregar categorias no formulário de estoque e comanda
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

// Função para carregar subcategorias no formulário de estoque e comanda
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

// Função para carregar os produtos no formulário de comandas
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

// Função para carregar os produtos no estoque
function carregarProdutosEstoque() {
    fetch('php/listar_produtos.php')
        .then(response => response.json())
        .then(data => {
            const produtosDiv = document.getElementById('produtos');
            produtosDiv.innerHTML = '';

            data.forEach(produto => {
                const produtoDiv = document.createElement('div');
                produtoDiv.textContent = `Produto: ${produto.nome}, Quantidade: ${produto.quantidade}, Preço: R$${produto.preco}`;
                produtosDiv.appendChild(produtoDiv);
            });
        });
}

let comandaItens = [];

// Função para adicionar produto à comanda
function adicionarProduto() {
    const produtoSelect = document.getElementById('produto_id');
    const quantidadeInput = document.getElementById('quantidade');
    const produtoId = produtoSelect.value;
    const produtoNome = produtoSelect.options[produtoSelect.selectedIndex].text;
    const quantidade = quantidadeInput.value;

    const produto = {
        id: produtoId,
        nome: produtoNome,
        quantidade: quantidade
    };

    comandaItens.push(produto);
    exibirComandaItens();
}

// Função para exibir os itens da comanda
function exibirComandaItens() {
    const comandaItensDiv = document.getElementById('comandaItens');
    comandaItensDiv.innerHTML = '';

    comandaItens.forEach((item, index) => {
        const itemDiv = document.createElement('div');
        itemDiv.textContent = `Produto: ${item.nome}, Quantidade: ${item.quantidade}`;
        comandaItensDiv.appendChild(itemDiv);
    });
}

// Função para finalizar a comanda
function finalizarComanda() {
    comandaItens.forEach(item => {
        fetch('php/criar_comanda.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: `produto_id=${item.id}&quantidade=${item.quantidade}`
        })
        .then(response => response.text())
        .then(data => {
            console.log(data);
            carregarComandas();
        });
    });

    comandaItens = [];
    exibirComandaItens();
}

// Função para carregar as comandas criadas
function carregarComandas() {
    fetch('php/listar_comandas.php')
        .then(response => response.json())
        .then(data => {
            const comandasDiv = document.getElementById('comandas');
            comandasDiv.innerHTML = '';

            data.forEach(comanda => {
                const comandaDiv = document.createElement('div');
                comandaDiv.textContent = `Produto: ${comanda.nome}, Quantidade: ${comanda.quantidade}, Total: R$${comanda.preco_total}, Senha: ${comanda.senha_comanda}, Data: ${comanda.data}`;
                comandasDiv.appendChild(comandaDiv);
            });
        });
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
