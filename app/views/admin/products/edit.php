<?php
$title = 'Editar Produto';
ob_start();
?>

<div class="mb-4">
    <h3>Editar Produto</h3>
</div>

<div class="accordion mb-4" id="productAccordion">
    <!-- Informações Básicas -->
    <div class="accordion-item">
        <h2 class="accordion-header">
            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#basicInfo">
                <i class="fas fa-info-circle me-2"></i> Informações Básicas
            </button>
        </h2>
        <div id="basicInfo" class="accordion-collapse collapse show" data-bs-parent="#productAccordion">
            <div class="accordion-body">
                <form method="POST" action="/admin/produtos/atualizar" enctype="multipart/form-data" id="editProductForm">
                    <input type="hidden" name="id" value="<?php echo $product->id; ?>">

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name" class="form-label">Nome do Produto</label>
                                <input type="text" class="form-control" id="name" name="name" value="<?php echo escape($product->name); ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="category_ids" class="form-label">Categorias (Selecione uma ou mais)</label>
                                <div class="input-group mb-2">
                                    <select class="form-control" id="category_ids" name="category_ids[]" multiple size="5" required>
                                        <?php 
                                        $productCategories = [];
                                        if (isset($product->id)) {
                                            $productModel = new \Product();
                                            $cats = $productModel->getCategories($product->id);
                                            foreach ($cats as $cat) {
                                                $productCategories[] = $cat->id;
                                            }
                                        }
                                        foreach ($categories ?? [] as $category): 
                                        ?>
                                            <option value="<?php echo $category->id; ?>" <?php echo in_array($category->id, $productCategories) ? 'selected' : ''; ?>>
                                                <?php echo escape($category->name); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <button class="btn btn-outline-primary" type="button" data-bs-toggle="modal" data-bs-target="#newCategoryModal">
                                        <i class="fas fa-plus"></i> Nova
                                    </button>
                                </div>
                                <small class="text-muted d-block">Mantenha Ctrl (Cmd no Mac) pressionado para selecionar múltiplas categorias</small>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Descrição</label>
                        <textarea class="form-control" id="description" name="description" rows="4"><?php echo escape($product->description); ?></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="price" class="form-label">Preço</label>
                                <input type="number" class="form-control" id="price" name="price" step="0.01" value="<?php echo $product->price; ?>">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="stock" class="form-label">Estoque</label>
                                <input type="number" class="form-control" id="stock" name="stock" value="<?php echo $product->stock; ?>">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="weight" class="form-label">Peso (kg)</label>
                                <input type="number" class="form-control" id="weight" name="weight" step="0.01" value="<?php echo $product->weight; ?>">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="length" class="form-label">Comprimento (cm)</label>
                                <input type="number" class="form-control" id="length" name="length" step="0.01" value="<?php echo $product->length; ?>">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="width" class="form-label">Largura (cm)</label>
                                <input type="number" class="form-control" id="width" name="width" step="0.01" value="<?php echo $product->width; ?>">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="height" class="form-label">Altura (cm)</label>
                                <input type="number" class="form-control" id="height" name="height" step="0.01" value="<?php echo $product->height; ?>">
                            </div>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>

    <!-- Imagens -->
    <div class="accordion-item">
        <h2 class="accordion-header">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#imagesSection">
                <i class="fas fa-images me-2"></i> Imagens do Produto
            </button>
        </h2>
        <div id="imagesSection" class="accordion-collapse collapse" data-bs-parent="#productAccordion">
            <div class="accordion-body">
                <div class="mb-4">
                    <label for="images" class="form-label">Adicionar Novas Imagens</label>
                    <form method="POST" action="/admin/produtos/atualizar" enctype="multipart/form-data" id="uploadImagesForm">
                        <input type="hidden" name="id" value="<?php echo $product->id; ?>">
                        <div class="input-group">
                            <input type="file" class="form-control" id="images" name="images[]" multiple accept="image/*">
                            <button class="btn btn-primary" type="submit">
                                <i class="fas fa-upload"></i> Enviar Imagens
                            </button>
                        </div>
                    </form>
                    <small class="text-muted d-block mt-1">Selecione uma ou mais imagens (JPG, PNG, WEBP, AVIF)</small>
                </div>

                <?php if (!empty($images)): ?>
                <h6 class="mb-3">Imagens Atuais</h6>
                <div class="row">
                    <?php foreach ($images as $image): ?>
                        <div class="col-md-2 mb-3">
                            <div class="card">
                                <img src="<?php echo escape($image->image_path); ?>" class="card-img-top" style="height: 120px; object-fit: cover;">
                                <div class="card-body p-2">
                                    <?php if ($image->is_main): ?>
                                        <span class="badge bg-primary w-100 mb-1">CAPA</span>
                                    <?php else: ?>
                                        <form method="POST" action="/admin/produtos/definir-capa" class="mb-1">
                                            <input type="hidden" name="image_id" value="<?php echo $image->id; ?>">
                                            <input type="hidden" name="product_id" value="<?php echo $product->id; ?>">
                                            <button type="submit" class="btn btn-sm btn-outline-primary w-100">Definir Capa</button>
                                        </form>
                                    <?php endif; ?>
                                    <form method="POST" action="/admin/produtos/deletar-imagem" onsubmit="return confirm('Deletar esta imagem?')">
                                        <input type="hidden" name="image_id" value="<?php echo $image->id; ?>">
                                        <input type="hidden" name="product_id" value="<?php echo $product->id; ?>">
                                        <button type="submit" class="btn btn-sm btn-danger w-100">Deletar</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Variações -->
    <div class="accordion-item">
        <h2 class="accordion-header">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#variationsSection">
                <i class="fas fa-layer-group me-2"></i> Variações do Produto
            </button>
        </h2>
        <div id="variationsSection" class="accordion-collapse collapse" data-bs-parent="#productAccordion">
            <div class="accordion-body">
                <p class="text-muted mb-4">Crie tipos de variações (Tamanho, Cor, etc.) e adicione opções com estoque e preço específicos.</p>

                <!-- Criar Tipo de Variação -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="fas fa-plus"></i> Criar Tipo de Variação</h6>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="/admin/variacoes-tipos/criar">
                            <input type="hidden" name="product_id" value="<?php echo $product->id; ?>">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Nome da Variação</label>
                                        <input type="text" class="form-control" name="name" placeholder="Ex: Tamanho, Cor, Material" required>
                                        <small class="text-muted">Exemplos: Tamanho, Cor, Material, Marca</small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">&nbsp;</label>
                                        <button type="submit" class="btn btn-primary w-100">
                                            <i class="fas fa-plus"></i> Criar Tipo
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Gerenciar Tipos de Variações -->
                <?php if (!empty($variationTypes)): ?>
                    <?php foreach ($variationTypes as $type): ?>
                        <?php 
                        $variationType = new VariationType();
                        $options = $variationType->getOptions($type->id);
                        ?>
                        <div class="card mb-4">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h6 class="mb-0"><?php echo escape($type->name); ?></h6>
                                <form method="POST" action="/admin/variacoes-tipos/deletar" style="display: inline;">
                                    <input type="hidden" name="product_id" value="<?php echo $product->id; ?>">
                                    <input type="hidden" name="variation_type_id" value="<?php echo $type->id; ?>">
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Deletar este tipo? Todas as opções serão deletadas.')">
                                        <i class="fas fa-trash"></i> Deletar Tipo
                                    </button>
                                </form>
                            </div>
                            <div class="card-body">
                                <!-- Adicionar Opção -->
                                <div class="mb-4">
                                    <h6>Adicionar Opção</h6>
                                    <form method="POST" action="/admin/variacoes-tipos/adicionar-opcao">
                                        <input type="hidden" name="product_id" value="<?php echo $product->id; ?>">
                                        <input type="hidden" name="variation_type_id" value="<?php echo $type->id; ?>">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <input type="text" class="form-control" name="value" placeholder="Ex: P, M, G ou Preto, Vermelho" required>
                                                <small class="text-muted d-block mt-1">SKU será gerado automaticamente</small>
                                            </div>
                                            <div class="col-md-3">
                                                <input type="number" class="form-control" name="price" step="0.01" placeholder="Preço" value="<?php echo $product->price; ?>" required>
                                            </div>
                                            <div class="col-md-3">
                                                <input type="number" class="form-control" name="stock" placeholder="Estoque" value="0" required>
                                            </div>
                                            <div class="col-md-2">
                                                <button type="submit" class="btn btn-success w-100">
                                                    <i class="fas fa-plus"></i> Adicionar
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>

                                <!-- Listar Opções -->
                                <?php if (!empty($options)): ?>
                                    <h6>Opções Cadastradas</h6>
                                    <div class="table-responsive">
                                        <table class="table table-sm table-hover">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Valor</th>
                                                    <th>SKU</th>
                                                    <th>Preço</th>
                                                    <th>Estoque</th>
                                                    <th style="width: 60px;">Ações</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($options as $option): ?>
                                                <tr>
                                                    <td><strong><?php echo escape($option->value); ?></strong></td>
                                                    <td><code><?php echo escape($option->sku ?? '-'); ?></code></td>
                                                    <td>R$ <?php echo number_format($option->price, 2, ',', '.'); ?></td>
                                                    <td>
                                                        <span class="badge <?php echo $option->stock > 0 ? 'bg-success' : 'bg-danger'; ?>">
                                                            <?php echo $option->stock; ?>
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <form method="POST" action="/admin/variacoes-tipos/deletar-opcao" style="display: inline;">
                                                            <input type="hidden" name="product_id" value="<?php echo $product->id; ?>">
                                                            <input type="hidden" name="option_id" value="<?php echo $option->id; ?>">
                                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Deletar?')">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    </td>
                                                </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                <?php else: ?>
                                    <div class="alert alert-info mb-0">
                                        Nenhuma opção cadastrada para este tipo.
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> Nenhum tipo de variação cadastrado ainda.
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Botões de Ação -->
<div class="d-flex gap-2 mt-4 mb-4">
    <button type="submit" class="btn btn-success btn-lg" form="editProductForm">
        <i class="fas fa-save"></i> Salvar Alterações
    </button>
    <a href="/admin/produtos" class="btn btn-secondary btn-lg">
        <i class="fas fa-times"></i> Cancelar
    </a>
