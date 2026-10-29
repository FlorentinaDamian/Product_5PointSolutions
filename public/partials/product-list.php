<?php
?>
<div class="list-state" data-page="<?= (int) $page ?>" data-search="<?= e($search) ?>" hidden></div>
<div class="table-wrap">
<table>
    <thead>
        <tr>
            <th>Name</th>
            <th>Description</th>
            <th>Price</th>
            <th>Image</th>
            <th>Availability date</th>
            <th>Stock status</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
    <?php if (!$products): ?>
        <tr class="empty-row"><td colspan="7" class="empty">No products found.</td></tr>
    <?php endif; ?>
    <?php foreach ($products as $p): ?>
        <tr>
            <td data-label="Name"><?= e($p['name']) ?></td>
            <td data-label="Description"><?= e($p['description']) ?></td>
            <td data-label="Price"><?= number_format((float) $p['price'], 2) ?></td>
            <td data-label="Image"><?php if ($p['image']): ?><img class="thumb" src="uploads/<?= e($p['image']) ?>" alt="<?= e($p['name']) ?>"><?php endif; ?></td>
            <td data-label="Available from"><?= e($p['availability_date']) ?></td>
            <td data-label="Stock"><span class="badge <?= $p['in_stock'] ? 'in' : 'out' ?>"><?= $p['in_stock'] ? 'In stock' : 'Out of stock' ?></span></td>
            <td class="actions" data-label="Actions">
                <a class="btn" href="form.php?id=<?= (int) $p['id'] ?>">Edit</a>
                <button type="button" class="danger js-delete"
                        data-id="<?= (int) $p['id'] ?>" data-name="<?= e($p['name']) ?>">Delete</button>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
</div>
<?php if ($pages > 1): ?>
<nav class="pagination" aria-label="Pagination">
    <?php if ($page > 1): ?><a href="<?= e(pageUrl($page - 1, $search)) ?>">&laquo; Previous</a><?php endif; ?>
    <?php for ($i = 1; $i <= $pages; $i++): ?>
        <?php if ($i === 1 || $i === $pages || abs($i - $page) <= 2): ?>
            <a href="<?= e(pageUrl($i, $search)) ?>" class="<?= $i === $page ? 'active' : '' ?>"><?= $i ?></a>
        <?php elseif (abs($i - $page) === 3): ?><span>&hellip;</span>
        <?php endif; ?>
    <?php endfor; ?>
    <?php if ($page < $pages): ?><a href="<?= e(pageUrl($page + 1, $search)) ?>">Next &raquo;</a><?php endif; ?>
</nav>
<?php endif; ?>
