// Função para carregar categorias no formulário de estoque e comanda
function carregarCategorias() {
    fetch('php/listar_categorias.php')
        .then(response => response.json())
        .then(data => {
            const categoriaSelects = document.querySelectorAll('#categoria_id, #categoria_id_produto');
            categoriaSelects.forEach(select => {
                select.innerHTML = '<option value="" disabled selected>Selecione</option>';
                data.forEach(categoria => {
                    const option = document.createElement('option');
                    option.value = categoria.id;
                    option.textContent = categoria.nome;
                    select.appendChild(option);
                });
            });
        })
        .catch(error => console.error('Erro ao carregar categorias:', error));
}

// Função para carregar subcategorias com base na categoria selecionada
function carregarSubcategorias(categoriaId) {
    const subcategoriaSelect = document.getElementById('subcategoria_id');
    subcategoriaSelect.innerHTML = '<option value="" disabled selected>Selecione</option>';

    if (categoriaId) {
        fetch(`php/listar_subcategorias.php?categoria_id=${categoriaId}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(subcategorias => {
                console.log('Subcategorias recebidas:', subcategorias); // Verifique as subcategorias recebidas
                subcategorias.forEach(subcategoria => {
                    const option = document.createElement('option');
                    option.value = subcategoria.id;
                    option.textContent = subcategoria.nome;
                    subcategoriaSelect.appendChild(option);
                });
            })
            .catch(error => console.error('Erro ao carregar subcategorias:', error));
    }
}

// Função para carregar os produtos no formulário de comandas
function carregarProdutos() {
    const subcategoriaId = document.querySelector('#subcategoria_id').value;
    if (subcategoriaId) {
        fetch(`php/listar_produtos.php?subcategoria_id=${subcategoriaId}`)
            .then(response => response.json())
            .then(data => {
                const produtoSelect = document.getElementById('produto_id');
                produtoSelect.innerHTML = '<option value="" disabled selected>Selecione</option>';
                data.forEach(produto => {
                    const option = document.createElement('option');
                    option.value = produto.id;
                    option.textContent = `${produto.nome} - R$${produto.preco}`;
                    produtoSelect.appendChild(option);
                });
            })
            .catch(error => console.error('Erro ao carregar produtos:', error));
    }
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
        })
        .catch(error => console.error('Erro ao carregar produtos no estoque:', error));
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
        })
        .catch(error => console.error('Erro ao finalizar comanda:', error));
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
        })
        .catch(error => console.error('Erro ao carregar comandas:', error));
}

document.addEventListener('DOMContentLoaded', () => {
    carregarCategorias();
    
    const categoriaSelect = document.getElementById('categoria_id_produto');
    if (categoriaSelect) {
        categoriaSelect.addEventListener('change', event => {
            carregarSubcategorias(event.target.value);
        });
    }

    const subcategoriaSelect = document.getElementById('subcategoria_id');
    if (subcategoriaSelect) {
        subcategoriaSelect.addEventListener('change', () => {
            carregarProdutos();
        });
    }
    
    if (document.getElementById('produto_id')) {
        carregarProdutos();
    }
    if (document.getElementById('produtos')) {
        carregarProdutosEstoque();
    }
});
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

// Carregar produtos ao carregar a página
document.addEventListener('DOMContentLoaded', () => {
    carregarProdutosEstoque();
});