</div>

<!-- Modal para Nova Categoria -->
<div class="modal fade" id="newCategoryModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-plus-circle"></i> Nova Categoria</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="/admin/categorias/salvar" id="newCategoryForm">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="category_name" class="form-label">Nome da Categoria *</label>
                        <input type="text" class="form-control" id="category_name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="category_description" class="form-label">Descrição</label>
                        <textarea class="form-control" id="category_description" name="description" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Criar Categoria
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Debug: verificar categorias selecionadas antes de enviar
document.getElementById('editProductForm').addEventListener('submit', function(e) {
    const categorySelect = document.getElementById('category_ids');
    const selectedOptions = Array.from(categorySelect.selectedOptions);
    const selectedValues = selectedOptions.map(opt => opt.value);
    
    console.log('Categorias selecionadas:', selectedValues);
    console.log('Quantidade de categorias:', selectedValues.length);
    
    if (selectedValues.length === 0) {
        e.preventDefault();
        alert('Por favor, selecione pelo menos uma categoria!');
        return false;
    }
});

// Recarregar categorias após criar uma nova
document.getElementById('newCategoryForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    fetch('/admin/categorias/salvar', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Fechar modal
            const modalElement = document.getElementById('newCategoryModal');
            const modal = bootstrap.Modal.getInstance(modalElement);
            if (modal) {
                modal.hide();
            }
            
            // Adicionar nova categoria ao select
            const select = document.getElementById('category_ids');
            const option = document.createElement('option');
            option.value = data.id;
            option.textContent = data.name;
            option.selected = true;
            select.appendChild(option);
            
            // Limpar formulário
            document.getElementById('category_name').value = '';
            document.getElementById('category_description').value = '';
            
            // Mostrar mensagem de sucesso
            alert('Categoria criada com sucesso!');
        } else {
            alert('Erro ao criar categoria: ' + (data.message || 'Erro desconhecido'));
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        alert('Erro ao criar categoria');
    });
});
</script>

<?php
$content = ob_get_clean();
include APP_PATH . '/views/layouts/admin.php';
?>
