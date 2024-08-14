<!-- ROOT\templates\Modifications\view.ctp -->

<style>
    .modification-view {
        display: flex;
        flex-direction: column;
        align-items: center;
        margin-top: 20px;
    }
    
    .modification-user,
    .modification-description,
    .modification-date {
        text-align: center;
        margin-bottom: 30px; /* Increase margin bottom for more space */
    }
    
    .modification-description textarea {
        width: 80%; /* Adjust width as needed */
        height: 250px; /* Increase height for larger visible area */
        padding: 10px; /* Add padding inside the textarea */
        border: 1px solid #ccc;
        font-size: 16px; /* Example font size for textarea content */
        resize: vertical; /* Allow vertical resizing */
    }
</style>

<div class="modification-view">
    <!-- User at the top -->
    <div class="modification-user">
        <strong>Numero:</strong> <?= h($garanty->numero) ?>
    </div>

    <!-- Description as a paragraph -->
    <div class="modification-description">
    <textarea readonly rows="40" cols="50"><?= h($garanty->raison) ?></textarea>
    </div>

    <!-- Date at the bottom -->
    <div class="modification-date">
        <strong>Date:</strong> <?= h($garanty->del_at) ?>
    </div>
</div>
