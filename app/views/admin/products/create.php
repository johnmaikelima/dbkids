<?php
$title = 'Novo Produto';
ob_start();
?>

<div class="mb-4">
    <h3>Novo Produto</h3>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="/admin/produtos/salvar" enctype="multipart/form-data">
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="name" class="form-label">Nome do Produto *</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="category_ids" class="form-label">Categorias (Selecione uma ou mais) *</label>
                        <select class="form-control" id="category_ids" name="category_ids[]" multiple size="5" required>
                            <?php foreach ($categories ?? [] as $category): ?>
                                <option value="<?php echo $category->id; ?>">
                                    <?php echo escape($category->name); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <small class="text-muted d-block mt-2">Mantenha Ctrl (Cmd no Mac) pressionado para selecionar múltiplas categorias</small>
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Descrição</label>
                <textarea class="form-control" id="description" name="description" rows="4"></textarea>
            </div>

            <div class="row">
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="price" class="form-label">Preço *</label>
                        <input type="number" class="form-control" id="price" name="price" step="0.01" required>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="stock" class="form-label">Estoque</label>
                        <input type="number" class="form-control" id="stock" name="stock" value="0">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="weight" class="form-label">Peso (kg)</label>
                        <input type="number" class="form-control" id="weight" name="weight" step="0.01">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="length" class="form-label">Comprimento (cm)</label>
                        <input type="number" class="form-control" id="length" name="length" step="0.01">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="width" class="form-label">Largura (cm)</label>
                        <input type="number" class="form-control" id="width" name="width" step="0.01">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="height" class="form-label">Altura (cm)</label>
                        <input type="number" class="form-control" id="height" name="height" step="0.01">
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label for="images" class="form-label">Fotos do Produto</label>
                <input type="file" class="form-control" id="images" name="images[]" multiple accept="image/*" onchange="previewImages(event)">
                <small class="text-muted">Selecione uma ou mais imagens. A primeira será a capa.</small>
                
                <div id="imagePreview" class="mt-3">
                    <!-- Pré-visualização das imagens -->
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Características</label>
                <div id="attributes">
                    <div class="row mb-2">
                        <div class="col-md-5">
                            <input type="text" class="form-control" name="attributes[0][name]" placeholder="Nome da característica">
                        </div>
                        <div class="col-md-5">
                            <input type="text" class="form-control" name="attributes[0][value]" placeholder="Valor">
                        </div>
                        <div class="col-md-2">
                            <button type="button" class="btn btn-danger" onclick="removeAttribute(this)">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <button type="button" class="btn btn-secondary btn-sm" onclick="addAttribute()">
                    <i class="fas fa-plus"></i> Adicionar Característica
                </button>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-save"></i> Salvar Produto
                </button>
                <a href="/admin/produtos" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>

<!-- Modal para Nova Categoria -->
<div class="modal fade" id="newCategoryModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Nova Categoria</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="newCategoryForm">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="newCategoryName" class="form-label">Nome da Categoria *</label>
                        <input type="text" class="form-control" id="newCategoryName" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="newCategoryDesc" class="form-label">Descrição</label>
                        <textarea class="form-control" id="newCategoryDesc" name="description" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" onclick="createCategoryInline()">Criar Categoria</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
let attrCount = 1;

function addAttribute() {
    const html = `
        <div class="row mb-2">
            <div class="col-md-5">
                <input type="text" class="form-control" name="attributes[${attrCount}][name]" placeholder="Nome da característica">
            </div>
            <div class="col-md-5">
                <input type="text" class="form-control" name="attributes[${attrCount}][value]" placeholder="Valor">
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-danger" onclick="removeAttribute(this)">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </div>
    `;
    document.getElementById('attributes').insertAdjacentHTML('beforeend', html);
    attrCount++;
}

function removeAttribute(btn) {
    btn.closest('.row').remove();
}

function createCategoryInline() {
    const name = document.getElementById('newCategoryName').value;
    const description = document.getElementById('newCategoryDesc').value;

    if (!name.trim()) {
        alert('Nome da categoria é obrigatório');
        return;
    }

    fetch('/admin/categorias/salvar', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'name=' + encodeURIComponent(name) + '&description=' + encodeURIComponent(description)
    })
    .then(response => {
        if (response.ok) {
            // Recarregar página para atualizar categorias
            location.reload();
        } else {
            alert('Erro ao criar categoria');
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        alert('Erro ao criar categoria');
    });
}

function previewImages(event) {
    const files = event.target.files;
    const preview = document.getElementById('imagePreview');
    preview.innerHTML = '';

    if (files.length === 0) {
        return;
    }

    const html = `<div class="alert alert-info">
        <strong>Imagens selecionadas:</strong> ${files.length}
        <br><small>A primeira imagem será a capa do produto</small>
    </div>
    <div class="row">`;

    let previewHtml = html;

    for (let i = 0; i < files.length; i++) {
        const file = files[i];
        const reader = new FileReader();

        reader.onload = function(e) {
            const badge = i === 0 ? '<span class="badge bg-primary position-absolute top-0 start-0 m-2">CAPA</span>' : '';
            const imageHtml = `
                <div class="col-md-3 mb-3">
                    <div class="position-relative">
                        <img src="${e.target.result}" class="img-fluid rounded" alt="Preview">
                        ${badge}
                    </div>
                    <small class="text-muted">${file.name}</small>
                </div>
            `;
            preview.insertAdjacentHTML('beforeend', imageHtml);
        };

        reader.readAsDataURL(file);
    }

    preview.insertAdjacentHTML('beforeend', previewHtml);
}
</script>

<?php
$content = ob_get_clean();
include APP_PATH . '/views/layouts/admin.php';
?>
