<?php
$title = 'Editar Categorias em Massa - DbKids';
ob_start();
?>

<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12">
            <h2><i class="fas fa-tags"></i> Editar Categorias em Massa</h2>
            <p class="text-muted">Adicione, remova ou substitua categorias de múltiplos produtos de uma vez</p>
        </div>
    </div>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                    <h5 class="mb-0"><i class="fas fa-edit"></i> Seleção de Produtos e Categorias</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="/admin/produtos/atualizar-categorias-massa" id="bulkForm">
                        
                        <!-- Ação a realizar -->
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <label class="form-label fw-bold"><i class="fas fa-cog"></i> Ação a Realizar</label>
                                <div class="btn-group w-100" role="group">
                                    <input type="radio" class="btn-check" name="action" id="action_add" value="add" checked>
                                    <label class="btn btn-outline-success" for="action_add">
                                        <i class="fas fa-plus-circle"></i> Adicionar Categorias
                                    </label>

                                    <input type="radio" class="btn-check" name="action" id="action_remove" value="remove">
                                    <label class="btn btn-outline-danger" for="action_remove">
                                        <i class="fas fa-minus-circle"></i> Remover Categorias
                                    </label>

                                    <input type="radio" class="btn-check" name="action" id="action_replace" value="replace">
                                    <label class="btn btn-outline-primary" for="action_replace">
                                        <i class="fas fa-sync-alt"></i> Substituir Categorias
                                    </label>
                                </div>
                                <small class="text-muted d-block mt-2">
                                    <strong>Adicionar:</strong> Mantém categorias existentes e adiciona novas | 
                                    <strong>Remover:</strong> Remove apenas as categorias selecionadas | 
                                    <strong>Substituir:</strong> Remove todas e adiciona apenas as selecionadas
                                </small>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Seleção de Produtos -->
                            <div class="col-md-6 mb-4">
                                <label class="form-label fw-bold"><i class="fas fa-box"></i> Selecionar Produtos</label>
                                <div class="mb-2">
                                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="selectAllProducts()">
                                        <i class="fas fa-check-double"></i> Selecionar Todos
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-secondary" onclick="deselectAllProducts()">
                                        <i class="fas fa-times"></i> Desmarcar Todos
                                    </button>
                                    <input type="text" id="searchProducts" class="form-control form-control-sm d-inline-block w-50 ms-2" placeholder="Buscar produtos...">
                                </div>
                                <div style="max-height: 500px; overflow-y: auto; border: 1px solid #ddd; border-radius: 8px; padding: 15px; background: #f8f9fa;">
                                    <?php foreach ($products as $product): ?>
                                        <div class="form-check mb-2 product-item">
                                            <input class="form-check-input product-checkbox" type="checkbox" name="product_ids[]" value="<?php echo $product->id; ?>" id="product_<?php echo $product->id; ?>">
                                            <label class="form-check-label" for="product_<?php echo $product->id; ?>" style="cursor: pointer;">
                                                <strong><?php echo escape($product->name); ?></strong>
                                                <?php if ($product->category_names): ?>
                                                    <br><small class="text-muted">Categorias atuais: <?php echo escape($product->category_names); ?></small>
                                                <?php else: ?>
                                                    <br><small class="text-danger">Sem categorias</small>
                                                <?php endif; ?>
                                            </label>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                                <small class="text-muted d-block mt-2">
                                    <span id="selectedProductsCount">0</span> produto(s) selecionado(s)
                                </small>
                            </div>

                            <!-- Seleção de Categorias -->
                            <div class="col-md-6 mb-4">
                                <label class="form-label fw-bold"><i class="fas fa-tags"></i> Selecionar Categorias</label>
                                <div class="mb-2">
                                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="selectAllCategories()">
                                        <i class="fas fa-check-double"></i> Selecionar Todas
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-secondary" onclick="deselectAllCategories()">
                                        <i class="fas fa-times"></i> Desmarcar Todas
                                    </button>
                                </div>
                                <div style="max-height: 500px; overflow-y: auto; border: 1px solid #ddd; border-radius: 8px; padding: 15px; background: #f8f9fa;">
                                    <?php foreach ($categories as $category): ?>
                                        <div class="form-check mb-3">
                                            <input class="form-check-input category-checkbox" type="checkbox" name="category_ids[]" value="<?php echo $category->id; ?>" id="category_<?php echo $category->id; ?>">
                                            <label class="form-check-label" for="category_<?php echo $category->id; ?>" style="cursor: pointer;">
                                                <strong style="font-size: 16px;"><?php echo escape($category->name); ?></strong>
                                                <?php if ($category->description): ?>
                                                    <br><small class="text-muted"><?php echo escape($category->description); ?></small>
                                                <?php endif; ?>
                                            </label>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                                <small class="text-muted d-block mt-2">
                                    <span id="selectedCategoriesCount">0</span> categoria(s) selecionada(s)
                                </small>
                            </div>
                        </div>

                        <!-- Botões de Ação -->
                        <div class="row mt-4">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fas fa-save"></i> Aplicar Alterações
                                </button>
                                <a href="/admin/produtos" class="btn btn-secondary btn-lg">
                                    <i class="fas fa-arrow-left"></i> Voltar
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Contador de produtos selecionados
    function updateProductCount() {
        const count = document.querySelectorAll('.product-checkbox:checked').length;
        document.getElementById('selectedProductsCount').textContent = count;
    }

    // Contador de categorias selecionadas
    function updateCategoryCount() {
        const count = document.querySelectorAll('.category-checkbox:checked').length;
        document.getElementById('selectedCategoriesCount').textContent = count;
    }

    // Selecionar todos os produtos
    function selectAllProducts() {
        document.querySelectorAll('.product-checkbox').forEach(cb => {
            if (cb.closest('.product-item').style.display !== 'none') {
                cb.checked = true;
            }
        });
        updateProductCount();
    }

    // Desmarcar todos os produtos
    function deselectAllProducts() {
        document.querySelectorAll('.product-checkbox').forEach(cb => cb.checked = false);
        updateProductCount();
    }

    // Selecionar todas as categorias
    function selectAllCategories() {
        document.querySelectorAll('.category-checkbox').forEach(cb => cb.checked = true);
        updateCategoryCount();
    }

    // Desmarcar todas as categorias
    function deselectAllCategories() {
        document.querySelectorAll('.category-checkbox').forEach(cb => cb.checked = false);
        updateCategoryCount();
    }

    // Buscar produtos
    document.getElementById('searchProducts').addEventListener('input', function(e) {
        const search = e.target.value.toLowerCase();
        document.querySelectorAll('.product-item').forEach(item => {
            const text = item.textContent.toLowerCase();
            item.style.display = text.includes(search) ? 'block' : 'none';
        });
    });

    // Atualizar contadores ao marcar/desmarcar
    document.querySelectorAll('.product-checkbox').forEach(cb => {
        cb.addEventListener('change', updateProductCount);
    });

    document.querySelectorAll('.category-checkbox').forEach(cb => {
        cb.addEventListener('change', updateCategoryCount);
    });

    // Validação do formulário
    document.getElementById('bulkForm').addEventListener('submit', function(e) {
        const productsSelected = document.querySelectorAll('.product-checkbox:checked').length;
        const categoriesSelected = document.querySelectorAll('.category-checkbox:checked').length;

        if (productsSelected === 0) {
            e.preventDefault();
            alert('Selecione pelo menos um produto!');
            return false;
        }

        if (categoriesSelected === 0) {
            e.preventDefault();
            alert('Selecione pelo menos uma categoria!');
            return false;
        }

        // Confirmação
        const action = document.querySelector('input[name="action"]:checked').value;
        let actionText = '';
        
        if (action === 'add') {
            actionText = 'adicionar';
        } else if (action === 'remove') {
            actionText = 'remover';
        } else {
            actionText = 'substituir';
        }

        const confirm = window.confirm(
            `Você está prestes a ${actionText} ${categoriesSelected} categoria(s) em ${productsSelected} produto(s).\n\nDeseja continuar?`
        );

        if (!confirm) {
            e.preventDefault();
            return false;
        }
    });
</script>

<?php
$content = ob_get_clean();
include APP_PATH . '/views/layouts/admin.php';
?>
